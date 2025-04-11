<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Spotify Search</title>

        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        <!-- Custom CSS -->
        <style>
            body {
                background-color: #f8f9fa;
            }
            .search-card {
                box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
                border: none;
            }
            .search-button {
                background-color: #1DB954;
                border-color: #1DB954;
            }
            .search-button:hover {
                background-color: #1aa34a;
                border-color: #1aa34a;
            }
            .track-card:hover {
                transform: translateY(-2px);
                transition: all 0.3s ease;
                background-color: #f8f9fa;
            }
            .loading-spinner {
                width: 3rem;
                height: 3rem;
                color: #1DB954;
            }
            .show-more-button {
                background-color: transparent;
                border: 2px solid #1DB954;
                color: #1DB954;
                transition: all 0.3s ease;
                margin-top: 1rem;
                padding: 0.5rem 1.5rem;
                display: inline-block !important;
            }
            .show-more-button:hover {
                background-color: #1DB954;
                color: white;
            }
            .track-card.hidden-track {
                display: none;
            }
            .pagination {
                margin-top: 1rem;
                justify-content: center;
                display: flex !important;
            }
            .pagination .page-link {
                color: #1DB954;
                border-color: #1DB954;
                padding: 0.5rem 1rem;
                margin: 0 0.25rem;
            }
            .pagination .page-item.active .page-link {
                background-color: #1DB954;
                border-color: #1DB954;
                color: white;
            }
            .pagination .page-link:hover {
                background-color: #1DB954;
                border-color: #1DB954;
                color: white;
            }
            .pagination .page-item.disabled .page-link {
                color: #6c757d;
                border-color: #dee2e6;
                pointer-events: none;
            }
        </style>
    </head>
    <body>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="text-center mb-4">
                        <h1 class="display-5 fw-bold">
                        <a href="https://saweria.co/youthband" target="_blank">
                        Donasi Sekarang
                        </a>

                            <i class="bi bi-spotify me-2"></i>Spotify Search
                        </h1>
                    </div>
                    
                    @if(Auth::check())
                        @php
                            $activeCredentials = app(App\Services\SpotifyService::class)->getActiveCredentials();
                        @endphp
                        
                        @if($activeCredentials)
                            <div class="alert alert-info mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <div>
                                        <strong>Using credentials:</strong> {{ $activeCredentials->name }}
                                        <div class="small text-muted">
                                            Client ID: {{ Str::limit($activeCredentials->client_id, 15) }}...
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card search-card">
                                <div class="card-body p-4">
                                    <form id="searchForm" class="mb-4">
                                        <div class="input-group">
                                            <input 
                                                type="text" 
                                                id="searchInput" 
                                                name="query" 
                                                class="form-control form-control-lg"
                                                placeholder="Search for songs, artists, albums..." 
                                                required
                                            >
                                            <button 
                                                type="submit" 
                                                class="btn btn-lg search-button text-white"
                                            >
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                    </form>
                                    
                                    <div class="text-center mb-3">
                                        <button id="showAllSongsButton" class="btn btn-outline-success">
                                            <i class="bi bi-music-note-list me-1"></i>Show All Songs
                                        </button>
                                        <button id="requestSongButton" class="btn btn-outline-primary ms-2" data-bs-toggle="modal" data-bs-target="#songRequestModal">
                                            <i class="bi bi-plus-circle me-1"></i>Request a Song
                                        </button>
                                    </div>
                                    
                                    <div id="playlistInfo" class="text-center mb-3 d-none">
                                        <span class="badge bg-success">
                                            <i class="bi bi-music-note-list me-1"></i>
                                            <span id="totalTracksCount">0</span> tracks in playlist
                                        </span>
                                    </div>
                                    
                                    <div id="searchResults" class="d-flex flex-column gap-3">
                                        <!-- Results will be displayed here -->
                                    </div>
                                    
                                    <div id="showMoreContainer" class="text-center mt-4">
                                        <button id="showMoreButton" class="btn btn-lg show-more-button">
                                            Show More
                                            <i class="bi bi-chevron-down ms-1"></i>
                                        </button>
                                    </div>
                                    
                                    <div id="loadingIndicator" class="text-center py-4 d-none">
                                        <div class="spinner-border loading-spinner" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-3 text-muted">Searching...</p>
                                    </div>
                                    
                                    <div id="noResults" class="text-center py-4 text-muted d-none">
                                        <i class="bi bi-emoji-frown fs-1"></i>
                                        <p class="mt-2">No results found. Try a different search term.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Active Playlists Section -->
                            <div class="mt-5">
                                <h3 class="text-center">Active Playlists</h3>
                                <div id="activePlaylists" class="d-flex flex-column gap-3">
                                    <!-- Active playlists will be displayed here -->
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <div>
                                        <strong>No active credentials found.</strong>
                                        <a href="{{ route('spotify.credentials.create') }}" class="alert-link">Add your Spotify API credentials</a> to use your own account.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card search-card">
                                <div class="card-body p-4 text-center">
                                    <i class="bi bi-lock-fill text-muted" style="font-size: 3rem;"></i>
                                    <h4 class="mt-3">Search Feature Locked</h4>
                                    <p class="text-muted">You need to add Spotify API credentials to use the search feature.</p>
                                    <div class="d-flex justify-content-center gap-2 mt-3">
                                        <a href="{{ route('spotify.credentials.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-2"></i>Add Your Credentials
                                        </a>
                                        <a href="{{ route('documentation') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-book me-2"></i>View Documentation
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="card search-card">
                            <div class="card-body p-4 text-center">
                                <i class="bi bi-person-lock text-muted" style="font-size: 3rem;"></i>
                                <h4 class="mt-3">Authentication Required</h4>
                                <p class="text-muted">Please log in to use the search feature.</p>
                                <a href="{{ route('login') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Log In
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                

            </div>
        </div>
        
        <!-- Song Request Modal -->
        <div class="modal fade" id="songRequestModal" tabindex="-1" aria-labelledby="songRequestModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="songRequestModalLabel">Request a Song</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="songRequestForm">
                            <div class="mb-3">
                                <label for="requestName" class="form-label">Your Name</label>
                                <input type="text" class="form-control" id="requestName" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="songName" class="form-label">Song Name</label>
                                <input type="text" class="form-control" id="songName" name="song_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="artistName" class="form-label">Artist (Optional)</label>
                                <input type="text" class="form-control" id="artistName" name="artist">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="submitSongRequest">Submit Request</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Request Success Alert -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="requestSuccessToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Song request submitted successfully!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>

        <!-- Bootstrap 5 JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Bootstrap modals
                const songRequestModal = new bootstrap.Modal(document.getElementById('songRequestModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                
                const searchForm = document.getElementById('searchForm');
                const searchInput = document.getElementById('searchInput');
                const searchResults = document.getElementById('searchResults');
                const loadingIndicator = document.getElementById('loadingIndicator');
                const noResults = document.getElementById('noResults');
                const showMoreContainer = document.getElementById('showMoreContainer');
                const showMoreButton = document.getElementById('showMoreButton');
                const requestSongButton = document.getElementById('requestSongButton');
                const submitSongRequest = document.getElementById('submitSongRequest');
                const activePlaylistsContainer = document.getElementById('activePlaylists');
                
                // Only initialize event listeners if the elements exist (user has active credentials)
                if (searchForm && searchInput && searchResults) {
                    const itemsToShow = 10; // Number of items to show initially
                    let currentlyShown = itemsToShow;
                    let allTracks = [];
                    
                    // Store the active playlist ID
                    let activePlaylistId = null;
                    
                    // Function to display tracks
                    function displayTracks(tracks, showAll = false) {
                        if (!tracks || tracks.length === 0) {
                            noResults.classList.remove('d-none');
                            showMoreContainer.style.display = 'none';
                            document.getElementById('playlistInfo').classList.add('d-none');
                            return;
                        }
                        
                        allTracks = tracks;
                        currentlyShown = itemsToShow;
                        
                        // Clear existing results
                        searchResults.innerHTML = '';
                        
                        // Update total tracks count
                        document.getElementById('totalTracksCount').textContent = tracks.length;
                        document.getElementById('playlistInfo').classList.remove('d-none');
                        
                        // Generate all results HTML
                        const resultsHTML = tracks.map((track, index) => `
                            <div class="card track-card ${index >= itemsToShow ? 'hidden-track' : ''}" data-index="${index}">
                                <div class="card-body d-flex align-items-center gap-3 p-3">
                                    <img 
                                        src="${track.album?.images?.[0]?.url || 'https://via.placeholder.com/64'}" 
                                        alt="${track.album?.name || 'Album'}" 
                                        class="rounded"
                                        width="64" 
                                        height="64"
                                    >
                                    <div class="flex-grow-1">
                                        <h5 class="card-title mb-1">${track.name || 'Unknown Track'}</h5>
                                        <p class="card-text text-muted mb-1">
                                            <i class="bi bi-person-fill me-1"></i>
                                            ${track.artists ? track.artists.map(artist => artist.name).join(', ') : 'Unknown Artist'}
                                        </p>
                                        <p class="card-text text-muted small mb-0">
                                            <i class="bi bi-disc-fill me-1"></i>
                                            ${track.album?.name || 'Unknown Album'}
                                        </p>
                                    </div>
                                    <div>
                                        <button class="btn btn-sm btn-outline-primary request-song-btn" 
                                            data-song-name="${track.name || 'Unknown Track'}"
                                            data-artist="${track.artists ? track.artists.map(artist => artist.name).join(', ') : 'Unknown Artist'}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#songRequestModal">
                                            <i class="bi bi-plus-circle"></i> Request
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `).join('');
                        
                        // Add the results
                        searchResults.innerHTML = resultsHTML;
                        
                        // Debug logging
                        console.log('Total tracks:', tracks.length);
                        console.log('Items to show:', itemsToShow);
                        console.log('Currently shown:', currentlyShown);
                        
                        // Show/hide "Show More" button based on remaining tracks
                        const remainingTracks = tracks.length - currentlyShown;
                        console.log('Remaining tracks:', remainingTracks);
                        
                        if (remainingTracks > 0) {
                            console.log('Showing "Show More" button');
                            showMoreContainer.style.display = 'block';
                            showMoreButton.textContent = `Show More (${remainingTracks} more)`;
                        } else {
                            // console.log('Hiding "Show More" button');
                            // showMoreContainer.style.display = 'none';
                        }
                    }
                    
                    // Function to show more tracks
                    function showMoreTracks() {
                        const hiddenTracks = document.querySelectorAll('.track-card.hidden-track');
                        const tracksToShow = Math.min(hiddenTracks.length, itemsToShow);
                        
                        console.log('Hidden tracks:', hiddenTracks.length);
                        console.log('Tracks to show:', tracksToShow);
                        
                        for (let i = 0; i < tracksToShow; i++) {
                            hiddenTracks[i].classList.remove('hidden-track');
                        }
                        
                        currentlyShown += tracksToShow;
                        
                        // Update "Show More" button text and visibility
                        const remainingTracks = allTracks.length - currentlyShown;
                        if (remainingTracks > 0) {
                            showMoreButton.textContent = `Show More (${remainingTracks} more)`;
                        } else {
                            showMoreContainer.style.display = 'none';
                        }
                    }
                    
                    // Load playlist tracks when page loads
                    function loadPlaylistTracks(playlistids, showAll = false) {
                        loadingIndicator.classList.remove('d-none');
                        noResults.classList.add('d-none');
                        
                        // Check if playlistids is provided and has at least one element
                        if (!playlistids || !playlistids.length) {
                            // If no playlist IDs provided, use the active playlist ID or default
                            const playlistId = activePlaylistId;
                            fetchPlaylistTracks(playlistId, showAll);
                            return;
                        }
                        
                        var playlist_ids = playlistids[0];
                        console.log(playlist_ids);
                        
                        // Store the active playlist ID for later use
                        activePlaylistId = playlist_ids;
                        
                        // Fetch tracks from the specific playlist
                        fetchPlaylistTracks(playlist_ids, showAll);
                    }
                    
                    // Helper function to fetch tracks from a playlist
                    function fetchPlaylistTracks(playlistId, showAll = false) {
                        fetch(`/api/playlist/${playlistId}/tracks`)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Playlist endpoint not available');
                                }
                                return response.json();
                            })
                            .then(data => {
                                loadingIndicator.classList.add('d-none');
                                console.log(data)
                                // Handle Spotify API response structure
                                let tracks = [];
                                
                                if (data && data.items && Array.isArray(data.items)) {
                                    // Extract track objects from the Spotify API response
                                    tracks = data.items.map(item => item.track).filter(track => track);
                                }
                                
                                displayTracks(tracks, showAll);
                            })
                            .catch(error => {
                                console.error('Error fetching playlist tracks:', error);
                                loadingIndicator.classList.add('d-none');
                                searchResults.innerHTML = `
                                    <div class="alert alert-danger text-center" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        An error occurred while loading tracks. Please try again.
                                    </div>
                                `;
                            });
                    }
                    
                    // Function to fetch active playlists
                    function fetchActivePlaylists() {
                        fetch('/api/active-playlists')
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Failed to fetch active playlists');
                                }
                                // console.log(response);
                                return response.json();
                            })
                            .then(data => {
                                console.log(data)
                                if (data.success) {
                                    // Only call displayActivePlaylists if the container exists
                                    if (activePlaylistsContainer) {
                                        displayActivePlaylists(data.playlist_ids);
                                    }
                                    loadPlaylistTracks(data.playlist_ids);
                                } else {
                                    console.error(data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching active playlists:', error);
                            });
                    }
                    
                    // Call loadPlaylistTracks when page loads
                    // fetchActivePlaylists();
                    fetchActivePlaylists();
                    
                    // Add event listener for the "Show All Songs" button
                    const showAllSongsButton = document.getElementById('showAllSongsButton');
                    if (showAllSongsButton) {
                        showAllSongsButton.addEventListener('click', function() {
                            // Clear any search results
                            searchResults.innerHTML = '';
                            loadingIndicator.classList.remove('d-none');
                            noResults.classList.add('d-none');
                            
                            // Load all songs from the playlist with showAll=true
                            loadPlaylistTracks(null, true);
                        });
                    }
                    
                    // Show modal when request song button is clicked
                    if (requestSongButton) {
                        requestSongButton.addEventListener('click', function() {
                            songRequestModal.show();
                        });
                    }
                    
                    // Handle song request form submission
                    if (submitSongRequest) {
                        submitSongRequest.addEventListener('click', function() {
                            const form = document.getElementById('songRequestForm');
                            const formData = new FormData(form);
                            const data = {};
                            
                            // Convert FormData to JSON object
                            for (const [key, value] of formData.entries()) {
                                data[key] = value;
                            }
                            
                            // Show loading state
                            this.disabled = true;
                            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...';
                            
                            // Submit the request
                            fetch('/api/song-request', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify(data)
                            })
                            .then(response => response.json())
                            .then(result => {
                                // Reset button state
                                this.disabled = false;
                                this.innerHTML = 'Submit Request';
                                
                                // Close the modal
                                songRequestModal.hide();
                                
                                // Reset the form
                                form.reset();
                                
                                // Show success toast
                                const toast = new bootstrap.Toast(document.getElementById('requestSuccessToast'));
                                toast.show();

                                // Redirect to Saweria after a short delay
                                setTimeout(() => {
                                    window.location.href = 'https://saweria.co/youthband';
                                }, 1500);
                            })
                            .catch(error => {
                                console.error('Error submitting song request:', error);
                                
                                // Reset button state
                                this.disabled = false;
                                this.innerHTML = 'Submit Request';
                                
                                // Show error message
                                alert('An error occurred while submitting your request. Please try again.');
                            });
                        });
                    }
                    
                    // Handle request buttons in track cards
                    document.addEventListener('click', function(e) {
                        if (e.target.closest('.request-song-btn')) {
                            const button = e.target.closest('.request-song-btn');
                            const songName = button.getAttribute('data-song-name');
                            const artist = button.getAttribute('data-artist');
                            
                            // Pre-fill the form
                            document.getElementById('songName').value = songName;
                            document.getElementById('artistName').value = artist;
                            
                            // Show the modal
                            songRequestModal.show();
                        }
                    });
                    
                    searchForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        
                        const query = searchInput.value.trim();
                        if (!query) return;
                        
                        searchResults.innerHTML = '';
                        loadingIndicator.classList.remove('d-none');
                        noResults.classList.add('d-none');
                        
                        // Use the active playlist ID if available, otherwise use a default
                        const playlistId = activePlaylistId;
                        
                        // Use the playlist search endpoint with the active playlist ID
                        fetch(`/api/playlist/${playlistId}/search?query=${encodeURIComponent(query)}`)
                            .then(response => response.json())
                            .then(data => {
                                loadingIndicator.classList.add('d-none');
                                
                                // Handle different possible response structures
                                let tracks = [];
                                let total = 0;
                                
                                if (Array.isArray(data)) {
                                    tracks = data;
                                } else if (data && typeof data === 'object') {
                                    if (data.tracks && Array.isArray(data.tracks)) {
                                        tracks = data.tracks;
                                    } else if (data.tracks && data.tracks.items && Array.isArray(data.tracks.items)) {
                                        tracks = data.tracks.items;
                                        total = data.tracks.total || 0;
                                    } else if (data.items && Array.isArray(data.items)) {
                                        tracks = data.items;
                                        total = data.total || 0;
                                    }
                                }
                                displayTracks(tracks);
                            })
                            .catch(error => {
                                console.error('Error fetching search results:', error);
                                loadingIndicator.classList.add('d-none');
                                searchResults.innerHTML = `
                                    <div class="alert alert-danger text-center" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        An error occurred while searching. Please try again.
                                    </div>
                                `;
                            });
                    });

                    // Function to display active playlists
                    function displayActivePlaylists(playlistIds) {
                        if (!activePlaylistsContainer) return;
                        
                        if (playlistIds.length === 0) {
                            activePlaylistsContainer.innerHTML = '<p class="text-muted">No active playlists found.</p>';
                            return;
                        }

                        const playlistsHTML = playlistIds.map(id => `
                            <div class="card track-card">
                                <div class="card-body">
                                    <h5 class="card-title">Playlist ID: ${id}</h5>
                                </div>
                            </div>
                        `).join('');

                        activePlaylistsContainer.innerHTML = playlistsHTML;
                    }

                    // Add event listener for the "Show More" button
                    if (showMoreButton) {
                        showMoreButton.addEventListener('click', showMoreTracks);
                    }
                }
            });
        </script>
    </body>
</html> 