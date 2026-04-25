<?php

declare(strict_types=1);

require_once __DIR__ . '/app/bootstrap.php';

$today = lta_today();
$selected = lta_selected_date($today);
$view = (string) ($_GET['view'] ?? lta_view_from_path() ?? 'today');
$view = in_array($view, ['today', 'month', 'nap-am', 'convert', 'embed'], true) ? $view : 'today';
$month = lta_int_param('month', $selected['month'], 1, 12);
$year = lta_int_param('year', $selected['year'], 1800, 2199);
$selected['month'] = $month;
$selected['year'] = $year;
if (!checkdate($selected['month'], $selected['day'], $selected['year'])) {
    $selected['day'] = min($selected['day'], cal_days_in_month(CAL_GREGORIAN, $selected['month'], $selected['year']));
}
$cells = lta_month_cells($month, $year, $selected, $today);
$dayInfo = lta_day_info($selected);

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
$viewUrl = static function (string $target, array $date) use ($month, $year): string {
    $params = ['view' => $target];
    if ($target !== 'today') {
        $params += ['day' => $date['day'], 'month' => $month, 'year' => $year];
    }

    return $target === 'today' ? './' : 'index.php?' . http_build_query($params);
};
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
            <a class="<?= $view === 'today' ? 'is-active' : '' ?>" href="<?= lta_h($viewUrl('today', $selected)) ?>"><span aria-hidden="true">⌂</span>Hôm nay</a>
            <a class="<?= $view === 'month' ? 'is-active' : '' ?>" href="<?= lta_h($viewUrl('month', $selected)) ?>"><span aria-hidden="true">□</span>Lịch tháng</a>
            <a class="<?= $view === 'nap-am' ? 'is-active' : '' ?>" href="<?= lta_h($viewUrl('nap-am', $selected)) ?>"><span aria-hidden="true">◎</span>Nạp âm</a>
            <a class="<?= $view === 'convert' ? 'is-active' : '' ?>" href="<?= lta_h($viewUrl('convert', $selected)) ?>"><span aria-hidden="true">⇄</span>Đổi ngày</a>
            <a class="<?= $view === 'embed' ? 'is-active' : '' ?>" href="<?= lta_h($viewUrl('embed', $selected)) ?>"><span aria-hidden="true">{ }</span>Mã nhúng</a>
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
            <div class="lta-home-actions">
                <a href="<?= lta_h($viewUrl('month', $selected)) ?>"><span aria-hidden="true">□</span>Xem lịch tháng</a>
                <a href="<?= lta_h($viewUrl('nap-am', $selected)) ?>"><span aria-hidden="true">◎</span>Lọc Nạp âm</a>
                <a href="<?= lta_h($viewUrl('convert', $selected)) ?>"><span aria-hidden="true">⇄</span>Đổi ngày</a>
            </div>
        </div>

        <aside class="lta-panel lta-detail-panel" aria-label="Chi tiết ngày hôm nay">
            <p class="lta-eyebrow">Thông tin ngày</p>
            <dl class="lta-facts">
                <div><dt><span aria-hidden="true">☰</span>Ngày</dt><dd><?= lta_h($dayInfo['canChi']['day']) ?></dd></div>
                <div><dt><span aria-hidden="true">☷</span>Tháng</dt><dd><?= lta_h($dayInfo['canChi']['month']) ?></dd></div>
                <div><dt><span aria-hidden="true">✦</span>Năm</dt><dd><?= lta_h($dayInfo['canChi']['year']) ?></dd></div>
                <div><dt><span aria-hidden="true">◐</span>Tiết khí</dt><dd><?= lta_h($dayInfo['term']) ?></dd></div>
                <div><dt><span aria-hidden="true">⌁</span>Trực</dt><dd><?= lta_h($dayInfo['fortune']['truc']) ?></dd></div>
                <div><dt><span aria-hidden="true">◎</span>Nạp âm</dt><dd><?= lta_h($dayInfo['fortune']['napAm']) ?></dd></div>
                <div><dt><span aria-hidden="true">✓</span>Đổng Công</dt><dd><?= lta_h($dayInfo['fortune']['dongCong']['label']) ?> · trực <?= lta_h($dayInfo['fortune']['dongCong']['truc']) ?></dd></div>
            </dl>
            <div class="lta-hours">
                <span>Giờ hoàng đạo</span>
                <p><?= lta_h(implode(', ', $dayInfo['hours'])) ?></p>
            </div>
        </aside>
    </section>
    <?php endif; ?>

    <?php if ($view === 'month'): ?>
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
                <div><dt>Giờ đầu ngày</dt><dd><?= lta_h($dayInfo['firstHour']) ?></dd></div>
                <div><dt>Tiết khí</dt><dd><?= lta_h($dayInfo['term']) ?></dd></div>
                <div><dt>Trực</dt><dd><?= lta_h($dayInfo['fortune']['truc']) ?></dd></div>
                <div><dt>Sao</dt><dd><?= lta_h($dayInfo['fortune']['saoNhiThapBatTu']) ?></dd></div>
                <div><dt>Lục diệu</dt><dd><?= lta_h($dayInfo['fortune']['lucDieu']) ?></dd></div>
                <div><dt>Nạp âm</dt><dd><?= lta_h($dayInfo['fortune']['napAm']) ?></dd></div>
                <div><dt>Ngày</dt><dd><?= lta_h($dayInfo['fortune']['hoangHacDao']) ?><?= $dayInfo['fortune']['hoangHacDaoStar'] !== null ? ' - ' . lta_h($dayInfo['fortune']['hoangHacDaoStar']) : '' ?></dd></div>
                <div><dt>Đổng Công</dt><dd><?= lta_h($dayInfo['fortune']['dongCong']['label']) ?> · trực <?= lta_h($dayInfo['fortune']['dongCong']['truc']) ?></dd></div>
            </dl>

            <div class="lta-hours">
                <span>Giờ hoàng đạo</span>
                <p><?= lta_h(implode(', ', $dayInfo['hours'])) ?></p>
            </div>

            <div class="lta-fortune-note">
                <span>Tuổi xung</span>
                <p><?= lta_h(implode(', ', $dayInfo['fortune']['tuoiXung'])) ?> · xung chi <?= lta_h($dayInfo['fortune']['ngayXung']) ?></p>
                <small><?= lta_h($dayInfo['fortune']['lucDieuHint']) ?></small>
            </div>

            <?php if ($dayInfo['events'] !== []): ?>
                <div class="lta-events">
                    <span>Sự kiện</span>
                    <ul>
                        <?php foreach ($dayInfo['events'] as $event): ?>
                            <li><?= lta_h($event['name']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
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
            <h2>Dương lịch sang âm lịch</h2>
            <form class="lta-converter" method="get" action="">
                <input name="view" type="hidden" value="convert">
                <label><span>Ngày</span><input name="day" type="number" min="1" max="31" value="<?= (int) $selected['day'] ?>"></label>
                <label><span>Tháng</span><input name="month" type="number" min="1" max="12" value="<?= (int) $selected['month'] ?>"></label>
                <label><span>Năm</span><input name="year" type="number" min="1800" max="2199" value="<?= (int) $selected['year'] ?>"></label>
                <button type="submit">Đổi ngày</button>
            </form>
        </div>

        <aside class="lta-panel lta-detail-panel" aria-label="Kết quả đổi ngày">
            <p class="lta-eyebrow">Kết quả</p>
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
    <?php endif; ?>
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
