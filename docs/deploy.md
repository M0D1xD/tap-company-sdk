# Deploying the Tap Company Laravel SDK

This package is a Composer library. “Deploy” means publishing a **semver Git tag** (and GitHub Release) so [Packagist](https://packagist.org) can serve `composer require m0d1xd/tap-company-sdk`.

Packagist does not host zip uploads. It clones your GitHub tags. GitHub Actions only needs to **create/update** the Packagist package entry after each release.

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

Ensure GitHub Actions are enabled. The [release-please](../.github/workflows/release-please.yml) workflow needs permission to create releases and PRs.

Also enable this **repository** setting (or release-please fails with “GitHub Actions is not permitted to create and approve pull requests”):

1. Open [Settings → Actions → General](https://github.com/M0D1xD/tap-company-sdk/settings/actions)
2. Under **Workflow permissions**, choose **Read and write permissions**
3. Check **Allow GitHub Actions to create and approve pull requests**
4. Save

### 2. Create Packagist API credentials

1. Sign in at [packagist.org](https://packagist.org) (GitHub login is fine).
2. Open your [Packagist profile](https://packagist.org/profile/).
3. Copy:
   - **Username** (your Packagist username)
   - **API tokens** — Packagist shows two tokens:
     - **SAFE** — use this for day-to-day CI `update-package` calls (recommended for GitHub Actions)
     - **MAIN** — required only for one-time `create-package` (treat like a password)

Confirm you can publish under the `m0d1xd` vendor (Composer names are lowercase; GitHub user is `M0D1xD`).

### 3. Add GitHub Actions secrets

1. Open [Settings → Secrets and variables → Actions](https://github.com/M0D1xD/tap-company-sdk/settings/secrets/actions)
2. Create repository secrets:

| Secret | Value |
|--------|--------|
| `PACKAGIST_USERNAME` | Your Packagist username |
| `PACKAGIST_TOKEN` | Prefer the **SAFE** token for normal releases |

If you plan to run **create** from Actions once, temporarily set `PACKAGIST_TOKEN` to the **MAIN** token, run create, then switch back to the SAFE token.

### 4. Register the package on Packagist (first time only)

Pick one method:

**A. GitHub Actions (recommended with this repo)**

1. Ensure secrets from step 3 are set (`PACKAGIST_TOKEN` = **MAIN** for create).
2. Open [Actions → Packagist](https://github.com/M0D1xD/tap-company-sdk/actions/workflows/packagist.yml)
3. **Run workflow** → mode **`create`** → Run
4. Switch `PACKAGIST_TOKEN` back to the **SAFE** token afterward

**B. Packagist website**

1. Open [Submit package](https://packagist.org/packages/submit)
2. Paste `https://github.com/M0D1xD/tap-company-sdk`
3. Confirm the name is `m0d1xd/tap-company-sdk`

### 5. Optional: Packagist GitHub App / webhook

You may also enable Packagist’s GitHub integration so tags sync without waiting for Actions. With [packagist.yml](../.github/workflows/packagist.yml) already calling the update API on each GitHub Release, the App is optional redundancy — keep one reliable path; both together is fine.

## How GitHub Actions integrates with Packagist

```text
merge Release Please PR
        → Git tag + GitHub Release (release-please)
        → Packagist workflow (on: release published)
        → POST /api/update-package
        → Packagist crawls new tags
        → composer require m0d1xd/tap-company-sdk
```

Workflow file: [`.github/workflows/packagist.yml`](../.github/workflows/packagist.yml)

| Trigger | Mode | Packagist API | Token |
|---------|------|---------------|-------|
| GitHub Release `published` | `update` | `POST /api/update-package` | SAFE |
| Manual **Run workflow** | `update` | same | SAFE |
| Manual **Run workflow** | `create` | `POST /api/create-package` | MAIN |

Auth uses Bearer headers (Packagist-recommended):

```bash
curl -X POST \
  -H 'Content-Type: application/json' \
  -H "Authorization: Bearer ${PACKAGIST_USERNAME}:${PACKAGIST_TOKEN}" \
  -d '{"repository":"https://github.com/M0D1xD/tap-company-sdk"}' \
  https://packagist.org/api/update-package
```

Official API docs: https://packagist.org/apidoc

### Test the integration

1. Secrets configured (`PACKAGIST_USERNAME`, `PACKAGIST_TOKEN`)
2. Package already created on Packagist
3. Actions → Packagist → **Run workflow** → mode **`update`**
4. Confirm a green run and that https://packagist.org/packages/m0d1xd/tap-company-sdk shows your tags

Or after the next release-please release is published, the Packagist job should run automatically.

## Day-to-day release flow

Releases are driven by [Conventional Commits](../CONTRIBUTING.md) and [release-please](https://github.com/googleapis/release-please).

```text
PR (conventional title) → CI green → merge to main
        → release-please opens/updates a Release PR
        → merge the Release PR
        → Git tag + GitHub Release created
        → Packagist workflow notifies packagist.org
```

1. Open PRs with titles like `feat: …`, `fix: …`, `docs: …` (enforced by [pr-title.yml](../.github/workflows/pr-title.yml)).
2. Squash-merge into `main` when possible so the history stays conventional.
3. After merge, release-please opens (or updates) a **Release Please** PR with `CHANGELOG.md` and version bump.
4. Merge that Release PR when you are ready to publish.
5. Wait a few minutes, then check:

```bash
composer show m0d1xd/tap-company-sdk --all
```

### Version bumps

| Commit type | Bump (pre-1.0 with current config) |
|-------------|-------------------------------------|
| `fix:` | patch (`0.1.0` → `0.1.1`) |
| `feat:` | minor (`0.1.0` → `0.2.0`) |
| `BREAKING CHANGE` / `type!:` | minor while pre-1.0 (`bump-minor-pre-major`) |

## Consumers

```bash
composer require m0d1xd/tap-company-sdk
# or pin a version:
composer require m0d1xd/tap-company-sdk:^1.0
```

## Manual release (fallback)

If you need a tag without release-please:

```bash
git checkout main
git pull
git tag v1.0.1
git push origin v1.0.1
```

Create a GitHub Release from that tag (Actions → Packagist runs on `release: published`), or manually run the Packagist workflow with mode `update`. Prefer keeping [`.release-please-manifest.json`](../.release-please-manifest.json) in sync with the latest tagged version.

## Checklist

- [ ] GitHub repo on `main` with Actions enabled (`M0D1xD/tap-company-sdk`)
- [ ] Actions → General: **Read and write permissions** + **Allow GitHub Actions to create and approve pull requests**
- [ ] Packagist profile: username + SAFE (and optionally MAIN) API token
- [ ] GitHub secrets: `PACKAGIST_USERNAME`, `PACKAGIST_TOKEN`
- [ ] Package created on Packagist (`create` workflow or web submit) as `m0d1xd/tap-company-sdk`
- [ ] Manual Packagist workflow `update` succeeds
- [ ] CI matrix green on `main`
- [ ] Release PR merged → tag + Packagist job green → version visible on Packagist
- [ ] `composer require m0d1xd/tap-company-sdk` resolves in a fresh Laravel app
