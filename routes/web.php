<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotifyController;
use App\Http\Controllers\DashboardController;

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
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::patch('/api/song-request/{id}/status', [DashboardController::class, 'updateStatus'])->name('song.request.update.status');
