<?php

require "Database.php";
$config = require("config.php");

$db = new Database($config["database"]);
$firstname = $db->query("SELECT * FROM children")->fetchALL(PDO::FETCH_ASSOC);

echo "<ul>";
foreach ($firstname as $child){
    echo "<li>" . htmlspecialchars($child['firstname']) . ' ' . $child["middlename"] . ' ' . $child["surname"] . ' ' . $child["age"] .  "</li>";
}
echo "</ul>";
?>