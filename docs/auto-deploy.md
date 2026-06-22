# Automatic deployment (push to `main` → live)

When you push to the `main` branch, GitHub Actions connects to the production
server over SSH and runs [`deploy.sh`](../deploy.sh). No more "push, then SSH in
and pull" — that happens for you.

```
git push origin main  ──►  GitHub Actions  ──ssh──►  /var/www/skelapp.tz/deploy.sh  ──►  site live
```

The workflow lives in [`.github/workflows/deploy.yml`](../.github/workflows/deploy.yml).

---

## One-time setup

### 1. Create a dedicated SSH key for deploys

On your laptop (don't reuse your personal key):

```bash
ssh-keygen -t ed25519 -C "github-actions-deploy" -f ~/.ssh/skelapp_deploy -N ""
```

This makes `~/.ssh/skelapp_deploy` (private) and `~/.ssh/skelapp_deploy.pub` (public).

### 2. Authorise the key on the server

Add the **public** key to root's authorised keys on the server:

```bash
ssh-copy-id -i ~/.ssh/skelapp_deploy.pub root@YOUR_SERVER_IP
# or manually append the contents of skelapp_deploy.pub to /root/.ssh/authorized_keys
```

Test it works without a password:

```bash
ssh -i ~/.ssh/skelapp_deploy root@YOUR_SERVER_IP "whoami"   # should print: root
```

### 3. Add the repo secrets on GitHub

Repo → **Settings → Secrets and variables → Actions → New repository secret**.
Add these four:

| Secret name | Value |
|-------------|-------|
| `SSH_HOST`  | Server IP or hostname (e.g. `203.0.113.10` or `skelapp.tz`) |
| `SSH_USER`  | `root` |
| `SSH_KEY`   | The **private** key — full contents of `~/.ssh/skelapp_deploy` (include the `-----BEGIN/END-----` lines) |
| `SSH_PORT`  | `22` (or your custom SSH port) |

### 4. Make sure the server can fetch from GitHub

`deploy.sh` runs `git fetch origin && git reset --hard origin/main`, so the repo at
`/var/www/skelapp.tz` must already be cloned with the `origin` remote and be able to
authenticate (it already can if your manual `git pull` works there). For a private
repo, that means either a stored HTTPS token or a deploy key configured on the server.

---

## How it runs

- **Automatic:** every push (or merge) to `main` triggers a deploy.
- **Manual:** GitHub → **Actions → Deploy to production → Run workflow** re-runs the
  latest `main` on demand.
- **Serialised:** the `concurrency` group prevents two deploys overlapping.
- **Logs:** the full `deploy.sh` output (maintenance mode, migrations, cache rebuild)
  shows in the Actions run, so a failed deploy is visible in GitHub.

`deploy.sh` itself puts the site in maintenance mode, hard-resets to `origin/main`,
runs `composer install --no-dev`, migrations, the `PagesSeeder`, rebuilds caches,
fixes `storage`/`bootstrap/cache` ownership, and brings the site back up.

---

## First run / verifying

1. Commit and push these files to `main`.
2. Watch GitHub → **Actions**; the "Deploy to production" run should go green.
3. If it fails on SSH, re-check the four secrets and that step 2's password-less
   `ssh` test succeeds.
