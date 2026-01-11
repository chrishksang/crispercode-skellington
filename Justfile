default:
    @just --list

# run a composer command inside the development container (e.g., just composer install)
composer *args:
    docker compose run --rm frankenphp composer {{args}}

# run a console command (e.g., just console schema:sync --dry-run)
console *args:
    docker compose run --rm frankenphp php src/bin/console {{args}}

# run phpcbf (PHP Code Beautifier and Fixer) via container (will modify files)
phpcbf:
    docker compose run --rm frankenphp vendor/bin/phpcbf --standard=phpcs.xml

# run phpcs (PHP Code Sniffer) via container
phpcs:
    docker compose run --rm frankenphp vendor/bin/phpcs --standard=phpcs.xml

# run phpstan (PHP Static Analysis Tool) via container
phpstan:
    docker compose run --rm frankenphp php -d memory_limit=256M vendor/bin/phpstan analyse

# run rector (PHP Refactoring Tool) via container in dry-run mode
rector:
    docker compose run --rm frankenphp vendor/bin/rector process --dry-run

# run phpunit (PHP Unit Testing Framework) via container
test:
    docker compose run --rm frankenphp vendor/bin/phpunit --configuration /srv/phpunit.xml
