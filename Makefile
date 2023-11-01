.PHONY: help

help: ## This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

include .env
dc := docker-compose

check:
ifeq ($(APP_NAME),)
	$(error Missed APP_NAME argument.)
endif

build: check ## Build containers
	$(dc) build --force-rm

up: ## Run containers
	$(dc) up -d --remove-orphans

down: ## Remove docker containers
	@$(dc) down
