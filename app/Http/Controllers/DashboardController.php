<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SongRequest;
use App\Events\SongRequestUpdated;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with song requests
     */
    public function index()
    {
        $songRequests = SongRequest::orderBy('created_at', 'desc')->get();
        
        return view('dashboard', compact('songRequests'));
    }
    
    /**
     * Update the status of a song request
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);
        
        $songRequest = SongRequest::findOrFail($id);
        $songRequest->status = $validated['status'];
        $songRequest->save();
        
        // Broadcast the update event
        event(new SongRequestUpdated($songRequest));
        
        return response()->json([
            'success' => true,
            'message' => 'Song request status updated successfully',
            'data' => $songRequest
        ]);
    }
}
