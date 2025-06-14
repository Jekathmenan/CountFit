<?php 
    $trainingssessions = $_SESSION['trainingsessions'];
    $length = count($trainingssessions);
    $totaladded = 0;
    if ($length <= 6) {
        $cardnum = 1;
        for($i = 0; $i < $length; $i++) {
            $trainingses = $trainingssessions[$i][0];
            $tsId = $trainingssessions[$i][1];
            echo "<div class='card $cardnum'><a class='link-block' href='../app/planer.bp.php?id=$i&in=$tsId'>$trainingses</a></div>";
            $totaladded++;
            $cardnum++;
        }
        if ($totaladded < 6) {
            for($j = 0; $totaladded < 6; $totaladded++) {
                echo "<div class='card $cardnum'><a class='link-block' href='../includes/addts.inc.php'><i class='fas fa-plus-circle'></i></a></div>";
                $cardnum++;
            }
        }
    }
?>