<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Playlist extends Model
{
    protected $fillable = [
        'spotify_playlist_id',
        'name',
        'description',
        'is_active',
        'user_id'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get the user that owns the playlist.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Ensure only one active playlist per user.
     * 
     * @param int $userId
     * @return void
     */
    public static function ensureOnlyOneActivePerUser(int $userId): void
    {
        // Get all active playlists for this user
        $activePlaylists = self::where('user_id', $userId)
            ->where('is_active', true)
            ->get();
        
        // If there's more than one active playlist, deactivate all except the most recent one
        if ($activePlaylists->count() > 1) {
            $mostRecent = $activePlaylists->sortByDesc('updated_at')->first();
            
            $activePlaylists->where('id', '!=', $mostRecent->id)->each(function ($playlist) {
                $playlist->update(['is_active' => false]);
            });
        }
    }
} 