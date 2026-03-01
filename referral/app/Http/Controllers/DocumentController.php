<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\ReferralDocument;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function store(Request $request, Referral $referral)
    {
        $request->validate([
            'document' => ['required', 'file', 'mimes:pdf', 'max:20480'],
        ]);

        $file = $request->file('document');
        $storedName = Str::uuid() . '.pdf';
        $dir = storage_path('app/referral_docs/' . $referral->id);

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file->move($dir, $storedName);

        $maxOrder = $referral->documents()->max('sort_order') ?? -1;

        $doc = ReferralDocument::create([
            'referral_id' => $referral->id,
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => $storedName,
            'mime_type' => 'application/pdf',
            'file_size' => $file->getSize(),
            'sort_order' => $maxOrder + 1,
            'created_by' => Auth::id(),
        ]);

        AuditService::log($referral, Auth::user(), 'document_uploaded', [
            ['field' => 'document', 'old' => null, 'new' => $doc->original_name],
        ]);

        return back()->with('success', 'Document uploaded.');
    }

    public function destroy(ReferralDocument $document)
    {
        $referral = $document->referral;
        $name = $document->original_name;
        $path = $document->storagePath();

        if (file_exists($path)) {
            unlink($path);
        }

        $document->delete();

        AuditService::log($referral, Auth::user(), 'document_deleted', [
            ['field' => 'document', 'old' => $name, 'new' => null],
        ]);

        return back()->with('success', 'Document deleted.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        foreach ($request->order as $position => $docId) {
            ReferralDocument::where('id', $docId)->update(['sort_order' => $position]);
        }

        return response()->json(['ok' => true]);
    }
}
