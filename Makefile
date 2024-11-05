SHELL := /bin/bash
EXEC_PHP := docker compose exec -it php
ifeq (locally,$(firstword $(MAKECMDGOALS)))
	EXEC_PHP :=
endif

locally:;@:
.PHONY: locally

##
## Проект
## ------

create: ## Собрать и запустить проект
	$(MAKE) up

vendor: composer.json composer.lock ## Собрать vendor
	$(EXEC_PHP) composer install
	$(EXEC_PHP) touch vendor

var:
	mkdir -p var

up: var ## Пересобрать контейнеры
	docker compose up --build --detach --remove-orphans

	$(MAKE) vendor
.PHONY: up

down: ## Удалить контейнеры
	docker compose down --remove-orphans
.PHONY: down

start: var ## Запустить проект
	docker compose start
	$(MAKE) vendor
.PHONY: start

##
## Контроль качества кода
## ----------------------

check: lint psalm rector check-composer ## Запустить все проверки
.PHONY: check

lint: var vendor ## Проверить PHP code style при помощи PHP CS Fixer (https://github.com/FriendsOfPHP/PHP-CS-Fixer)
	$(EXEC_PHP) vendor/bin/php-cs-fixer fix --dry-run --diff --verbose
.PHONY: lint

fixcs: var vendor ## Исправить ошибки PHP code style при помощи PHP CS Fixer (https://github.com/FriendsOfPHP/PHP-CS-Fixer)
	$(EXEC_PHP) vendor/bin/php-cs-fixer fix --diff --verbose
.PHONY: fixcs

psalm: var vendor ## Запустить полный статический анализ PHP кода при помощи Psalm (https://psalm.dev/)
	$(EXEC_PHP) vendor/bin/psalm --no-diff $(file)
.PHONY: psalm

rector: var vendor ## Запустить полный анализ PHP кода при помощи Rector (https://getrector.org)
	$(EXEC_PHP) vendor/bin/rector process --dry-run
.PHONY: rector

rector-fix: var vendor ## Запустить исправление PHP кода при помощи Rector (https://getrector.org)
	$(EXEC_PHP) vendor/bin/rector process
.PHONY: rector-fix

deptrac-directories: var vendor ## Проверить зависимости слоев (https://github.com/sensiolabs-de/deptrac)
	$(EXEC_PHP) vendor/bin/deptrac analyze --config-file=deptrac.directories.yaml --cache-file=var/.deptrac.directories.cache
.PHONY: deptrac-directories

deptrac-features: var vendor deptrac-generate-features-depfile ## Проверить зависимости в Feature (https://github.com/sensiolabs-de/deptrac)
	$(PHP) vendor/bin/deptrac analyze --config-file=var/deptrac.features.yaml --cache-file=var/.deptrac.features.cache
.PHONY: deptrac-features

deptrac-generate-features-depfile: var vendor ## Создаёт depfile для Feature (https://github.com/sensiolabs-de/deptrac)
	$(EXEC_PHP) bin/console deptrac:generate-features-depfile deptrac.features.template.yaml var/deptrac.features.yaml
.PHONY: deptrac-generate-features-depfile

test-unit: var vendor ## Запустить unit-тесты PHPUnit (https://phpunit.de)
	$(EXEC_PHP) vendor/bin/phpunit --exclude-group=integration --coverage-text --coverage-cobertura=$(or $(CI_PROJECT_DIR),var/coverage)/coverage.cobertura.xml
.PHONY: test-unit

test-integration: var vendor db-integration-test ## Запустить интеграционные тесты PHPUnit (https://phpunit.de)
	$(EXEC_PHP) vendor/bin/phpunit --group=integration
.PHONY: test-integration

check-composer: composer-validate composer-audit composer-require composer-normalize  ## Запустить все проверки для Composer
.PHONY: check-composer

composer-validate: ## Провалидировать composer.json и composer.lock при помощи composer validate (https://getcomposer.org/doc/03-cli.md#validate)
	$(EXEC_PHP) composer validate --strict --no-check-publish
.PHONY: composer-validate

composer-require: vendor ## Обнаружить неявные зависимости от внешних пакетов при помощи ComposerRequireChecker (https://github.com/maglnet/ComposerRequireChecker)
	$(EXEC_PHP) vendor/bin/composer-require-checker check
.PHONY: composer-require

composer-unused: vendor ## Обнаружить неиспользуемые зависимости Composer при помощи composer-unused (https://github.com/icanhazstring/composer-unused)
	$(EXEC_PHP) vendor/bin/composer-unused
.PHONY: composer-unused

composer-audit: vendor ## Обнаружить уязвимости в зависимостях Composer при помощи composer audit (https://getcomposer.org/doc/03-cli.md#audit)
	$(EXEC_PHP) composer audit
.PHONY: composer-audit

composer-normalize: vendor ## Проверить, что composer.json отнормализован (https://github.com/ergebnis/composer-normalize)
	$(EXEC_PHP) composer normalize --dry-run --diff
.PHONY: composer-normalize

composer-normalize-fix: vendor ## Отнормализовать composer.json (https://github.com/ergebnis/composer-normalize)
	$(EXEC_PHP) composer normalize --diff
.PHONY: composer-normalize-fix

prod-di: var vendor
	$(EXEC_PHP) bin/console cache:clear --env=prod
.PHONY: prod-di

cache-clear: var vendor
	$(EXEC_PHP) bin/console cache:clear
.PHONY: cache-clear
