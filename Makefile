# Default shell to use
SHELL := bash

# Reuse the same shell instance within a target
.ONESHELL:

# Set bash to fail immediately (-e), to error on unset variables (-u) and to fail on piped commands (pipefail)
.SHELLFLAGS := -eu -o pipefail -c

# Delete any generated target on failure
.DELETE_ON_ERROR:

# Make flags
MAKEFLAGS += --warn-undefined-variables
MAKEFLAGS += --no-builtin-rules

# Shortcuts
DOCKER = $(shell which docker)
DOCKER_COMPOSE = $(DOCKER) compose
APP_SHELL = $(DOCKER_COMPOSE) run --rm app
PHP = $(APP_SHELL) php

# Default target when run with just 'make'
default: help

.PHONY: start
start:
	$(DOCKER_COMPOSE) up -d --remove-orphans

.PHONY: deps
deps:
	$(PHP) composer.phar install

.PHONY: refresh-fixtures
refresh-fixtures:
	$(PHP) bin/console doc:sch:drop --force
	$(PHP) bin/console doc:sch:create
	$(PHP) bin/console doc:fix:load --no-interaction

.PHONY: stop
stop:
	$(DOCKER_COMPOSE) --profile async-events --profile async-commands --profile async-projections stop

.PHONY: help
help:
	@LC_ALL=C $(MAKE) -pRrq -f $(lastword $(MAKEFILE_LIST)) : 2>/dev/null | awk -v RS= -F: '/(^|\n)# Files(\n|$$)/,/(^|\n)# Finished Make data base/ {if ($$1 !~ "^[#.]") {print $$1}}' | sort | egrep -v -e '^[^[:alnum:]]' -e '^$@$$'

.PHONY: docker-build
docker-build:
	docker-compose build

ci: tests psalm-github check-cs deptrack
local-ci: unit-tests psalm check-cs deptrack

tests: unit-tests functional-tests mutation-tests

.PHONY: unit-tests
unit-tests: deps
	$(PHP) bin/phpunit

.PHONY: functional-tests
functional-tests: start refresh-fixtures
	$(PHP) bin/phpunit --testsuite FunctionalTests

.PHONY: mutation-tests
mutation-tests: deps
	$(PHP) vendor/bin/roave-infection-static-analysis-plugin

.PHONY: psalm
psalm: deps
	$(PHP) vendor/bin/psalm --no-cache

.PHONY: psalm-github
psalm-github: deps
	$(PHP) vendor/bin/psalm --no-cache --no-progress --output-format=github

.PHONY: check-cs
check-cs:
	$(PHP) bin/php-cs-fixer.phar -vvvv --config=.php-cs-fixer.dist.php --using-cache=no --dry-run --path-mode=intersection fix src

.PHONY: fix-cs
fix-cs:
	$(PHP) bin/php-cs-fixer.phar -vvvv --config=.php-cs-fixer.dist.php --using-cache=no --path-mode=intersection fix src

.PHONY: deptrack
deptrack: deps
	$(PHP) ./vendor/bin/deptrac analyse

.PHONY: shell
shell:
	$(APP_SHELL) bash