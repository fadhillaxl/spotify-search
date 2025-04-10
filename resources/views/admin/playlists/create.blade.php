@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">Add New Playlist 1</h3>
                        <p class="text-muted mb-0">Create a new Spotify playlist</p>
                    </div>
                    <a href="{{ route('playlists.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Playlists
                    </a>
                </div>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('playlists.store') }}" class="needs-validation" novalidate autocomplete="on">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold">Playlist Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-music-note-list text-primary"></i>
                            </span>
                            <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required 
                                   placeholder="Enter playlist name" autocomplete="off">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="spotify_playlist_id" class="form-label fw-bold">Spotify Playlist ID</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-spotify text-primary"></i>
                            </span>
                            <input type="text" class="form-control form-control-lg @error('spotify_playlist_id') is-invalid @enderror" 
                                   id="spotify_playlist_id" name="spotify_playlist_id" value="{{ old('spotify_playlist_id') }}" 
                                   required placeholder="Enter Spotify playlist ID" autocomplete="off">
                            @error('spotify_playlist_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            You can find the playlist ID in the Spotify URL (e.g., 4cxDUFNaxogK9wYjHmvhN4)
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-card-text text-primary"></i>
                            </span>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Enter playlist description" autocomplete="off">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }} autocomplete="off">
                            <label class="form-check-label fw-bold" for="is_active">
                                <i class="bi bi-toggle-on me-1"></i>Active Playlist
                            </label>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-plus-circle me-2"></i>Add Playlist
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 