# Lunar Algorithm Memory

This repo contains two algorithm families:

1. Astronomical conversion algorithm in `tinhlich.md`, `tietkhi.md`, and the archived PHP file `thuvien.php`.
2. Precomputed 1800-2199 JavaScript year-code calendar in `amlich-js/amlich-hnd.js`.

For the future PHP app, prefer the astronomical algorithm in `src/LunarCalendar.php` as the reusable core. It does not depend on the 1800-2199 year-code table and keeps the timezone explicit. Use `timeZone = 7.0` for Vietnam.

## Core Rules

- Lunar month starts on the local day containing the new moon.
- A normal lunar year has 12 months; a leap lunar year has 13 months.
- Winter solstice must fall in lunar month 11.
- In a leap year, the first month after winter solstice without a major solar term is the leap month.
- Vietnamese lunar calendar uses UTC+7 / longitude 105E. This is the common reason it differs from China calendar around boundary new moons.

## Reusable PHP API

Main file: `src/LunarCalendar.php`

- `LichTa\LunarCalendar::solarToLunar($day, $month, $year, 7.0)`
  returns `day`, `month`, `year`, `leap`, `julianDay`.
- `LichTa\LunarCalendar::lunarToSolar($day, $month, $year, $leap, 7.0)`
  returns `day`, `month`, `year`.
- `yearCanChi($year)` returns the year cyclic name.
- `canChiForSolarDate($day, $month, $year)` returns day/month/year cyclic names.
- `solarTermName($day, $month, $year)` returns the solar term segment active at that day boundary.
- `auspiciousHours($day, $month, $year)` returns the good-hour branches copied from Ho Ngoc Duc JS logic.

## Source Notes

- `thuvien.php` is not directly reusable: it is wrapped in Wayback HTML and has copied JavaScript fragments inside PHP (`T`, `Math.sin`) plus HTML entities (`&lt;`, `&gt;`).
- `amlich-js/amlich-hnd.js` is useful for UI behavior, can-chi formulas, good-hour patterns, and regression comparison for years 1800-2199.
- `tietkhi.md` explains the 24 solar terms. The implementation exposes both 12-segment major-term logic for leap-month detection and 24-segment solar-term names for UI.

## Known Regression Anchors

- `02/02/1984` solar = `01/01/1984` lunar.
- `10/02/2024` solar = `01/01/2024` lunar.
- `17/02/2026` solar = `01/01/2026` lunar.
- `21/03/2004` solar = `01/02/2004` leap lunar, matching the documentation example.

## Build Direction

Keep the calendar engine separate from presentation:

- `src/LunarCalendar.php`: deterministic date math.
- App layer later: routes/controllers/forms/month grid.
- UI layer later: HTML table, responsive app shell, localization labels.
- Tests: keep conversion tests in `tests/check-lunar-calendar.php` and expand around any future app behavior.
