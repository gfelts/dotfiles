<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'referral_id', 'user_id', 'user_initials',
        'action', 'field_changes',
    ];

    protected $casts = [
        'field_changes' => 'array',
        'created_at' => 'datetime',
    ];

    public function referral()
    {
        return $this->belongsTo(Referral::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function actionLabel(): string
    {
        return match($this->action) {
            'created' => 'Created referral',
            'updated' => 'Updated referral',
            'status_changed' => 'Changed status',
            'document_uploaded' => 'Uploaded document',
            'document_deleted' => 'Deleted document',
            'pdf_generated' => 'Generated PDF',
            'confirmation_saved' => 'Saved confirmation',
            'note_added'        => 'Added note',
            'note_deleted'      => 'Deleted note',
            default => ucfirst(str_replace('_', ' ', $this->action)),
        };
    }
}
