<?php
define('APP_INIT', true);
require_once __DIR__ . '/../common/security.php';
require_once __DIR__ . '/../common/functions.php';
$licenseData = [
    'fingerprint' => getFingerprint(),
    'expires_at'  => '2026-12-31',
    'type'        => 'POS_PRO',
];
$json = json_encode($licenseData);
$encrypted = encryptString($json);
file_put_contents(__DIR__ . '/../license/license.dat', $encrypted);
echo "LICENSE GENERATED";
