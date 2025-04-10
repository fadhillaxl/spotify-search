<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaylistSetting extends Model
{
    protected $fillable = [
        'playlist_id',
        'name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
} 