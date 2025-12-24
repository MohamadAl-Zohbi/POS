<?php 
function encryptString(string $plainText): string
{
    return base64_encode(
        openssl_encrypt(
            $plainText,
            ENC_METHOD,
            ENC_SECRET,
            OPENSSL_RAW_DATA,
            ENC_IV
        )
    );
}


function decryptString(string $encryptedText): string
{
    return openssl_decrypt(
        base64_decode($encryptedText),
        ENC_METHOD,
        ENC_SECRET,
        OPENSSL_RAW_DATA,
        ENC_IV
    );
}

function getMacAddress() {
    $output = shell_exec('getmac /FO CSV /NH');
    if (!$output) return null;

    $lines = explode("\n", trim($output));
    foreach ($lines as $line) {
        $cols = str_getcsv($line);
        if (!empty($cols[0]) && $cols[0] !== 'N/A') {
            return $cols[0]; // MAC ADDRESS
        }
    }
    return null;
}

function getFingerprint(): string
{
    $mac  = getMacAddress();
    $host = gethostname();
    $os   = PHP_OS;

    return hash('sha256', $mac . '|' . $host . '|' . $os);
}

function checkLicense(): void
{
    $licenseFile = __DIR__ . '/../license/license.dat';

    if (!file_exists($licenseFile)) {
        die('LICENSE FILE MISSING');
    }

    $encrypted = file_get_contents($licenseFile);
    $json = decryptString($encrypted);

    if (!$json) {
        die('INVALID LICENSE');
    }

    $data = json_decode($json, true);

    if (!is_array($data)) {
        die('CORRUPTED LICENSE');
    }

    // fingerprint check
    if ($data['fingerprint'] !== getFingerprint()) {
        die('LICENSE NOT FOR THIS MACHINE');
    }

    // expiration check
    if (strtotime($data['expires_at']) < time()) {
        header('Location: ../sys/endDate.php');
        die('LICENSE EXPIRED');
    }
}

function checkTimeLock(PDO $db): void
{
    $now = time();

    $stmt = $db->query("SELECT last_run_time FROM data WHERE 1=1");
    $last = $stmt->fetchColumn();

    if ($last && $now < (int)$last) {
        die('SYSTEM CLOCK MANIPULATION DETECTED');
    }

    $stmt = $db->prepare("
        UPDATE data SET last_run_time = :v
    ");
    $stmt->execute(['v' => $now]);
}
?>