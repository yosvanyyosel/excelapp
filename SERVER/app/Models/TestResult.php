<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    protected $fillable = [
        'user_name',
        'user_surname',
        'test_type',
        'completed_at',
        'answers'
    ];

    protected $casts = [
        'answers' => 'array',
        'completed_at' => 'datetime'
    ];
}
