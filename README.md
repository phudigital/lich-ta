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

- `src/LunarCalendar.php`: clean PHP calendar engine for future app work.
- `docs/lunar-algorithm-memory.md`: project memory explaining the algorithm, source files, API, and regression anchors.
- `tests/check-lunar-calendar.php`: focused smoke tests for conversion anchors.
- `tinhlich.md`, `tietkhi.md`, `thuvien.php`: source/reference algorithm notes.
- `amlich-js/amlich-hnd.js`: original JavaScript UI/calendar implementation with precomputed 1800-2199 year data.

## Verify

```bash
php -l src/LunarCalendar.php
php -l tests/check-lunar-calendar.php
php tests/check-lunar-calendar.php
```

