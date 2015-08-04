#!/bin/bash

# Run phpunit inside the container from build.sh
docker run -ti -v $(pwd):/app -w /app --rm phpaes ./vendor/bin/phpunit
