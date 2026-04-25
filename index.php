<?php

declare(strict_types=1);

require_once __DIR__ . '/app/bootstrap.php';

$today = lta_today();
$selectedState = lta_selected_date_state($today);
$selected = $selectedState['date'];
$view = (string) ($_GET['view'] ?? lta_view_from_path() ?? 'today');
$view = in_array($view, ['today', 'month', 'nap-am', 'convert', 'embed', 'almanac', 'about', 'terms', 'privacy'], true) ? $view : 'today';
if ($view === 'nap-am') {
    $view = 'month';
}
$usingLunarInput = ($_GET['date_type'] ?? '') === 'lunar';
$month = $usingLunarInput ? $selected['month'] : lta_int_param('month', $selected['month'], 1, 12);
$year = $usingLunarInput ? $selected['year'] : lta_int_param('year', $selected['year'], 1800, 2199);
$selected['month'] = $month;
$selected['year'] = $year;
if (!checkdate($selected['month'], $selected['day'], $selected['year'])) {
    $selected['day'] = min($selected['day'], cal_days_in_month(CAL_GREGORIAN, $selected['month'], $selected['year']));
}
$cells = lta_month_cells($month, $year, $selected, $today);
$dayInfo = lta_day_info($selected);
$traditional = $dayInfo['fortune']['traditional'];
$almanacLibrary = \LichTa\TraditionalAlmanac::library();
$levelLabel = static fn (string $level): string => match ($level) {
    'good' => 'Tốt',
    'bad' => 'Xấu',
    'mixed' => 'Cân nhắc',
    default => 'Đang bổ sung',
};
$dateInputMode = $selectedState['inputMode'];
$dateInputError = $selectedState['error'];
$selectedLunar = $dayInfo['lunar'];

if (lta_is_programmatic_request()) {
    $markdown = lta_prefers_markdown();
    header('Content-Type: ' . ($markdown ? 'text/markdown' : 'text/plain') . '; charset=UTF-8');
    echo lta_render_text($selected, $markdown);
    exit;
}

