# Lich Ta deploy package

Upload the contents of this `deploy` folder to the hosting path for:

the chosen domain or subfolder, for example `https://xemngay.io.vn`

## Files and folders to upload

- `index.php`
- `.htaccess` if the hosting uses Apache or LiteSpeed
- `embed.php`
- `embed.js`
- `CHANGELOG.md`
- `llms.txt`
- `robots.txt`
- `sitemap.xml`
- `app/`
- `assets/`
- `bin/`
- `src/`

## Notes

- Keep the folder structure exactly as-is.
- Make sure `app/cache/months/` is writable by PHP-FPM if you want runtime month cache files to be generated automatically.
- After upload, run `php bin/precompute-cache.php` once to remove stale cache files and precompute the rolling window from five years before through five years after the current year. Specific year/month targets inside that window are also supported.
- Nginx should route deep links like `/2026-04-25`, `/2026-04`, `/2026`, and `/l2026-03-08` back to `index.php` when deployed at a root domain. If deployed in a subfolder, prefix those examples with the subfolder path.
- Apache/LiteSpeed hosting should upload the hidden `.htaccess` file in this package. Some FTP clients hide dotfiles by default.
- This package intentionally excludes tests, source notes, PDF references, backup archives, and local-only files.
