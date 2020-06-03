# Default shell to use
SHELL := bash

# Reuse the same shell instance within a target
.ONESHELL:

# Set bash to fail immediately (-e), to error on unset variables (-u) and to fail on piped commands (pipefail)
.SHELLFLAGS := -eu -o pipefail -c

# Delete any generated target on failure
.DELETE_ON_ERROR:

# Make flags
MAKEFLAGS += --warn-undefined-variables
MAKEFLAGS += --no-builtin-rules

# Shortcut for docker-compose command
COMPOSE = docker-compose

# Shortcut for docker run on the app container with all dependencies already up
RUN_APP = $(COMPOSE) run --rm app -d memory_limit=-1

# Non file-generating targets
.PHONY: install-deps update-deps ci-analysis ci-tests

INFECTION_VERSION=0.16.3

# Default target when run with just 'make'
default: ci-tests

# Main docker image build
build:
	@$(COMPOSE) build --parallel
	$(COMPOSE) up --no-start --remove-orphans

install-deps: build
	$(RUN_APP) /usr/local/bin/composer install

ci-infection: install-deps
	wget https://github.com/infection/infection/releases/download/$(INFECTION_VERSION)/infection.phar
	php infection.phar --min-msi=80 --min-covered-msi=70 --threads=4 --show-mutations --only-covered
	rm -rf infection.phar

ci-analysis: install-deps
	$(RUN_APP) /usr/local/bin/composer psalm

ci-tests: install-deps
	$(RUN_APP) /usr/local/bin/composer unit-tests
