<?php

declare(strict_types=1);

namespace LichTa;

final class LunarCalendar
{
    public const VIETNAM_TIMEZONE = 7.0;

    private const STEMS = [
        'Giap', 'At', 'Binh', 'Dinh', 'Mau', 'Ky', 'Canh', 'Tan', 'Nham', 'Quy',
    ];

    private const BRANCHES = [
        'Ty', 'Suu', 'Dan', 'Mao', 'Thin', 'Ty.', 'Ngo', 'Mui', 'Than', 'Dau', 'Tuat', 'Hoi',
    ];

    private const WEEKDAYS = [
        'Chu nhat', 'Thu hai', 'Thu ba', 'Thu tu', 'Thu nam', 'Thu sau', 'Thu bay',
    ];

    private const AUSPICIOUS_HOUR_PATTERNS = [
        '110100101100',
        '001101001011',
        '110011010010',
        '101100110100',
        '001011001101',
        '010010110011',
    ];

    private const SOLAR_TERMS = [
        'Xuan phan', 'Thanh minh', 'Coc vu', 'Lap ha', 'Tieu man', 'Mang chung',
        'Ha chi', 'Tieu thu', 'Dai thu', 'Lap thu', 'Xu thu', 'Bach lo',
        'Thu phan', 'Han lo', 'Suong giang', 'Lap dong', 'Tieu tuyet', 'Dai tuyet',
        'Dong chi', 'Tieu han', 'Dai han', 'Lap xuan', 'Vu thuy', 'Kinh trap',
    ];

    public static function julianDayFromDate(int $day, int $month, int $year): int
    {
        self::assertValidSolarDate($day, $month, $year);

        $a = self::intdivFloor(14 - $month, 12);
        $y = $year + 4800 - $a;
        $m = $month + 12 * $a - 3;
        $jd = $day
            + self::intdivFloor(153 * $m + 2, 5)
            + 365 * $y
            + self::intdivFloor($y, 4)
            - self::intdivFloor($y, 100)
            + self::intdivFloor($y, 400)
            - 32045;

        if ($jd < 2299161) {
            $jd = $day
                + self::intdivFloor(153 * $m + 2, 5)
                + 365 * $y
                + self::intdivFloor($y, 4)
                - 32083;
        }

        return $jd;
    }

    /**
     * @return array{day:int, month:int, year:int}
     */
    public static function dateFromJulianDay(int $julianDay): array
    {
        if ($julianDay > 2299160) {
            $a = $julianDay + 32044;
            $b = self::intdivFloor(4 * $a + 3, 146097);
            $c = $a - self::intdivFloor($b * 146097, 4);
        } else {
            $b = 0;
            $c = $julianDay + 32082;
        }

        $d = self::intdivFloor(4 * $c + 3, 1461);
        $e = $c - self::intdivFloor(1461 * $d, 4);
        $m = self::intdivFloor(5 * $e + 2, 153);

        return [
            'day' => $e - self::intdivFloor(153 * $m + 2, 5) + 1,
            'month' => $m + 3 - 12 * self::intdivFloor($m, 10),
            'year' => $b * 100 + $d - 4800 + self::intdivFloor($m, 10),
        ];
    }

    /**
     * @return array{day:int, month:int, year:int, leap:int, julianDay:int}
     */
    public static function solarToLunar(
        int $day,
        int $month,
        int $year,
        float $timeZone = self::VIETNAM_TIMEZONE
    ): array {
        $dayNumber = self::julianDayFromDate($day, $month, $year);
        $k = (int) floor(($dayNumber - 2415021.076998695) / 29.530588853);
        $monthStart = self::newMoonDay($k + 1, $timeZone);

        if ($monthStart > $dayNumber) {
            $monthStart = self::newMoonDay($k, $timeZone);
        }

        $a11 = self::lunarMonth11($year, $timeZone);
        $b11 = $a11;

        if ($a11 >= $monthStart) {
            $lunarYear = $year;
            $a11 = self::lunarMonth11($year - 1, $timeZone);
        } else {
            $lunarYear = $year + 1;
            $b11 = self::lunarMonth11($year + 1, $timeZone);
        }

        $lunarDay = $dayNumber - $monthStart + 1;
        $diff = (int) floor(($monthStart - $a11) / 29);
        $lunarLeap = 0;
        $lunarMonth = $diff + 11;

        if ($b11 - $a11 > 365) {
            $leapMonthDiff = self::leapMonthOffset($a11, $timeZone);
            if ($diff >= $leapMonthDiff) {
                $lunarMonth = $diff + 10;
                if ($diff === $leapMonthDiff) {
                    $lunarLeap = 1;
                }
            }
        }

        if ($lunarMonth > 12) {
            $lunarMonth -= 12;
        }

        if ($lunarMonth >= 11 && $diff < 4) {
            $lunarYear--;
        }

        return [
            'day' => $lunarDay,
            'month' => $lunarMonth,
            'year' => $lunarYear,
            'leap' => $lunarLeap,
            'julianDay' => $dayNumber,
        ];
    }

