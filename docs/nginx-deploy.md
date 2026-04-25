# Nginx Deploy Notes

Target URL:

```text
https://app.pdl.vn/lich-ta
```

This project is a plain PHP app and can live under a subdirectory. There is no build command.

## Expected Files On VPS

Keep these paths available under the `/lich-ta` URL prefix:

- `index.php`
- `embed.php`
- `embed.js`
- `.htaccess` if the server is Apache/LiteSpeed
- `app/`
- `assets/`
- `src/`

The old source/reference files can remain in the repo, but only the files above are needed to serve the app.

## Example Nginx Location

Adjust `root` and PHP socket to match the server.

```nginx
location /lich-ta/ {
    alias /var/www/app.pdl.vn/lich-ta/;
    index index.php;
    try_files $uri $uri/ /lich-ta/index.php?$query_string;
}

location ~ ^/lich-ta/(.+\.php)$ {
    alias /var/www/app.pdl.vn/lich-ta/$1;
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME /var/www/app.pdl.vn/lich-ta/$1;
    fastcgi_param SCRIPT_NAME /lich-ta/$1;
    fastcgi_pass unix:/run/php/php8.2-fpm.sock;
}
```

If the site already has a generic PHP handler, only the static `/lich-ta/` mapping may be needed.

## Smoke Checks After Deploy

```bash
curl -I https://app.pdl.vn/lich-ta/
curl -I https://app.pdl.vn/lich-ta/embed.php
curl -I https://app.pdl.vn/lich-ta/embed.js
curl -I https://app.pdl.vn/lich-ta/2026-04-25
curl -I https://app.pdl.vn/lich-ta/2026-04
curl -I https://app.pdl.vn/lich-ta/l2026-03-08
```

Open:

```text
https://app.pdl.vn/lich-ta/?day=17&month=2&year=2026
https://app.pdl.vn/lich-ta/2026-02-17
https://app.pdl.vn/lich-ta/embed.php?day=17&month=2&year=2026
```

Both should show `1/1/2026` âm lịch for `17/2/2026`.
