# Contributing to DevFlow

Thank you for your interest in contributing to DevFlow!

## Code of Conduct

Be respectful, inclusive, and constructive. We're here to build great software together.

## How to Contribute

### Reporting Bugs

1. Check existing issues first
2. Create a new issue with:
   - Clear title and description
   - Steps to reproduce
   - Expected vs actual behavior
   - Laravel/PHP version
   - Screenshots if applicable

### Suggesting Features

1. Open an issue with the `enhancement` label
2. Describe the feature and use case
3. Explain why it fits the project

### Pull Requests

1. Fork the repository
2. Create a feature branch:
   ```bash
   git checkout -b feature/amazing-feature
   ```
3. Make your changes
4. Run tests:
   ```bash
   composer test
   ```
5. Run linting:
   ```bash
   vendor/bin/pint
   ```
6. Commit with clear message:
   ```bash
   git commit -m "feat: add amazing feature"
   ```
7. Push to your fork:
   ```bash
   git push origin feature/amazing-feature
   ```
8. Open a Pull Request

## Development Setup

```bash
git clone https://github.com/yourusername/devflow.git
cd devflow
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
npm run build
composer run dev
```

## Coding Standards

- Follow PSR-12
- Use meaningful variable and method names
- Write tests for new features
- Update documentation for changes
- Keep methods focused and small

## Commit Convention

We use [Conventional Commits](https://www.conventionalcommits.org/):

| Type | Description | Example |
|------|-------------|---------|
| `feat` | New feature | `feat: add project archiving` |
| `fix` | Bug fix | `fix: resolve task pagination error` |
| `docs` | Documentation | `docs: update README` |
| `style` | Formatting | `style: format with Pint` |
| `refactor` | Code change | `refactor: simplify query logic` |
| `test` | Tests | `test: add project policy tests` |
| `chore` | Maintenance | `chore: update dependencies` |

## Branch Naming

- `feature/description`
- `fix/description`
- `docs/description`
- `refactor/description`

## Review Process

1. All PRs require review
2. CI must pass (tests, lint)
3. Maintainer will merge after approval

## Questions?

Open an issue with the `question` label.
