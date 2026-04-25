# Lich Ta deploy package

Upload the contents of this `deploy` folder to the hosting path for:

`https://app.pdl.vn/lich-ta`

## Files and folders to upload

- `index.php`
- `.htaccess` if the hosting uses Apache or LiteSpeed
- `embed.php`
- `embed.js`
- `llms.txt`
- `app/`
- `assets/`
- `bin/`
- `src/`

## Notes

- Keep the folder structure exactly as-is.
- Make sure `app/cache/months/` is writable by PHP-FPM if you want runtime month cache files to be generated automatically.
- Optional precompute command after upload: `php bin/precompute-cache.php 2026` or `php bin/precompute-cache.php 2026-04`.
- Nginx should route deep links like `/lich-ta/2026-04-25`, `/lich-ta/2026-04`, `/lich-ta/2026`, and `/lich-ta/l2026-03-08` back to `index.php`.
- Apache/LiteSpeed hosting should upload the hidden `.htaccess` file in this package. Some FTP clients hide dotfiles by default.
- This package intentionally excludes tests, source notes, PDF references, backup archives, and local-only files.
