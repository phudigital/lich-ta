<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/LunarCalendar.php';

use LichTa\LunarCalendar;

const LTA_MONTHS = [
    1 => 'Tháng 1',
    2 => 'Tháng 2',
    3 => 'Tháng 3',
    4 => 'Tháng 4',
    5 => 'Tháng 5',
    6 => 'Tháng 6',
    7 => 'Tháng 7',
    8 => 'Tháng 8',
    9 => 'Tháng 9',
    10 => 'Tháng 10',
    11 => 'Tháng 11',
    12 => 'Tháng 12',
];

const LTA_WEEKDAYS = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];

const LTA_STEMS_VI = [
    'Giap' => 'Giáp',
    'At' => 'Ất',
    'Binh' => 'Bính',
    'Dinh' => 'Đinh',
    'Mau' => 'Mậu',
    'Ky' => 'Kỷ',
    'Canh' => 'Canh',
    'Tan' => 'Tân',
    'Nham' => 'Nhâm',
    'Quy' => 'Quý',
];

const LTA_BRANCHES_VI = [
    'Ty' => 'Tý',
    'Suu' => 'Sửu',
    'Dan' => 'Dần',
    'Mao' => 'Mão',
    'Thin' => 'Thìn',
    'Ty.' => 'Tỵ',
    'Ngo' => 'Ngọ',
    'Mui' => 'Mùi',
    'Than' => 'Thân',
    'Dau' => 'Dậu',
    'Tuat' => 'Tuất',
    'Hoi' => 'Hợi',
];

const LTA_TERMS_VI = [
    'Xuan phan' => 'Xuân phân',
    'Thanh minh' => 'Thanh minh',
    'Coc vu' => 'Cốc vũ',
    'Lap ha' => 'Lập hạ',
    'Tieu man' => 'Tiểu mãn',
    'Mang chung' => 'Mang chủng',
    'Ha chi' => 'Hạ chí',
    'Tieu thu' => 'Tiểu thử',
    'Dai thu' => 'Đại thử',
    'Lap thu' => 'Lập thu',
    'Xu thu' => 'Xử thử',
    'Bach lo' => 'Bạch lộ',
    'Thu phan' => 'Thu phân',
    'Han lo' => 'Hàn lộ',
    'Suong giang' => 'Sương giáng',
    'Lap dong' => 'Lập đông',
    'Tieu tuyet' => 'Tiểu tuyết',
    'Dai tuyet' => 'Đại tuyết',
    'Dong chi' => 'Đông chí',
    'Tieu han' => 'Tiểu hàn',
    'Dai han' => 'Đại hàn',
    'Lap xuan' => 'Lập xuân',
    'Vu thuy' => 'Vũ thủy',
    'Kinh trap' => 'Kinh trập',
];

function lta_today(): array
{
    $now = new DateTimeImmutable('now', new DateTimeZone('Asia/Ho_Chi_Minh'));

    return [
        'day' => (int) $now->format('j'),
        'month' => (int) $now->format('n'),
        'year' => (int) $now->format('Y'),
    ];
}

function lta_int_param(string $key, int $default, int $min, int $max): int
{
    $value = filter_input(INPUT_GET, $key, FILTER_VALIDATE_INT);
    if ($value === false || $value === null) {
        return $default;
    }

    return max($min, min($max, $value));
}

function lta_h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function lta_base_url(): string
{
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    $scriptDir = rtrim($scriptDir, '/');

    return $scriptDir === '' ? '' : $scriptDir;
}

function lta_full_base_url(): string
{
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    $scheme = $isHttps ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'app.pdl.vn';

    return $scheme . '://' . $host . lta_base_url();
}

function lta_vi_name(string $asciiName): string
{
    $parts = explode(' ', $asciiName);
    $translated = [];

    foreach ($parts as $part) {
        $translated[] = LTA_STEMS_VI[$part] ?? LTA_BRANCHES_VI[$part] ?? $part;
    }

    return implode(' ', $translated);
}

function lta_vi_term(string $asciiName): string
{
    return LTA_TERMS_VI[$asciiName] ?? $asciiName;
}

function lta_vi_hours(array $hours): array
{
    return array_map(static function (string $hour): string {
        return strtr($hour, LTA_BRANCHES_VI);
    }, $hours);
}

function lta_selected_date(array $today): array
{
    $day = lta_int_param('day', $today['day'], 1, 31);
    $month = lta_int_param('month', $today['month'], 1, 12);
    $year = lta_int_param('year', $today['year'], 1800, 2199);

    if (!checkdate($month, $day, $year)) {
        $day = min($day, cal_days_in_month(CAL_GREGORIAN, $month, $year));
    }

    return ['day' => $day, 'month' => $month, 'year' => $year];
}

