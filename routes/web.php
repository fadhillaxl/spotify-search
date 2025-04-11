<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotifyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SpotifyApiCredentialController;
use App\Http\Controllers\DocumentationController;

Route::middleware(['web'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    // Documentation route
    Route::get('/documentation', [DocumentationController::class, 'index'])->name('documentation');

    // Display the search page
    Route::get('/search', [SpotifyController::class, 'index'])->name('search.index');

    // API endpoint for searching
    Route::get('/api/search', [SpotifyController::class, 'search'])->name('search.api');

    // API endpoint for playlist tracks
    Route::get('/api/playlist/{playlistId}/tracks', [SpotifyController::class, 'playlistTracks'])->name('playlist.tracks');

    // API endpoint for searching within a playlist
    Route::get('/api/playlist/{playlistId}/search', [SpotifyController::class, 'searchPlaylist'])->name('playlist.search');

    // API endpoint for song requests
    Route::post('/api/song-request', [SpotifyController::class, 'storeSongRequest'])->name('song.request.store');

    // Dashboard routes
    Route::patch('/api/song-request/{id}/status', [DashboardController::class, 'updateStatus'])->name('song.request.update.status');

    // Playlist routes with authentication
    Route::middleware(['auth'])->group(function () {
        Route::get('/documentation', [DocumentationController::class, 'index'])->name('documentation');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/overlay', [DashboardController::class, 'overlay'])->name('overlay');


        Route::get('/playlists', [PlaylistController::class, 'index'])->name('playlists.index');
        Route::get('/playlists/create', [PlaylistController::class, 'create'])->name('playlists.create');
        Route::post('/playlists', [PlaylistController::class, 'store'])->name('playlists.store');
        Route::get('/playlists/{playlist}/edit', [PlaylistController::class, 'edit'])->name('playlists.edit');
        Route::patch('/playlists/{playlist}', [PlaylistController::class, 'update'])->name('playlists.update');
        Route::delete('/playlists/{playlist}', [PlaylistController::class, 'destroy'])->name('playlists.destroy');
        // CRUD API routes for Spotify
        Route::get('/api/spotify', [SpotifyController::class, 'index'])->name('spotify.index'); // Get all Spotify items
        Route::post('/api/spotify', [SpotifyController::class, 'store'])->name('spotify.store'); // Create a new Spotify item
        Route::get('/api/spotify/{id}', [SpotifyController::class, 'show'])->name('spotify.show'); // Get a specific Spotify item
        Route::patch('/api/spotify/{id}', [SpotifyController::class, 'update'])->name('spotify.update'); // Update a specific Spotify item
        Route::delete('/api/spotify/{id}', [SpotifyController::class, 'destroy'])->name('spotify.destroy'); // Delete a specific Spotify item
        
        // Spotify API Credentials routes
        Route::resource('spotify/credentials', SpotifyApiCredentialController::class)->names([
            'index' => 'spotify.credentials.index',
            'create' => 'spotify.credentials.create',
            'store' => 'spotify.credentials.store',
            'show' => 'spotify.credentials.show',
            'edit' => 'spotify.credentials.edit',
            'update' => 'spotify.credentials.update',
            'destroy' => 'spotify.credentials.destroy',
        ]);
    });


    Route::get('/api/active-playlists', [PlaylistController::class, 'getActivePlaylistIds']);

    require __DIR__.'/auth.php';
});
