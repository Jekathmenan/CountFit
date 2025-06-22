<?php

use CountFit\Models\Bodypart;

require_once __DIR__ . '/../bootstrap.php';

$bodyparts = Bodypart::getAllRelevant();

echo "<div class='card'><a class='link-block' href='../includes/addbp.inc.php'><i class='fas fa-plus-circle'></i></a></div>";
foreach ($bodyparts as $key => $bp) {
    echo "<div class='card card1'><a class='link-block' href='../app/planer.uebung.php?&id=$bp->bodypartId'>$bp->bodypartName</a></div>";
}
