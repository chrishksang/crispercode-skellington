# CrisperCode Skeleton

Minimal skeleton application using the [CrisperCode Framework](https://github.com/chrishksang/crispercode-framework).

## Quick Start

```bash
# Create new project
composer create-project --repository='{"type":"vcs","url":"git@github.com:chrishksang/crispercode-skellington.git"}' crispercode/skeleton my-app
cd my-app

# Start development server
docker compose up -d

# Visit http://localhost:8080
```

## What's Included

- PHP 8.4 + FrankenPHP (worker mode)
- Slim Framework + Twig
- SQLite database
- PSR-12 coding standards
- PHPUnit, PHPStan, Rector

## Development

```bash
just composer install    # Install dependencies
just test               # Run tests
just phpstan           # Static analysis
just phpcs             # Check coding standards
```

Edit `src/public/index.php` to start building your application.

