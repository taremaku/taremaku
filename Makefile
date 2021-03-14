build:
	$(MAKE) prepare-test
	$(MAKE) analyze
	$(MAKE) tests

it:
	$(MAKE) prepare-dev
	$(MAKE) analyze

.PHONY: translations
translations:
	php bin/console translation:update --force fr

.PHONY: tests
tests:
	php bin/pest

analyze:
	composer valid
	php bin/console doctrine:schema:valid --skip-sync --env=test
	vendor/bin/phpstan analyse
	php bin/ecs check

prepare-dev:
	composer install --prefer-dist
	php bin/console doctrine:database:drop --if-exists -f --env=dev
	php bin/console doctrine:database:create --env=dev
	php bin/console doctrine:schema:update -f --env=dev
	php bin/console doctrine:fixtures:load -n --env=dev

prepare-test:
	composer install --prefer-dist
	php bin/console cache:clear --env=test
	php bin/console doctrine:database:drop --if-exists -f --env=test
	php bin/console doctrine:database:create --env=test
	php bin/console doctrine:schema:update -f --env=test
	php bin/console doctrine:fixtures:load -n --env=test
