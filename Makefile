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

DOCKER = $(shell which docker)

# Shortcut for docker-compose command
DOCKER_COMPOSE = $(DOCKER) compose

# Default target when run with just 'make'
default: up

.PHONY: up
up:
	$(DOCKER_COMPOSE) up -d --remove-orphans
	symfony serve -d

.PHONY: database
database:
	php bin/console doc:sch:drop --force
	php bin/console doc:sch:create

.PHONY: stop
stop:
	$(DOCKER_COMPOSE) stop
	symfony server:stop