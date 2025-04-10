@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">Playlists</h3>
                        <p class="text-muted mb-0">Manage your Spotify playlists</p>
                    </div>
                    <a href="{{ route('playlists.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add New Playlist
                    </a>
                </div>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 30%">Name</th>
                                <th style="width: 20%">Spotify ID</th>
                                <th style="width: 30%">Description</th>
                                <th style="width: 10%">Status</th>
                                <th style="width: 10%" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($playlists as $playlist)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="bi bi-music-note-list fs-4 text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">{{ $playlist->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code class="bg-light p-1 rounded">{{ $playlist->spotify_playlist_id }}</code>
                                    </td>
                                    <td>
                                        <p class="mb-0 text-muted">{{ Str::limit($playlist->description, 50) }}</p>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill {{ $playlist->is_active ? 'bg-success' : 'bg-danger' }}">
                                            <i class="bi {{ $playlist->is_active ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                            {{ $playlist->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('playlists.edit', $playlist) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               data-bs-toggle="tooltip" 
                                               title="Edit playlist">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('playlists.destroy', $playlist) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this playlist?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="tooltip"
                                                        title="Delete playlist">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-music-note-list fs-1"></i>
                                            <h5 class="mt-3">No playlists found</h5>
                                            <p class="mb-3">Get started by adding your first playlist</p>
                                            <a href="{{ route('playlists.create') }}" class="btn btn-primary">
                                                <i class="bi bi-plus-circle me-2"></i>Add Your First Playlist
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 