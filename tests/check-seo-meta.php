<?php

declare(strict_types=1);

function renderIndexForPath(string $path): string
{
    $_GET = [];
    $_POST = [];
    $_SERVER['SCRIPT_NAME'] = '/lich-ta/index.php';
    $_SERVER['REQUEST_URI'] = '/lich-ta' . $path;
    $_SERVER['HTTP_HOST'] = 'calendar.example.test';
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['HTTP_ACCEPT'] = 'text/html';
    $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 SEO check';
    $_SERVER['HTTP_SEC_FETCH_MODE'] = 'navigate';

    ob_start();
    include __DIR__ . '/../index.php';

    return (string) ob_get_clean();
}

function assertSeoContains(string $needle, string $haystack, string $message): void
{
    if (!str_contains($haystack, $needle)) {
        fwrite(STDERR, $message . PHP_EOL);
        fwrite(STDERR, 'Missing: ' . $needle . PHP_EOL);
        exit(1);
    }
}

$dayPage = renderIndexForPath('/2026-04-25');
assertSeoContains('<title>Lịch âm ngày 25/4/2026 -', $dayPage, 'Day deeplink title should use selected day');
assertSeoContains('content="Xem lịch âm ngày 25/4/2026', $dayPage, 'Day deeplink description should use selected day');
assertSeoContains('giờ hoàng đạo, tuổi xung, nạp âm', $dayPage, 'Day deeplink description should be richer');
assertSeoContains('<link rel="canonical" href="https://calendar.example.test/lich-ta/2026-04-25">', $dayPage, 'Day deeplink canonical should keep day path');
assertSeoContains('<meta property="og:url" content="https://calendar.example.test/lich-ta/2026-04-25">', $dayPage, 'Day deeplink OG URL should keep day path');
assertSeoContains('<h2>Tra lịch âm ngày 25/4/2026</h2>', $dayPage, 'Day deeplink should render crawlable day content');
assertSeoContains('Ngày này thuộc nạp âm', $dayPage, 'Day deeplink should include dynamic day article copy');
assertSeoContains('assets/ngu-hanh-seo.svg', $dayPage, 'Day deeplink should include visual SEO image');
assertSeoContains('Phân tích ngũ hành ngày', $dayPage, 'Day deeplink should include element analysis');

$monthPage = renderIndexForPath('/2026-04');
assertSeoContains('<title>Lịch âm tháng 4/2026 -', $monthPage, 'Month deeplink title should use selected month');
assertSeoContains('content="Xem lịch âm tháng 4 năm 2026', $monthPage, 'Month deeplink description should use selected month');
assertSeoContains('<link rel="canonical" href="https://calendar.example.test/lich-ta/2026-04">', $monthPage, 'Month deeplink canonical should keep month path');
assertSeoContains('<h2>Lịch âm tháng 4 năm 2026</h2>', $monthPage, 'Month deeplink should render crawlable month content');
assertSeoContains('Các ngày nổi bật trong tháng', $monthPage, 'Month deeplink should include dynamic month article copy');
assertSeoContains('Phân bố ngũ hành trong tháng', $monthPage, 'Month deeplink should include element distribution');

$yearPage = renderIndexForPath('/2026');
assertSeoContains('<title>Lịch âm năm 2026 -', $yearPage, 'Year deeplink title should use selected year');
assertSeoContains('content="Xem lịch âm năm 2026', $yearPage, 'Year deeplink description should use selected year');
assertSeoContains('<link rel="canonical" href="https://calendar.example.test/lich-ta/2026">', $yearPage, 'Year deeplink canonical should keep year path');
assertSeoContains('<h2>Lịch âm năm 2026</h2>', $yearPage, 'Year deeplink should render crawlable year content');
assertSeoContains('Xem nhanh từng tháng trong năm 2026', $yearPage, 'Year deeplink should include dynamic year month links');
assertSeoContains('Bản đồ nội dung SEO theo năm', $yearPage, 'Year deeplink should include year SEO visual framing');

echo "SEO meta checks passed.\n";
