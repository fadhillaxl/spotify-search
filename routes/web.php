<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotifyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\AuthController;

Route::middleware(['web'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

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
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/overlay', [DashboardController::class, 'overlay'])->name('overlay');


        Route::get('/playlists', [PlaylistController::class, 'index'])->name('playlists.index');
        Route::get('/playlists/create', [PlaylistController::class, 'create'])->name('playlists.create');
        Route::post('/playlists', [PlaylistController::class, 'store'])->name('playlists.store');
        Route::get('/playlists/{playlist}/edit', [PlaylistController::class, 'edit'])->name('playlists.edit');
        Route::patch('/playlists/{playlist}', [PlaylistController::class, 'update'])->name('playlists.update');
        Route::delete('/playlists/{playlist}', [PlaylistController::class, 'destroy'])->name('playlists.destroy');
    });


    Route::get('/api/active-playlists', [PlaylistController::class, 'getActivePlaylistIds']);

    require __DIR__.'/auth.php';
});
