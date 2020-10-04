#!/bin/bash
function pprint() {
    echo -e "\e[1;35m -> $1\e[0m"
}

function eprint() {
    echo -e "\e[1;31m -> $1\e[0m"
}

pprint "Installing composer packages..."
docker-compose exec app composer install

# Create a .env file (if it does not yet exist)
if [ ! -f ".env" ]; then
    if [ ! -f ".env.example" ]; then
        eprint "Could not create .env from .env.example, please create it manually."
    else
        pprint "Creating .env from .env.example..."
        cp .env.example .env
    fi
fi

# Set the application key (requires .env file)
if [ ! -f ".env" ]; then
    eprint "Could not set application key. Try to create it manually via: php artisan key:generate"
else
    pprint "Setting application key..."
    php artisan key:generate
fi

# Set permissions
docker-compose exec app chmod -R 775 storage/logs
# Run migrations
pprint "Running migrations..."
docker-compose exec app php artisan migrate

# Run seeds
pprint "Seeding database..."
docker-compose exec app php artisan db:seed

# Start the server
pprint "Starting server..."
docker-compose exec -d app php artisan serve --host=0.0.0.0 --port=80
pprint "Done!"
