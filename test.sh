#!/usr/bin/env bash

CONSOLE="php bin/console"

$CONSOLE doctrine:database:drop --env=test --no-debug --force --if-exists
$CONSOLE doctrine:database:create --env=test --no-debug --no-interaction --if-not-exists
$CONSOLE doctrine:migrations:migrate --env=test --no-debug --no-interaction --quiet
$CONSOLE doctrine:fixtures:load --env=test --no-debug --no-interaction
$CONSOLE cache:clear --env=test --no-debug --no-warmup

export SYMFONY_DEPRECATIONS_HELPER=weak

bin/phpunit
