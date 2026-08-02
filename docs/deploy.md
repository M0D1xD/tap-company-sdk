# Deploying the Tap Company Laravel SDK

This package is a Composer library. “Deploy” means publishing a **semver Git tag** (and GitHub Release) so [Packagist](https://packagist.org) can serve `composer require m0d1xd/tap-company-sdk`.

## One-time setup

### 1. Create the GitHub repository

```bash
git init   # if needed
git add .
git commit -m "chore: initial commit"
git branch -M main
git remote add origin git@github.com:M0D1xD/tap-company-sdk.git
git push -u origin main
```

Ensure GitHub Actions are enabled for the repo. The [release-please](../.github/workflows/release-please.yml) workflow needs permission to create releases and PRs (`contents: write`, `pull-requests: write` — already set in the workflow).

### 2. Claim the Packagist vendor

1. Sign in at [packagist.org](https://packagist.org) with GitHub.
2. Confirm you can publish under the `m0d1xd` vendor (matches your GitHub user `M0D1xD`; Composer names are lowercase).

### 3. Submit the package

1. Open [Submit package](https://packagist.org/packages/submit).
2. Paste the public GitHub repository URL (`https://github.com/M0D1xD/tap-company-sdk`).
3. Submit and verify the package name is `m0d1xd/tap-company-sdk`.

### 4. Enable auto-updates from GitHub

So new tags appear on Packagist without manual “Update”:

1. On Packagist, open your package → **GitHub Hook** / integration settings.
2. Prefer the **Packagist GitHub App** (or the classic Service Hook) so pushes and tags trigger a sync.
3. Confirm a test update succeeds after the first tag.

No Packagist API token is required in GitHub Actions when using the webhook/app sync.

## Day-to-day release flow

Releases are driven by [Conventional Commits](../CONTRIBUTING.md) and [release-please](https://github.com/googleapis/release-please).

```text
PR (conventional title) → CI green → merge to main
        → release-please opens/updates a Release PR
        → merge the Release PR
        → Git tag + GitHub Release created
        → Packagist syncs the new version
```

1. Open PRs with titles like `feat: …`, `fix: …`, `docs: …` (enforced by [pr-title.yml](../.github/workflows/pr-title.yml)).
2. Squash-merge into `main` when possible so the history stays conventional.
3. After merge, release-please opens (or updates) a **Release Please** PR with `CHANGELOG.md` and version bump.
4. Merge that Release PR when you are ready to publish.
5. Wait a few minutes, then check Packagist and:

```bash
composer show m0d1xd/tap-company-sdk --all
```

### Version bumps

| Commit type | Bump (pre-1.0 with current config) |
|-------------|-------------------------------------|
| `fix:` | patch (`0.1.0` → `0.1.1`) |
| `feat:` | minor (`0.1.0` → `0.2.0`) |
| `BREAKING CHANGE` / `type!:` | minor while pre-1.0 (`bump-minor-pre-major`) |

The manifest starts at `0.0.0`; the first releasable commit (typically `feat`) produces **0.1.0**.

## Consumers

```bash
composer require m0d1xd/tap-company-sdk
# or pin a version:
composer require m0d1xd/tap-company-sdk:^0.1
```

## Manual release (fallback)

If you need a tag without release-please:

```bash
git checkout main
git pull
git tag v0.1.0
git push origin v0.1.0
```

Then create a GitHub Release from that tag (optional but recommended) and confirm Packagist updated. Prefer keeping [`.release-please-manifest.json`](../.release-please-manifest.json) in sync with the latest tagged version so the next automated release does not conflict.

## Checklist

- [ ] GitHub repo on `main` with Actions enabled (`M0D1xD/tap-company-sdk`)
- [ ] Packagist package submitted as `m0d1xd/tap-company-sdk`
- [ ] Packagist ↔ GitHub hook / app connected
- [ ] CI matrix green on `main`
- [ ] At least one conventional `feat`/`fix` merged so release-please can open a Release PR
- [ ] Release PR merged → tag visible on GitHub and Packagist
- [ ] `composer require m0d1xd/tap-company-sdk` resolves in a fresh Laravel app
