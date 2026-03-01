<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\ReferralNote;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function store(Request $request, Referral $referral)
    {
        $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        ReferralNote::create([
            'referral_id' => $referral->id,
            'user_id'     => Auth::id(),
            'body'        => $request->body,
        ]);

        AuditService::log($referral, Auth::user(), 'note_added');

        return back()->with('success', 'Note added.');
    }

    public function destroy(ReferralNote $note)
    {
        $referral = $note->referral;

        if ($note->user_id !== Auth::id()) {
            return back()->with('error', 'You can only delete your own notes.');
        }

        $note->delete();

        AuditService::log($referral, Auth::user(), 'note_deleted');

        return back()->with('success', 'Note deleted.');
    }
}
