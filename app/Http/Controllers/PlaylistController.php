<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the playlists.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $playlists = Playlist::where('user_id', auth()->id())
            ->latest()
            ->get();

        // dd($playlists);
        return view('admin.playlists.index', compact('playlists'));
    }

    /**
     * Get active Spotify playlist IDs.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActivePlaylistIds()
    {
        try {
            $playlistIds = Playlist::where('is_active', true)
                ->pluck('spotify_playlist_id')
                ->toArray();

            return response()->json([
                'success' => true,
                'playlist_ids' => $playlistIds
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching active playlist IDs: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch active playlist IDs'
            ], 500);
        }
    }

    /**
     * Show the form for creating a new playlist.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.playlists.create');
    }

    /**
     * Store a newly created playlist in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'spotify_playlist_id' => 'required|string|unique:playlists',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'is_active' => 'boolean'
            ]);

            // Use the user's createPlaylist method to create the playlist
            $playlist = auth()->user()->createPlaylist($validated);

            return redirect()->route('playlists.index')
                ->with('success', 'Playlist added successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating playlist: ' . $e->getMessage());
            // dd($e);
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage() ?? 'Failed to create playlist. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified playlist.
     *
     * @param  \App\Models\Playlist  $playlist
     * @return \Illuminate\View\View
     */
    public function edit(Playlist $playlist)
    {
        // Ensure the user can only edit their own playlists
        if ($playlist->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('admin.playlists.edit', compact('playlist'));
    }

    /**
     * Update the specified playlist in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Playlist  $playlist
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Playlist $playlist)
    {
        try {
            // Ensure the user can only update their own playlists
            if ($playlist->user_id !== auth()->id()) {
                abort(403, 'Unauthorized action.');
            }
            
            $validated = $request->validate([
                'spotify_playlist_id' => 'required|string|unique:playlists,spotify_playlist_id,' . $playlist->id,
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'is_active' => 'boolean'
            ]);

            $playlist->update($validated);

            // If this playlist is active, ensure it's the only active playlist for this user
            if ($playlist->is_active) {
                Playlist::ensureOnlyOneActivePerUser(auth()->id());
            }

            return redirect()->route('playlists.index')
                ->with('success', 'Playlist updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating playlist: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update playlist. Please try again.');
        }
    }

    /**
     * Remove the specified playlist from storage.
     *
     * @param  \App\Models\Playlist  $playlist
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Playlist $playlist)
    {
        try {
            // Ensure the user can only delete their own playlists
            if ($playlist->user_id !== auth()->id()) {
                abort(403, 'Unauthorized action.');
            }
            
            $playlist->delete();

            return redirect()->route('playlists.index')
                ->with('success', 'Playlist deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting playlist: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete playlist. Please try again.');
        }
    }
} 