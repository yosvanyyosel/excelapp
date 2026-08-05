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
        'staff_title',
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

    public function notes()
    {
        return $this->hasMany(Note::class, 'author_id');
    }

    public function taggedInNotes()
    {
        return $this->hasMany(Note::class, 'tagged_user_id');
    }

    public function evaluationItems()
    {
        return $this->hasMany(EvaluationItem::class);
    }
}
