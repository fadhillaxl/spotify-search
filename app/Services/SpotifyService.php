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
                'limit' => 20,
            ]);

        return $response->json();
    }
    
    /**
     * Get tracks from a specific playlist
     */
    public function getPlaylistTracks($playlistId)
    {
        $token = $this->getAccessToken();
        $allTracks = [];
        $offset = 0;
        $limit = 50;
        
        do {
            $response = Http::withToken($token)
                ->get("https://api.spotify.com/v1/playlists/{$playlistId}/tracks", [
                    'limit' => $limit,
                    'offset' => $offset,
                ]);
                
            $data = $response->json();
            
            if (isset($data['items']) && is_array($data['items'])) {
                $allTracks = array_merge($allTracks, $data['items']);
            }
            
            $offset += $limit;
        } while (isset($data['next']) && $data['next'] !== null);
        
        return [
            'items' => $allTracks,
            'total' => count($allTracks)
        ];
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
