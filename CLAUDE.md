## Agent skills

### Issue tracker

Local markdown. Spec: `docs/specs/website-ban-hang-dien-may.md`. Tickets: `.scratch/website-ban-hang-dien-may/issues/`. See `docs/agents/issue-tracker.md`.

### Triage labels

`Status:` uses `needs-triage`, `needs-info`, `ready-for-agent`, `ready-for-human`, `wontfix`. See `docs/agents/triage-labels.md`.

### Domain docs

Single-context: `GLOSSARY.md` and `docs/adr/`. See `docs/agents/domain.md`.

## Browser testing

- Preferred stack: `docker compose --env-file .env up -d` then open `http://localhost:${HTTP_PORT}`.
- This environment has no Docker. Local stack: PHP 8.3-FPM, MariaDB, nginx on port 8080, site root `site/`. Start MariaDB and PHP-FPM, then nginx with the NukeViet rewrite rules in `docker/nginx.conf` (fastcgi to the local socket, `SERVER_PORT` taken from the Host header).
- Canonical host of a local install is whatever host you used for the installer. Tests here use `SITE_URL=http://127.0.0.1:8080`.
- Admin account: `NV_ADMIN_USER` and `NV_ADMIN_PASS` in `.env` (not committed).
- Sample member after seeding: `khach001` / `Mau123a!`.
- HTTP suite: `cd tests && SITE_URL=http://127.0.0.1:8080 npm test`. It restores `tests/.baseline/site.sql` (create with `npm run baseline` on a clean install, before shops).
- E2E flows: `e2e/flows.md`. Run `node e2e/run.mjs` with `--headless` Chrome after `php tools/seed-sangnha.php`. Report PASS/FAIL and keep screenshots.
- Seeder: `php tools/seed-sangnha.php`. See `docs/seed.md`.

## Sample data

`php tools/seed-sangnha.php` loads 20 ngành hàng, 420 sản phẩm, 130 thành viên, 260 đơn and 450 đánh giá. Safe to run again.
