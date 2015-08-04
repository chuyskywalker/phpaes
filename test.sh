#!/bin/bash

# Run phpunit inside the container from build.sh

echo "Running tests with mbstring.func_overload=0"
docker run -ti -v $(pwd):/app -w /app --rm phpaes php -d mbstring.func_overload=0 ./vendor/bin/phpunit -d mbstring.func_overload=0

echo "Running tests with mbstring.func_overload=7"
docker run -ti -v $(pwd):/app -w /app --rm phpaes php -d mbstring.func_overload=7 ./vendor/bin/phpunit -d mbstring.func_overload=7
