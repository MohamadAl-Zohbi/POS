<?php
try {
    $db_path = 'sqlite:../db/test.db';
    $db = new \PDO($db_path);
} catch (PDOException $e) {
    echo '<script>alert("undefined db")</script>';
}
