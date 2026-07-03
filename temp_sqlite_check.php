<?php
$path = __DIR__ . '/database/database.sqlite';
echo "DB_PATH: $path\n";
echo 'EXISTS:' . (file_exists($path) ? 'yes' : 'no') . "\n";
$pdo = new PDO('sqlite:' . $path);
$stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
foreach ($stmt as $row) {
    echo $row[0] . "\n";
}
