@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0"><i class="fas fa-book"></i> Documentation</h2>
                </div>

                <div class="card-body">
                    <section class="mb-5">
                        <h3><i class="fas fa-rocket"></i> Getting Started</h3>
                        <p class="ms-4">Welcome to the Spotify Search application! This application allows you to search for tracks, artists, and albums on Spotify using the Spotify Web API.</p>
                    </section>

                    <section class="mb-5">
                        <h3><i class="fas fa-list-check"></i> Prerequisites</h3>
                        <p class="ms-4">To use this application, you need:</p>
                        <ul class="list-group list-group-flush ms-4">
                            <li class="list-group-item"><i class="fab fa-spotify text-success"></i> A Spotify Developer account</li>
                            <li class="list-group-item"><i class="fas fa-key text-warning"></i> Spotify API credentials (Client ID and Client Secret)</li>
                        </ul>
                    </section>

                    <section class="mb-5">
                        <h3><i class="fas fa-cog"></i> Setting Up Spotify API Credentials</h3>
                        <ol class="list-group list-group-numbered ms-4">
                            <li class="list-group-item">Go to the <a href="https://developer.spotify.com/dashboard" target="_blank" class="text-decoration-none"><i class="fas fa-external-link-alt"></i> Spotify Developer Dashboard</a></li>
                            <li class="list-group-item"><i class="fas fa-sign-in-alt"></i> Log in with your Spotify account</li>
                            <li class="list-group-item"><i class="fas fa-plus-circle"></i> Create a new application</li>
                            <li class="list-group-item"><i class="fas fa-key"></i> Once created, you'll receive a Client ID and Client Secret</li>
                            <li class="list-group-item"><i class="fas fa-save"></i> In this application, go to the Credentials page and add your Client ID and Client Secret</li>
                        </ol>
                    </section>

                    <section class="mb-5">
                        <h3><i class="fas fa-search"></i> Using the Search Feature</h3>
                        <p class="ms-4">Once you've added your Spotify API credentials:</p>
                        <ol class="list-group list-group-numbered ms-4">
                            <li class="list-group-item"><i class="fas fa-location-arrow"></i> Navigate to the Search page</li>
                            <li class="list-group-item"><i class="fas fa-keyboard"></i> Enter your search query in the search box</li>
                            <li class="list-group-item"><i class="fas fa-filter"></i> Select the type of content you want to search for (tracks, artists, or albums)</li>
                            <li class="list-group-item"><i class="fas fa-search"></i> Click the Search button</li>
                            <li class="list-group-item"><i class="fas fa-list"></i> View the results displayed below the search form</li>
                        </ol>
                    </section>

                    <section class="mb-5">
                        <h3><i class="fas fa-user-cog"></i> Managing Credentials</h3>
                        <p class="ms-4">You can manage your Spotify API credentials in the Credentials section:</p>
                        <ul class="list-group list-group-flush ms-4">
                            <li class="list-group-item"><i class="fas fa-plus-circle text-success"></i> Add new credentials</li>
                            <li class="list-group-item"><i class="fas fa-edit text-primary"></i> Edit existing credentials</li>
                            <li class="list-group-item"><i class="fas fa-trash-alt text-danger"></i> Delete credentials you no longer need</li>
                            <li class="list-group-item"><i class="fas fa-toggle-on text-success"></i> Set credentials as active or inactive</li>
                        </ul>
                    </section>

                    <section class="mb-5">
                        <h3><i class="fas fa-question-circle"></i> Need Help?</h3>
                        <p class="ms-4">If you encounter any issues or have questions, please refer to the <a href="https://developer.spotify.com/documentation/web-api" target="_blank" class="text-decoration-none"><i class="fas fa-external-link-alt"></i> Spotify Web API documentation</a> or contact the application administrator.</p>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 