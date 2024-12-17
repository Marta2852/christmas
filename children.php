<?php

require "Database.php";
$config = require("config.php");

$db = new Database($config["database"]);

$name = $db->query("SELECT * FROM children")->fetchALL(PDO::FETCH_ASSOC);
$letter = $db->query("SELECT * FROM letters")->fetchALL(PDO::FETCH_ASSOC);

$gifts = $db->query("SELECT * FROM gifts")->fetchALL(PDO::FETCH_ASSOC);

$giftNames = array_column($gifts, 'name');

$lettersByChildId = [];
foreach ($letter as $let) {
    $lettersByChildId[$let['id']] = $let['letter_text'];
}

function highlightGifts($letterText, $giftNames) {
    foreach ($giftNames as $gift) {
        $letterText = preg_replace('/\b' . preg_quote($gift, '/') . '\b/i', 
            '<strong style="color: #ff66b2;">' . $gift . '</strong>', $letterText);
    }
    return $letterText;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Children and Christmas Letters</title>
    <link rel="stylesheet" href="christmas_style.css">
</head>
<body>

<header>
    🎅 Christmas Letters for the Children 🎄
</header>

<main>
    <ul>
        <?php foreach ($name as $child): ?>
            <li>
                <div class="child-info">
                    <?php 
                        echo htmlspecialchars($child['firstname']) . ' ' . 
                             htmlspecialchars($child['middlename']) . ' ' . 
                             htmlspecialchars($child['surname']) . ', Age: ' . 
                             htmlspecialchars($child['age']); 
                    ?>
                </div>
                
                <?php if (isset($lettersByChildId[$child['id']])): ?>
                    <?php
                        $highlightedLetter = highlightGifts($lettersByChildId[$child['id']], $giftNames);
                    ?>
                    <p><strong>Letter:</strong> <?php echo nl2br($highlightedLetter); ?></p>
                    
                    <p><strong>Wishes:</strong></p>
                    <ul>
                        <?php
                            $mentionedGifts = [];

                            foreach ($giftNames as $gift) {
                                if (stripos($lettersByChildId[$child['id']], $gift) !== false) {
                                    if (!in_array($gift, $mentionedGifts)) {
                                        $mentionedGifts[] = $gift;
                                    }
                                }
                            }

                            if (count($mentionedGifts) > 0) {
                                foreach ($mentionedGifts as $mentionedGift) {
                                    echo "<li>" . htmlspecialchars($mentionedGift) . "</li>";
                                }
                            } else {
                                echo "<li>No gifts mentioned in the letter.</li>";
                            }
                        ?>
                    </ul>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</main>

</body>
</html>
