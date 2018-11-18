#!/usr/bin/env bash

read -p "Are you sure you want to continue? <y/N> " prompt
if [[ $prompt == "y" || $prompt == "Y" || $prompt == "yes" || $prompt == "Yes" ]]
then
    CONSOLE="php bin/console"

    $CONSOLE doctrine:database:drop --force --if-exists
    $CONSOLE doctrine:database:create --if-not-exists
    $CONSOLE doctrine:migrations:migrate --no-interaction --quiet
    $CONSOLE doctrine:fixtures:load --no-interaction

    cd ./public/uploads/posts && ls | grep -v .gitignore | xargs rm -f
    cd ../avatars && ls | grep -v .gitignore | xargs rm -f

    cd ../../../ && php bin/console cache:clear --no-debug
else
  exit 0
fi