$prev = lta_prev_month($month, $year);
$next = lta_next_month($month, $year);
$selectedDate = new DateTimeImmutable(sprintf('%04d-%02d-%02d', $selected['year'], $selected['month'], $selected['day']));
$prevDay = $selectedDate->modify('-1 day');
$nextDay = $selectedDate->modify('+1 day');
$baseUrl = 'https://app.pdl.vn/lich-ta';
$iframeCode = '<iframe src="' . $baseUrl . '/embed.php" width="100%" height="620" style="border:0;max-width:760px;border-radius:16px;overflow:hidden" loading="lazy"></iframe>';
$scriptCode = '<div id="pdl-lich-ta"></div>' . "\n" . '<script src="' . $baseUrl . '/embed.js" data-target="pdl-lich-ta" data-view="month" async></script>';
$pagePaths = [
    'today' => './',
    'embed' => './ma-nhung-lich-viet',
    'almanac' => './thu-vien-thong-thu',
    'about' => './gioi-thieu',
    'terms' => './dieu-khoan-su-dung',
    'privacy' => './chinh-sach-bao-mat',
];
$canonicalPaths = [
    'today' => '',
    'month' => sprintf('%04d-%02d', $year, $month),
    'convert' => 'index.php?view=convert',
    'embed' => 'ma-nhung-lich-viet',
    'almanac' => 'thu-vien-thong-thu',
    'about' => 'gioi-thieu',
    'terms' => 'dieu-khoan-su-dung',
    'privacy' => 'chinh-sach-bao-mat',
];
$viewMeta = [
    'today' => [
        'title' => 'Lịch Ta - Lịch âm Việt Nam hôm nay, đổi ngày âm dương',
        'description' => 'Tra lịch âm hôm nay, ngày Can Chi, tiết khí, giờ hoàng đạo và đổi ngày âm dương bằng ứng dụng Lịch Ta chạy nhanh trên nền PHP.',
    ],
    'month' => [
        'title' => 'Lịch âm tháng ' . (int) $month . '/' . (int) $year . ' - Lịch Việt, Can Chi, tiết khí',
        'description' => 'Xem lịch âm tháng ' . (int) $month . ' năm ' . (int) $year . ' với ngày âm, Can Chi, nạp âm, tiết khí, ngày lễ và bộ lọc ngày tốt xấu cơ bản.',
    ],
    'convert' => [
        'title' => 'Đổi ngày âm dương - Công cụ chuyển lịch âm lịch Việt Nam',
        'description' => 'Công cụ đổi ngày dương sang âm và âm sang dương cho lịch Việt Nam, hỗ trợ tháng nhuận, Can Chi, tiết khí và thông tin ngày.',
    ],
    'embed' => [
        'title' => 'Code nhúng lịch Việt - Nhúng lịch âm vào website bằng iframe hoặc JavaScript',
        'description' => 'Lấy code nhúng lịch Việt, lịch âm Việt Nam cho website bằng iframe hoặc JavaScript. Widget responsive, có lịch tháng, ngày âm, Can Chi và thông tin ngày.',
    ],
    'almanac' => [
        'title' => 'Thư viện Thông Thư - Cách xem ngày tốt xấu theo lịch Việt',
        'description' => 'Tìm hiểu cách xem ngày tốt xấu theo lịch Việt: ngày kỵ, sao tốt xấu, Nhị Thập Bát Tú, Lục Nhâm xuất hành, Ngọc Hạp và nạp âm.',
    ],
    'about' => [
        'title' => 'Giới thiệu Lịch Ta - Ứng dụng lịch âm Việt Nam có mã nhúng website',
        'description' => 'Tìm hiểu Lịch Ta, ứng dụng lịch âm Việt Nam hỗ trợ tra ngày, đổi ngày âm dương, lịch tháng, Can Chi, tiết khí và code nhúng lịch Việt cho website.',
    ],
    'terms' => [
        'title' => 'Điều khoản sử dụng - Lịch Ta',
        'description' => 'Điều khoản sử dụng ứng dụng Lịch Ta, phạm vi thông tin lịch âm, quyền nhúng widget và giới hạn trách nhiệm khi tham khảo ngày tốt xấu.',
    ],
    'privacy' => [
        'title' => 'Chính sách bảo mật - Lịch Ta',
        'description' => 'Chính sách bảo mật của Lịch Ta: dữ liệu truy cập, cookie, mã nhúng lịch Việt và cách chúng tôi hạn chế thu thập thông tin cá nhân.',
    ],
];
$meta = $viewMeta[$view] ?? $viewMeta['today'];
$canonicalPath = $canonicalPaths[$view] ?? '';
$canonicalUrl = rtrim($baseUrl, '/') . ($canonicalPath !== '' ? '/' . ltrim($canonicalPath, '/') : '/');
$thumbnailUrl = rtrim($baseUrl, '/') . '/assets/lich-ta-thumbnail.svg';
$viewUrl = static function (string $target, array $date) use ($month, $year, $pagePaths): string {
    if (isset($pagePaths[$target])) {
        return $pagePaths[$target];
    }

    $params = ['view' => $target];
    if ($target !== 'today') {
        $params += ['day' => $date['day'], 'month' => $month, 'year' => $year];
    }

    return $target === 'today' ? './' : 'index.php?' . http_build_query($params);
};
$faqJson = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => [
        [
            '@type' => 'Question',
            'name' => 'Làm sao nhúng lịch âm Việt Nam vào website?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Bạn có thể dùng iframe hoặc file embed.js của Lịch Ta. Iframe phù hợp khi muốn copy nhanh, còn JavaScript phù hợp khi muốn widget tự tạo iframe và tự điều chỉnh chiều cao trong trang.',
            ],
        ],
        [
            '@type' => 'Question',
            'name' => 'Widget lịch Việt có responsive trên điện thoại không?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Có. Widget được thiết kế co theo chiều rộng container, giữ thông tin ngày dương, ngày âm và phần chi tiết ngày ở dưới khi hiển thị trên mobile.',
            ],
        ],
        [
            '@type' => 'Question',
            'name' => 'Lịch Ta tính âm lịch dựa trên nguyên tắc nào?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Ứng dụng dùng thuật toán lịch âm Việt Nam theo chu kỳ sóc, tiết khí, múi giờ Việt Nam và các lớp thông tin truyền thống như Can Chi, nạp âm, trực, lục diệu.',
            ],
        ],
    ],
];
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= lta_h($meta['title']) ?></title>
    <meta name="description" content="<?= lta_h($meta['description']) ?>">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <link rel="canonical" href="<?= lta_h($canonicalUrl) ?>">
    <link rel="icon" href="assets/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="assets/favicon.svg">
    <link rel="manifest" href="site.webmanifest">
    <meta name="theme-color" content="#315f4b">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="vi_VN">
    <meta property="og:site_name" content="Lịch Ta">
    <meta property="og:title" content="<?= lta_h($meta['title']) ?>">
    <meta property="og:description" content="<?= lta_h($meta['description']) ?>">
    <meta property="og:url" content="<?= lta_h($canonicalUrl) ?>">
    <meta property="og:image" content="<?= lta_h($thumbnailUrl) ?>">
    <meta property="og:image:type" content="image/svg+xml">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Lịch Ta - Âm lịch Việt Nam">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="<?= lta_h($thumbnailUrl) ?>">
    <?php if ($view === 'embed'): ?>
    <script type="application/ld+json"><?= json_encode($faqJson, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
    <?php endif; ?>
    <link rel="stylesheet" href="assets/site.css?v=<?= lta_h(LTA_APP_VERSION) ?>">
</head>
<body data-day-element="<?= lta_h($dayInfo['fortune']['napAmElement']) ?>">
<main class="lta-shell">
    <header class="lta-topbar">
        <a class="lta-brand" href="./" aria-label="Lịch Ta">
            <img src="assets/lich-ta-mark.svg" alt="" width="42" height="42">
            <span>
                <strong>Lịch Ta</strong>
                <small>Âm lịch Việt Nam · v<?= lta_h(LTA_APP_VERSION) ?></small>
            </span>
        </a>
        <nav class="lta-nav" aria-label="Điều hướng">
            <a class="<?= $view === 'today' ? 'is-active' : '' ?>" href="<?= lta_h($viewUrl('today', $selected)) ?>"><span aria-hidden="true">⌂</span>Hôm nay</a>
            <a class="<?= $view === 'month' ? 'is-active' : '' ?>" href="<?= lta_h($viewUrl('month', $selected)) ?>"><span aria-hidden="true">□</span>Lịch tháng</a>
            <a class="<?= $view === 'convert' ? 'is-active' : '' ?>" href="<?= lta_h($viewUrl('convert', $selected)) ?>"><span aria-hidden="true">⇄</span>Đổi ngày</a>
            <a class="<?= $view === 'almanac' ? 'is-active' : '' ?>" href="<?= lta_h($viewUrl('almanac', $selected)) ?>"><span aria-hidden="true">i</span>Thông Thư</a>
            <a class="<?= $view === 'embed' ? 'is-active' : '' ?>" href="<?= lta_h($viewUrl('embed', $selected)) ?>"><span aria-hidden="true">{ }</span>Mã nhúng</a>
            <a class="<?= $view === 'about' ? 'is-active' : '' ?>" href="<?= lta_h($viewUrl('about', $selected)) ?>"><span aria-hidden="true">i</span>Giới thiệu</a>
        </nav>
    </header>

    <?php if ($view === 'today'): ?>
    <section class="lta-home" id="today" data-home-element="<?= lta_h($dayInfo['fortune']['napAmElement']) ?>">
        <div class="lta-panel lta-today-panel">
            <p class="lta-eyebrow">Hôm nay</p>
            <div class="lta-today-hero">
                <span><?= (int) $dayInfo['solar']['day'] ?></span>
                <div>
                    <h1><?= lta_h($dayInfo['weekdayFull']) ?>, <?= (int) $dayInfo['solar']['day'] ?>/<?= (int) $dayInfo['solar']['month'] ?>/<?= (int) $dayInfo['solar']['year'] ?></h1>
                    <p>Âm lịch <?= (int) $dayInfo['lunar']['day'] ?>/<?= (int) $dayInfo['lunar']['month'] ?>/<?= (int) $dayInfo['lunar']['year'] ?><?= (int) $dayInfo['lunar']['leap'] === 1 ? ' nhuận' : '' ?></p>
                    <strong class="lta-element-badge"><span aria-hidden="true">◎</span><?= lta_h($dayInfo['fortune']['napAmElement']) ?> · <?= lta_h($dayInfo['fortune']['napAm']) ?></strong>
                </div>
            </div>
            <nav class="lta-day-nav" aria-label="Chọn ngày">
                <a href="<?= lta_h(lta_date_url((int) $prevDay->format('j'), (int) $prevDay->format('n'), (int) $prevDay->format('Y'))) ?>" aria-label="Ngày trước">‹</a>
                <a class="lta-day-nav-today" href="./">Hôm nay</a>
                <a href="<?= lta_h(lta_date_url((int) $nextDay->format('j'), (int) $nextDay->format('n'), (int) $nextDay->format('Y'))) ?>" aria-label="Ngày sau">›</a>
            </nav>
            <form class="lta-date-lookup" method="get" action="" data-date-form>
                <fieldset class="lta-toggle">
                    <legend>Chọn loại ngày</legend>
                    <label>
                        <input type="radio" name="date_type" value="solar" <?= $dateInputMode === 'solar' ? 'checked' : '' ?>>
                        <span>Dương lịch</span>
                    </label>
                    <label>
                        <input type="radio" name="date_type" value="lunar" <?= $dateInputMode === 'lunar' ? 'checked' : '' ?>>
                        <span>Âm lịch</span>
                    </label>
                </fieldset>
                <div class="lta-date-fields">
                    <label><span>Ngày</span><input name="day" type="number" min="1" max="<?= $dateInputMode === 'lunar' ? 30 : cal_days_in_month(CAL_GREGORIAN, $selected['month'], $selected['year']) ?>" value="<?= (int) ($dateInputMode === 'lunar' ? $selectedLunar['day'] : $selected['day']) ?>" required></label>
                    <label><span>Tháng</span><input name="month" type="number" min="1" max="12" value="<?= (int) ($dateInputMode === 'lunar' ? $selectedLunar['month'] : $selected['month']) ?>" required></label>
                    <label><span>Năm</span><input name="year" type="number" min="1800" max="2199" value="<?= (int) ($dateInputMode === 'lunar' ? $selectedLunar['year'] : $selected['year']) ?>" required></label>
                    <label class="lta-lunar-leap <?= $dateInputMode === 'lunar' ? '' : 'is-hidden' ?>"><input name="lunar_leap" type="checkbox" value="1" <?= $dateInputMode === 'lunar' && (int) $selectedLunar['leap'] === 1 ? 'checked' : '' ?>><span>Tháng nhuận</span></label>
                    <button type="submit">Xem ngày</button>
                </div>
                <?php if ($dateInputError !== ''): ?>
                    <p class="lta-form-error"><?= lta_h($dateInputError) ?></p>
                <?php endif; ?>
            </form>
            <div class="lta-home-actions">
                <a href="<?= lta_h($viewUrl('month', $selected)) ?>"><span aria-hidden="true">□</span>Xem lịch tháng</a>
                <a href="<?= lta_h($viewUrl('month', $selected)) ?>"><span aria-hidden="true">◎</span>Lọc ngày</a>
                <a href="<?= lta_h($viewUrl('convert', $selected)) ?>"><span aria-hidden="true">⇄</span>Đổi ngày</a>
            </div>
        </div>

        <aside class="lta-panel lta-detail-panel" aria-label="Chi tiết ngày hôm nay">
            <p class="lta-eyebrow">Thông tin ngày</p>
            <dl class="lta-facts">
                <div><dt><span aria-hidden="true">✦</span>Năm</dt><dd><?= lta_h($dayInfo['canChi']['year']) ?></dd></div>
                <div><dt><span aria-hidden="true">☷</span>Tháng</dt><dd><?= lta_h($dayInfo['canChi']['month']) ?></dd></div>
                <div><dt><span aria-hidden="true">☰</span>Ngày</dt><dd><?= lta_h($dayInfo['canChi']['day']) ?></dd></div>
                <div><dt><span aria-hidden="true">◷</span>Giờ đầu ngày</dt><dd><?= lta_h($dayInfo['firstHour']) ?></dd></div>
                <div><dt><span aria-hidden="true">◐</span>Tiết khí</dt><dd><?= lta_h($dayInfo['term']) ?></dd></div>
                <div><dt><span aria-hidden="true">⌁</span>Trực</dt><dd><?= lta_h($dayInfo['fortune']['truc']) ?></dd></div>
                <div><dt><span aria-hidden="true">✧</span>Sao</dt><dd><?= lta_h($dayInfo['fortune']['saoNhiThapBatTu']) ?></dd></div>
                <div><dt><span aria-hidden="true">◎</span>Lục diệu</dt><dd><?= lta_h($dayInfo['fortune']['lucDieu']) ?></dd></div>
                <div><dt><span aria-hidden="true">◇</span>Nạp âm</dt><dd><?= lta_h($dayInfo['fortune']['napAm']) ?></dd></div>
                <div><dt><span aria-hidden="true">☯</span>Ngày</dt><dd><?= lta_h($dayInfo['fortune']['hoangHacDao']) ?><?= $dayInfo['fortune']['hoangHacDaoStar'] !== null ? ' - ' . lta_h($dayInfo['fortune']['hoangHacDaoStar']) : '' ?></dd></div>
                <div><dt><span aria-hidden="true">✓</span>Đổng Công</dt><dd><?= lta_h($dayInfo['fortune']['dongCong']['label']) ?> · trực <?= lta_h($dayInfo['fortune']['dongCong']['truc']) ?></dd></div>
            </dl>
            <details class="lta-hours lta-collapse" data-lta-icon="◷">
                <summary>Giờ hoàng đạo</summary>
                <p><?= lta_h(implode(', ', $dayInfo['hours'])) ?></p>
            </details>
            <details class="lta-fortune-note lta-collapse" data-lta-icon="☯">
                <summary>Tuổi xung</summary>
                <p><?= lta_h(implode(', ', $dayInfo['fortune']['tuoiXung'])) ?> · xung chi <?= lta_h($dayInfo['fortune']['ngayXung']) ?></p>
                <small><?= lta_h($dayInfo['fortune']['lucDieuHint']) ?></small>
            </details>
            <details class="lta-almanac-detail lta-collapse" data-lta-icon="✦">
                <summary>Thông tin cổ lịch</summary>
                <article>
                    <span>Nhị Thập Bát Tú <a class="lta-info-link" href="<?= lta_h($viewUrl('almanac', $selected)) ?>#nhi-thap-bat-tu" aria-label="Xem dữ liệu Nhị Thập Bát Tú">i</a></span>
                    <strong><?= lta_h($dayInfo['fortune']['saoNhiThapBatTu']) ?> · <?= lta_h($traditional['nhiThapBatTu']['animal']) ?></strong>
                    <p><?= lta_h($traditional['nhiThapBatTu']['summary']) ?></p>
                </article>
                <article>
                    <span>Lục Nhâm xuất hành <a class="lta-info-link" href="<?= lta_h($viewUrl('almanac', $selected)) ?>#luc-nham" aria-label="Xem dữ liệu Lục Nhâm xuất hành">i</a></span>
                    <strong><?= lta_h($traditional['lucNhan']['dayResult']['name']) ?> · <?= lta_h($traditional['lucNhan']['dayResult']['element']) ?></strong>
                    <p><?= lta_h($traditional['lucNhan']['dayResult']['summary']) ?></p>
                    <small>Giờ tốt: <?= lta_h(implode(', ', array_map(static fn (array $hour): string => $hour['branch'] . ' ' . $hour['result']['name'], $traditional['lucNhan']['goodHours']))) ?></small>
                </article>
                <?php if ($traditional['kyNgay'] !== []): ?>
                    <article>
                        <span>Các ngày kỵ <a class="lta-info-link" href="<?= lta_h($viewUrl('almanac', $selected)) ?>#ngay-ky" aria-label="Xem dữ liệu các ngày kỵ">i</a></span>
                        <ul>
                            <?php foreach (array_slice($traditional['kyNgay'], 0, 4) as $kyNgay): ?>
                                <li><strong><?= lta_h($kyNgay['name']) ?></strong>: <?= lta_h(implode(', ', $kyNgay['appliesTo'])) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </article>
                <?php endif; ?>
                <article>
                    <span>Ngọc Hạp <a class="lta-info-link" href="<?= lta_h($viewUrl('almanac', $selected)) ?>#ngoc-hap" aria-label="Xem dữ liệu Ngọc Hạp">i</a></span>
                    <strong><?= lta_h($traditional['ngocHap']['ritual']['canChi']) ?> · <?= lta_h($traditional['ngocHap']['ritual']['level']) ?></strong>
                    <p><?= lta_h($traditional['ngocHap']['ritual']['summary']) ?></p>
                    <small><?= lta_h($traditional['ngocHap']['note']) ?></small>
                </article>
            </details>
            <?php if ($dayInfo['events'] !== []): ?>
                <details class="lta-events lta-collapse" data-lta-icon="◆">
                    <summary>Sự kiện</summary>
                    <ul>
                        <?php foreach ($dayInfo['events'] as $event): ?>
                            <li><?= lta_h($event['name']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </details>
            <?php endif; ?>
        </aside>
    </section>
    <?php endif; ?>

    <?php if ($view === 'month'): ?>
    <section class="lta-workspace" id="calendar" data-nap-am-tool data-month-app data-month="<?= (int) $month ?>" data-year="<?= (int) $year ?>">
        <div class="lta-panel lta-calendar-panel">
            <div class="lta-panel-head">
                <div>
                    <p class="lta-eyebrow">Lịch tháng</p>
                    <h1><?= lta_h(LTA_MONTHS[$month]) ?> năm <?= (int) $year ?></h1>
                </div>
                <div class="lta-month-actions" aria-label="Chuyển tháng" data-month-nav>
                    <a href="<?= lta_h(lta_build_url(['month' => $prev['month'], 'year' => $prev['year'], 'day' => 1])) ?>" aria-label="Tháng trước">‹</a>
                    <a href="<?= lta_h(lta_build_url(['month' => $today['month'], 'year' => $today['year'], 'day' => $today['day']])) ?>">Hôm nay</a>
                    <a href="<?= lta_h(lta_build_url(['month' => $next['month'], 'year' => $next['year'], 'day' => 1])) ?>" aria-label="Tháng sau">›</a>
                </div>
            </div>

            <div class="lta-month-controls">
                <form class="lta-picker" method="get" action="" data-month-picker>
                    <input name="view" type="hidden" value="month">
                    <label>
                        <span>Tháng</span>
                        <select name="month">
                            <?php foreach (LTA_MONTHS as $value => $label): ?>
                                <option value="<?= (int) $value ?>" <?= $value === $month ? 'selected' : '' ?>><?= lta_h($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label>
                        <span>Năm</span>
                        <input name="year" type="number" min="1800" max="2199" value="<?= (int) $year ?>">
                    </label>
                    <input name="day" type="hidden" value="1">
                    <button type="submit">Xem</button>
                </form>

                <div class="lta-month-filters" aria-label="Lọc lịch tháng">
                    <label>
                        <span>Ngũ hành ngày</span>
                        <select data-nap-filter-select>
                            <option value="">Tất cả ngũ hành</option>
                            <option value="Kim">Kim</option>
                            <option value="Mộc">Mộc</option>
                            <option value="Thủy">Thủy</option>
                            <option value="Hỏa">Hỏa</option>
                            <option value="Thổ">Thổ</option>
                        </select>
                    </label>
                    <label>
                        <span>Đổng Công</span>
                        <select data-dong-filter-select>
                            <option value="">Tất cả Đổng Công</option>
                            <option value="good">Tốt</option>
                            <option value="mixed">Cân nhắc</option>
                            <option value="bad">Chưa tốt</option>
                        </select>
                    </label>
                </div>
            </div>

            <?= lta_render_calendar($cells, false, ['showNapAm' => true, 'class' => 'lta-nap-calendar']) ?>
        </div>

        <aside class="lta-panel lta-detail-panel" aria-label="Chi tiết ngày">
            <p class="lta-eyebrow">Ngày đang chọn</p>
            <div class="lta-date-hero">
                <span><?= (int) $dayInfo['solar']['day'] ?></span>
                <div>
                    <strong><?= lta_h($dayInfo['weekday']) ?>, <?= (int) $dayInfo['solar']['day'] ?>/<?= (int) $dayInfo['solar']['month'] ?>/<?= (int) $dayInfo['solar']['year'] ?></strong>
                    <small>Dương lịch</small>
                </div>
            </div>

            <div class="lta-lunar-result">
                <span>Âm lịch</span>
                <strong>
                    <?= (int) $dayInfo['lunar']['day'] ?>/<?= (int) $dayInfo['lunar']['month'] ?>/<?= (int) $dayInfo['lunar']['year'] ?>
                    <?= (int) $dayInfo['lunar']['leap'] === 1 ? 'nhuận' : '' ?>
                </strong>
            </div>

            <dl class="lta-facts">
                <div><dt>Năm</dt><dd><?= lta_h($dayInfo['canChi']['year']) ?></dd></div>
                <div><dt>Tháng</dt><dd><?= lta_h($dayInfo['canChi']['month']) ?></dd></div>
                <div><dt>Ngày</dt><dd><?= lta_h($dayInfo['canChi']['day']) ?></dd></div>
                <div><dt>Giờ đầu ngày</dt><dd><?= lta_h($dayInfo['firstHour']) ?></dd></div>
                <div><dt>Tiết khí</dt><dd><?= lta_h($dayInfo['term']) ?></dd></div>
                <div><dt>Trực</dt><dd><?= lta_h($dayInfo['fortune']['truc']) ?></dd></div>
                <div><dt>Sao</dt><dd><?= lta_h($dayInfo['fortune']['saoNhiThapBatTu']) ?></dd></div>
                <div><dt>Lục diệu</dt><dd><?= lta_h($dayInfo['fortune']['lucDieu']) ?></dd></div>
                <div><dt>Nạp âm</dt><dd><?= lta_h($dayInfo['fortune']['napAm']) ?></dd></div>
                <div><dt>Ngày</dt><dd><?= lta_h($dayInfo['fortune']['hoangHacDao']) ?><?= $dayInfo['fortune']['hoangHacDaoStar'] !== null ? ' - ' . lta_h($dayInfo['fortune']['hoangHacDaoStar']) : '' ?></dd></div>
                <div><dt>Đổng Công</dt><dd><?= lta_h($dayInfo['fortune']['dongCong']['label']) ?> · trực <?= lta_h($dayInfo['fortune']['dongCong']['truc']) ?></dd></div>
            </dl>

            <details class="lta-hours lta-collapse">
                <summary>Giờ hoàng đạo</summary>
                <p><?= lta_h(implode(', ', $dayInfo['hours'])) ?></p>
            </details>

            <details class="lta-fortune-note lta-collapse">
                <summary>Tuổi xung</summary>
                <p><?= lta_h(implode(', ', $dayInfo['fortune']['tuoiXung'])) ?> · xung chi <?= lta_h($dayInfo['fortune']['ngayXung']) ?></p>
                <small><?= lta_h($dayInfo['fortune']['lucDieuHint']) ?></small>
            </details>
            <details class="lta-almanac-detail lta-collapse">
                <summary>Thông tin cổ lịch</summary>
                <article>
                    <span>Nhị Thập Bát Tú <a class="lta-info-link" href="<?= lta_h($viewUrl('almanac', $selected)) ?>#nhi-thap-bat-tu" aria-label="Xem dữ liệu Nhị Thập Bát Tú">i</a></span>
                    <strong><?= lta_h($dayInfo['fortune']['saoNhiThapBatTu']) ?> · <?= lta_h($traditional['nhiThapBatTu']['animal']) ?></strong>
                    <p><?= lta_h($traditional['nhiThapBatTu']['summary']) ?></p>
                </article>
                <article>
                    <span>Lục Nhâm xuất hành <a class="lta-info-link" href="<?= lta_h($viewUrl('almanac', $selected)) ?>#luc-nham" aria-label="Xem dữ liệu Lục Nhâm xuất hành">i</a></span>
                    <strong><?= lta_h($traditional['lucNhan']['dayResult']['name']) ?> · <?= lta_h($traditional['lucNhan']['dayResult']['element']) ?></strong>
                    <p><?= lta_h($traditional['lucNhan']['dayResult']['summary']) ?></p>
                    <small>Giờ tốt: <?= lta_h(implode(', ', array_map(static fn (array $hour): string => $hour['branch'] . ' ' . $hour['result']['name'], $traditional['lucNhan']['goodHours']))) ?></small>
                </article>
                <?php if ($traditional['kyNgay'] !== []): ?>
                    <article>
                        <span>Các ngày kỵ <a class="lta-info-link" href="<?= lta_h($viewUrl('almanac', $selected)) ?>#ngay-ky" aria-label="Xem dữ liệu các ngày kỵ">i</a></span>
                        <ul>
                            <?php foreach (array_slice($traditional['kyNgay'], 0, 4) as $kyNgay): ?>
                                <li><strong><?= lta_h($kyNgay['name']) ?></strong>: <?= lta_h(implode(', ', $kyNgay['appliesTo'])) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </article>
                <?php endif; ?>
                <article>
                    <span>Ngọc Hạp <a class="lta-info-link" href="<?= lta_h($viewUrl('almanac', $selected)) ?>#ngoc-hap" aria-label="Xem dữ liệu Ngọc Hạp">i</a></span>
                    <strong><?= lta_h($traditional['ngocHap']['ritual']['canChi']) ?> · <?= lta_h($traditional['ngocHap']['ritual']['level']) ?></strong>
                    <p><?= lta_h($traditional['ngocHap']['ritual']['summary']) ?></p>
                    <small><?= lta_h($traditional['ngocHap']['note']) ?></small>
                </article>
            </details>

            <?php if ($dayInfo['events'] !== []): ?>
                <details class="lta-events lta-collapse">
                    <summary>Sự kiện</summary>
                    <ul>
                        <?php foreach ($dayInfo['events'] as $event): ?>
                            <li><?= lta_h($event['name']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </details>
            <?php endif; ?>
        </aside>
    </section>
    <?php endif; ?>

    <?php if ($view === 'nap-am'): ?>
    <section class="lta-panel lta-nap-panel" id="nap-am" data-nap-am-tool>
        <div class="lta-panel-head">
            <div>
                <p class="lta-eyebrow">Nạp âm ngày</p>
                <h2><?= lta_h(LTA_MONTHS[$month]) ?> năm <?= (int) $year ?></h2>
            </div>
            <div class="lta-nap-controls">
                <div class="lta-month-actions" aria-label="Chuyển tháng">
                    <a href="<?= lta_h(lta_build_url(['view' => 'nap-am', 'month' => $prev['month'], 'year' => $prev['year'], 'day' => 1])) ?>" aria-label="Tháng trước">‹</a>
                    <a href="<?= lta_h(lta_build_url(['view' => 'nap-am', 'month' => $today['month'], 'year' => $today['year'], 'day' => $today['day']])) ?>">Hôm nay</a>
                    <a href="<?= lta_h(lta_build_url(['view' => 'nap-am', 'month' => $next['month'], 'year' => $next['year'], 'day' => 1])) ?>" aria-label="Tháng sau">›</a>
                </div>
                <div class="lta-element-filter lta-nap-filter" aria-label="Lọc theo ngũ hành">
                    <button type="button" class="is-active" data-nap-filter=""><span aria-hidden="true">◎</span>Tất cả</button>
                    <button type="button" data-nap-filter="Kim"><span aria-hidden="true">◇</span>Kim</button>
                    <button type="button" data-nap-filter="Mộc"><span aria-hidden="true">✦</span>Mộc</button>
                    <button type="button" data-nap-filter="Thủy"><span aria-hidden="true">≋</span>Thủy</button>
                    <button type="button" data-nap-filter="Hỏa"><span aria-hidden="true">△</span>Hỏa</button>
                    <button type="button" data-nap-filter="Thổ"><span aria-hidden="true">■</span>Thổ</button>
                </div>
                <div class="lta-element-filter lta-dong-filter" aria-label="Lọc theo Đổng Công">
                    <button type="button" class="is-active" data-dong-filter=""><span aria-hidden="true">☷</span>Đổng Công</button>
                    <button type="button" data-dong-filter="good"><span aria-hidden="true">✓</span>Tốt</button>
                    <button type="button" data-dong-filter="mixed"><span aria-hidden="true">±</span>Cân nhắc</button>
                    <button type="button" data-dong-filter="bad"><span aria-hidden="true">!</span>Chưa tốt</button>
                </div>
            </div>
        </div>
        <?= lta_render_calendar($cells, false, ['showNapAm' => true, 'class' => 'lta-nap-calendar']) ?>
    </section>
    <?php endif; ?>

    <?php if ($view === 'convert'): ?>
    <section class="lta-lower-grid" id="convert">
        <div class="lta-panel">
            <p class="lta-eyebrow">Đổi ngày nhanh</p>
            <h2><?= $dateInputMode === 'lunar' ? 'Âm lịch sang dương lịch' : 'Dương lịch sang âm lịch' ?></h2>
            <form class="lta-converter" method="get" action="" data-date-form>
                <input name="view" type="hidden" value="convert">
                <fieldset class="lta-toggle">
                    <legend>Kiểu đổi</legend>
                    <label>
                        <input type="radio" name="date_type" value="solar" <?= $dateInputMode === 'solar' ? 'checked' : '' ?>>
                        <span>Dương sang âm</span>
                    </label>
                    <label>
                        <input type="radio" name="date_type" value="lunar" <?= $dateInputMode === 'lunar' ? 'checked' : '' ?>>
                        <span>Âm sang dương</span>
                    </label>
                </fieldset>
                <label><span>Ngày</span><input name="day" type="number" min="1" max="<?= $dateInputMode === 'lunar' ? 30 : cal_days_in_month(CAL_GREGORIAN, $selected['month'], $selected['year']) ?>" value="<?= (int) ($dateInputMode === 'lunar' ? $selectedLunar['day'] : $selected['day']) ?>" required></label>
                <label><span>Tháng</span><input name="month" type="number" min="1" max="12" value="<?= (int) ($dateInputMode === 'lunar' ? $selectedLunar['month'] : $selected['month']) ?>" required></label>
                <label><span>Năm</span><input name="year" type="number" min="1800" max="2199" value="<?= (int) ($dateInputMode === 'lunar' ? $selectedLunar['year'] : $selected['year']) ?>" required></label>
                <label class="lta-lunar-leap <?= $dateInputMode === 'lunar' ? '' : 'is-hidden' ?>"><input name="lunar_leap" type="checkbox" value="1" <?= $dateInputMode === 'lunar' && (int) $selectedLunar['leap'] === 1 ? 'checked' : '' ?>><span>Tháng nhuận</span></label>
                <button type="submit">Đổi ngày</button>
                <?php if ($dateInputError !== ''): ?>
                    <p class="lta-form-error"><?= lta_h($dateInputError) ?></p>
                <?php endif; ?>
            </form>
        </div>

        <aside class="lta-panel lta-detail-panel" aria-label="Kết quả đổi ngày">
            <p class="lta-eyebrow">Kết quả</p>
            <div class="lta-date-hero">
                <span><?= (int) $dayInfo['solar']['day'] ?></span>
                <div>
                    <strong><?= lta_h($dayInfo['weekdayFull']) ?>, <?= (int) $dayInfo['solar']['day'] ?>/<?= (int) $dayInfo['solar']['month'] ?>/<?= (int) $dayInfo['solar']['year'] ?></strong>
                    <small>Dương lịch</small>
                </div>
            </div>
            <div class="lta-lunar-result">
                <span>Âm lịch</span>
                <strong><?= (int) $dayInfo['lunar']['day'] ?>/<?= (int) $dayInfo['lunar']['month'] ?>/<?= (int) $dayInfo['lunar']['year'] ?><?= (int) $dayInfo['lunar']['leap'] === 1 ? ' nhuận' : '' ?></strong>
            </div>
            <dl class="lta-facts">
                <div><dt>Dương lịch</dt><dd><?= (int) $selected['day'] ?>/<?= (int) $selected['month'] ?>/<?= (int) $selected['year'] ?></dd></div>
                <div><dt>Can Chi</dt><dd><?= lta_h($dayInfo['canChi']['day']) ?></dd></div>
                <div><dt>Tiết khí</dt><dd><?= lta_h($dayInfo['term']) ?></dd></div>
            </dl>
        </aside>
    </section>
    <?php endif; ?>

    <?php if ($view === 'embed'): ?>
    <section class="lta-lower-grid" id="embed">
        <div class="lta-panel">
            <p class="lta-eyebrow">Nhúng vào website</p>
            <h1>Code nhúng lịch Việt cho website</h1>
            <p class="lta-lead">Lịch Ta cung cấp mã nhúng lịch âm Việt Nam gọn nhẹ cho landing page, website doanh nghiệp, blog phong thủy, trang tin địa phương hoặc hệ thống nội bộ cần hiển thị lịch Việt.</p>
            <div class="lta-embed-preview">
                <div class="lta-embed-preview-head">
                    <span>Xem trước giao diện nhúng</span>
                    <strong>Iframe responsive</strong>
                </div>
                <iframe src="<?= lta_h($baseUrl) ?>/embed.php" title="Xem trước widget lịch âm Việt Nam" loading="lazy"></iframe>
            </div>
            <div class="lta-code-tabs" data-code-tabs>
                <div class="lta-segmented" role="tablist">
                    <button type="button" class="is-active" data-code-tab="iframe">Iframe</button>
                    <button type="button" data-code-tab="script">JavaScript</button>
                </div>
                <pre data-code-panel="iframe"><code><?= lta_h($iframeCode) ?></code></pre>
                <pre data-code-panel="script" hidden><code><?= lta_h($scriptCode) ?></code></pre>
            </div>
        </div>
        <aside class="lta-panel lta-seo-aside">
            <p class="lta-eyebrow">Dành cho website</p>
            <h2>Widget lịch âm responsive</h2>
            <p class="lta-embed-preview-copy">Khung xem trước bên cạnh là giao diện thực tế khi nhúng vào website: có lịch tháng, ngày âm, thông tin ngày đang chọn và tự co theo mobile.</p>
            <ul class="lta-check-list">
                <li>Hiển thị lịch tháng, ngày âm, ngày dương và thông tin ngày đang chọn.</li>
                <li>Hỗ trợ iframe hoặc JavaScript, dễ đặt trong WordPress, Ladipage, HTML tĩnh hoặc CMS riêng.</li>
                <li>Tự co theo giao diện mobile để không mất thông tin khi khung nhúng nhỏ.</li>
                <li>Không cần cài database riêng trên website nhúng.</li>
            </ul>
        </aside>
    </section>

    <section class="lta-panel lta-seo-content">
        <p class="lta-eyebrow">Hướng dẫn SEO</p>
        <h2>Nhúng lịch âm Việt Nam giúp website có nội dung hữu ích hơn</h2>
        <p>Khi người dùng tìm kiếm các cụm như <strong>code nhúng lịch Việt</strong>, <strong>nhúng lịch âm vào website</strong>, <strong>lịch âm Việt Nam cho WordPress</strong> hoặc <strong>iframe lịch âm</strong>, họ thường cần một công cụ có thể copy nhanh, hiển thị ổn trên điện thoại và vẫn giữ đúng thông tin ngày âm lịch Việt Nam. Trang này được viết để giải thích rõ cách dùng Lịch Ta, phạm vi dữ liệu và những lựa chọn nhúng phù hợp cho từng loại website.</p>
        <div class="lta-seo-grid">
            <article>
                <h3>Khi nào nên dùng iframe?</h3>
                <p>Iframe phù hợp nếu bạn muốn đặt lịch âm lên trang trong vài phút. Chỉ cần copy đoạn mã, dán vào vùng HTML và chỉnh chiều cao hoặc bo góc nếu cần. Đây là cách đơn giản cho landing page, bài viết giới thiệu dự án, sidebar blog hoặc trang liên hệ.</p>
            </article>
            <article>
                <h3>Khi nào nên dùng JavaScript?</h3>
                <p>Mã JavaScript phù hợp khi bạn muốn widget tự tạo iframe trong một vùng đã định sẵn. Cách này gọn hơn cho website có nhiều block nội dung, đồng thời có thể tự nhận chiều cao thực tế của widget để hạn chế việc bị cắt nội dung khi responsive.</p>
            </article>
            <article>
                <h3>Nội dung lịch gồm những gì?</h3>
                <p>Widget hiển thị tháng dương lịch, ngày âm tương ứng, Can Chi của ngày, tiết khí, trực, lục diệu và một số ngày lễ phổ biến. Các thông tin ngày tốt xấu nên được dùng như dữ liệu tham khảo văn hóa, không thay thế tư vấn chuyên môn.</p>
            </article>
        </div>
        <div class="lta-faq">
            <h2>Câu hỏi thường gặp về code nhúng lịch Việt</h2>
            <details open>
                <summary>Code nhúng lịch Việt có dùng được cho WordPress không?</summary>
                <p>Có. Bạn có thể dán iframe hoặc script vào block Custom HTML, widget HTML, template của theme hoặc một page builder hỗ trợ mã HTML.</p>
            </details>
            <details>
                <summary>Có thể nhúng lịch âm vào landing page bán hàng không?</summary>
                <p>Có. Với landing page, iframe là lựa chọn nhanh nhất. Nếu nền tảng hỗ trợ script bên ngoài, bạn có thể dùng <code>embed.js</code> để tự động tạo khung lịch và giữ chiều cao phù hợp.</p>
            </details>
            <details>
                <summary>Thông tin âm lịch có dùng cho tra cứu ngày tốt xấu không?</summary>
                <p>Lịch Ta cung cấp các lớp thông tin như Can Chi, tiết khí, trực, lục diệu, nạp âm và Đổng Công ở mức tham khảo. Người dùng nên kiểm chứng thêm nếu dùng cho quyết định quan trọng.</p>
            </details>
            <details>
                <summary>Có cần ghi nguồn khi nhúng lịch không?</summary>
                <p>Widget có thương hiệu Lịch Ta trong phần đầu. Bạn có thể giữ nguyên để người dùng biết nguồn công cụ và mở trang đầy đủ khi cần tra cứu sâu hơn.</p>
            </details>
        </div>
    </section>
    <?php endif; ?>

    <?php if ($view === 'almanac'): ?>
    <section class="lta-panel lta-page-article lta-almanac-library">
        <p class="lta-eyebrow">Thư viện Thông Thư</p>
        <h1>Cách xem ngày tốt xấu theo lịch Việt</h1>
        <p class="lta-lead">Người Việt khi xem lịch âm thường không chỉ xem ngày mấy, tháng mấy, mà còn xem thêm Can Chi, sao tốt xấu, ngày kỵ, Nhị Thập Bát Tú, giờ xuất hành, Ngọc Hạp và nạp âm. Trang này gom các lớp thông tin đó vào một nơi dễ đọc hơn, để bạn hiểu vì sao một ngày được gợi ý là nên làm việc này, nên tránh việc kia.</p>
        <div class="lta-source-note">
            <strong>Cách tham khảo</strong>
            <p>Nội dung được tổng hợp và đối chiếu từ nhiều nguồn lịch cổ truyền, thông thư dân gian, tài liệu tra cứu phổ biến và các nguồn thiên văn công khai. Lịch Ta viết lại theo lối ngắn gọn, dễ kiểm tra, để người dùng có thể đọc nhanh thay vì phải lần từng bảng rời rạc.</p>
            <div class="lta-source-links" aria-label="Nguồn tham khảo">
                <a href="https://vi.wikipedia.org/wiki/Ti%E1%BA%BFt_kh%C3%AD" target="_blank" rel="noopener">Tiết khí - Wikipedia</a>
                <a href="https://vi.wikipedia.org/wiki/M%C3%B9a" target="_blank" rel="noopener">Mùa - Wikipedia</a>
                <a href="https://science.nasa.gov/solar-system/skywatching/night-sky-network/embracing-the-equinox/" target="_blank" rel="noopener">NASA: Equinox</a>
                <a href="https://www.nesdis.noaa.gov/about/k-12-education/optical-phenomena/what-solstice" target="_blank" rel="noopener">NOAA: Solstice</a>
                <a href="https://www.xemamlich.uhm.vn/vncal_en.html" target="_blank" rel="noopener">Hồ Ngọc Đức: Vietnamese lunar calendar</a>
            </div>
        </div>

        <nav class="lta-library-toc" aria-label="Mục lục Thông Thư">
            <a href="#am-lich-ho-ngoc-duc">Âm lịch</a>
            <a href="#tiet-khi-mua">Tiết khí và mùa</a>
            <a href="#gio-hoang-dao">Giờ hoàng đạo</a>
            <a href="#sao-tot-xau">Sao tốt xấu</a>
            <a href="#ngay-ky">Ngày kỵ</a>
            <a href="#nhi-thap-bat-tu">Nhị Thập Bát Tú</a>
            <a href="#luc-nham">Lục Nhâm</a>
            <a href="#dong-cong">Đổng Công</a>
            <a href="#ngoc-hap">Ngọc Hạp</a>
            <a href="#nap-am">Nạp âm</a>
        </nav>

        <section id="am-lich-ho-ngoc-duc" class="lta-library-section">
            <h2>Cách tính ngày âm theo giáo sư Hồ Ngọc Đức</h2>
            <p>Phần lõi đổi ngày dương sang âm của Lịch Ta tham khảo thuật toán âm lịch Việt Nam do giáo sư Hồ Ngọc Đức công bố rộng rãi, trong đó các phép tính được điều chỉnh theo giờ Hà Nội GMT+7 và có xét tiết khí. Cách tính này không chỉ tra bảng ngày có sẵn, mà dựa trên các mốc thiên văn như ngày Julius, thời điểm sóc, tiết khí và múi giờ Việt Nam. <a class="lta-citation-link" href="https://www.xemamlich.uhm.vn/vncal_en.html" target="_blank" rel="noopener">Nguồn thuật toán</a></p>
            <div class="lta-method-grid">
                <article>
                    <h3>1. Đổi ngày dương sang ngày Julius</h3>
                    <p>Mỗi ngày dương lịch được quy về một số ngày liên tục gọi là Julian day. Nhờ vậy app có thể tính khoảng cách giữa các ngày, tìm ngày sóc gần nhất và xác định tháng âm chính xác hơn.</p>
                </article>
                <article>
                    <h3>2. Tìm ngày sóc đầu tháng âm</h3>
                    <p>Tháng âm bắt đầu từ ngày có trăng mới theo múi giờ Việt Nam. Lịch Ta dùng UTC+7, vì vậy một số ngày có thể khác lịch Trung Quốc nếu thời điểm sóc nằm gần ranh giới ngày.</p>
                </article>
                <article>
                    <h3>3. Xác định tháng 11 và tháng nhuận</h3>
                    <p>Tháng 11 âm lịch phải chứa tiết Đông chí. Nếu một năm có 13 tháng âm, tháng đầu tiên sau Đông chí không có trung khí sẽ được tính là tháng nhuận.</p>
                </article>
                <article>
                    <h3>4. Tính Can Chi, tiết khí và lịch ngày</h3>
                    <p>Sau khi có ngày âm, app tính tiếp Can Chi ngày/tháng/năm, 24 tiết khí, giờ đầu ngày và các lớp thông tin truyền thống khác để hiển thị trên trang ngày.</p>
                </article>
            </div>
        </section>

        <section id="tiet-khi-mua" class="lta-library-section">
            <h2>Tiết khí, mùa và điểm phân chí</h2>
            <p>Tiết khí là hệ 24 mốc trên vòng chuyển động biểu kiến của Mặt Trời, thường được chia theo mỗi 15 độ kinh độ Mặt Trời để đồng bộ lịch với mùa. Vì quỹ đạo Trái Đất là elip và thời điểm bắt đầu tiết khí được làm tròn theo ngày địa phương, khoảng cách giữa hai tiết khí liền nhau không luôn bằng nhau tuyệt đối. <a class="lta-citation-link" href="https://vi.wikipedia.org/wiki/Ti%E1%BA%BFt_kh%C3%AD" target="_blank" rel="noopener">Tham khảo 24 tiết khí</a></p>
            <p>Các mùa thiên văn gắn với điểm phân và điểm chí: điểm phân xảy ra khi Mặt Trời đi qua mặt phẳng xích đạo Trái Đất, còn điểm chí là lúc độ nghiêng trục Trái Đất tạo ra ngày dài nhất hoặc ngắn nhất trong năm ở mỗi bán cầu. Vì vậy Lịch Ta dùng tiết khí như một lớp neo mùa vụ, còn thông tin ngày tốt xấu chỉ nên đọc như dữ liệu văn hóa tham khảo. <a class="lta-citation-link" href="https://science.nasa.gov/solar-system/skywatching/night-sky-network/embracing-the-equinox/" target="_blank" rel="noopener">NASA về điểm phân</a> <a class="lta-citation-link" href="https://www.nesdis.noaa.gov/about/k-12-education/optical-phenomena/what-solstice" target="_blank" rel="noopener">NOAA về điểm chí</a> <a class="lta-citation-link" href="https://vi.wikipedia.org/wiki/M%C3%B9a" target="_blank" rel="noopener">Khái niệm mùa</a></p>
        </section>

        <section id="gio-hoang-dao" class="lta-library-section">
            <h2>Cách tính giờ hoàng đạo</h2>
            <p>Giờ hoàng đạo trong Lịch Ta được tính theo chi của ngày. Mỗi ngày thuộc một trong 12 địa chi, sau đó tra vào 6 mẫu giờ tốt lặp lại. Mỗi chi giờ kéo dài hai tiếng: Tý từ 23-1, Sửu từ 1-3, Dần từ 3-5 và cứ thế đi tiếp đến Hợi từ 21-23.</p>
            <div class="lta-data-table-wrap">
                <table class="lta-data-table">
                    <thead><tr><th>Nhóm ngày</th><th>Các giờ hoàng đạo thường gặp</th></tr></thead>
                    <tbody>
                        <tr><td>Ngày Tý hoặc Ngọ</td><td>Tý, Sửu, Mão, Ngọ, Thân, Dậu</td></tr>
                        <tr><td>Ngày Sửu hoặc Mùi</td><td>Dần, Mão, Tỵ, Thân, Tuất, Hợi</td></tr>
                        <tr><td>Ngày Dần hoặc Thân</td><td>Tý, Sửu, Thìn, Tỵ, Mùi, Tuất</td></tr>
                        <tr><td>Ngày Mão hoặc Dậu</td><td>Tý, Dần, Mão, Ngọ, Mùi, Dậu</td></tr>
                        <tr><td>Ngày Thìn hoặc Tuất</td><td>Dần, Thìn, Tỵ, Thân, Dậu, Hợi</td></tr>
                        <tr><td>Ngày Tỵ hoặc Hợi</td><td>Sửu, Thìn, Ngọ, Mùi, Tuất, Hợi</td></tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="sao-tot-xau" class="lta-library-section">
            <h2>Sao tốt và sao xấu trong Thông Thư</h2>
            <p>Sao tốt và sao xấu là cách người xưa gắn một ngày với những việc nên làm hoặc nên tránh. Có sao thiên về cưới hỏi, đi xa, chữa bệnh, cầu tài; cũng có sao nhắc phải cẩn trọng với kiện tụng, động thổ, an táng hoặc việc lớn trong nhà.</p>
            <div class="lta-library-columns">
                <article>
                    <h3>Sao tốt</h3>
                    <div class="lta-data-list">
                        <?php foreach ($almanacLibrary['starGlossary']['good'] as $name => $rule): ?>
                            <details>
                                <summary><span><?= lta_h($name) ?></span><em>Tốt</em></summary>
                                <p>Nên: <?= lta_h(implode(', ', $rule['goodFor'])) ?><?= $rule['avoid'] !== [] ? '. Kỵ: ' . lta_h(implode(', ', $rule['avoid'])) : '' ?>.</p>
                            </details>
                        <?php endforeach; ?>
                    </div>
                </article>
                <article>
                    <h3>Sao xấu</h3>
                    <div class="lta-data-list">
                        <?php foreach ($almanacLibrary['starGlossary']['bad'] as $name => $rule): ?>
                            <details>
                                <summary><span><?= lta_h($name) ?></span><em>Xấu</em></summary>
                                <p>Kỵ: <?= lta_h(implode(', ', $rule['avoid'])) ?>.</p>
                            </details>
                        <?php endforeach; ?>
                    </div>
                </article>
            </div>
        </section>

        <section id="ngay-ky" class="lta-library-section">
            <h2>Các ngày kỵ thường gặp</h2>
            <p>Một số ngày kỵ xuất hiện rất quen thuộc trong lịch Việt, như Nguyệt kỵ, Tam nương hoặc Dương Công kỵ nhật. Khi gặp những ngày này, Lịch Ta sẽ ghi rõ tên ngày và nhóm việc thường được khuyên nên tránh để người dùng tự cân nhắc.</p>
            <div class="lta-data-table-wrap">
                <table class="lta-data-table">
                    <thead><tr><th>Tên</th><th>Cách nhận biết</th><th>Thường kiêng</th><th>Ghi chú</th></tr></thead>
                    <tbody>
                        <?php foreach ($almanacLibrary['kyNgayRules'] as $rule): ?>
                            <tr>
                                <td><?= lta_h($rule['name']) ?></td>
                                <td><?= lta_h($rule['rule']) ?></td>
                                <td><?= lta_h(implode(', ', $rule['avoid'])) ?></td>
                                <td><?= $rule['confidence'] === 'high' ? 'Quy tắc phổ biến' : 'Nên đối chiếu thêm khi dùng cho việc lớn' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="nhi-thap-bat-tu" class="lta-library-section">
            <h2>Nhị Thập Bát Tú</h2>
            <p>Nhị Thập Bát Tú gồm 28 sao luân phiên theo ngày. Mỗi sao có sắc thái riêng: có sao hợp cưới hỏi, xây cất, mở hàng, thi cử; có sao lại nghiêng về tránh động thổ, hôn nhân hoặc giao dịch lớn.</p>
            <div class="lta-star-grid">
                <?php foreach ($almanacLibrary['twentyEightStars'] as $name => $star): ?>
                    <article class="lta-star-card is-<?= lta_h($star['rating']) ?>">
                        <span><?= lta_h($levelLabel($star['rating'])) ?></span>
                        <h3><?= lta_h($name) ?></h3>
                        <strong><?= lta_h($star['animal']) ?></strong>
                        <p><?= lta_h($star['summary']) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section id="luc-nham" class="lta-library-section">
            <h2>Lục Nhâm Đại Độn và giờ xuất hành</h2>
            <p>Lục Nhâm Đại Độn thường được dùng để xem nhanh giờ xuất hành. Cách đọc khá gần gũi: từ tháng âm xác định điểm khởi, tính đến ngày cần xem, rồi tính tiếp theo từng giờ. Những cung Đại An, Tốc Hỷ và Tiểu Cát thường được xem là thuận hơn cho việc đi lại, gặp gỡ hoặc cầu việc.</p>
            <div class="lta-lucnham-grid">
                <?php foreach ($almanacLibrary['lucNhan'] as $item): ?>
                    <article class="is-<?= lta_h($item['level']) ?>">
                        <span><?= lta_h($levelLabel($item['level'])) ?> · <?= lta_h($item['element']) ?></span>
                        <h3><?= lta_h($item['name']) ?></h3>
                        <p><?= lta_h($item['summary']) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
            <div class="lta-data-table-wrap">
                <table class="lta-data-table">
                    <thead><tr><th>Tháng âm</th><th>Mùng 1 khởi tại</th></tr></thead>
                    <tbody>
                        <?php foreach ($almanacLibrary['lucNhanMonthStarts'] as $monthNumber => $startName): ?>
                            <tr><td>Tháng <?= (int) $monthNumber ?></td><td><?= lta_h($startName) ?></td></tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="dong-cong" class="lta-library-section">
            <h2>Cách tính ngày tốt theo Đổng Công tuyển trạch</h2>
            <p>Đổng Công tuyển trạch là một lớp xem ngày khá riêng. Điểm quan trọng là tháng Đổng Công đi theo tiết khí, không phải chỉ lấy tháng âm. Ví dụ tháng 1 bắt đầu quanh Lập xuân, tháng 2 quanh Kinh trập, tháng 3 quanh Thanh minh. Vì vậy cùng một tháng âm nhưng nếu đã qua tiết khí mới, cách xét Đổng Công có thể đổi sang tháng khác.</p>
            <div class="lta-method-grid">
                <article>
                    <h3>1. Xác định tháng theo tiết khí</h3>
                    <p>Lịch Ta lấy tiết khí hiện tại của ngày dương lịch, rồi quy về tháng Đổng Công tương ứng: Lập xuân/Vũ thủy là tháng 1, Kinh trập/Xuân phân là tháng 2, Thanh minh/Cốc vũ là tháng 3, tiếp tục như vậy đến Tiểu hàn/Đại hàn là tháng 12.</p>
                </article>
                <article>
                    <h3>2. Tính trực theo nguyệt kiến và chi ngày</h3>
                    <p>Mỗi tháng có một nguyệt kiến, như tháng 1 kiến Dần, tháng 2 kiến Mão, tháng 3 kiến Thìn. Từ chi ngày và nguyệt kiến, app tính ra một trong 12 trực: Kiến, Trừ, Mãn, Bình, Định, Chấp, Phá, Nguy, Thành, Thu, Khai, Bế.</p>
                </article>
                <article>
                    <h3>3. Tra bảng tốt xấu của từng tháng</h3>
                    <p>Sau khi biết tháng và trực, Lịch Ta tra bảng Đổng Công để phân loại ngày thành Tốt, Cân nhắc hoặc Chưa tốt. Một số ngày Can Chi riêng có ngoại lệ tốt/xấu khác với mức mặc định của trực.</p>
                </article>
                <article>
                    <h3>4. Dùng như bộ lọc tham khảo</h3>
                    <p>Đổng Công có nhiều ngoại lệ theo việc cụ thể, nên Lịch Ta hiện dùng như lớp lọc nhanh trên lịch tháng. Nếu ngày được đánh dấu Cân nhắc, người dùng nên đọc thêm các lớp sao tốt xấu, ngày kỵ và mục đích việc cần làm.</p>
                </article>
            </div>
        </section>

        <section id="ngoc-hap" class="lta-library-section">
            <h2>Ngọc Hạp Thông Thư</h2>
            <p>Ngọc Hạp là nhóm thông tin sâu hơn, thường gắn với sao tốt, sao xấu và những việc lễ nghi, cầu phúc, nhập trạch hoặc việc trong gia đình. Lịch Ta ưu tiên hiển thị phần có thể kiểm tra rõ ràng, còn những mục chưa đủ chắc sẽ được ghi chú để tránh làm người đọc hiểu nhầm.</p>
            <div class="lta-source-note">
                <strong>Nguyệt Đức</strong>
                <p><?= lta_h($almanacLibrary['ngocHap']['nguyetDuc']) ?></p>
            </div>
            <div class="lta-data-list lta-ngoc-hap-list">
                <?php foreach ($almanacLibrary['ngocHap']['ritual'] as $canChi => $rule): ?>
                    <details>
                        <summary><span><?= lta_h($canChi) ?></span><em><?= lta_h($levelLabel($rule['level'])) ?></em></summary>
                        <p><?= lta_h($rule['summary']) ?></p>
                    </details>
                <?php endforeach; ?>
            </div>
            <p class="lta-data-warning">Một số mục Ngọc Hạp cần thêm bản đối chiếu rõ hơn trước khi đưa vào phần luận ngày. Nếu bạn có tài liệu đáng tin cậy, có thể bổ sung để Lịch Ta hoàn thiện dần.</p>
        </section>

        <section id="nap-am" class="lta-library-section">
            <h2>Nạp âm lục thập hoa giáp</h2>
            <p>Nạp âm là cách gọi mệnh ngũ hành của từng cặp Can Chi trong lục thập hoa giáp, ví dụ Hải Trung Kim, Lư Trung Hỏa, Đại Lâm Mộc. Trên Lịch Ta, nạp âm không chỉ để đọc thông tin ngày mà còn giúp tạo tông màu ngũ hành cho trang hôm nay và bộ lọc lịch tháng.</p>
        </section>

        <section class="lta-library-section lta-disclaimer">
            <h2>Lưu ý khi sử dụng</h2>
            <p>Các thông tin ngày tốt xấu trên Lịch Ta chỉ mang tính chất tham khảo văn hóa và tra cứu truyền thống. Nội dung này không phải lời khuyên bắt buộc, không thay thế tư vấn chuyên môn, pháp lý, y tế, tài chính hoặc quyết định cá nhân quan trọng. Khi cần dùng cho việc lớn, bạn nên đối chiếu thêm nhiều nguồn và tự chịu trách nhiệm với lựa chọn của mình.</p>
        </section>
    </section>
    <?php endif; ?>

    <?php if ($view === 'about'): ?>
    <section class="lta-panel lta-page-article">
        <p class="lta-eyebrow">Giới thiệu sản phẩm</p>
        <h1>Lịch Ta - ứng dụng lịch âm Việt Nam và mã nhúng lịch Việt cho website</h1>
        <p class="lta-lead">Lịch Ta là công cụ tra lịch âm Việt Nam chạy nhanh trên nền PHP, tập trung vào các nhu cầu thực tế: xem lịch hôm nay, xem lịch tháng, đổi ngày âm dương, đọc thông tin Can Chi - tiết khí và nhúng lịch âm vào website.</p>

        <h2>Lịch Ta giải quyết nhu cầu gì?</h2>
        <p>Nhiều website Việt Nam cần một block lịch âm gọn, dễ đọc và có thể đặt ngay trong trang mà không phải tự xây dựng thuật toán lịch. Lịch Ta cung cấp giao diện xem ngày trực tiếp cho người dùng cuối và một widget nhúng cho chủ website. Công cụ này phù hợp với website doanh nghiệp, blog văn hóa, trang phong thủy, cổng thông tin địa phương, landing page bất động sản, trang sự kiện và các hệ thống nội bộ cần hiển thị lịch Việt.</p>

        <h2>Cách tính lịch âm ở mức cơ bản</h2>
        <p>Âm lịch Việt Nam dựa trên chu kỳ Mặt Trăng, trong đó ngày đầu tháng âm thường gắn với thời điểm sóc. Để đồng bộ với mùa trong năm, lịch còn xét các tiết khí theo chuyển động biểu kiến của Mặt Trời; hệ 24 tiết khí thường được hiểu là các mốc cách nhau 15 độ trên kinh độ Mặt Trời. Vì một năm âm lịch ngắn hơn năm dương lịch, một số năm sẽ có tháng nhuận để giữ lịch không lệch quá xa mùa vụ. Khi chuyển đổi ngày dương sang âm, ứng dụng cần xác định ngày Julius, thời điểm sóc, tháng âm, năm âm và trường hợp tháng nhuận theo múi giờ Việt Nam. <a class="lta-citation-link" href="https://vi.wikipedia.org/wiki/Ti%E1%BA%BFt_kh%C3%AD" target="_blank" rel="noopener">Tham khảo tiết khí</a></p>
        <p>Phần lõi tính ngày âm của Lịch Ta tham khảo thư viện và thuật toán âm lịch Việt Nam do giáo sư Hồ Ngọc Đức công bố rộng rãi, kết hợp nguyên lý mùa thiên văn từ độ nghiêng trục Trái Đất và các điểm phân chí. Đây là nền tảng chính để app tính ngày âm, tháng nhuận, Can Chi, 24 tiết khí, giờ hoàng đạo và các mốc liên quan theo múi giờ Việt Nam UTC+7. <a class="lta-citation-link" href="https://www.xemamlich.uhm.vn/vncal_en.html" target="_blank" rel="noopener">Thuật toán âm lịch</a> <a class="lta-citation-link" href="https://science.nasa.gov/solar-system/skywatching/night-sky-network/embracing-the-equinox/" target="_blank" rel="noopener">NASA về mùa</a></p>
        <p>Sau khi có ngày âm cơ bản, Lịch Ta bổ sung các lớp thông tin truyền thống như Can Chi ngày, Can Chi tháng, Can Chi năm, nạp âm, trực, lục diệu, tiết khí, giờ hoàng đạo và một số ngày lễ phổ biến. Các lớp này giúp người dùng đọc lịch Việt theo thói quen văn hóa quen thuộc hơn thay vì chỉ thấy con số ngày tháng.</p>

        <h2>Vì sao có thêm mã nhúng?</h2>
        <p>Không phải website nào cũng cần xây một app lịch riêng. Với mã nhúng của Lịch Ta, chủ website có thể thêm lịch âm bằng iframe hoặc JavaScript. Iframe phù hợp cho nhu cầu copy nhanh. JavaScript phù hợp khi muốn đặt widget vào một vùng nội dung cụ thể và để khung nhúng tự điều chỉnh chiều cao theo nội dung.</p>

        <h2>Định hướng phát triển</h2>
        <p>Lịch Ta ưu tiên tính dễ dùng, tốc độ tải nhanh, giao diện mobile rõ ràng và nội dung hữu ích cho người tìm kiếm lịch âm Việt Nam. Các phần ngày tốt xấu được trình bày như thông tin tham khảo văn hóa, không khẳng định thay cho tư vấn chuyên môn, pháp lý, y tế, tài chính hoặc quyết định cá nhân quan trọng.</p>
    </section>
    <?php endif; ?>

    <?php if ($view === 'terms'): ?>
    <section class="lta-panel lta-page-article">
        <p class="lta-eyebrow">Điều khoản sử dụng</p>
        <h1>Điều khoản sử dụng Lịch Ta</h1>
        <p class="lta-lead">Khi truy cập, tra cứu hoặc nhúng Lịch Ta vào website, bạn đồng ý sử dụng thông tin trong phạm vi tham khảo và tôn trọng giới hạn của công cụ.</p>

        <h2>1. Phạm vi thông tin</h2>
        <p>Lịch Ta cung cấp thông tin lịch âm Việt Nam, chuyển đổi ngày âm dương, Can Chi, tiết khí, giờ hoàng đạo, trực, lục diệu, nạp âm, ngày lễ và một số lớp dữ liệu ngày tốt xấu phổ biến. Thông tin được thiết kế để tham khảo văn hóa, tra cứu lịch và hỗ trợ hiển thị nội dung trên website.</p>

        <h2>2. Giới hạn trách nhiệm</h2>
        <p>Các thông tin như ngày tốt xấu, giờ hoàng đạo, trực, lục diệu hoặc Đổng Công không phải lời khuyên bắt buộc. Người dùng chịu trách nhiệm khi áp dụng dữ liệu vào quyết định cá nhân, kinh doanh, nghi lễ, pháp lý, y tế, tài chính hoặc các tình huống quan trọng khác.</p>

        <h2>3. Quyền nhúng widget</h2>
        <p>Bạn có thể sử dụng mã iframe hoặc JavaScript được cung cấp trên trang mã nhúng để hiển thị Lịch Ta trên website. Vui lòng không sửa widget theo cách gây hiểu nhầm nguồn dữ liệu, che khuất thương hiệu hoặc làm sai lệch nội dung hiển thị.</p>

        <h2>4. Thay đổi dịch vụ</h2>
        <p>Lịch Ta có thể được cập nhật giao diện, thuật toán, dữ liệu ngày lễ, endpoint nhúng hoặc nội dung mô tả để cải thiện độ ổn định và trải nghiệm người dùng. Các thay đổi sẽ được triển khai theo hướng hạn chế phá vỡ mã nhúng hiện có.</p>
    </section>
    <?php endif; ?>

    <?php if ($view === 'privacy'): ?>
    <section class="lta-panel lta-page-article">
        <p class="lta-eyebrow">Chính sách bảo mật</p>
        <h1>Chính sách bảo mật Lịch Ta</h1>
        <p class="lta-lead">Lịch Ta được xây dựng theo hướng thu thập tối thiểu. Công cụ tra cứu lịch và mã nhúng không yêu cầu người dùng tạo tài khoản để xem thông tin cơ bản.</p>

        <h2>Dữ liệu truy cập</h2>
        <p>Khi bạn truy cập website hoặc tải widget nhúng, máy chủ có thể ghi nhận các thông tin kỹ thuật thông thường như địa chỉ IP, thời gian truy cập, trình duyệt, trang được yêu cầu và mã phản hồi để vận hành, bảo mật và xử lý lỗi.</p>

        <h2>Cookie và theo dõi</h2>
        <p>Ở phiên bản hiện tại, Lịch Ta không yêu cầu cookie đăng nhập cho chức năng tra cứu công khai. Nếu trong tương lai có thêm phân tích truy cập hoặc tính năng cá nhân hóa, nội dung chính sách sẽ được cập nhật để mô tả rõ mục đích sử dụng.</p>

        <h2>Dữ liệu từ website nhúng</h2>
        <p>Khi một website bên thứ ba nhúng Lịch Ta, iframe hoặc script có thể tạo request tới máy chủ Lịch Ta để tải giao diện lịch. Chúng tôi không yêu cầu website nhúng gửi thông tin cá nhân của khách truy cập vào widget.</p>

        <h2>Liên hệ và cập nhật</h2>
        <p>Nếu bạn vận hành website có nhúng Lịch Ta và cần điều chỉnh cách hiển thị, vui lòng kiểm tra trang mã nhúng hoặc cập nhật lên đoạn code mới nhất. Chính sách này có thể được cập nhật khi sản phẩm bổ sung tính năng mới.</p>
    </section>
    <?php endif; ?>

    <footer class="lta-footer">
        <a href="<?= lta_h($viewUrl('about', $selected)) ?>">Giới thiệu</a>
        <a href="<?= lta_h($viewUrl('almanac', $selected)) ?>">Thư viện Thông Thư</a>
        <a href="<?= lta_h($viewUrl('embed', $selected)) ?>">Code nhúng lịch Việt</a>
        <a href="<?= lta_h($viewUrl('terms', $selected)) ?>">Điều khoản sử dụng</a>
        <a href="<?= lta_h($viewUrl('privacy', $selected)) ?>">Chính sách bảo mật</a>
    </footer>
</main>
<div class="lta-modal" data-lta-modal hidden>
    <div class="lta-modal-backdrop" data-lta-modal-close></div>
    <section class="lta-modal-card" role="dialog" aria-modal="true" aria-labelledby="lta-modal-title">
        <button class="lta-modal-close" type="button" aria-label="Đóng" data-lta-modal-close>×</button>
        <h2 id="lta-modal-title">Chi tiết ngày</h2>
        <pre data-lta-modal-content></pre>
    </section>
</div>
<script src="assets/site.js?v=<?= lta_h(LTA_APP_VERSION) ?>"></script>
</body>
</html>
