<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SpotifyService;
use App\Events\SongRequestCreated;

class SpotifyController extends Controller
{
    protected $spotify;

    public function __construct(SpotifyService $spotify)
    {
        $this->spotify = $spotify;
    }

    /**
     * Display the search page
     */
    public function index()
    {
        return view('search');
    }

    /**
     * Search for tracks
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
        ]);

        $results = $this->spotify->searchTracks($request->query('query'));

        return response()->json($results);
    }
    
    /**
     * Get tracks from a specific playlist
     */
    public function playlistTracks($playlistId)
    {
        $results = $this->spotify->getPlaylistTracks($playlistId);
        
        return response()->json($results);
    }
    
    /**
     * Search for tracks within a specific playlist
     */
    public function searchPlaylist(Request $request, $playlistId)
    {
        $request->validate([
            'query' => 'required|string',
        ]);
        
        $results = $this->spotify->searchPlaylistTracks($playlistId, $request->query('query'));
        
        return response()->json($results);
    }
    
    /**
     * Store a song request
     */
    public function storeSongRequest(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'song_name' => 'required|string|max:255',
            'artist' => 'nullable|string|max:255',
        ]);

        $songRequest = \App\Models\SongRequest::create([
            'name' => $validated['name'],
            'song_name' => $validated['song_name'],
            'artist' => $validated['artist'],
            'status' => 'pending',
        ]);

        // Broadcast the new request event
        event(new SongRequestCreated($songRequest));

        return response()->json([
            'success' => true,
            'message' => 'Song request submitted successfully',
            'data' => $songRequest
        ]);
    }
}
