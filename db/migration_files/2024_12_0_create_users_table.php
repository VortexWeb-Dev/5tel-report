<?php

require_once __DIR__ . "/../db.php";

$config = require __DIR__ . "/../../config/config.php";

try {
    $db = new Database($config['db']);
    $pdo = $db->getConnection();

    $sql = '
    CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        name VARCHAR(100),
        bitrix_id INTEGER UNIQUE
    );
    ';

    $pdo->exec($sql);
    echo "Migration successful: 'users' table created.";
} catch (PDOException $e) {
    die("Error in migration: " . $e->getMessage());
}
