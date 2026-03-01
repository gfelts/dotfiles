<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralDocument extends Model
{
    protected $fillable = [
        'referral_id', 'original_name', 'stored_name',
        'mime_type', 'file_size', 'sort_order', 'created_by',
    ];

    public function referral()
    {
        return $this->belongsTo(Referral::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function storagePath(): string
    {
        return storage_path('app/referral_docs/' . $this->referral_id . '/' . $this->stored_name);
    }
}
