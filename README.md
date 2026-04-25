# Lich Ta

Workspace for rebuilding a PHP Vietnamese lunar calendar app from the archived Ho Ngoc Duc lunar-calendar algorithms.

## Current Core

Reusable library:

```php
require_once __DIR__ . '/src/LunarCalendar.php';

use LichTa\LunarCalendar;

$lunar = LunarCalendar::solarToLunar(17, 2, 2026);
// ['day' => 1, 'month' => 1, 'year' => 2026, 'leap' => 0, 'julianDay' => ...]

$solar = LunarCalendar::lunarToSolar(1, 1, 2026);
// ['day' => 17, 'month' => 2, 'year' => 2026]
```

## Files To Know

- `index.php`: customer-facing website for `https://app.pdl.vn/lich-ta`.
- `embed.php`: embeddable iframe widget.
- `embed.js`: JavaScript embed loader that creates the iframe for customers.
- `assets/site.css`, `assets/site.js`: UI styling and small interaction code.
- `src/LunarCalendar.php`: clean PHP calendar engine for future app work.
- `src/DayFortune.php`: clean Vietnamese day-fortune layer for Trực, Lục diệu, Nạp âm, Sao nhị thập bát tú, Hoàng/Hắc đạo, tuổi xung.
- `src/DongCongCalendar.php`: summarized Đổng Công day-quality layer for good/mixed/bad filtering by solar term month and trực.
- `docs/lunar-algorithm-memory.md`: project memory explaining the algorithm, source files, API, and regression anchors.
- `docs/maphuong-reference-notes.md`: notes from reading the maphuong.com Lịch Việt files and the copyright boundary for not copying their obfuscated data file.
- `docs/dong-cong-memory.md`: memory notes from the Đổng Công web article and local PDF, plus the current implementation boundary.
- `tests/check-lunar-calendar.php`: focused smoke tests for conversion anchors.
- `tinhlich.md`, `tietkhi.md`, `thuvien.php`: source/reference algorithm notes.
- `amlich-js/amlich-hnd.js`: original JavaScript UI/calendar implementation with precomputed 1800-2199 year data.

## Customer Embed

Iframe:

```html
<iframe src="https://app.pdl.vn/lich-ta/embed.php" width="100%" height="620" style="border:0;max-width:760px;border-radius:16px;overflow:hidden" loading="lazy"></iframe>
```

JavaScript:

```html
<div id="pdl-lich-ta"></div>
<script src="https://app.pdl.vn/lich-ta/embed.js" data-target="pdl-lich-ta" data-view="month" async></script>
```

Optional script attributes:

- `data-day`, `data-month`, `data-year`: preselect a date.
- `data-height`: iframe height, default `620px`.
- `data-max-width`: iframe max width, default `760px`.
- `data-radius`: iframe border radius, default `18px`.

## VPS Deploy

The app is plain PHP. No Composer or build step is required.

For `https://app.pdl.vn/lich-ta`, place this repository folder at the Nginx-served path for `/lich-ta` and ensure PHP-FPM handles `.php` files. The public entry points are:

- `/lich-ta/index.php`
- `/lich-ta/embed.php`
- `/lich-ta/embed.js`
- `/lich-ta/assets/*`

## Verify

```bash
php -l src/LunarCalendar.php
php -l app/bootstrap.php
php -l index.php
php -l embed.php
php -l tests/check-lunar-calendar.php
php tests/check-lunar-calendar.php
```
