<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the playlists for the user.
     */
    public function playlists(): HasMany
    {
        return $this->hasMany(Playlist::class);
    }

    /**
     * Create a new playlist for this user.
     *
     * @param array $attributes
     * @return \App\Models\Playlist
     */
    public function createPlaylist(array $attributes): Playlist
    {
        // Add the user_id to the attributes
        $attributes['user_id'] = $this->id;
        
        // Create the playlist
        $playlist = $this->playlists()->create($attributes);
        
        // If this playlist is active, ensure it's the only active playlist for this user
        if ($playlist->is_active) {
            Playlist::ensureOnlyOneActivePerUser($this->id);
        }
        
        return $playlist;
    }
}
