<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "Run this script from the command line.\n");
    exit(1);
}

$targets = array_slice($argv, 1);
if ($targets === []) {
    $targets = [(string) (int) date('Y')];
}

foreach ($targets as $target) {
    if (preg_match('/^(\d{4})-(\d{1,2})$/', $target, $matches) === 1) {
        $year = (int) $matches[1];
        $month = (int) $matches[2];
        lta_precompute_month($month, $year);
        continue;
    }

    if (preg_match('/^(\d{4})$/', $target) === 1) {
        $year = (int) $target;
        for ($month = 1; $month <= 12; $month++) {
            lta_precompute_month($month, $year);
        }
        continue;
    }

    fwrite(STDERR, "Skip invalid target: {$target}\n");
}

function lta_precompute_month(int $month, int $year): void
{
    if ($month < 1 || $month > 12 || $year < 1800 || $year > 2199) {
        fwrite(STDERR, "Skip out-of-range month: {$year}-{$month}\n");
        return;
    }

    lta_month_days($month, $year);
    fwrite(STDOUT, sprintf("Cached %04d-%02d\n", $year, $month));
}
