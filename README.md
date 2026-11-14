# Al-Amoudi website

Laravel 13, Vue 3, Inertia, TypeScript, Tailwind and MySQL rebuild of the archived Al-Amoudi PHP site. The original source is preserved in `legacy/alamoudi/` and `alamoudi.zip`. The bilingual copy snapshot is `database/seeders/legacy-content.json`.

## Local setup

Requirements: PHP 8.3+, Composer, Node 22+, MySQL 8+ and Python 3 with Pillow if regenerating images.

1. `composer install`
2. `npm install --legacy-peer-deps`
3. Copy `.env.example` to `.env`, run `php artisan key:generate`, and configure MySQL there. No credentials are committed. The requested local database is `alamoudi` on `127.0.0.1:3306`; production needs a restricted account.
4. Create the database, then run `php artisan migrate --seed`.
5. Run `php artisan storage:link` for CMS uploads.
6. Run `php artisan admin:bootstrap` and enter private credentials. No default administrator is seeded.
7. Run `npm run build` and, on Windows, start the local server with `powershell -File scripts/serve-with-uploads.ps1`. This places PHP upload temporary files on the project drive and sets the PHP limits to 5 MB per image and 72 MB per request for gallery uploads. The helper uses `--no-reload` so Laravel passes the local PHP configuration to its HTTP worker; restart the server after changing `.env`. On other hosts, configure `upload_tmp_dir`, `upload_max_filesize`, and `post_max_size` in PHP before starting the server.

Visit `/ar` or `/en`; the admin panel is at `/admin` after login. Public registration is disabled. Run `php artisan wayfinder:generate --with-form` before standalone TypeScript checking when generated route files are absent.

## Content and media

`python scripts/extract_legacy.py` refreshes the source text snapshot without running legacy PHP. `python scripts/prepare_media.py` regenerates WebP copies while retaining originals in `legacy/`. The three original 8–22 MB slides are shipped as roughly 75–193 KB WebP files. Public content is stored in separate tables for slides, sections, achievements, projects, gallery, contact locations, settings and messages. Seeding uses stable slugs, so reruns avoid duplicate rows, but it resets seeded fields; do not rerun over editorial changes in production.

The CMS provides bilingual editing, ordering and publish controls. For the organization chart, edit the `team-chart` setting's Arabic and English body paths. Footer copy is in the `footer` setting. Page SEO titles and descriptions are in `seo-home`, `seo-projects`, `seo-team` and `seo-contact`. Image uploads return a storage path to place in content fields.

## Checks

- `php artisan test --compact`
- `vendor/bin/pint --test`
- `php artisan wayfinder:generate --with-form && npm run types:check`
- `npm run check:site`
- `npm run build`
- `node scripts/qa-browser.mjs` after starting the server, using locally installed Edge

Contact requests are validated, CSRF protected, rate limited to ten per ten minutes per client, and include a read-only hidden honeypot. Messages are unread on arrival and visible only in the admin inbox. Retain only messages needed to answer inquiries; administrators should remove old records under an agreed retention policy. Restrict backups and administrator access because messages contain personal information.

## Deployment

Set `APP_ENV=production`, `APP_DEBUG=false`, a real `APP_URL`, HTTPS and secure session cookies. Configure a restricted MySQL user, email delivery, backups and a message retention period. Run `php artisan migrate --force`, `php artisan storage:link`, `npm run build`, `php artisan config:cache` and `php artisan route:cache` on deployment. Confirm public pages, uploads and admin login after deployment. Review old asset licensing before publishing.

## Migration checklist

- [x] Archived original PHP files and extracted bilingual wording.
- [x] Migrated hero slides, about/services, achievements, original counters, project tiles, chart images and contact locations.
- [x] Added protected CMS, contact form and inbox.
- [x] Optimized copies of legacy images.
- [ ] Verify ambiguous project titles, translated category labels and contact data with the company.
- [ ] Agree on message retention, licensing and final production environment.
- [ ] Run deployment Lighthouse and screen reader review on representative pages.
