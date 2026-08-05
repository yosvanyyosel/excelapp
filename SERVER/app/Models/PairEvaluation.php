<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PairEvaluation extends Model
{
    protected $fillable = ['center_id', 'pair_name', 'decision', 'decision_text', 'visible_at'];

    protected $casts = [
        'visible_at' => 'datetime',
    ];

    public function center()
    {
        return $this->belongsTo(DiscoveryCenter::class, 'center_id');
    }
}
