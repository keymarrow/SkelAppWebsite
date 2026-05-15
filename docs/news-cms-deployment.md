# SkelApp News CMS Deployment

This project now supports:

- Public frontend on `skelapp.tz`
- Private CMS on `admin.skelapp.tz`
- Stable public news URLs on `/news/{slug}`
- Canonical tags on news pages
- XML sitemap with news posts at `/sitemap.xml`
- 301 redirects when a CMS post slug changes
- Daily database backups through Laravel scheduler

## 1. Environment

Set these values on the Ubuntu server:

```env
APP_URL=https://skelapp.tz
CMS_PUBLIC_HOST=skelapp.tz
CMS_ADMIN_HOST=admin.skelapp.tz
CMS_BACKUP_DISK=local
CMS_BACKUP_PATH=backups/database
CMS_BACKUP_RETENTION_DAYS=14
```

## 2. Migrate and import the current news posts

```bash
php artisan migrate --force
php artisan news:import-legacy
```

The importer copies the existing `config/news.php` articles into the database so the CMS can manage them. Public URLs stay the same because the imported slugs match the existing slugs.

## 3. Create the private CMS admin

Use a strong password. The command enforces a minimum length of 16 plus mixed case, numbers, and symbols.

```bash
php artisan cms:create-admin you@example.com --name="SkelApp Admin"
```

## 4. Run the CMS app behind the admin subdomain

If you want a strict reverse-proxy separation from the current frontend vhost, run the Laravel app for admin traffic on an internal port:

```bash
php artisan serve --host=127.0.0.1 --port=8088
```

For production, replace that with your preferred Laravel app runtime behind the same port if you already use Supervisor, Octane, or another app server.

## 5. Add the admin Nginx site without changing the current frontend site

Use the example file at:

```text
deploy/nginx/admin.skelapp.tz.conf.example
```

Copy it to `/etc/nginx/sites-available/admin.skelapp.tz`, adjust only the upstream port if needed, then enable it:

```bash
sudo ln -s /etc/nginx/sites-available/admin.skelapp.tz /etc/nginx/sites-enabled/admin.skelapp.tz
sudo nginx -t
sudo systemctl reload nginx
```

This adds a new admin subdomain config only. It does not require touching the current `skelapp.tz` frontend server block.

Important: the admin Nginx site should allow larger request bodies for image uploads. The example config now includes:

```nginx
client_max_body_size 10M;
```

If the live server was configured before that change, add it manually to the `admin.skelapp.tz` server block, then run:

```bash
sudo nginx -t
sudo systemctl reload nginx
```

## 6. SSL for all subdomains

Issue or expand certificates with Certbot:

```bash
sudo certbot --nginx -d skelapp.tz -d www.skelapp.tz -d admin.skelapp.tz
```

If the main domain already has a certificate, re-run Certbot and include `admin.skelapp.tz` so the existing cert can be expanded.

## 7. Scheduler and backups

The CMS registers a daily backup command:

```bash
php artisan cms:backup-database
```

Make sure Laravel scheduler is enabled in cron:

```bash
* * * * * cd /var/www/skelapp && php artisan schedule:run >> /dev/null 2>&1
```

Backups are written to:

```text
storage/app/backups/database
```

Old backups are pruned automatically based on `CMS_BACKUP_RETENTION_DAYS`.

## 8. SEO notes

- Public news URLs remain `/news/{slug}`
- When a slug changes in the CMS, old slugs 301 to the current one
- Canonical tags are output on the public news index and article pages
- `/sitemap.xml` now includes public news posts

## 9. Local fallback

If `CMS_ADMIN_HOST` is not set, the CMS falls back to a local path prefix:

```text
/admin
```

That is only for development convenience. In production, set `CMS_ADMIN_HOST=admin.skelapp.tz`.
