<?php 
    $exercises = $_SESSION['exercises'];
    $length = count($exercises);
    $totaladded = 0;

    if ($length <= 12) {
        $cardnum = 1;
        $exindex = $_GET['id'];
        for($i = 0; $i < $length; $i++) {
            $excerciseName = $exercises[$i][0];
            $exid = $exercises[$i][1];
            
            echo "
                <div class='card card$cardnum'>
                    <div class='block'>
                        $excerciseName
                    </div>
                    <div class='input-exc'>
                        <form class='exc' action='../includes/addset.inc.php?id=$exid&exid=$exindex' method='post'>
                            <div class='weight'>
                                <span class='label'> Gewicht in kg:</span>
                                <div class='weight-adj'>
                                    <input type='number' step='0.01' placeholder='1.25' class='weight-input' name='weight-input' required></input>
                                    
                                </div>
                            </div>
                            <div class='reps'>
                                <span class='label'> Wiederholung:</span>
                                <div class='reps-adj'>
                                    <input type='number' placeholder='150' class='rep-input' name='reps-input' required></input>
                                </div>
                            </div>
                            <div class='buttons'>
                                <!-- <button type='submit' class='cancel' name='cancel'>Abbrechen</button> -->
                                <button type='submit' class='submit' name='save'>Speichern</button>
                            </div>
                        </form>
                    </div>
                </div>
            ";
            $cardnum++;
            $totaladded++;
        }
        if ($totaladded < 12) {
            for($j = 0; $totaladded < 12; $totaladded++) {
                echo "<div class='card card$cardnum'><a class='link-block' href='../includes/addex.inc.php'><i class='fas fa-plus-circle'></i></a></div>";
                $cardnum++;
            }
        }
    }
?>