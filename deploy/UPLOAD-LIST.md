# Lich Ta deploy package

Upload the contents of this `deploy` folder to the hosting path for:

`https://app.pdl.vn/lich-ta`

## Files and folders to upload

- `index.php`
- `embed.php`
- `embed.js`
- `llms.txt`
- `app/`
- `assets/`
- `src/`

## Notes

- Keep the folder structure exactly as-is.
- Nginx should route deep links like `/lich-ta/2026-04-25`, `/lich-ta/2026-04`, and `/lich-ta/2026` back to `index.php`.
- This package intentionally excludes tests, source notes, PDF references, backup archives, and local-only files.
