<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Services\AuditService;
use App\Services\PdfService;
use Illuminate\Support\Facades\Auth;

class PdfController extends Controller
{
    public function __construct(private PdfService $pdfService) {}

    public function referralPdf(Referral $referral)
    {
        AuditService::log($referral, Auth::user(), 'pdf_generated');

        return $this->pdfService->generateReferralPdf($referral)
            ->stream($referral->referral_number . '.pdf');
    }

    public function faxPdf(Referral $referral)
    {
        AuditService::log($referral, Auth::user(), 'pdf_generated');

        $content = $this->pdfService->generateFaxPdf($referral);

        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $referral->referral_number . '-fax.pdf"',
        ]);
    }
}
