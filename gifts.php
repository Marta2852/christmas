<?php

require "Database.php";
$config = require("config.php");

$db = new Database($config["database"]);

$gifts = $db->query("SELECT * FROM gifts")->fetchAll(PDO::FETCH_ASSOC);

$letters = $db->query("SELECT letter_text FROM letters")->fetchAll(PDO::FETCH_ASSOC);

$wishes = [];
foreach ($letters as $letter) {
    foreach ($gifts as $gift) {
        if (stripos($letter['letter_text'], $gift['name']) !== false) {
            if (!isset($wishes[$gift['name']])) {
                $wishes[$gift['name']] = 0;
            }
            $wishes[$gift['name']]++;
        }
    }
}

echo "<h1>Dāvanu grāmatvedība</h1>\n<ol>";
foreach ($gifts as $gift) {
    $giftName = $gift['name'];
    $requested = $wishes[$giftName] ?? 0;
    $available = $gift['count_available'];
    $status = $available >= $requested ? 'Pietiek' : 'Trūkst';

    echo "<li>{$giftName}: Pieejams - {$available}, Vēlamais - {$requested} ({$status})</li>\n";
}
echo "</ol>";
?>
