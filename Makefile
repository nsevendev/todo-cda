# name container
NAME_CONT_API = todo-cda-api
NAME_CONT_FRONT = todo-cda-front
NAME_CONT_DB = todo-cda-db

# command
DOCKER = docker
DOCKER_COMP = docker compose
DOT = dotnet

# access 
APP_CONT_API = $(DOCKER_COMP) exec $(NAME_CONT_API) bash
APP_CONT_FRONT = $(DOCKER_COMP) exec $(NAME_CONT_FRONT) bash
APP_CONT_DB = $(DOCKER_COMP) exec $(NAME_CONT_DB) bash

APP_CONT_API_DOT = $(DOCKER_COMP) exec $(NAME_CONT_API) $(DOT)

# Misc
.DEFAULT_GOAL = help
.PHONY        : help build dev logs-api

## —— 🎵 🐳 Docker Makefile Todo-cda 🐳 🎵 —————————————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Docker dev 🚀 🐳 —————————————————————————————————————————————————————————————
build: ## Builds the Docker images
	@echo "🚀 Lancement du build des images ---> START"
	@$(DOCKER_COMP) build --pull --no-cache
	@echo "✅ Lancement du build des images ---> END OK"

dev: ## Start the docker hub mode dev in detached mode (no logs)
	@echo "🚀 Lancement en mode development ---> START"
	@$(DOCKER_COMP) up --detach
	@echo "✅ Lancement en mode development ---> END OK"

down: ## Stop the docker hub
	@echo "🚀 Fermeture des containers ---> START"
	@$(DOCKER_COMP) down
	@echo "✅ Fermeture des containers ---> END OK"

logs: ## Show live logs of the specified container c="api", c="front" or c="db" (default is api)
	@$(eval c ?= api)
	@echo "🚀 Affichage des logs du container $(c) ---> START"
	@$(DOCKER) logs -f $(if $(filter $(c),api),$(NAME_CONT_API),$(if $(filter $(c),front),$(NAME_CONT_FRONT),$(if $(filter $(c),db),$(NAME_CONT_DB),$(error "Valeur de c invalide : $(c)"))))

sh: ## Open a shell in the specified container c="api", c="front" or c="db" (default is api)
	@$(eval c ?= api)
	@echo "🚀 Ouverture d'un shell dans le container $(c) ---> START"
	@$(if $(filter $(c),api),$(APP_CONT_API),$(if $(filter $(c),front),$(APP_CONT_FRONT),$(if $(filter $(c),db),$(APP_CONT_DB),$(error "Valeur de c invalide : $(c)"))))
	@echo "✅ Fermeture d'un shell dans le container $(c) ---> END OK"

## —— Docker command dotnet 🧪 🐳 ——————————————————————————————————————————————————————
dotadd: ## Add a package in api project c="<name_package>"
	@echo "🚀 Ajout du package $(c) dans le projet API ---> START"
	@$(APP_CONT_API_DOT) add package $(c)
	@echo "✅ Ajout du package $(c) dans le projet API ---> END OK"

## —— Docker test 🧪 🐳 ————————————————————————————————————————————————————————————————

## —— Docker prod 🚀🚀🚀🚀 🐳 🎉🎉🎉🎉 —————————————————————————————————————————————————
