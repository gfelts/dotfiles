<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralNote extends Model
{
    protected $fillable = ['referral_id', 'user_id', 'body'];

    public function referral()
    {
        return $this->belongsTo(Referral::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
