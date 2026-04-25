<?php

declare(strict_types=1);

require_once __DIR__ . '/app/bootstrap.php';

$today = lta_today();
$selected = lta_selected_date($today);
$month = lta_int_param('month', $selected['month'], 1, 12);
$year = lta_int_param('year', $selected['year'], 1800, 2199);
$selected['month'] = $month;
$selected['year'] = $year;
if (!checkdate($selected['month'], $selected['day'], $selected['year'])) {
    $selected['day'] = min($selected['day'], cal_days_in_month(CAL_GREGORIAN, $selected['month'], $selected['year']));
}
$cells = lta_month_cells($month, $year, $selected, $today);
$dayInfo = lta_day_info($selected);
$prev = lta_prev_month($month, $year);
$next = lta_next_month($month, $year);
$baseUrl = 'https://app.pdl.vn/lich-ta';
$iframeCode = '<iframe src="' . $baseUrl . '/embed.php" width="100%" height="620" style="border:0;max-width:760px;border-radius:16px;overflow:hidden" loading="lazy"></iframe>';
$scriptCode = '<div id="pdl-lich-ta"></div>' . "\n" . '<script src="' . $baseUrl . '/embed.js" data-target="pdl-lich-ta" data-view="month" async></script>';
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lịch Ta - Lịch âm Việt Nam nhúng website</title>
    <meta name="description" content="Lịch âm Việt Nam chạy PHP, có thể nhúng qua iframe hoặc JavaScript.">
    <link rel="stylesheet" href="assets/site.css">
</head>
<body>
<main class="lta-shell">
    <header class="lta-topbar">
        <a class="lta-brand" href="./" aria-label="Lịch Ta">
            <img src="assets/lich-ta-mark.svg" alt="" width="42" height="42">
            <span>
                <strong>Lịch Ta</strong>
                <small>Âm lịch Việt Nam</small>
            </span>
        </a>
        <nav class="lta-nav" aria-label="Điều hướng">
            <a href="#calendar">Lịch tháng</a>
            <a href="#convert">Đổi ngày</a>
            <a href="#embed">Mã nhúng</a>
        </nav>
    </header>

    <section class="lta-workspace" id="calendar">
        <div class="lta-panel lta-calendar-panel">
            <div class="lta-panel-head">
                <div>
                    <p class="lta-eyebrow">Lịch tháng</p>
                    <h1><?= lta_h(LTA_MONTHS[$month]) ?> năm <?= (int) $year ?></h1>
                </div>
                <div class="lta-month-actions" aria-label="Chuyển tháng">
                    <a href="<?= lta_h(lta_build_url(['month' => $prev['month'], 'year' => $prev['year'], 'day' => 1])) ?>" aria-label="Tháng trước">‹</a>
                    <a href="<?= lta_h(lta_build_url(['month' => $today['month'], 'year' => $today['year'], 'day' => $today['day']])) ?>">Hôm nay</a>
                    <a href="<?= lta_h(lta_build_url(['month' => $next['month'], 'year' => $next['year'], 'day' => 1])) ?>" aria-label="Tháng sau">›</a>
                </div>
            </div>

            <form class="lta-picker" method="get" action="">
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

            <?= lta_render_calendar($cells) ?>
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
                <div><dt>Tiết khí</dt><dd><?= lta_h($dayInfo['term']) ?></dd></div>
            </dl>

            <div class="lta-hours">
                <span>Giờ hoàng đạo</span>
                <p><?= lta_h(implode(', ', $dayInfo['hours'])) ?></p>
            </div>
        </aside>
    </section>

    <section class="lta-lower-grid" id="convert">
        <div class="lta-panel">
            <p class="lta-eyebrow">Đổi ngày nhanh</p>
            <h2>Dương lịch sang âm lịch</h2>
            <form class="lta-converter" method="get" action="">
                <label><span>Ngày</span><input name="day" type="number" min="1" max="31" value="<?= (int) $selected['day'] ?>"></label>
                <label><span>Tháng</span><input name="month" type="number" min="1" max="12" value="<?= (int) $selected['month'] ?>"></label>
                <label><span>Năm</span><input name="year" type="number" min="1800" max="2199" value="<?= (int) $selected['year'] ?>"></label>
                <button type="submit">Đổi ngày</button>
            </form>
        </div>

        <div class="lta-panel" id="embed">
            <p class="lta-eyebrow">Nhúng vào website</p>
            <h2>Iframe hoặc JavaScript</h2>
            <div class="lta-code-tabs" data-code-tabs>
                <div class="lta-segmented" role="tablist">
                    <button type="button" class="is-active" data-code-tab="iframe">Iframe</button>
                    <button type="button" data-code-tab="script">JavaScript</button>
                </div>
                <pre data-code-panel="iframe"><code><?= lta_h($iframeCode) ?></code></pre>
                <pre data-code-panel="script" hidden><code><?= lta_h($scriptCode) ?></code></pre>
            </div>
        </div>
    </section>
</main>
<script src="assets/site.js"></script>
</body>
</html>
