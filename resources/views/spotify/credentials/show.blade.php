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
                            <p class="text-muted mb-0">View credential details</p>
                        </div>
                        <div>
                            <a href="{{ route('spotify.credentials.edit', $credential) }}" class="btn btn-primary me-2">
                                <i class="bi bi-pencil me-2"></i>Edit
                            </a>
                            <a href="{{ route('spotify.credentials.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="card-title fw-bold">Name</h5>
                        <p class="card-text">{{ $credential->name }}</p>
                    </div>

                    <div class="mb-4">
                        <h5 class="card-title fw-bold">Client ID</h5>
                        <p class="card-text">
                            <code class="bg-light p-2 rounded">{{ $credential->client_id }}</code>
                        </p>
                    </div>

                    <div class="mb-4">
                        <h5 class="card-title fw-bold">Status</h5>
                        <p class="card-text">
                            @if($credential->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-4">
                        <h5 class="card-title fw-bold">Created At</h5>
                        <p class="card-text">{{ $credential->created_at->format('F j, Y, g:i a') }}</p>
                    </div>

                    <div class="mb-4">
                        <h5 class="card-title fw-bold">Last Updated</h5>
                        <p class="card-text">{{ $credential->updated_at->format('F j, Y, g:i a') }}</p>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <form action="{{ route('spotify.credentials.destroy', $credential) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete these credentials?')">
                                <i class="bi bi-trash me-2"></i>Delete Credentials
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 