<?php

namespace App\Services;

use App\Models\Referral;
use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\Fpdi;

class PdfService
{
    public function generateReferralPdf(Referral $referral): \Barryvdh\DomPDF\PDF
    {
        $pdf = Pdf::loadView('referrals.pdf-template', ['referral' => $referral]);
        $pdf->setPaper('letter', 'portrait');
        return $pdf;
    }

    public function generateFaxPdf(Referral $referral): string
    {
        // Step 1: Generate referral form PDF to temp file
        $tempRef = tempnam(sys_get_temp_dir(), 'ref_') . '.pdf';
        $this->generateReferralPdf($referral)->save($tempRef);

        // Step 2: Collect document paths
        $documents = $referral->documents()->orderBy('sort_order')->get();
        $docPaths = [];
        foreach ($documents as $doc) {
            $path = $doc->storagePath();
            if (file_exists($path) && mime_content_type($path) === 'application/pdf') {
                $docPaths[] = $path;
            }
        }

        // Step 3: If no documents, just return the referral PDF
        if (empty($docPaths)) {
            $content = file_get_contents($tempRef);
            unlink($tempRef);
            return $content;
        }

        // Step 4: Merge using FPDI
        $fpdi = new Fpdi();
        $fpdi->SetAutoPageBreak(false);

        $allFiles = array_merge([$tempRef], $docPaths);
        foreach ($allFiles as $filePath) {
            try {
                $pageCount = $fpdi->setSourceFile($filePath);
                for ($i = 1; $i <= $pageCount; $i++) {
                    $tpl = $fpdi->importPage($i);
                    $size = $fpdi->getTemplateSize($tpl);
                    $fpdi->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
                    $fpdi->useTemplate($tpl);
                }
            } catch (\Exception $e) {
                // Skip files that can't be parsed
            }
        }

        $output = $fpdi->Output('S');
        unlink($tempRef);
        return $output;
    }
}
