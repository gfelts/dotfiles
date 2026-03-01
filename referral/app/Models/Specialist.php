<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialist extends Model
{
    protected $fillable = [
        'practice_name', 'specialty', 'phone', 'fax', 'address', 'notes',
    ];
}
