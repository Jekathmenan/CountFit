<?php 
    $bodypart = $_SESSION['bodyparts'];
    $length = count($bodypart);
    $totaladded = 0;
    if ($length <= 4) {
        for($i = 0; $i < $length; $i++) {
            //var_dump($bp);
            $bp = $bodypart[$i][0];
            $exid = $_SESSION['currentTsId'];
            
            echo "<div class='card card1'><a class='link-block' href='../app/planer.uebung.php?&id=$i'>$bp</a></div>";
            $totaladded++;
        }
        if ($totaladded < 4) {
            for($j = 0; $totaladded < 4; $totaladded++) {
                echo "<div class='card'><a class='link-block' href='../includes/addbp.inc.php'><i class='fas fa-plus-circle'></i></a></div>";
            }
        }
        
    }
?>