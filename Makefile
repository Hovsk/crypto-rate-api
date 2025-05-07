# Makefile

# Start Docker containers
start:
	docker-compose up -d

# Install Composer dependencies
install:
	symfony run composer install

# Run database migrations
migrate:
	symfony console doctrine:migrations:migrate --no-interaction

seed:
	php bin/console app:seed-data

fetch:
	php bin/console app:fetch-rates

# Start Symfony server
serve:
	symfony server:start -d

# Run all steps
setup: start install migrate seed fetch serve