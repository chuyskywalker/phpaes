#!/bin/bash

# Build the docker image for us
docker build -t phpaes .

# Install the composer deps
docker run --rm -v $(pwd):/app composer/composer -v install
docker run -ti -v $(pwd):/app -w /app --rm phpaes php ./vendor/bin/phpunit