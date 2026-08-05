<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationItem extends Model
{
    protected $fillable = ['user_id', 'type', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
