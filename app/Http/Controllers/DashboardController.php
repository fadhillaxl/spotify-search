<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SongRequest;
use App\Models\PlaylistSetting;
use App\Events\SongRequestUpdated;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with song requests
     */
    public function index()
    {
        $songRequests = SongRequest::orderBy('created_at', 'desc')->get();
        $playlistSettings = PlaylistSetting::first();
        
        return view('dashboard', compact('songRequests', 'playlistSettings'));
    }

    public function overlay()
    {
        $songRequests = SongRequest::orderBy('created_at', 'desc')->get();
        $playlistSettings = PlaylistSetting::first();
        
        return view('overlay', compact('songRequests', 'playlistSettings'));
    }
    
    /**
     * Update the status of a song request
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);
        
        $songRequest = SongRequest::findOrFail($id);
        $songRequest->update($validated);
        
        // Broadcast the update event
        broadcast(new SongRequestUpdated($songRequest))->toOthers();
        
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $songRequest
        ]);
    }

    public function updatePlaylistSettings(Request $request)
    {
        $validated = $request->validate([
            'playlist_id' => 'required|string',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $settings = PlaylistSetting::firstOrNew();
        $settings->fill($validated);
        $settings->save();

        return response()->json([
            'success' => true,
            'message' => 'Playlist settings updated successfully',
            'data' => $settings
        ]);
    }
}
