#!/usr/bin/env bash
read -p "Are you sure you want to continue? <y/N> " prompt
if [[ $prompt == "y" || $prompt == "Y" || $prompt == "yes" || $prompt == "Yes" ]]
then
    php bin/console doctrine:database:drop --force --if-exists
    php bin/console doctrine:database:create --if-not-exists
    php bin/console doctrine:migrations:migrate --no-interaction --quiet
    php bin/console doctrine:fixtures:load --no-interaction

    cd ./public/uploads/posts && ls | grep -v .gitignore | xargs rm -f
    cd ../avatars && ls | grep -v .gitignore | xargs rm -f

    cd ../../../ && php bin/console cache:clear --no-debug
else
  exit 0
fi