<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    protected $fillable = [
        'user_name',
        'pair_name',
        'center_name',
        'test_type',
        'completed_at',
        'answers',
        'metadata'
    ];

    protected $casts = [
        'answers' => 'array',
        'metadata' => 'array',
        'completed_at' => 'datetime'
    ];
}
