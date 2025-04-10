<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SpotifyService
{
    protected function getAccessToken()
    {
        return Cache::remember('spotify_token', 3500, function () {
            $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
                'grant_type' => 'client_credentials',
                'client_id' => config('services.spotify.client_id'),
                'client_secret' => config('services.spotify.client_secret'),
            ]);

            return $response['access_token'];
        });
    }

    public function searchTracks($query)
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->get('https://api.spotify.com/v1/search', [
                'q' => $query,
                'type' => 'track',
                'limit' => 50,
            ]);

        return $response->json();
    }
    
    /**
     * Get the total number of tracks in a playlist
     */
    public function getPlaylistTotalTracks($playlistId)
    {
        $token = $this->getAccessToken();
        
        $response = Http::withToken($token)
            ->get("https://api.spotify.com/v1/playlists/{$playlistId}", [
                'limit' => 150,
            ]);
            
        $data = $response->json();
        
        // Debug logging
        \Log::info('Playlist Info Response', [
            'playlist_id' => $playlistId,
            'total_tracks' => $data['tracks']['total'] ?? 'N/A'
        ]);
        
        return $data['tracks']['total'] ?? 0;
    }
    
    /**
     * Get tracks from a specific playlist
     */
    public function getPlaylistTracks($playlistId)
    {
        $token = $this->getAccessToken();
        $response1 = Http::withToken($token)
        ->get("https://api.spotify.com/v1/playlists/{$playlistId}");
        $data1 = $response1->json();
        $limit = $data1['tracks']['limit'];

        $response = Http::withToken($token)
            ->get("https://api.spotify.com/v1/playlists/{$playlistId}/tracks", [
                'limit' => $limit,
            ]);

        $data = $response->json();
        
        // Debug logging
        \Log::info('Spotify API Response', [
            'playlist_id' => $playlistId,
            'total' => $data['total'] ?? 'N/A',
            'items_count' => count($data['items'] ?? [])
        ]);
        
        $allTracks = isset($data['items']) && is_array($data['items']) ? $data['items'] : [];
        
        // Debug logging for final result
        \Log::info('Final Playlist Tracks Result', [
            'playlist_id' => $playlistId,
            'total_tracks' => count($allTracks),
            'api_total' => $data['limit'] ?? 0
        ]);
        
        // return [
        //     'items' => $allTracks,
        //     'total' => count($allTracks) // Use the actual count of tracks instead of API total
        // ];
        return $data;
    }
    
    /**
     * Search for tracks within a specific playlist
     */
    public function searchPlaylistTracks($playlistId, $query)
    {
        // First get all tracks from the playlist
        $playlistData = $this->getPlaylistTracks($playlistId);
        
        // Extract tracks from the response
        $tracks = [];
        if (isset($playlistData['items']) && is_array($playlistData['items'])) {
            $tracks = array_map(function($item) {
                return $item['track'];
            }, $playlistData['items']);
        }
        
        // Filter tracks based on the search query
        $filteredTracks = array_filter($tracks, function($track) use ($query) {
            if (!$track) return false;
            
            $query = strtolower($query);
            $trackName = strtolower($track['name'] ?? '');
            $artistName = '';
            
            if (isset($track['artists']) && is_array($track['artists']) && count($track['artists']) > 0) {
                $artistName = strtolower($track['artists'][0]['name'] ?? '');
            }
            
            return strpos($trackName, $query) !== false || strpos($artistName, $query) !== false;
        });
        
        // Return the filtered tracks in a format similar to the search API
        return [
            'tracks' => [
                'items' => array_values($filteredTracks),
                'total' => count($filteredTracks)
            ]
        ];
    }
}
