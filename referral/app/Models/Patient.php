<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'chart_number', 'last_name', 'first_name', 'dob',
        'parent_name', 'phone', 'best_time', 'insurance', 'special_considerations',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->last_name . ', ' . $this->first_name;
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class);
    }
}
