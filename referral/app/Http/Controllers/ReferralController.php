<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Referral;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    public function index(Request $request)
    {
        $query = Referral::with(['createdBy', 'updatedBy'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('patient_name', 'like', "%{$s}%")
                  ->orWhere('referral_number', 'like', "%{$s}%")
                  ->orWhere('to_specialty', 'like', "%{$s}%");
            });
        }

        $referrals = $query->paginate(25)->withQueryString();

        $counts = Referral::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $overdue = Referral::whereIn('status', ['sent', 'accepted'])
            ->whereNull('confirmation_date')
            ->where('created_at', '<', now()->subDays(7))
            ->count();

        return view('referrals.index', compact('referrals', 'counts', 'overdue'));
    }

    public function create()
    {
        return view('referrals.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateReferral($request);

        // Fill patient fields from patient record
        if (!empty($data['patient_id'])) {
            $patient = Patient::find($data['patient_id']);
            if ($patient) {
                $data['patient_name']                = $patient->full_name;
                $data['patient_dob']                 = $patient->dob;
                $data['patient_parent_name']         = $patient->parent_name;
                $data['patient_phone']               = $patient->phone;
                $data['patient_best_time']           = $patient->best_time;
                $data['patient_insurance']           = $patient->insurance;
                $data['patient_special_considerations'] = $patient->special_considerations;
            }
        }

        // Fill provider / PCP fields from provider selection
        $this->applyProvider($data, $request->input('provider'));

        // Map schedule dropdown to boolean columns
        $this->applySchedule($data, $request->input('schedule_type'), $request);

        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        $referral = Referral::create($data);
        AuditService::log($referral, Auth::user(), 'created');

        return redirect()->route('referrals.show', $referral)
            ->with('success', 'Referral ' . $referral->referral_number . ' created.');
    }

    public function show(Referral $referral)
    {
        $referral->load(['createdBy', 'updatedBy', 'documents.createdBy', 'notes.user', 'auditLogs.user', 'patient']);
        return view('referrals.show', compact('referral'));
    }

    public function edit(Referral $referral)
    {
        return view('referrals.edit', compact('referral'));
    }

    public function update(Request $request, Referral $referral)
    {
        $data = $this->validateReferral($request);

        // Re-sync patient fields if patient changed
        if (!empty($data['patient_id']) && $data['patient_id'] != $referral->patient_id) {
            $patient = Patient::find($data['patient_id']);
            if ($patient) {
                $data['patient_name']                = $patient->full_name;
                $data['patient_dob']                 = $patient->dob;
                $data['patient_parent_name']         = $patient->parent_name;
                $data['patient_phone']               = $patient->phone;
                $data['patient_best_time']           = $patient->best_time;
                $data['patient_insurance']           = $patient->insurance;
                $data['patient_special_considerations'] = $patient->special_considerations;
            }
        }

        // Update provider fields if provider changed
        if ($request->filled('provider')) {
            $this->applyProvider($data, $request->input('provider'));
        }

        // Map schedule dropdown to boolean columns
        if ($request->filled('schedule_type')) {
            $this->applySchedule($data, $request->input('schedule_type'), $request);
        }

        $data['updated_by'] = Auth::id();

        $referral->fill($data);
        $changes = AuditService::buildChanges($referral);
        $referral->save();

        if (!empty($changes)) {
            AuditService::log($referral, Auth::user(), 'updated', $changes);
        }

        return redirect()->route('referrals.show', $referral)
            ->with('success', 'Referral updated.');
    }

    public function updateStatus(Request $request, Referral $referral)
    {
        $request->validate([
            'status' => ['required', 'in:' . implode(',', Referral::STATUSES)],
        ]);

        $old = $referral->status;
        $referral->status = $request->status;
        $referral->updated_by = Auth::id();
        $referral->save();

        AuditService::log($referral, Auth::user(), 'status_changed', [
            ['field' => 'status', 'old' => $old, 'new' => $request->status],
        ]);

        return back()->with('success', 'Status updated to ' . ucfirst($request->status) . '.');
    }

    public function showConfirm(Referral $referral)
    {
        return view('referrals.confirm', compact('referral'));
    }

    public function storeConfirm(Request $request, Referral $referral)
    {
        $data = $request->validate([
            'referral_accepted'        => ['nullable', 'boolean'],
            'referral_accepted_explain'=> ['nullable', 'string', 'max:1000'],
            'appointment_with'         => ['nullable', 'string', 'max:150'],
            'appointment_datetime'     => ['nullable', 'date'],
            'scheduling_status'        => ['nullable', 'in:scheduled,patient_refused,patient_will_schedule'],
            'additional_info_request'  => ['nullable', 'string', 'max:1000'],
            'confirmation_by'          => ['nullable', 'string', 'max:150'],
            'confirmation_date'        => ['nullable', 'date'],
        ]);

        $data['updated_by'] = Auth::id();
        $referral->fill($data);
        $changes = AuditService::buildChanges($referral);
        $referral->save();

        AuditService::log($referral, Auth::user(), 'confirmation_saved', $changes);

        return redirect()->route('referrals.show', $referral)
            ->with('success', 'Confirmation saved.');
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    private function applyProvider(array &$data, ?string $provider): void
    {
        if (!$provider || !isset(Referral::PROVIDERS[$provider])) {
            return;
        }
        $name = Referral::PROVIDERS[$provider];
        $data['provider']                = $provider;
        $data['referring_provider_name'] = $name;
        $data['referring_provider_phone']= Referral::PROVIDER_PHONE;
        $data['referring_provider_fax']  = Referral::PROVIDER_FAX;
        $data['patient_pcp_name']        = $name;
        $data['patient_pcp_phone']       = Referral::PROVIDER_PHONE;
        $data['patient_pcp_fax']         = Referral::PROVIDER_FAX;
    }

    private function applySchedule(array &$data, ?string $type, Request $request): void
    {
        $data['schedule_urgent']           = $type === 'urgent';
        $data['schedule_routine_specific'] = $type === 'routine_specific';
        $data['schedule_first_available']  = $type === 'first_available';
        $data['schedule_urgent_called']    = $type === 'urgent' ? $request->input('schedule_urgent_called') : null;
        $data['schedule_routine_physician']= $type === 'routine_specific' ? $request->input('schedule_routine_physician') : null;
    }

    private function validateReferral(Request $request): array
    {
        return $request->validate([
            'patient_id'                  => ['nullable', 'integer', 'exists:patients,id'],
            'provider'                    => ['nullable', 'string', 'in:' . implode(',', array_keys(Referral::PROVIDERS))],
            'to_specialty'                => ['required', 'string', 'max:150'],
            'to_phone'                    => ['nullable', 'string', 'max:20'],
            'to_fax'                      => ['nullable', 'string', 'max:20'],
            'to_practice'                 => ['nullable', 'string'],
            'schedule_urgent_called'      => ['nullable', 'string', 'max:255'],
            'schedule_routine_physician'  => ['nullable', 'string', 'max:255'],
            'referring_provider_name'     => ['nullable', 'string', 'max:150'],
            'referring_provider_phone'    => ['nullable', 'string', 'max:20'],
            'referring_provider_fax'      => ['nullable', 'string', 'max:20'],
            'referral_type_eval_primary'  => ['boolean'],
            'referral_type_eval_assumed'  => ['boolean'],
            'referral_type_eval_shared'   => ['boolean'],
            'referral_type_specialist'    => ['boolean'],
            'referral_type_other'         => ['boolean'],
            'referral_type_other_text'    => ['nullable', 'string', 'max:255'],
            'patient_name'                => ['nullable', 'string', 'max:150'],
            'patient_dob'                 => ['nullable', 'date'],
            'patient_parent_name'         => ['nullable', 'string', 'max:150'],
            'patient_phone'               => ['nullable', 'string', 'max:20'],
            'patient_best_time'           => ['nullable', 'string', 'max:100'],
            'patient_special_considerations' => ['nullable', 'string'],
            'patient_insurance'           => ['nullable', 'string'],
            'patient_pcp_name'            => ['nullable', 'string', 'max:150'],
            'patient_pcp_phone'           => ['nullable', 'string', 'max:20'],
            'patient_pcp_fax'             => ['nullable', 'string', 'max:20'],
            'reason_for_referral'         => ['required', 'string'],
            'comments_considerations'     => ['nullable', 'string'],
            'patient_aware'               => ['boolean'],
            'patient_aware_explain'       => ['nullable', 'string'],
            'status'                      => ['sometimes', 'in:' . implode(',', Referral::STATUSES)],
        ]);
    }
}
