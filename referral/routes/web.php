<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SpecialistController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware(Authenticate::class)->group(function () {
    Route::get('/password/change', [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('/password/change', [AuthController::class, 'changePassword'])->name('password.change.store');
});

// Protected routes
Route::middleware(Authenticate::class)->group(function () {
    Route::get('/', [ReferralController::class, 'index'])->name('referrals.index');

    // Referrals
    Route::get('/referrals/create', [ReferralController::class, 'create'])->name('referrals.create');
    Route::post('/referrals', [ReferralController::class, 'store'])->name('referrals.store');
    Route::get('/referrals/{referral}', [ReferralController::class, 'show'])->name('referrals.show');
    Route::get('/referrals/{referral}/edit', [ReferralController::class, 'edit'])->name('referrals.edit');
    Route::put('/referrals/{referral}', [ReferralController::class, 'update'])->name('referrals.update');
    Route::post('/referrals/{referral}/status', [ReferralController::class, 'updateStatus'])->name('referrals.status');
    Route::get('/referrals/{referral}/confirm', [ReferralController::class, 'showConfirm'])->name('referrals.confirm');
    Route::post('/referrals/{referral}/confirm', [ReferralController::class, 'storeConfirm'])->name('referrals.confirm.store');

    // Notes
    Route::post('/referrals/{referral}/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

    // Documents
    Route::post('/referrals/{referral}/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::post('/documents/reorder', [DocumentController::class, 'reorder'])->name('documents.reorder');

    // PDFs
    Route::get('/referrals/{referral}/pdf', [PdfController::class, 'referralPdf'])->name('referrals.pdf');
    Route::get('/referrals/{referral}/fax-pdf', [PdfController::class, 'faxPdf'])->name('referrals.fax-pdf');

    // Reports
    Route::get('/reports/followup', [ReportController::class, 'followup'])->name('reports.followup');

    // Specialists
    Route::get('/specialists', [SpecialistController::class, 'index'])->name('specialists.index');
    Route::get('/specialists/create', [SpecialistController::class, 'create'])->name('specialists.create');
    Route::post('/specialists', [SpecialistController::class, 'store'])->name('specialists.store');
    Route::get('/specialists/{specialist}/edit', [SpecialistController::class, 'edit'])->name('specialists.edit');
    Route::put('/specialists/{specialist}', [SpecialistController::class, 'update'])->name('specialists.update');
    Route::delete('/specialists/{specialist}', [SpecialistController::class, 'destroy'])->name('specialists.destroy');
    Route::get('/api/specialists', [SpecialistController::class, 'apiSearch'])->name('specialists.api');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});