function lta_prev_month(int $month, int $year): array
{
    return $month === 1 ? ['month' => 12, 'year' => $year - 1] : ['month' => $month - 1, 'year' => $year];
}

function lta_next_month(int $month, int $year): array
{
    return $month === 12 ? ['month' => 1, 'year' => $year + 1] : ['month' => $month + 1, 'year' => $year];
}

function lta_build_url(array $params, string $path = ''): string
{
    $merged = array_merge($_GET, $params);
    $query = http_build_query($merged);

    return ($path === '' ? strtok($_SERVER['REQUEST_URI'] ?? '', '?') : $path) . ($query === '' ? '' : '?' . $query);
}

function lta_month_cells(int $month, int $year, array $selected, array $today): array
{
    $firstJd = LunarCalendar::julianDayFromDate(1, $month, $year);
    $offset = ($firstJd + 1) % 7;
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $cells = [];

    for ($i = 0; $i < $offset; $i++) {
        $cells[] = null;
    }

    for ($day = 1; $day <= $daysInMonth; $day++) {
        $lunar = LunarCalendar::solarToLunar($day, $month, $year);
        $cells[] = [
            'solarDay' => $day,
            'solarMonth' => $month,
            'solarYear' => $year,
            'lunar' => $lunar,
            'isToday' => $day === $today['day'] && $month === $today['month'] && $year === $today['year'],
            'isSelected' => $day === $selected['day'] && $month === $selected['month'] && $year === $selected['year'],
        ];
    }

    while (count($cells) % 7 !== 0) {
        $cells[] = null;
    }

    return $cells;
}

function lta_day_info(array $date): array
{
    $lunar = LunarCalendar::solarToLunar($date['day'], $date['month'], $date['year']);
    $canChi = LunarCalendar::canChiForSolarDate($date['day'], $date['month'], $date['year']);

    return [
        'solar' => $date,
        'lunar' => $lunar,
        'weekday' => LTA_WEEKDAYS[(LunarCalendar::julianDayFromDate($date['day'], $date['month'], $date['year']) + 1) % 7],
        'term' => lta_vi_term(LunarCalendar::solarTermName($date['day'], $date['month'], $date['year'])),
        'canChi' => [
            'day' => lta_vi_name($canChi['day']),
            'month' => lta_vi_name(str_replace(' nhuan', '', $canChi['month'])) . (str_contains($canChi['month'], 'nhuan') ? ' nhuận' : ''),
            'year' => lta_vi_name($canChi['year']),
        ],
        'hours' => lta_vi_hours(LunarCalendar::auspiciousHours($date['day'], $date['month'], $date['year'])),
    ];
}

function lta_render_calendar(array $cells, bool $isEmbed = false): string
{
    ob_start();
    ?>
    <div class="lta-calendar-grid" role="grid" aria-label="Lịch tháng">
        <?php foreach (LTA_WEEKDAYS as $weekday): ?>
            <div class="lta-weekday" role="columnheader"><?= lta_h($weekday) ?></div>
        <?php endforeach; ?>

        <?php foreach ($cells as $cell): ?>
            <?php if ($cell === null): ?>
                <div class="lta-day lta-day-empty" aria-hidden="true"></div>
                <?php continue; ?>
            <?php endif; ?>
            <?php
            $lunar = $cell['lunar'];
            $dayUrl = lta_build_url([
                'day' => $cell['solarDay'],
                'month' => $cell['solarMonth'],
                'year' => $cell['solarYear'],
            ]);
            $classes = ['lta-day'];
            if ($cell['isToday']) {
                $classes[] = 'is-today';
            }
            if ($cell['isSelected']) {
                $classes[] = 'is-selected';
            }
            if ($lunar['day'] === 1 && $lunar['month'] === 1) {
                $classes[] = 'is-tet';
            }
            ?>
            <a class="<?= lta_h(implode(' ', $classes)) ?>" role="gridcell" href="<?= lta_h($dayUrl) ?>">
                <span class="lta-solar-day"><?= (int) $cell['solarDay'] ?></span>
                <span class="lta-lunar-day">
                    <?= (int) $lunar['day'] === 1 || (int) $cell['solarDay'] === 1 ? (int) $lunar['day'] . '/' . (int) $lunar['month'] : (int) $lunar['day'] ?>
                    <?= (int) $lunar['leap'] === 1 ? 'N' : '' ?>
                </span>
            </a>
        <?php endforeach; ?>
    </div>
    <?php
    return trim((string) ob_get_clean());
}

