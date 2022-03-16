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
	$(DOCKER_COMPOSE) --profile async-events --profile async-commands --profile async-projections stop

.PHONY: demo
demo:
	$(HTTPIE) --json --body http://127.0.0.1:8000/chapter7/author/a64a52cc-3ee9-4a15-918b-099e18b43119/followers-counter
	$(HTTPIE) --json --body http://127.0.0.1:8000/chapter7/author/a64a52cc-3ee9-4a15-918b-099e18b43119/timeline
	$(HTTPIE) --json --body POST http://127.0.0.1:8000/chapter7/author author_id='a64a52cc-3ee9-4a15-918b-099e18b43119' username='bob' email='bob@bob.com'
	$(HTTPIE) --json --body POST http://127.0.0.1:8000/chapter7/author author_id='1fd7d739-2ad7-41a8-8c18-565603e3733f' username='alice' email='alice@alice.com'
	$(HTTPIE) --json --body POST http://127.0.0.1:8000/chapter7/author author_id='1da1366f-b066-4514-9b29-7346df41e371' username='charlie' email='charlie@charlie.com'
	$(PHP) bin/console messenger:consume events_async --limit 3 -vv
	$(HTTPIE) --json --body http://127.0.0.1:8000/chapter7/author/a64a52cc-3ee9-4a15-918b-099e18b43119/followers-counter
	$(HTTPIE) --json --body http://127.0.0.1:8000/chapter7/author/a64a52cc-3ee9-4a15-918b-099e18b43119/timeline
	$(HTTPIE) --json --body POST http://127.0.0.1:8000/chapter7/follow follow_id='8cc71bf2-f827-4c92-95a5-43bb1bc622ad' from_author_id='1fd7d739-2ad7-41a8-8c18-565603e3733f' to_author_id='a64a52cc-3ee9-4a15-918b-099e18b43119'
	$(HTTPIE) --json --body POST http://127.0.0.1:8000/chapter7/follow follow_id='f3088920-841e-4577-a3c2-efdc80f0dea5' from_author_id='1da1366f-b066-4514-9b29-7346df41e371' to_author_id='a64a52cc-3ee9-4a15-918b-099e18b43119'
	$(HTTPIE) --json --body POST http://127.0.0.1:8000/chapter7/follow follow_id='f3088920-841e-4577-a3c2-efdc80f0dea5' from_author_id='1da1366f-b066-4514-9b29-7346df41e371' to_author_id='a64a52cc-3ee9-4a15-918b-099e18b43119'
	$(PHP) bin/console messenger:consume commands_async --limit 3 -vv
	$(HTTPIE) --json http://127.0.0.1:8000/chapter7/author/a64a52cc-3ee9-4a15-918b-099e18b43119/followers-counter
	$(PHP) bin/console messenger:consume events_async --limit 2 -vv
	$(PHP) bin/console messenger:consume commands_async --limit 3 -vv
	$(PHP) bin/console messenger:consume events_async --limit 3 -vv
	$(PHP) bin/console messenger:consume projections_async --limit 2 -vv
	$(HTTPIE) --json http://127.0.0.1:8000/chapter7/author/a64a52cc-3ee9-4a15-918b-099e18b43119/timeline
	$(HTTPIE) --json http://127.0.0.1:8000/chapter7/author/1fd7d739-2ad7-41a8-8c18-565603e3733f/timeline
	$(HTTPIE) --json http://127.0.0.1:8000/chapter7/author/1da1366f-b066-4514-9b29-7346df41e371/timeline
