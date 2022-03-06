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
default: start

.PHONY: start
start:
	$(DOCKER_COMPOSE) up -d --remove-orphans
	symfony serve -d --no-tls

.PHONY: infrastructure
infrastructure:
	redis-cli flushall
	http --auth guest:guest DELETE http://localhost:15672/api/queues/%2F/events/contents
	http --auth guest:guest DELETE http://localhost:15672/api/queues/%2F/commands/contents
	http --auth guest:guest DELETE http://localhost:15672/api/queues/%2F/projections/contents
	http --auth guest:guest DELETE http://localhost:15672/api/queues/%2F/failed_messages/contents
	php bin/console doc:sch:drop --force
	php bin/console doc:sch:create

.PHONY: stop
stop:
	$(DOCKER_COMPOSE) stop
	symfony server:stop