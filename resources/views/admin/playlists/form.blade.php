@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">{{ isset($playlist) ? 'Edit Playlist' : 'Add New Playlist' }}</h3>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ isset($playlist) ? route('admin.playlists.update', $playlist) : route('admin.playlists.store') }}">
                        @csrf
                        @if(isset($playlist))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label for="name" class="form-label">Playlist Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $playlist->name ?? '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="spotify_playlist_id" class="form-label">Spotify Playlist ID</label>
                            <input type="text" class="form-control @error('spotify_playlist_id') is-invalid @enderror" id="spotify_playlist_id" name="spotify_playlist_id" value="{{ old('spotify_playlist_id', $playlist->spotify_playlist_id ?? '') }}" required>
                            <div class="form-text">Enter the Spotify playlist ID (e.g., 4cxDUFNaxogK9wYjHmvhN4)</div>
                            @error('spotify_playlist_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $playlist->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $playlist->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.playlists.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                {{ isset($playlist) ? 'Update Playlist' : 'Add Playlist' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 