    /**
     * @return array{day:int, month:int, year:int}
     */
    public static function lunarToSolar(
        int $lunarDay,
        int $lunarMonth,
        int $lunarYear,
        int $lunarLeap = 0,
        float $timeZone = self::VIETNAM_TIMEZONE
    ): array {
        self::assertValidLunarDate($lunarDay, $lunarMonth, $lunarYear, $lunarLeap);

        if ($lunarMonth < 11) {
            $a11 = self::lunarMonth11($lunarYear - 1, $timeZone);
            $b11 = self::lunarMonth11($lunarYear, $timeZone);
        } else {
            $a11 = self::lunarMonth11($lunarYear, $timeZone);
            $b11 = self::lunarMonth11($lunarYear + 1, $timeZone);
        }

        $k = (int) floor(0.5 + ($a11 - 2415021.076998695) / 29.530588853);
        $off = $lunarMonth - 11;
        if ($off < 0) {
            $off += 12;
        }

        if ($b11 - $a11 > 365) {
            $leapOff = self::leapMonthOffset($a11, $timeZone);
            $leapMonth = $leapOff - 2;
            if ($leapMonth < 0) {
                $leapMonth += 12;
            }

            if ($lunarLeap !== 0 && $lunarMonth !== $leapMonth) {
                return ['day' => 0, 'month' => 0, 'year' => 0];
            }

            if ($lunarLeap !== 0 || $off >= $leapOff) {
                $off++;
            }
        }

        $monthStart = self::newMoonDay($k + $off, $timeZone);

        return self::dateFromJulianDay($monthStart + $lunarDay - 1);
    }

    /**
     * @return array{day:string, month:string, year:string}
     */
    public static function canChiForSolarDate(
        int $day,
        int $month,
        int $year,
        float $timeZone = self::VIETNAM_TIMEZONE
    ): array {
        $lunar = self::solarToLunar($day, $month, $year, $timeZone);
        $jd = $lunar['julianDay'];

        return [
            'day' => self::cyclicName($jd + 9, $jd + 1),
            'month' => self::cyclicName($lunar['year'] * 12 + $lunar['month'] + 3, $lunar['month'] + 1)
                . ($lunar['leap'] === 1 ? ' nhuan' : ''),
            'year' => self::yearCanChi($lunar['year']),
        ];
    }

    public static function yearCanChi(int $year): string
    {
        return self::cyclicName($year + 6, $year + 8);
    }

    public static function weekdayName(int $day, int $month, int $year): string
    {
        $jd = self::julianDayFromDate($day, $month, $year);

        return self::WEEKDAYS[($jd + 1) % 7];
    }

    public static function solarTermName(int $day, int $month, int $year, float $timeZone = self::VIETNAM_TIMEZONE): string
    {
        $jd = self::julianDayFromDate($day, $month, $year);

        return self::SOLAR_TERMS[self::sunLongitudeSegment24($jd + 1, $timeZone)];
    }

    /**
     * @return list<string>
     */
    public static function auspiciousHours(int $day, int $month, int $year): array
    {
        $jd = self::julianDayFromDate($day, $month, $year);
        $chiOfDay = ($jd + 1) % 12;
        $pattern = self::AUSPICIOUS_HOUR_PATTERNS[$chiOfDay % 6];
        $hours = [];

        for ($i = 0; $i < 12; $i++) {
            if ($pattern[$i] === '1') {
                $start = ($i * 2 + 23) % 24;
                $end = ($i * 2 + 1) % 24;
                $hours[] = self::BRANCHES[$i] . ' (' . $start . '-' . $end . ')';
            }
        }

        return $hours;
    }

    public static function newMoonDay(int $k, float $timeZone = self::VIETNAM_TIMEZONE): int
    {
        $t = $k / 1236.85;
        $t2 = $t * $t;
        $t3 = $t2 * $t;
        $dr = M_PI / 180;

        $jd1 = 2415020.75933 + 29.53058868 * $k + 0.0001178 * $t2 - 0.000000155 * $t3;
        $jd1 += 0.00033 * sin((166.56 + 132.87 * $t - 0.009173 * $t2) * $dr);

        $m = 359.2242 + 29.10535608 * $k - 0.0000333 * $t2 - 0.00000347 * $t3;
        $mpr = 306.0253 + 385.81691806 * $k + 0.0107306 * $t2 + 0.00001236 * $t3;
        $f = 21.2964 + 390.67050646 * $k - 0.0016528 * $t2 - 0.00000239 * $t3;

        $c1 = (0.1734 - 0.000393 * $t) * sin($m * $dr) + 0.0021 * sin(2 * $dr * $m);
        $c1 -= 0.4068 * sin($mpr * $dr) - 0.0161 * sin($dr * 2 * $mpr);
        $c1 -= 0.0004 * sin($dr * 3 * $mpr);
        $c1 += 0.0104 * sin($dr * 2 * $f) - 0.0051 * sin($dr * ($m + $mpr));
        $c1 -= 0.0074 * sin($dr * ($m - $mpr)) - 0.0004 * sin($dr * (2 * $f + $m));
        $c1 -= 0.0004 * sin($dr * (2 * $f - $m)) + 0.0006 * sin($dr * (2 * $f + $mpr));
        $c1 += 0.0010 * sin($dr * (2 * $f - $mpr)) + 0.0005 * sin($dr * (2 * $mpr + $m));

        if ($t < -11) {
            $deltaT = 0.001 + 0.000839 * $t + 0.0002261 * $t2 - 0.00000845 * $t3 - 0.000000081 * $t * $t3;
        } else {
            $deltaT = -0.000278 + 0.000265 * $t + 0.000262 * $t2;
        }

        return (int) floor($jd1 + $c1 - $deltaT + 0.5 + $timeZone / 24);
    }

