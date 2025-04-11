<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SpotifyService;
use App\Events\SongRequestCreated;
use App\Models\SpotifyApiCredential;
use Illuminate\Support\Facades\Auth;

class SpotifyController extends Controller
{
    protected $spotify;

    public function __construct(SpotifyService $spotify)
    {
        $this->spotify = $spotify;
        
        // Always use the authenticated user's credentials if available
        if (Auth::check()) {
            $this->spotify->forUser(Auth::user());
        }
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
    
    /**
     * Store Spotify API credentials for the authenticated user
     */
    public function storeCredentials(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|string|max:255',
            'client_secret' => 'required|string|max:255',
        ]);
        
        try {
            $user = Auth::user();
            
            // Deactivate any existing active credentials
            SpotifyApiCredential::where('user_id', $user->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
            
            // Create new credentials
            $credentials = SpotifyApiCredential::create([
                'user_id' => $user->id,
                'client_id' => $validated['client_id'],
                'client_secret' => $validated['client_secret'],
                'is_active' => true,
            ]);
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Spotify API credentials stored successfully',
                    'data' => $credentials
                ]);
            }
            
            return redirect()->back()->with('success', 'Spotify API credentials stored successfully');
        } catch (\Exception $e) {
            \Log::error('Failed to store Spotify credentials', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store Spotify credentials'
                ], 500);
            }
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to store Spotify credentials']);
        }
    }
    
    /**
     * Display the Spotify API credentials page
     */
    public function credentialsPage()
    {
        $user = Auth::user();
        $credentials = $user->spotifyApiCredential;
        
        return view('spotify.credentials', [
            'credentials' => $credentials,
        ]);
    }
}
