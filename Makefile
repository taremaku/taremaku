isDocker := $(shell docker info > /dev/null 2>&1 && echo 1)
isContainerRunning := $(shell docker ps | grep taremaku-php-1 > /dev/null 2>&1 && echo 1)
user := $(shell id -u)
group := $(shell id -g)

ifneq ("$(wildcard .env.local)","")
include .env.local
endif

ifeq ($(isDocker), $(isContainerRunning), 1)
	dc := USER_ID=$(user) GROUP_ID=$(group) docker-compose
	de := docker exec -u $(user):$(group) taremaku-php-1
	dr := $(dc) run --rm
	sy := $(de) php bin/console
	drtest := $(dc) -f docker-compose.test.yml run --rm
	php := $(de) --no-deps php
else ifeq ($(isDocker), 1)
	dc := USER_ID=$(user) GROUP_ID=$(group) docker-compose
else
	de :=
	sy := php bin/console
	node :=
	php :=
endif

COMPOSER = $(de) composer
CONSOLE = $(dc) php bin/console

## —— App ————————————————————————————————————————————————————————————————

build-docker:
	$(dc) pull --ignore-pull-failures
	$(dc) build --no-cache php

dev:
	$(dc) up -d

install-project: install reset-database generate-jwt ## First installation for setup the project

update-project: install  reset-database ## update the project after a checkout on another branch or to reset the state of the project

sync: update-project test-all ## Synchronize the project with the current branch, install composer dependencies, drop DB and run all migrations, fixtures and all test

## —— 🐝 The Symfony Makefile 🐝 ———————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?## .*$$)|(^## )' Makefile | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Composer 🧙‍♂️ ————————————————————————————————————————————————————————————
install: composer.lock ## Install vendors according to the current composer.lock file
	$(COMPOSER) install -n

update: composer.json ## Update vendors according to the composer.json file
	$(COMPOSER) update -w

## —— Symfony ————————————————————————————————————————————————————————————————
cc: ## Apply cache clear
	$(de) sh -c "rm -rf var/cache/*"
	$(sy) cache:clear
	$(de) sh -c "chmod -R 777 var/cache"

doctrine-validate:
	$(sy) doctrine:schema:validate --skip-sync $c

reset-database: drop-database database migrate load-fixtures ## Reset database with migration

database: ## Create database if no exists
	$(sy) doctrine:database:create --if-not-exists

drop-database: ## Drop the database
	$(sy) doctrine:database:drop --force --if-exists

migration: ## Apply doctrine migration
	$(sy) make:migration

migrate: ## Apply doctrine migrate
	$(sy) doctrine:migration:migrate -n --all-or-nothing

load-fixtures: ## Load fixtures
	$(sy) doctrine:fixtures:load -n

generate-jwt:
	$(sy) lexik:jwt:generate-keypair --overwrite -q $c

## —— Tests ✅ ————————————————————————————————————————————————————————————
test-load-fixtures: ## load database schema & fixtures
	$(dc) sh -c "APP_ENV=test php bin/console doctrine:database:drop --if-exists --force"
	$(dc) sh -c "APP_ENV=test php bin/console doctrine:database:create --if-not-exists"
	$(dc) sh -c "APP_ENV=test php bin/console doctrine:migration:migrate -n --all-or-nothing"
	$(dc) sh -c "APP_ENV=test php bin/console doctrine:fixtures:load -n"

test: phpunit.xml* ## Launch main functional and unit tests, stopped on failure
	$(php) APP_ENV=test ./vendor/bin/pest

test-all: phpunit.xml* test-load-fixtures ## Launch main functional and unit tests
	$(php) APP_ENV=test ./vendor/bin/pest

test-report: phpunit.xml* test-load-fixtures ## Launch main functionnal and unit tests with report
	$(php) APP_ENV=test ./vendor/bin/pest

## —— Coding standards ✨ ——————————————————————————————————————————————————————
ecs: ## Run ECS only
	$(php) ./vendor/bin/ecs check --memory-limit 256M

ecs-fix: ## Run php-cs-fixer and fix the code.
	$(php) ./vendor/bin/ecs check --fix --memory-limit 256M
