<?php

require_once __DIR__ . "/../db.php";

$config = require __DIR__ . "/../../config/config.php";

try {
    $db = new Database($config['db']);
    $pdo = $db->getConnection();

    $sql = '
    CREATE TABLE IF NOT EXISTS company (
        id SERIAL PRIMARY KEY,
        name VARCHAR(100),
        mid VARCHAR(100) UNIQUE,
        responsible_person VARCHAR(100),
        responsible_person_bitrix_id INTEGER REFERENCES users(bitrix_id)
    );
    ';

    $pdo->exec($sql);
    echo "Migration successful: 'company' table created.";
} catch (PDOException $e) {
    die("Error in migration: " . $e->getMessage());
}
