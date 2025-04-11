@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">Spotify API Credentials</h3>
                            <p class="text-muted mb-0">Manage your Spotify API credentials</p>
                        </div>
                        <a href="{{ route('spotify.credentials.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Add New Credentials
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($credentials->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-spotify text-muted" style="font-size: 3rem;"></i>
                            <h4 class="mt-3">No Spotify API Credentials</h4>
                            <p class="text-muted">You haven't added any Spotify API credentials yet.</p>
                            <a href="{{ route('spotify.credentials.create') }}" class="btn btn-primary mt-2">
                                <i class="bi bi-plus-circle me-2"></i>Add Your First Credentials
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Client ID</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($credentials as $credential)
                                        <tr>
                                            <td>{{ $credential->name }}</td>
                                            <td>
                                                <code class="text-muted">{{ Str::limit($credential->client_id, 20) }}</code>
                                            </td>
                                            <td>
                                                @if($credential->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $credential->created_at->format('M j, Y') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('spotify.credentials.show', $credential) }}" 
                                                       class="btn btn-sm btn-outline-secondary" 
                                                       data-bs-toggle="tooltip" 
                                                       title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('spotify.credentials.edit', $credential) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       data-bs-toggle="tooltip" 
                                                       title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('spotify.credentials.destroy', $credential) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete these credentials?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-outline-danger" 
                                                                data-bs-toggle="tooltip" 
                                                                title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 