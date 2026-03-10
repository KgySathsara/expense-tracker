<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = ['user_id', 'content', 'date', 'reminder_time', 'is_reminded'];

    protected $casts = [
        'date' => 'date',
        'is_reminded' => 'boolean',
    ];
}
