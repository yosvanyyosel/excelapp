<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'center_id',
        'pair_name',
        'pair_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function center()
    {
        return $this->belongsTo(DiscoveryCenter::class, 'center_id');
    }
}
