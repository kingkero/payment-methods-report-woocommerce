# from https://github.com/dunglas/symfony-docker/blob/b6f8692f6f649959d1bdbc3ec9afc7127096634e/docs/makefile.md
# originally MIT licensed

# Executables (local)
DOCKER_COMP = docker compose --file ./local/compose.yaml

# Docker containers
PHP_CONT  = $(DOCKER_COMP) exec php
DB_CONT   = $(DOCKER_COMP) exec -it db
NODE_CONT = $(DOCKER_COMP) run --rm node

# Executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer

# Misc
.DEFAULT_GOAL = help
.PHONY        : help build up start down logs sh composer vendor sf cc test

## —— 🎵 🐳 The Symfony Docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
build: ## Builds the Docker images
	@$(DOCKER_COMP) build --pull --no-cache

up: ## Start the docker hub in detached mode (no logs)
	@$(DOCKER_COMP) up --detach

down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

logs: ## Show live logs
	@$(DOCKER_COMP) logs --follow

sh: ## Connect to the FrankenPHP container
	@$(PHP_CONT) sh

## —— Composer 🧙 ——————————————————————————————————————————————————————————————
composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER) $(c)

test: ## Run all PHP tests
	@$(COMPOSER) test

vendor: ## Install vendors according to the current composer.lock file
vendor: c=install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction
vendor: composer

## —— Yarn 🧶 ——————————————————————————————————————————————————————————————
yarn-install: ## Install node modules
	@$(NODE_CONT) yarn

yarn: ## Run yarn, pass the parameter "c=" to run a given command, example: make yarn c='build'
	@$(eval c ?=)
	@$(NODE_CONT) yarn run $(c)
