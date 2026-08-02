# Contributing

Thanks for contributing to the Tap Company Laravel SDK.

## Pull requests

1. Fork the repo and create a branch from `main`.
2. Keep changes focused and covered by tests when behavior changes.
3. Open a PR against `main`. **PR titles must follow Conventional Commits** (enforced by CI). Prefer squash merges so the merge commit stays conventional.

## Conventional Commits

Use this format for commits and PR titles:

```text
<type>(optional-scope): <short description>
```

### Types

| Type | When to use | Version bump |
|------|-------------|--------------|
| `feat` | New user-facing capability | minor |
| `fix` | Bug fix | patch |
| `docs` | Documentation only | none* |
| `refactor` | Code change that is not a fix or feature | none* |
| `test` | Adding or updating tests | none* |
| `ci` | CI/CD workflow changes | none* |
| `chore` | Maintenance, tooling, deps | none* |
| `perf` | Performance improvement | patch |
| `build` | Build system or packaging | none* |
| `revert` | Revert a previous commit | patch |

\* Non-releasable types do not bump the package version unless you add a `BREAKING CHANGE` footer.

### Breaking changes

Mark a breaking change with `!` after the type/scope, and/or a footer:

```text
feat!: drop support for Laravel 9

fix: correct webhook signature check

BREAKING CHANGE: hashstring validation now rejects empty secrets.
```

Breaking changes bump the **major** version (or minor while still pre-1.0.0).

### Examples

```text
feat: add destinations list endpoint
fix(webhooks): validate hashstring case-insensitively
docs: document Packagist deploy steps
test: cover charge download binary response
ci: add Laravel 13 to the test matrix
chore: ignore phpunit cache directory
```

### Scopes (optional)

Use a short area name when helpful: `charges`, `webhooks`, `http`, `config`, `ci`.

## Tests

```bash
composer install
composer test
```

## Releases

Versioning and GitHub Releases are automated by [release-please](https://github.com/googleapis/release-please) from conventional commits on `main`. See [docs/deploy.md](docs/deploy.md) for publishing to Packagist.
