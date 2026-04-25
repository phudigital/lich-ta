<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

$_SERVER['SCRIPT_NAME'] = '/lich-ta/index.php';
$_SERVER['HTTP_HOST'] = 'app.pdl.vn';
$_SERVER['HTTPS'] = 'on';

function assertContainsText(string $needle, string $haystack, string $message): void
{
    if (!str_contains($haystack, $needle)) {
        fwrite(STDERR, $message . PHP_EOL);
        fwrite(STDERR, 'Missing: ' . $needle . PHP_EOL);
        fwrite(STDERR, 'Actual: ' . $haystack . PHP_EOL);
        exit(1);
    }
}

function assertSameAppValue(mixed $expected, mixed $actual, string $message): void
{
    if ($expected !== $actual) {
        fwrite(STDERR, $message . PHP_EOL);
        fwrite(STDERR, 'Expected: ' . var_export($expected, true) . PHP_EOL);
        fwrite(STDERR, 'Actual:   ' . var_export($actual, true) . PHP_EOL);
        exit(1);
    }
}

assertSameAppValue(['day' => 17, 'month' => 2, 'year' => 2026], lta_date_from_path('/lich-ta/2026-02-17'), 'Solar path parsing failed');
assertSameAppValue(['day' => 17, 'month' => 2, 'year' => 2026], lta_date_from_path('/lich-ta/l2026-01-01'), 'Lunar path parsing failed');

$popup = lta_popup_text(['day' => 25, 'month' => 6, 'year' => 2019]);
assertContainsText('Thứ Ba 25/6/2019 -+- Ngày 23 tháng 5 âm lịch', $popup, 'Popup heading failed');
assertContainsText('Ngày Quý Tỵ, tháng Canh Ngọ, năm Kỷ Hợi', $popup, 'Popup can-chi failed');
assertContainsText('Giờ đầu ngày: Nhâm Tý', $popup, 'Popup first hour failed');
assertContainsText('Tiết: Hạ chí', $popup, 'Popup solar term failed');
assertContainsText('Giờ hoàng đạo: Sửu (1-3), Thìn (7-9), Ngọ (11-13), Mùi (13-15), Tuất (19-21), Hợi (21-23)', $popup, 'Popup auspicious hours failed');
assertContainsText('Trực: Bế; Sao: Chủy; Lục diệu: Tốc Hỷ', $popup, 'Popup fortune line failed');
assertContainsText('Đổng Công:', $popup, 'Popup dong cong line failed');

$markdown = lta_render_text(['day' => 17, 'month' => 2, 'year' => 2026], true);
assertContainsText('# Thứ Ba, 17/2/2026', $markdown, 'Markdown heading failed');
assertContainsText('Tết Nguyên Đán', $markdown, 'Markdown event failed');
assertContainsText('https://app.pdl.vn/lich-ta/2026-02-17', $markdown, 'Markdown solar link failed');

$april2026 = lta_day_info(['day' => 25, 'month' => 4, 'year' => 2026]);
assertSameAppValue('Trừ', $april2026['fortune']['truc'], 'Fortune truc failed');
assertSameAppValue('Liễu', $april2026['fortune']['saoNhiThapBatTu'], 'Fortune 28-star failed');
assertSameAppValue('Tiểu Cát', $april2026['fortune']['lucDieu'], 'Fortune luc dieu failed');
assertSameAppValue('Đại Lâm Mộc', $april2026['fortune']['napAm'], 'Fortune nap am failed');
assertSameAppValue('Mộc', $april2026['fortune']['napAmElement'], 'Fortune nap am element failed');
assertSameAppValue(['Tân Hợi', 'Đinh Hợi'], $april2026['fortune']['tuoiXung'], 'Fortune conflict ages failed');
assertSameAppValue('Trừ', $april2026['fortune']['dongCong']['truc'], 'Dong Cong truc failed');
assertSameAppValue('mixed', $april2026['fortune']['dongCong']['level'], 'Dong Cong level failed');

$calendar = lta_render_calendar(
    lta_month_cells(4, 2026, ['day' => 25, 'month' => 4, 'year' => 2026], ['day' => 25, 'month' => 4, 'year' => 2026]),
    false,
    ['showNapAm' => true]
);
assertContainsText('data-popup-title="Thứ Bảy 25/4/2026"', $calendar, 'Calendar popup title failed');
assertContainsText('data-nap-am="Đại Lâm Mộc"', $calendar, 'Calendar nap am data failed');
assertContainsText('data-nap-element="Mộc"', $calendar, 'Calendar nap am element failed');
assertContainsText('data-dong-cong="mixed"', $calendar, 'Calendar dong cong data failed');
assertContainsText('class="lta-nap-label">Đại Lâm Mộc</span>', $calendar, 'Calendar nap am label failed');

echo "App feature checks passed.\n";
