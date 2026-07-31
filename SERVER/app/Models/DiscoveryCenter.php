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

    public function staff()
    {
        return $this->hasMany(User::class, 'center_id')->where('role', 'admin');
    }

    public function participants()
    {
        return $this->hasMany(User::class, 'center_id')->where('role', 'participant');
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'center_id');
    }
}
