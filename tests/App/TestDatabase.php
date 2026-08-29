<?php

require_once __DIR__ . '/../../app/App/Database.php';

use app\Database\Database;


$db = Database::getConnection();
$db2 = Database::getConnection();

if ($db === $db2) {
    echo 'Merujuk pada object yang sama';
} else {
    echo 'object berbeda';
}