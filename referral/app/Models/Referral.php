<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Referral extends Model
{
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING = 'pending';
    const STATUS_SENT = 'sent';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DECLINED = 'declined';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    const PROVIDERS = [
        'john_roach'    => 'John Roach, MD',
        'sheena_felts'  => 'Sheena Felts, APRN, FNP-C',
    ];
    const PROVIDER_PHONE = '270-366-0960';
    const PROVIDER_FAX   = '270-554-1108';
    const PROVIDER_PRACTICE = 'Bluegrass Pediatrics';

    const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_PENDING,
        self::STATUS_SENT,
        self::STATUS_ACCEPTED,
        self::STATUS_DECLINED,
        self::STATUS_SCHEDULED,
        self::STATUS_COMPLETED,
        self::STATUS_CANCELLED,
    ];

    protected $fillable = [
        'referral_number', 'status', 'patient_id', 'provider',
        'to_specialty', 'to_phone', 'to_fax', 'to_practice',
        'schedule_urgent', 'schedule_urgent_called',
        'schedule_routine_specific', 'schedule_routine_physician',
        'schedule_first_available',
        'referring_provider_name', 'referring_provider_phone', 'referring_provider_fax',
        'referral_type_eval_primary', 'referral_type_eval_assumed',
        'referral_type_eval_shared', 'referral_type_specialist',
        'referral_type_other', 'referral_type_other_text',
        'patient_name', 'patient_dob', 'patient_parent_name', 'patient_phone',
        'patient_best_time', 'patient_special_considerations', 'patient_insurance',
        'patient_pcp_name', 'patient_pcp_phone', 'patient_pcp_fax',
        'reason_for_referral', 'comments_considerations',
        'patient_aware', 'patient_aware_explain',
        'referral_accepted', 'referral_accepted_explain',
        'appointment_with', 'appointment_datetime', 'scheduling_status',
        'additional_info_request', 'confirmation_by', 'confirmation_date',
        'created_by', 'updated_by',
    ];

    protected $casts = [
        'patient_dob' => 'date',
        'appointment_datetime' => 'datetime',
        'confirmation_date' => 'date',
        'schedule_urgent' => 'boolean',
        'schedule_routine_specific' => 'boolean',
        'schedule_first_available' => 'boolean',
        'referral_type_eval_primary' => 'boolean',
        'referral_type_eval_assumed' => 'boolean',
        'referral_type_eval_shared' => 'boolean',
        'referral_type_specialist' => 'boolean',
        'referral_type_other' => 'boolean',
        'patient_aware' => 'boolean',
        'referral_accepted' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Referral $referral) {
            if (empty($referral->referral_number)) {
                $year = now()->year;
                $count = DB::table('referrals')
                    ->whereYear('created_at', $year)
                    ->count();
                $referral->referral_number = 'REF-' . $year . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function documents()
    {
        return $this->hasMany(ReferralDocument::class)->orderBy('sort_order');
    }

    public function notes()
    {
        return $this->hasMany(ReferralNote::class)->latest();
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class)->latest();
    }

    public function statusLabel(): string
    {
        return ucfirst($this->status);
    }

    public function statusClass(): string
    {
        return match($this->status) {
            'draft' => 'muted',
            'pending', 'sent' => 'warn',
            'accepted', 'scheduled' => 'success',
            'declined', 'cancelled' => 'danger',
            'completed' => 'info',
            default => 'muted',
        };
    }
}
