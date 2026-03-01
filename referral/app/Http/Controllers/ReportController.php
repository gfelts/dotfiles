<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Services\PdfService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function followup(Request $request)
    {
        $query = Referral::with(['createdBy'])
            ->whereNotIn('status', ['completed', 'cancelled', 'draft']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('specialty')) {
            $query->where('to_specialty', 'like', '%' . $request->specialty . '%');
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $referrals = $query->orderBy('created_at')->get();

        $cutoff = now()->subDays(7);
        $referrals = $referrals->map(function ($r) use ($cutoff) {
            $r->overdue = in_array($r->status, ['sent', 'accepted'])
                && $r->created_at->lt($cutoff)
                && !$r->confirmation_date;
            return $r;
        });

        if ($request->get('export') === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.followup-pdf', compact('referrals'));
            return $pdf->download('followup-report-' . now()->format('Y-m-d') . '.pdf');
        }

        return view('reports.followup', compact('referrals'));
    }
}
