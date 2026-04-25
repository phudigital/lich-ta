<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/LunarCalendar.php';

use LichTa\LunarCalendar;

function assertSameValue(mixed $expected, mixed $actual, string $message): void
{
    if ($expected !== $actual) {
        fwrite(STDERR, $message . PHP_EOL);
        fwrite(STDERR, 'Expected: ' . var_export($expected, true) . PHP_EOL);
        fwrite(STDERR, 'Actual:   ' . var_export($actual, true) . PHP_EOL);
        exit(1);
    }
}

$solarToLunarCases = [
    [[2, 2, 1984], ['day' => 1, 'month' => 1, 'year' => 1984, 'leap' => 0]],
    [[10, 2, 2024], ['day' => 1, 'month' => 1, 'year' => 2024, 'leap' => 0]],
    [[17, 2, 2026], ['day' => 1, 'month' => 1, 'year' => 2026, 'leap' => 0]],
    [[26, 4, 2026], ['day' => 10, 'month' => 3, 'year' => 2026, 'leap' => 0]],
    [[25, 9, 2026], ['day' => 15, 'month' => 8, 'year' => 2026, 'leap' => 0]],
    [[10, 2, 2026], ['day' => 23, 'month' => 12, 'year' => 2025, 'leap' => 0]],
    [[21, 3, 2004], ['day' => 1, 'month' => 2, 'year' => 2004, 'leap' => 1]],
];

foreach ($solarToLunarCases as [$solar, $expected]) {
    [$day, $month, $year] = $solar;
    $actual = LunarCalendar::solarToLunar($day, $month, $year);
    unset($actual['julianDay']);
    assertSameValue($expected, $actual, "solarToLunar failed for {$day}/{$month}/{$year}");

    $roundTrip = LunarCalendar::lunarToSolar($actual['day'], $actual['month'], $actual['year'], $actual['leap']);
    assertSameValue(
        ['day' => $day, 'month' => $month, 'year' => $year],
        $roundTrip,
        "lunarToSolar round-trip failed for {$day}/{$month}/{$year}"
    );
}

assertSameValue(2451545, LunarCalendar::julianDayFromDate(1, 1, 2000), 'Julian day anchor failed');
assertSameValue(['day' => 1, 'month' => 1, 'year' => 2000], LunarCalendar::dateFromJulianDay(2451545), 'Julian date round-trip failed');
assertSameValue('Giap Than', LunarCalendar::yearCanChi(2004), 'Can-chi year failed');
assertSameValue('Dong chi', LunarCalendar::solarTermName(21, 12, 2008), 'Solar term anchor failed');

echo "Lunar calendar checks passed.\n";
