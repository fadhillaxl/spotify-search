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
            }
            .show-more-button:hover {
                background-color: #1DB954;
                color: white;
            }
            .track-card.hidden-track {
                display: none;
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
                            
                            <div id="searchResults" class="d-flex flex-column gap-3">
                                <!-- Results will be displayed here -->
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
                const searchForm = document.getElementById('searchForm');
                const searchInput = document.getElementById('searchInput');
                const searchResults = document.getElementById('searchResults');
                const loadingIndicator = document.getElementById('loadingIndicator');
                const noResults = document.getElementById('noResults');
                
                const itemsToShow = 20; // Number of items to show initially
                let currentlyShown = itemsToShow;
                
                // Add this line to define the activePlaylistsContainer
                const activePlaylistsContainer = document.getElementById('activePlaylists');
                
                // Function to display tracks
                function displayTracks(tracks) {
                    if (!tracks || tracks.length === 0) {
                        noResults.classList.remove('d-none');
                        return;
                    }
                    
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
                    
                    // Add the results and show more button if needed
                    searchResults.innerHTML = resultsHTML;
                    
                    // Remove any existing "Show More" button first
                    const existingShowMoreContainer = document.getElementById('showMoreContainer');
                    if (existingShowMoreContainer) {
                        existingShowMoreContainer.remove();
                    }
                    
                    // Add "Show More" button if there are more items to show
                    if (tracks.length > itemsToShow) {
                        searchResults.insertAdjacentHTML('afterend', `
                            <div id="showMoreContainer" class="text-center mt-4">
                                <button id="showMoreButton" class="btn btn-lg show-more-button">
                                    Show More
                                    <i class="bi bi-chevron-down ms-1"></i>
                                </button>
                            </div>
                        `);
                        
                        // Add event listener to the new button
                        document.getElementById('showMoreButton').addEventListener('click', function() {
                            const hiddenTracks = document.querySelectorAll('.track-card.hidden-track');
                            const nextBatch = Array.from(hiddenTracks).slice(0, itemsToShow);
                            
                            nextBatch.forEach(track => {
                                track.classList.remove('hidden-track');
                            });
                            
                            currentlyShown += nextBatch.length;
                            
                            // Remove "Show More" button if all items are shown
                            if (currentlyShown >= tracks.length) {
                                this.parentElement.remove();
                            }
                        });
                    }
                }
                
                // Load playlist tracks when page loads
                function loadPlaylistTracks(playlistids) {
                    loadingIndicator.classList.remove('d-none');
                    noResults.classList.add('d-none');
                    var playlist_ids = playlistids[0];
                    console.log(playlist_ids)
                    // Fetch tracks from the specific playlist using the correct Spotify API endpoint
                    fetch(`/api/playlist/${playlist_ids}/tracks`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Playlist endpoint not available');
                            }
                            // console.log(response)
                            return response.json();
                        })
                        .then(data => {
                            loadingIndicator.classList.add('d-none');
                            // console.log(data)
                            // Handle Spotify API response structure
                            let tracks = [];
                            
                            if (data && data.items && Array.isArray(data.items)) {
                                // Extract track objects from the Spotify API response
                                tracks = data.items.map(item => item.track).filter(track => track);
                            }
                            
                            displayTracks(tracks);
                        })
                        .catch(error => {
                            console.error('Error fetching playlist tracks:', error);
                            
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
                                // displayActivePlaylists(data.playlist_ids);
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
                document.getElementById('showAllSongsButton').addEventListener('click', function() {
                    // Clear any search results
                    searchResults.innerHTML = '';
                    loadingIndicator.classList.remove('d-none');
                    noResults.classList.add('d-none');
                    
                    // Load all songs from the playlist
                    loadPlaylistTracks();
                });
                
                // Handle song request form submission
                document.getElementById('submitSongRequest').addEventListener('click', function() {
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
                        const modal = bootstrap.Modal.getInstance(document.getElementById('songRequestModal'));
                        modal.hide();
                        
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
                
                // Handle request buttons in track cards
                document.addEventListener('click', function(e) {
                    if (e.target.closest('.request-song-btn')) {
                        const button = e.target.closest('.request-song-btn');
                        const songName = button.getAttribute('data-song-name');
                        const artist = button.getAttribute('data-artist');
                        
                        // Pre-fill the form
                        document.getElementById('songName').value = songName;
                        document.getElementById('artistName').value = artist;
                    }
                });
                
                searchForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const query = searchInput.value.trim();
                    if (!query) return;
                    
                    searchResults.innerHTML = '';
                    loadingIndicator.classList.remove('d-none');
                    noResults.classList.add('d-none');
                    console.log(data)
                    // Use the playlist search endpoint instead of the general search
                    fetch(`/api/playlist/1QlwZS4gfx0XepdxpBG1UH/search?query=${encodeURIComponent(query)}`)
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
                                displayActivePlaylists(data.playlist_ids);
                                loadPlaylistTracks(data.playlist_ids);

                            } else {
                                console.error(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching active playlists:', error);
                        });
                }

                // Function to display active playlists
                function displayActivePlaylists(playlistIds) {
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

                // Fetch active playlists when the page loads
                
            });
        </script>
    </body>
</html> 