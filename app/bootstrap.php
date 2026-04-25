<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/LunarCalendar.php';
require_once __DIR__ . '/../src/DayFortune.php';

use LichTa\DayFortune;
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
const LTA_WEEKDAYS_FULL = ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'];

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

const LTA_EVENTS = [
    ['type' => 'SOLAR', 'month' => 1, 'day' => 1, 'name' => 'Tết Dương lịch', 'public' => true],
    ['type' => 'SOLAR', 'month' => 4, 'day' => 30, 'name' => 'Ngày Chiến thắng', 'public' => true],
    ['type' => 'SOLAR', 'month' => 5, 'day' => 1, 'name' => 'Quốc tế Lao động', 'public' => true],
    ['type' => 'SOLAR', 'month' => 9, 'day' => 2, 'name' => 'Quốc khánh', 'public' => true],
    ['type' => 'SOLAR', 'month' => 12, 'day' => 25, 'name' => 'Giáng sinh', 'public' => false],
    ['type' => 'LUNAR', 'month' => 1, 'day' => 1, 'name' => 'Tết Nguyên Đán', 'public' => true],
    ['type' => 'LUNAR', 'month' => 1, 'day' => 2, 'name' => 'Mùng 2 Tết', 'public' => true],
    ['type' => 'LUNAR', 'month' => 1, 'day' => 3, 'name' => 'Mùng 3 Tết', 'public' => true],
    ['type' => 'LUNAR', 'month' => 1, 'day' => 15, 'name' => 'Rằm tháng Giêng', 'public' => false],
    ['type' => 'LUNAR', 'month' => 3, 'day' => 3, 'name' => 'Tết Hàn Thực', 'public' => false],
    ['type' => 'LUNAR', 'month' => 3, 'day' => 10, 'name' => 'Giỗ Tổ Hùng Vương', 'public' => true],
    ['type' => 'LUNAR', 'month' => 4, 'day' => 15, 'name' => 'Phật Đản', 'public' => false],
    ['type' => 'LUNAR', 'month' => 5, 'day' => 5, 'name' => 'Tết Đoan Ngọ', 'public' => false],
    ['type' => 'LUNAR', 'month' => 7, 'day' => 15, 'name' => 'Lễ Vu Lan', 'public' => false],
    ['type' => 'LUNAR', 'month' => 8, 'day' => 15, 'name' => 'Tết Trung Thu', 'public' => false],
    ['type' => 'LUNAR', 'month' => 12, 'day' => 23, 'name' => 'Ông Táo về trời', 'public' => false],
    ['type' => 'LUNAR', 'month' => 12, 'day' => 30, 'name' => '30 Tháng Chạp', 'public' => true],
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
    $pathDate = lta_date_from_path();
    if ($pathDate !== null) {
        return $pathDate;
    }

    $day = lta_int_param('day', $today['day'], 1, 31);
    $month = lta_int_param('month', $today['month'], 1, 12);
    $year = lta_int_param('year', $today['year'], 1800, 2199);

    if (!checkdate($month, $day, $year)) {
        $day = min($day, cal_days_in_month(CAL_GREGORIAN, $month, $year));
    }

    return ['day' => $day, 'month' => $month, 'year' => $year];
}

function lta_date_from_path(?string $path = null): ?array
{
    $path ??= parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $base = lta_base_url();
    if ($base !== '' && str_starts_with($path, $base)) {
        $path = substr($path, strlen($base));
    }
    $path = trim($path, '/');
    if ($path === '' || in_array($path, ['index.php', 'embed.php'], true)) {
        return null;
    }

    if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $path, $matches) === 1) {
        $year = (int) $matches[1];
        $month = (int) $matches[2];
        $day = (int) $matches[3];
        if (checkdate($month, $day, $year)) {
            return ['day' => $day, 'month' => $month, 'year' => $year];
        }
    }

    if (preg_match('/^[lL](\d{4})[-\/](\d{1,2})[-\/](\d{1,2})$/', $path, $matches) === 1) {
        $solar = LunarCalendar::lunarToSolar((int) $matches[3], (int) $matches[2], (int) $matches[1]);
        if ($solar['day'] > 0 && checkdate($solar['month'], $solar['day'], $solar['year'])) {
            return $solar;
        }
    }

    return null;
}

