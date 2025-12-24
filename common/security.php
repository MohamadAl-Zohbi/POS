<?php
if (!defined('APP_INIT')) {
    exit('No direct access');
}
define('ENC_METHOD', 'AES-256-CBC');
define('ENC_SECRET', '123');
define('ENC_IV', substr(hash('sha256', 'PUT_ANOTHER_RANDOM_IV'), 0, 16));
?>
