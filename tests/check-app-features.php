<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

$_SERVER['SCRIPT_NAME'] = '/lich-ta/index.php';
$_SERVER['HTTP_HOST'] = 'calendar.example.test';
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
assertSameAppValue(['day' => 1, 'month' => 4, 'year' => 2026], lta_date_from_path('/lich-ta/2026-04'), 'Month path parsing failed');
assertSameAppValue(['day' => 1, 'month' => 1, 'year' => 2026], lta_date_from_path('/lich-ta/2026'), 'Year path parsing failed');
assertSameAppValue('month', lta_view_from_path('/lich-ta/2026-04'), 'Month path view failed');
assertSameAppValue('month', lta_view_from_path('/lich-ta/2026'), 'Year path view failed');
assertSameAppValue(null, lta_view_from_path('/lich-ta/2026-04-25'), 'Day path should use default home view');
assertSameAppValue('almanac', lta_view_from_path('/lich-ta/thu-vien-thong-thu'), 'Almanac path view failed');
assertSameAppValue(['day' => 24, 'month' => 4, 'year' => 2026], lta_solar_from_lunar_input(8, 3, 2026), 'Lunar input conversion failed');
assertSameAppValue(null, lta_solar_from_lunar_input(30, 2, 2026, 1), 'Invalid leap lunar input should fail');

$cachedApril = lta_month_days(4, 2026);
assertSameAppValue(30, count($cachedApril), 'Month cache day count failed');
assertSameAppValue('Đại Lâm Mộc', $cachedApril[25]['fortune']['napAm'], 'Month cache fortune failed');

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
assertContainsText('https://calendar.example.test/lich-ta/2026-02-17', $markdown, 'Markdown solar link failed');

$_SERVER['SCRIPT_NAME'] = '/index.php';
assertSameAppValue('https://calendar.example.test/2026-02-17', lta_date_url(17, 2, 2026, true), 'Root-domain solar link failed');
$_SERVER['SCRIPT_NAME'] = '/lich-ta/index.php';

$april2026 = lta_day_info(['day' => 25, 'month' => 4, 'year' => 2026]);
assertSameAppValue('Trừ', $april2026['fortune']['truc'], 'Fortune truc failed');
assertSameAppValue('Liễu', $april2026['fortune']['saoNhiThapBatTu'], 'Fortune 28-star failed');
assertSameAppValue('Tiểu Cát', $april2026['fortune']['lucDieu'], 'Fortune luc dieu failed');
assertSameAppValue('Đại Lâm Mộc', $april2026['fortune']['napAm'], 'Fortune nap am failed');
assertSameAppValue('Mộc', $april2026['fortune']['napAmElement'], 'Fortune nap am element failed');
assertSameAppValue(['Tân Hợi', 'Đinh Hợi'], $april2026['fortune']['tuoiXung'], 'Fortune conflict ages failed');
assertSameAppValue('Liễu Thổ Chương', $april2026['fortune']['traditional']['nhiThapBatTu']['animal'], '28-star detail failed');
assertSameAppValue('Tiểu Cát', $april2026['fortune']['traditional']['lucNhan']['dayResult']['name'], 'Luc Nham day result failed');
assertSameAppValue('verified', $april2026['fortune']['traditional']['napAmReference']['sourceStatus'], 'Nap am reference failed');
assertSameAppValue('partial', $april2026['fortune']['traditional']['ngocHap']['coverage'], 'Ngoc Hap coverage failed');
assertSameAppValue(true, isset($april2026['fortune']['traditional']['starGlossary']['good']['Thiên hỷ']), 'Good star glossary failed');
assertSameAppValue('Trừ', $april2026['fortune']['dongCong']['truc'], 'Dong Cong truc failed');
assertSameAppValue('good', $april2026['fortune']['dongCong']['level'], 'Dong Cong level failed');
assertSameAppValue('canChi', $april2026['fortune']['dongCong']['matched'], 'Dong Cong matched source failed');

$nguyetKy = lta_day_info(['day' => 19, 'month' => 6, 'year' => 2026]);
assertSameAppValue(true, in_array('Nguyệt kỵ', array_map(static fn (array $item): string => $item['name'], $nguyetKy['fortune']['traditional']['kyNgay']), true), 'Ky ngay module failed');

$library = \LichTa\TraditionalAlmanac::library();
assertSameAppValue(true, isset($library['twentyEightStars']['Giác']), 'Almanac library 28-star data failed');
assertSameAppValue(true, isset($library['starGlossary']['bad']['Thiên Cương']), 'Almanac library bad star data failed');
assertSameAppValue('partial', $library['ngocHap']['coverage'], 'Almanac library Ngoc Hap note failed');

$march2026 = lta_day_info(['day' => 10, 'month' => 3, 'year' => 2026]);
assertSameAppValue('Định', $march2026['fortune']['dongCong']['truc'], 'Dong Cong month 2 truc failed');
assertSameAppValue('good', $march2026['fortune']['dongCong']['level'], 'Dong Cong Quý Mùi override failed');

$june2026 = lta_day_info(['day' => 29, 'month' => 6, 'year' => 2026]);
assertSameAppValue('Định', $june2026['fortune']['dongCong']['truc'], 'Dong Cong month 5 truc failed');
assertSameAppValue('good', $june2026['fortune']['dongCong']['level'], 'Dong Cong Giáp Tuất override failed');

$calendar = lta_render_calendar(
    lta_month_cells(4, 2026, ['day' => 25, 'month' => 4, 'year' => 2026], ['day' => 25, 'month' => 4, 'year' => 2026]),
    false,
    ['showNapAm' => true]
);
assertContainsText('data-popup-title="Thứ Bảy 25/4/2026"', $calendar, 'Calendar popup title failed');
assertContainsText('data-nap-am="Đại Lâm Mộc"', $calendar, 'Calendar nap am data failed');
assertContainsText('data-nap-element="Mộc"', $calendar, 'Calendar nap am element failed');
assertContainsText('lta-element-moc', $calendar, 'Calendar element class failed');
assertContainsText('data-dong-cong="good"', $calendar, 'Calendar dong cong data failed');
assertContainsText('class="lta-nap-label">Đại Lâm Mộc</span>', $calendar, 'Calendar nap am label failed');

echo "App feature checks passed.\n";
