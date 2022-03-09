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
HTTPIE = docker run --network=host -ti --rm alpine/httpie
PHP = $(DOCKER_COMPOSE) exec app php

# Default target when run with just 'make'
default: start

.PHONY: start
start:
	$(DOCKER_COMPOSE) up -d --remove-orphans

.PHONY: infrastructure
infrastructure:
	$(DOCKER_COMPOSE) exec redis redis-cli flushall
	$(HTTPIE) --auth guest:guest DELETE http://localhost:15672/api/queues/%2F/events/contents
	$(HTTPIE) --auth guest:guest DELETE http://localhost:15672/api/queues/%2F/commands/contents
	$(HTTPIE) --auth guest:guest DELETE http://localhost:15672/api/queues/%2F/projections/contents
	$(HTTPIE) --auth guest:guest DELETE http://localhost:15672/api/queues/%2F/failed_messages/contents
	$(PHP) bin/console doc:sch:drop --force
	$(PHP) bin/console doc:sch:create
	$(PHP) bin/console messenger:setup-transports

.PHONY: stop
stop:
	$(DOCKER_COMPOSE) stop