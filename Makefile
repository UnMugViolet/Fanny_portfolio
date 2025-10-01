COMPOSER = composer
COMPOSER_PROD = composer2
DOCKER = docker
DOCKER_COMPOSE = docker compose
ARTISAN = php artisan
NPM = npm

APP_CONTAINER = fanny-portfolio

# Colors for output
BOLD = \033[1m
UNDERLINE = \033[4m
CLR_RESET = \033[0m
CLR_GREEN = \033[32m
CLR_YELLOW = \033[33m
CLR_RED = \033[31m

help:
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s$(CLR_RESET) %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Installation ——————————————————————————————————————————————————————————————

install: ## Install all dependencies (need php composer npm installed)
	@echo "$(CLR_YELLOW) Installing dependencies...$(CLR_RESET)"
	@sudo -v && sudo apt install php8.3-curl php8.3-mysql php8.3-xml php8.3-fpm php8.3-mbstring mysql-server -y
	@$(COMPOSER) update --with-all-dependencies
	@$(COMPOSER) install
	@$(NPM) install --force

## —— Developpement ——————————————————————————————————————————————————————————————

clean: ## Clean the cache and compiled files
	@echo "$(CLR_YELLOW) Cleaning cache and compiled files...$(CLR_RESET)"
	@$(DOCKER) exec -it $(APP_CONTAINER) $(ARTISAN) optimize:clear
	@$(DOCKER) exec -it $(APP_CONTAINER) $(ARTISAN) config:clear
	@$(DOCKER) exec -it $(APP_CONTAINER) $(ARTISAN) route:clear
	@$(DOCKER) exec -it $(APP_CONTAINER) $(ARTISAN) view:clear
	@$(DOCKER) exec -it $(APP_CONTAINER) $(ARTISAN) cache:clear

fclean: ## Run database migrations
	@echo "$(CLR_YELLOW) Running database migrations...$(CLR_RESET)"
	@$(DOCKER) exec -it $(APP_CONTAINER) $(ARTISAN) migrate:fresh --seed
	@sudo rm -rf ./public/storage/uploads/*
	@$(MAKE) clean

user: ## Create a new admin user
	@echo "$(CLR_YELLOW) Creating a new user...$(CLR_RESET)"
	@$(DOCKER) exec -it $(APP_CONTAINER) $(ARTISAN) orchid:admin

migrate: ## Run database migrations
	@echo "$(CLR_YELLOW) Running database migrations...$(CLR_RESET)"
	@$(DOCKER) exec -it $(APP_CONTAINER) $(ARTISAN) migrate

migrate-seed: ## Run database migrations with seeding
	@echo "$(CLR_YELLOW) Running database migrations with seeding...$(CLR_RESET)"
	@$(DOCKER) exec -it $(APP_CONTAINER) $(ARTISAN) migrate:fresh --seed

## —— Docker Utils ———————————————————————————————————————————————————————————————————

up: ## Start the production environment
	@echo "$(CLR_YELLOW) Starting production environment...$(CLR_RESET)"
	@$(DOCKER_COMPOSE) up -d
	@$(MAKE) clean

prod: ## Start the production containers
	@$(DOCKER_COMPOSE) -f docker-compose.prod.yml up -d

down: ## Stop the production environment
	@echo "$(CLR_YELLOW) Stopping production environment...$(CLR_RESET)"
	@$(DOCKER_COMPOSE) down

logs: ## View the logs of all the containers
	@echo "$(CLR_YELLOW) Viewing production environment logs...$(CLR_RESET)"
	@$(DOCKER_COMPOSE) logs -f

list: ## List all the routes from the application
	@echo "$(CLR_YELLOW) Listing all application routes...$(CLR_RESET)"
	@$(DOCKER) exec -it $(APP_CONTAINER) $(ARTISAN) route:list

re: ## Restart the docker containers
	@echo "$(CLR_YELLOW) Restarting production environment...$(CLR_RESET)"
	@$(MAKE) down
	@$(MAKE) up

## —— Docker Production Deployment ————————————————————————————————————————————————————

build-frontend: ## Build frontend assets
	@echo "$(CLR_YELLOW)📦 Installing frontend dependencies...$(CLR_RESET)"
	@$(NPM) install 
	@echo "$(CLR_YELLOW)🏗️  Building frontend assets with environment variables...$(CLR_RESET)"
	@$(NPM) run build

build: build-frontend ## Build Docker container after frontend is ready
	@echo "$(CLR_YELLOW)🐳 Building Docker container...$(CLR_RESET)"
	@$(DOCKER_COMPOSE) build --no-cache


build-prod: build-frontend ## Build Docker container for production
	@echo "$(CLR_YELLOW)🐳 Building Docker container for production...$(CLR_RESET)"
	@$(DOCKER) build -t $(APP_CONTAINER):latest --target production .

push-prod: ## Push Docker image to Docker Hub as latest
	@echo "$(CLR_YELLOW)🚀 Pushing Docker image to Docker Hub as latest...$(CLR_RESET)"
	@$(DOCKER) tag $(APP_CONTAINER):latest unmugviolet/$(APP_CONTAINER):latest
	@$(DOCKER) push unmugviolet/$(APP_CONTAINER):latest

deploy: ## Complete secure Docker deployment
	@echo "🔄 Preparing for prod environment"
	@$(ARTISAN) config:clear
	@$(ARTISAN) cache:clear
	@$(ARTISAN) route:clear
	@$(ARTISAN) view:clear
	@$(ARTISAN) optimize:clear
	@echo "🗄️  Running database migrations..."
	@$(ARTISAN) migrate --force
	@echo "🔗 Creating storage link..."
	@$(ARTISAN) storage:link
	@echo "⚡ Rebuilding optimized caches..."
	@$(ARTISAN) config:cache
	@$(ARTISAN) route:cache
	@$(ARTISAN) view:cache
	@echo "✅ Deployment completed successfully!"

.PHONY: dev install migration prod deploy help clean fclean user build-frontend build deploy
