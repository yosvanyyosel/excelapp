<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'center_id',
        'content',
        'is_public',
        'tagged_user_id',
        'tagged_pair_name',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function center()
    {
        return $this->belongsTo(DiscoveryCenter::class, 'center_id');
    }

    public function taggedUser()
    {
        return $this->belongsTo(User::class, 'tagged_user_id');
    }
}
