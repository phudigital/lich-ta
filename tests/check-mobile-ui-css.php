<?php

declare(strict_types=1);

$css = file_get_contents(__DIR__ . '/../assets/site.css');
if ($css === false) {
    fwrite(STDERR, "Unable to read assets/site.css\n");
    exit(1);
}

foreach ([
    '.lta-mobile-nav' => 'Mobile bottom navigation should be styled',
    'grid-template-columns: repeat(4, minmax(0, 1fr));' => 'Mobile nav should become a 4-item menu',
    '.lta-mobile-menu-card' => 'Mobile overflow menu should be styled',
    '.lta-deeplink-visual' => 'SEO content should include visual styling',
    '.lta-element-breakdown' => 'SEO content should include element breakdown styling',
    'grid-template-columns: 108px minmax(0, 1fr);' => 'Small mobile today hero should keep a readable two-column layout',
    'font-size: 4.1rem;' => 'Small mobile day tile should remain visually prominent',
] as $needle => $message) {
    if (!str_contains($css, $needle)) {
        fwrite(STDERR, $message . PHP_EOL);
        fwrite(STDERR, 'Missing: ' . $needle . PHP_EOL);
        exit(1);
    }
}

echo "Mobile UI CSS checks passed.\n";
