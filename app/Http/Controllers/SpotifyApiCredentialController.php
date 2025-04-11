<?php

namespace App\Http\Controllers;

use App\Models\SpotifyApiCredential;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SpotifyApiCredentialController extends Controller
{
    /**
     * Display a listing of the credentials.
     */
    public function index()
    {
        $credentials = SpotifyApiCredential::where('user_id', Auth::id())
            ->latest()
            ->get();
            
        return view('spotify.credentials.index', compact('credentials'));
    }

    /**
     * Show the form for creating a new credential.
     */
    public function create()
    {
        return view('spotify.credentials.create');
    }

    /**
     * Store a newly created credential in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'required|string|max:255',
            'client_secret' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        // If this credential is active, deactivate any other active credentials
        if ($validated['is_active'] ?? false) {
            SpotifyApiCredential::where('user_id', Auth::id())
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $credential = SpotifyApiCredential::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'client_id' => $validated['client_id'],
            'client_secret' => $validated['client_secret'],
            'is_active' => $validated['is_active'] ?? false,
        ]);

        return redirect()->route('spotify.credentials.index')
            ->with('success', 'Spotify API credentials created successfully.');
    }

    /**
     * Display the specified credential.
     */
    public function show(SpotifyApiCredential $credential)
    {
        // Ensure the user can only view their own credentials
        if ($credential->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('spotify.credentials.show', compact('credential'));
    }

    /**
     * Show the form for editing the specified credential.
     */
    public function edit(SpotifyApiCredential $credential)
    {
        // Ensure the user can only edit their own credentials
        if ($credential->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('spotify.credentials.edit', compact('credential'));
    }

    /**
     * Update the specified credential in storage.
     */
    public function update(Request $request, SpotifyApiCredential $credential)
    {
        // Ensure the user can only update their own credentials
        if ($credential->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'required|string|max:255',
            'client_secret' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        // If this credential is being activated, deactivate any other active credentials
        if ($validated['is_active'] ?? false) {
            SpotifyApiCredential::where('user_id', Auth::id())
                ->where('id', '!=', $credential->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        // Only update client_secret if a new one is provided
        if (empty($validated['client_secret'])) {
            unset($validated['client_secret']);
        }

        $credential->update($validated);

        return redirect()->route('spotify.credentials.index')
            ->with('success', 'Spotify API credentials updated successfully.');
    }

    /**
     * Remove the specified credential from storage.
     */
    public function destroy(SpotifyApiCredential $credential)
    {
        // Ensure the user can only delete their own credentials
        if ($credential->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $credential->delete();

        return redirect()->route('spotify.credentials.index')
            ->with('success', 'Spotify API credentials deleted successfully.');
    }
}