    public static function sunLongitudeDegrees(float $julianDay): float
    {
        $t = ($julianDay - 2451545.0) / 36525;
        $t2 = $t * $t;
        $dr = M_PI / 180;
        $m = 357.52910 + 35999.05030 * $t - 0.0001559 * $t2 - 0.00000048 * $t * $t2;
        $l0 = 280.46645 + 36000.76983 * $t + 0.0003032 * $t2;
        $dl = (1.914600 - 0.004817 * $t - 0.000014 * $t2) * sin($dr * $m);
        $dl += (0.019993 - 0.000101 * $t) * sin($dr * 2 * $m) + 0.000290 * sin($dr * 3 * $m);
        $theta = $l0 + $dl;
        $omega = 125.04 - 1934.136 * $t;
        $lambda = $theta - 0.00569 - 0.00478 * sin($omega * $dr);

        return self::normalizeDegrees($lambda);
    }

    public static function sunLongitudeSegment12(int $dayNumber, float $timeZone = self::VIETNAM_TIMEZONE): int
    {
        $degrees = self::sunLongitudeDegrees($dayNumber - 0.5 - $timeZone / 24);

        return (int) floor($degrees / 30);
    }

    public static function sunLongitudeSegment24(int $dayNumber, float $timeZone = self::VIETNAM_TIMEZONE): int
    {
        $degrees = self::sunLongitudeDegrees($dayNumber - 0.5 - $timeZone / 24);

        return (int) floor($degrees / 15);
    }

    public static function lunarMonth11(int $year, float $timeZone = self::VIETNAM_TIMEZONE): int
    {
        $off = self::julianDayFromDate(31, 12, $year) - 2415021;
        $k = (int) floor($off / 29.530588853);
        $nm = self::newMoonDay($k, $timeZone);
        $sunLong = self::sunLongitudeSegment12($nm, $timeZone);

        if ($sunLong >= 9) {
            $nm = self::newMoonDay($k - 1, $timeZone);
        }

        return $nm;
    }

    public static function leapMonthOffset(int $a11, float $timeZone = self::VIETNAM_TIMEZONE): int
    {
        $k = (int) floor(($a11 - 2415021.076998695) / 29.530588853 + 0.5);
        $last = 0;
        $i = 1;
        $arc = self::sunLongitudeSegment12(self::newMoonDay($k + $i, $timeZone), $timeZone);

        do {
            $last = $arc;
            $i++;
            $arc = self::sunLongitudeSegment12(self::newMoonDay($k + $i, $timeZone), $timeZone);
        } while ($arc !== $last && $i < 14);

        return $i - 1;
    }

    private static function cyclicName(int $stemIndex, int $branchIndex): string
    {
        return self::STEMS[self::positiveModulo($stemIndex, 10)] . ' ' . self::BRANCHES[self::positiveModulo($branchIndex, 12)];
    }

    private static function normalizeDegrees(float $degrees): float
    {
        $normalized = fmod($degrees, 360.0);
        if ($normalized < 0) {
            $normalized += 360.0;
        }

        return $normalized;
    }

    private static function positiveModulo(int $value, int $modulo): int
    {
        return (($value % $modulo) + $modulo) % $modulo;
    }

    private static function intdivFloor(int $a, int $b): int
    {
        return (int) floor($a / $b);
    }

    private static function assertValidSolarDate(int $day, int $month, int $year): void
    {
        if (!checkdate($month, $day, $year)) {
            throw new \InvalidArgumentException("Invalid solar date: {$day}/{$month}/{$year}");
        }
    }

    private static function assertValidLunarDate(int $day, int $month, int $year, int $leap): void
    {
        if ($day < 1 || $day > 30 || $month < 1 || $month > 12 || $year === 0 || !in_array($leap, [0, 1], true)) {
            throw new \InvalidArgumentException("Invalid lunar date: {$day}/{$month}/{$year}, leap={$leap}");
        }
    }
}
