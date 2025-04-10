#!/bin/bash

# Stop any running containers
docker-compose down

# Copy the Docker environment file
cp .env.docker .env

# Generate a new application key
docker-compose run --rm app php artisan key:generate

# Build and start the containers
docker-compose up -d --build

# Wait for the database to be ready
echo "Waiting for database to be ready..."
sleep 10

# Run migrations
docker-compose exec app php artisan migrate --force

# Clear caches
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear

echo "Deployment completed!" 