function lta_is_programmatic_request(): bool
{
    $accept = strtolower($_SERVER['HTTP_ACCEPT'] ?? '');
    $ua = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
    $fetchMode = $_SERVER['HTTP_SEC_FETCH_MODE'] ?? '';

    if (str_contains($accept, 'text/markdown') || str_contains($accept, 'application/markdown') || str_contains($accept, 'text/plain')) {
        return true;
    }
    if ($fetchMode !== '') {
        return false;
    }
    if ($ua === '') {
        return true;
    }

    foreach (['curl/', 'wget/', 'openai', 'chatgpt', 'claude', 'anthropic', 'gemini', 'deepseek', 'qwen', 'gpt', 'langchain'] as $needle) {
        if (str_contains($ua, $needle)) {
            return true;
        }
    }

    return false;
}

function lta_prefers_markdown(): bool
{
    $accept = strtolower($_SERVER['HTTP_ACCEPT'] ?? '');
    $ua = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');

    return str_contains($accept, 'text/markdown')
        || str_contains($accept, 'application/markdown')
        || str_contains($ua, 'openai')
        || str_contains($ua, 'chatgpt')
        || str_contains($ua, 'claude')
        || str_contains($ua, 'anthropic');
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

function lta_date_url(int $day, int $month, int $year, bool $absolute = false): string
{
    $path = lta_base_url() . '/' . sprintf('%04d-%02d-%02d', $year, $month, $day);

    return $absolute ? lta_origin_url() . $path : $path;
}

function lta_lunar_date_url(array $lunar, bool $absolute = false): string
{
    $path = lta_base_url() . '/' . sprintf('l%04d-%02d-%02d', $lunar['year'], $lunar['month'], $lunar['day']);

    return $absolute ? lta_origin_url() . $path : $path;
}

function lta_origin_url(): string
{
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    $scheme = $isHttps ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'app.pdl.vn';

    return $scheme . '://' . $host;
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
        $events = lta_find_events($day, $month, $year, $lunar);
        $cells[] = [
            'solarDay' => $day,
            'solarMonth' => $month,
            'solarYear' => $year,
            'lunar' => $lunar,
            'events' => $events,
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
    $weekdayIndex = (LunarCalendar::julianDayFromDate($date['day'], $date['month'], $date['year']) + 1) % 7;
    $events = lta_find_events($date['day'], $date['month'], $date['year'], $lunar);
    $fortune = DayFortune::forSolarDate($date['day'], $date['month'], $date['year']);

    return [
        'solar' => $date,
        'lunar' => $lunar,
        'weekday' => LTA_WEEKDAYS[$weekdayIndex],
        'weekdayFull' => LTA_WEEKDAYS_FULL[$weekdayIndex],
        'term' => lta_vi_term(LunarCalendar::solarTermName($date['day'], $date['month'], $date['year'])),
        'canChi' => [
            'day' => lta_vi_name($canChi['day']),
            'month' => lta_vi_name(str_replace(' nhuan', '', $canChi['month'])) . (str_contains($canChi['month'], 'nhuan') ? ' nhuận' : ''),
            'year' => lta_vi_name($canChi['year']),
        ],
        'firstHour' => lta_first_hour_can_chi((int) $lunar['julianDay']),
        'hours' => lta_vi_hours(LunarCalendar::auspiciousHours($date['day'], $date['month'], $date['year'])),
        'events' => $events,
        'fortune' => $fortune,
    ];
}

function lta_first_hour_can_chi(int $julianDay): string
{
    $stems = array_values(LTA_STEMS_VI);

    return $stems[(($julianDay - 1) * 2) % 10] . ' Tý';
}

function lta_popup_text(array $date): string
{
    $info = lta_day_info($date);
    $lunar = $info['lunar'];
    $lunarMonth = 'tháng ' . (int) $lunar['month'] . ' âm lịch';
    if ((int) $lunar['leap'] === 1) {
        $lunarMonth = 'tháng ' . (int) $lunar['month'] . ' nhuận âm lịch';
    }

    $lines = [
        $info['weekdayFull'] . ' ' . (int) $date['day'] . '/' . (int) $date['month'] . '/' . (int) $date['year'] . ' -+- Ngày ' . (int) $lunar['day'] . ' ' . $lunarMonth,
        'Ngày ' . $info['canChi']['day'] . ', tháng ' . $info['canChi']['month'] . ', năm ' . $info['canChi']['year'],
        'Giờ đầu ngày: ' . $info['firstHour'],
        'Tiết: ' . $info['term'],
        'Giờ hoàng đạo: ' . implode(', ', $info['hours']),
        'Trực: ' . $info['fortune']['truc'] . '; Sao: ' . $info['fortune']['saoNhiThapBatTu'] . '; Lục diệu: ' . $info['fortune']['lucDieu'],
    ];

    if ($info['events'] !== []) {
        $lines[] = 'Sự kiện: ' . implode(', ', array_map(static fn (array $event): string => $event['name'], $info['events']));
    }

    return implode("\n", $lines);
}

function lta_find_events(int $solarDay, int $solarMonth, int $solarYear, array $lunar): array
{
    $events = [];
    foreach (LTA_EVENTS as $event) {
        $matchesSolar = $event['type'] === 'SOLAR' && $event['month'] === $solarMonth && $event['day'] === $solarDay;
        $matchesLunar = $event['type'] === 'LUNAR'
            && (int) $lunar['leap'] === 0
            && $event['month'] === (int) $lunar['month']
            && $event['day'] === (int) $lunar['day'];

        if ($matchesSolar || $matchesLunar) {
            $events[] = $event;
        }
    }

    return $events;
}

function lta_render_text(array $date, bool $markdown = false): string
{
    $info = lta_day_info($date);
    $cells = lta_month_cells($date['month'], $date['year'], $date, lta_today());
    $eventNames = array_map(static fn (array $event): string => $event['name'], $info['events']);
    $lunarLabel = $info['lunar']['day'] . '/' . $info['lunar']['month'] . '/' . $info['lunar']['year'] . ((int) $info['lunar']['leap'] === 1 ? ' nhuận' : '');

    if ($markdown) {
        $lines = [
            '# ' . $info['weekdayFull'] . ', ' . $date['day'] . '/' . $date['month'] . '/' . $date['year'],
            '',
            '- Âm lịch: **' . $lunarLabel . '**',
            '- Can Chi: ngày ' . $info['canChi']['day'] . ', tháng ' . $info['canChi']['month'] . ', năm ' . $info['canChi']['year'],
            '- Giờ đầu ngày: ' . $info['firstHour'],
            '- Tiết khí: ' . $info['term'],
            '- Giờ hoàng đạo: ' . implode(', ', $info['hours']),
            '- Trực: ' . $info['fortune']['truc'],
            '- Sao nhị thập bát tú: ' . $info['fortune']['saoNhiThapBatTu'],
            '- Lục diệu: ' . $info['fortune']['lucDieu'],
            '- Nạp âm ngày: ' . $info['fortune']['napAm'],
            '- Hoàng/Hắc đạo: ' . $info['fortune']['hoangHacDao'] . ($info['fortune']['hoangHacDaoStar'] !== null ? ' - ' . $info['fortune']['hoangHacDaoStar'] : ''),
        ];
        if ($eventNames !== []) {
            $lines[] = '- Sự kiện: ' . implode('; ', $eventNames);
        }
        $lines[] = '- Link dương lịch: ' . lta_date_url($date['day'], $date['month'], $date['year'], true);
        $lines[] = '- Link âm lịch: ' . lta_lunar_date_url($info['lunar'], true);
        $lines[] = '';
        $lines[] = '## Tháng ' . $date['month'] . ' ' . $date['year'];
        $lines[] = '';
        $lines[] = lta_render_markdown_grid($cells);

        return implode("\n", $lines) . "\n";
    }

    $lines = [
        $info['weekdayFull'] . ', ' . $date['day'] . '/' . $date['month'] . '/' . $date['year'],
        'Âm lịch: ' . $lunarLabel,
        'Can Chi: ngày ' . $info['canChi']['day'] . ', tháng ' . $info['canChi']['month'] . ', năm ' . $info['canChi']['year'],
        'Giờ đầu ngày: ' . $info['firstHour'],
        'Tiết khí: ' . $info['term'],
        'Giờ hoàng đạo: ' . implode(', ', $info['hours']),
        'Trực: ' . $info['fortune']['truc'],
        'Sao nhị thập bát tú: ' . $info['fortune']['saoNhiThapBatTu'],
        'Lục diệu: ' . $info['fortune']['lucDieu'],
        'Nạp âm ngày: ' . $info['fortune']['napAm'],
        'Hoàng/Hắc đạo: ' . $info['fortune']['hoangHacDao'] . ($info['fortune']['hoangHacDaoStar'] !== null ? ' - ' . $info['fortune']['hoangHacDaoStar'] : ''),
    ];
    if ($eventNames !== []) {
        $lines[] = 'Sự kiện: ' . implode('; ', $eventNames);
    }

    return implode("\n", $lines) . "\n";
}

function lta_render_markdown_grid(array $cells): string
{
    $rows = [];
    $rows[] = '| CN | T2 | T3 | T4 | T5 | T6 | T7 |';
    $rows[] = '|---|---|---|---|---|---|---|';

    foreach (array_chunk($cells, 7) as $week) {
        $solar = [];
        $lunar = [];
        foreach ($week as $cell) {
            if ($cell === null) {
                $solar[] = ' ';
                $lunar[] = ' ';
                continue;
            }
            $solar[] = $cell['isSelected'] ? '**' . $cell['solarDay'] . '**' : (string) $cell['solarDay'];
            $lunarValue = $cell['lunar']['day'] === 1 || $cell['solarDay'] === 1
                ? $cell['lunar']['day'] . '/' . $cell['lunar']['month']
                : (string) $cell['lunar']['day'];
            $lunar[] = 'AL ' . $lunarValue;
        }
        $rows[] = '| ' . implode(' | ', $solar) . ' |';
        $rows[] = '| ' . implode(' | ', $lunar) . ' |';
    }

    return implode("\n", $rows);
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
            $cellDate = ['day' => $cell['solarDay'], 'month' => $cell['solarMonth'], 'year' => $cell['solarYear']];
            $dayUrl = lta_date_url($cell['solarDay'], $cell['solarMonth'], $cell['solarYear']);
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
            if ($cell['events'] !== []) {
                $classes[] = 'has-event';
            }
            $eventLabel = implode(', ', array_map(static fn (array $event): string => $event['name'], $cell['events']));
            $popupText = lta_popup_text($cellDate);
            ?>
            <a class="<?= lta_h(implode(' ', $classes)) ?>" role="gridcell" href="<?= lta_h($dayUrl) ?>" title="<?= lta_h($eventLabel) ?>" data-lta-day data-popup="<?= lta_h($popupText) ?>">
                <span class="lta-solar-day"><?= (int) $cell['solarDay'] ?></span>
                <span class="lta-lunar-day">
                    <?= (int) $lunar['day'] === 1 || (int) $cell['solarDay'] === 1 ? (int) $lunar['day'] . '/' . (int) $lunar['month'] : (int) $lunar['day'] ?>
                    <?= (int) $lunar['leap'] === 1 ? 'N' : '' ?>
                </span>
                <?php if ($cell['events'] !== []): ?>
                    <span class="lta-event-dot" aria-label="<?= lta_h($eventLabel) ?>"></span>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </div>
    <?php
    return trim((string) ob_get_clean());
}
