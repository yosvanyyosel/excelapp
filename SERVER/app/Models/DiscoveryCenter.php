<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscoveryCenter extends Model
{
    protected $fillable = ['name', 'banner_photo', 'quiz_timer'];

    public function users()
    {
        return $this->hasMany(User::class, 'center_id');
    }
}
