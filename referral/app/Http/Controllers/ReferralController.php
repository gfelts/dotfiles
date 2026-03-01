<?php

namespace App\Http\Controllers;

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
        return view('referrals.index', compact('referrals'));
    }

    public function create()
    {
        return view('referrals.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateReferral($request);
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        $referral = Referral::create($data);
        AuditService::log($referral, Auth::user(), 'created');

        return redirect()->route('referrals.show', $referral)
            ->with('success', 'Referral ' . $referral->referral_number . ' created.');
    }

    public function show(Referral $referral)
    {
        $referral->load(['createdBy', 'updatedBy', 'documents.createdBy', 'auditLogs.user']);
        return view('referrals.show', compact('referral'));
    }

    public function edit(Referral $referral)
    {
        return view('referrals.edit', compact('referral'));
    }

    public function update(Request $request, Referral $referral)
    {
        $data = $this->validateReferral($request);
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
            'referral_accepted' => ['nullable', 'boolean'],
            'referral_accepted_explain' => ['nullable', 'string', 'max:1000'],
            'appointment_with' => ['nullable', 'string', 'max:150'],
            'appointment_datetime' => ['nullable', 'date'],
            'scheduling_status' => ['nullable', 'in:scheduled,patient_refused,patient_will_schedule'],
            'additional_info_request' => ['nullable', 'string', 'max:1000'],
            'confirmation_by' => ['nullable', 'string', 'max:150'],
            'confirmation_date' => ['nullable', 'date'],
        ]);

        $data['updated_by'] = Auth::id();
        $referral->fill($data);
        $changes = AuditService::buildChanges($referral);
        $referral->save();

        AuditService::log($referral, Auth::user(), 'confirmation_saved', $changes);

        return redirect()->route('referrals.show', $referral)
            ->with('success', 'Confirmation saved.');
    }

    private function validateReferral(Request $request): array
    {
        return $request->validate([
            'to_specialty' => ['required', 'string', 'max:150'],
            'to_phone' => ['nullable', 'string', 'max:20'],
            'to_fax' => ['nullable', 'string', 'max:20'],
            'to_practice' => ['nullable', 'string'],
            'schedule_urgent' => ['boolean'],
            'schedule_urgent_called' => ['nullable', 'string', 'max:255'],
            'schedule_routine_specific' => ['boolean'],
            'schedule_routine_physician' => ['nullable', 'string', 'max:255'],
            'schedule_first_available' => ['boolean'],
            'referring_provider_name' => ['nullable', 'string', 'max:150'],
            'referring_provider_phone' => ['nullable', 'string', 'max:20'],
            'referring_provider_fax' => ['nullable', 'string', 'max:20'],
            'referral_type_eval_primary' => ['boolean'],
            'referral_type_eval_assumed' => ['boolean'],
            'referral_type_eval_shared' => ['boolean'],
            'referral_type_specialist' => ['boolean'],
            'referral_type_other' => ['boolean'],
            'referral_type_other_text' => ['nullable', 'string', 'max:255'],
            'patient_name' => ['required', 'string', 'max:150'],
            'patient_dob' => ['required', 'date'],
            'patient_parent_name' => ['nullable', 'string', 'max:150'],
            'patient_phone' => ['required', 'string', 'max:20'],
            'patient_best_time' => ['nullable', 'string', 'max:100'],
            'patient_special_considerations' => ['nullable', 'string'],
            'patient_insurance' => ['nullable', 'string'],
            'patient_pcp_name' => ['nullable', 'string', 'max:150'],
            'patient_pcp_phone' => ['nullable', 'string', 'max:20'],
            'patient_pcp_fax' => ['nullable', 'string', 'max:20'],
            'reason_for_referral' => ['required', 'string'],
            'comments_considerations' => ['nullable', 'string'],
            'patient_aware' => ['boolean'],
            'patient_aware_explain' => ['nullable', 'string'],
            'status' => ['sometimes', 'in:' . implode(',', Referral::STATUSES)],
        ]);
    }
}
