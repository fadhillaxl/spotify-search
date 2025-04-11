@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">Edit Spotify API Credentials</h3>
                            <p class="text-muted mb-0">Update your Spotify API credentials</p>
                        </div>
                        <a href="{{ route('spotify.credentials.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Credentials
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('spotify.credentials.update', $credential) }}" class="needs-validation" novalidate autocomplete="on">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">Credential Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-tag text-primary"></i>
                                </span>
                                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $credential->name) }}" required 
                                       placeholder="Enter a name for these credentials" autocomplete="off">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Give your credentials a descriptive name to identify them later
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="client_id" class="form-label fw-bold">Client ID</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-key text-primary"></i>
                                </span>
                                <input type="text" class="form-control form-control-lg @error('client_id') is-invalid @enderror" 
                                       id="client_id" name="client_id" value="{{ old('client_id', $credential->client_id) }}" required 
                                       placeholder="Enter your Spotify Client ID" autocomplete="off">
                                @error('client_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                You can find your Client ID in your Spotify Developer Dashboard
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="client_secret" class="form-label fw-bold">Client Secret</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-shield-lock text-primary"></i>
                                </span>
                                <input type="password" class="form-control form-control-lg @error('client_secret') is-invalid @enderror" 
                                       id="client_secret" name="client_secret" value="{{ old('client_secret') }}" 
                                       placeholder="Leave blank to keep current secret" autocomplete="off">
                                @error('client_secret')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Leave this field blank to keep your current client secret
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', $credential->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_active">Active</label>
                            </div>
                            <div class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Only one set of credentials can be active at a time
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save me-2"></i>Update Credentials
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 