<?php
try {
    $db = new PDO(
        'sqlite:' . __DIR__ . '/../db/test.db',
        null,
        null,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}

?>