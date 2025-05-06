# Makefile

# Start Docker containers
start:
	docker-compose up -d

# Install Composer dependencies
install:
	symfony run composer install

# Set up environment variables
env:
	@NEW_DB_URL=$$(symfony var:export --multiline | grep DATABASE_URL | cut -d '=' -f2-); \
	if [ -n "$$NEW_DB_URL" ]; then \
		sed -i '' "s|^DATABASE_URL=.*|DATABASE_URL=|" .env; \
		sed -i '' "s|^DATABASE_URL=.*|DATABASE_URL=$$NEW_DB_URL|" .env; \
		echo "DATABASE_URL updated in .env file to $$NEW_DB_URL."; \
	else \
		echo "No DATABASE_URL found or it's empty."; \
	fi

# Run database migrations
migrate:
	symfony console doctrine:migrations:migrate --no-interaction

# Start Symfony server
serve:
	symfony server:start -d

# Run all steps
setup: start install env migrate serve