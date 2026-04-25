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
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lịch Ta Widget</title>
    <link rel="stylesheet" href="assets/site.css?v=<?= lta_h(LTA_APP_VERSION) ?>">
</head>
<body class="lta-embed-body">
<main class="lta-widget" data-lta-widget>
    <header class="lta-widget-head">
        <a class="lta-brand lta-brand-small" href="index.php" target="_blank" rel="noopener">
            <img src="assets/lich-ta-mark.svg" alt="" width="34" height="34">
            <span><strong>Lịch Ta</strong><small>Âm lịch Việt Nam · v<?= lta_h(LTA_APP_VERSION) ?></small></span>
        </a>
        <div class="lta-month-actions">
            <a href="<?= lta_h(lta_build_url(['month' => $prev['month'], 'year' => $prev['year'], 'day' => 1])) ?>" aria-label="Tháng trước">‹</a>
            <a href="<?= lta_h(lta_build_url(['month' => $today['month'], 'year' => $today['year'], 'day' => $today['day']])) ?>">Nay</a>
            <a href="<?= lta_h(lta_build_url(['month' => $next['month'], 'year' => $next['year'], 'day' => 1])) ?>" aria-label="Tháng sau">›</a>
        </div>
    </header>

    <section class="lta-widget-month">
        <div class="lta-widget-title">
            <strong><?= lta_h(LTA_MONTHS[$month]) ?> <?= (int) $year ?></strong>
            <span><?= (int) $dayInfo['lunar']['day'] ?>/<?= (int) $dayInfo['lunar']['month'] ?> âm lịch</span>
        </div>
        <?= lta_render_calendar($cells, true) ?>
    </section>

    <section class="lta-widget-detail">
        <div>
            <span><?= (int) $dayInfo['solar']['day'] ?>/<?= (int) $dayInfo['solar']['month'] ?></span>
            <strong><?= lta_h($dayInfo['canChi']['day']) ?></strong>
        </div>
        <p><?= lta_h($dayInfo['fortune']['truc']) ?> · <?= lta_h($dayInfo['fortune']['lucDieu']) ?> · <?= lta_h($dayInfo['term']) ?></p>
    </section>
</main>
<div class="lta-modal" data-lta-modal hidden>
    <div class="lta-modal-backdrop" data-lta-modal-close></div>
    <section class="lta-modal-card" role="dialog" aria-modal="true" aria-labelledby="lta-modal-title">
        <button class="lta-modal-close" type="button" aria-label="Đóng" data-lta-modal-close>×</button>
        <h2 id="lta-modal-title">Chi tiết ngày</h2>
        <pre data-lta-modal-content></pre>
    </section>
</div>
<script src="assets/site.js"></script>
</body>
</html>
