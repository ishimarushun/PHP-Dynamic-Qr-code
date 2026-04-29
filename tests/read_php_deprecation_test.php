<?php

declare(strict_types=1);

$readPhp = __DIR__ . '/../src/read.php';
$contents = file_get_contents($readPhp);

if ($contents === false) {
    fwrite(STDERR, "Unable to read {$readPhp}\n");
    exit(1);
}

$checks = [
    'read.php no longer uses deprecated FILTER_SANITIZE_STRING' => strpos($contents, 'FILTER_SANITIZE_STRING') === false,
    'read.php uses FILTER_UNSAFE_RAW via filter_input' => preg_match("/filter_input\s*\(\s*INPUT_GET\s*,\s*'id'\s*,\s*FILTER_UNSAFE_RAW\s*\)/", $contents) === 1,
    'read.php still applies strip_tags hardening' => strpos($contents, 'strip_tags') !== false,
];

$failed = false;

foreach ($checks as $label => $result) {
    if ($result) {
        echo "[PASS] {$label}\n";
    } else {
        $failed = true;
        echo "[FAIL] {$label}\n";
    }
}

exit($failed ? 1 : 0);
