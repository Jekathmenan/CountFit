<?php

use CountFit\Models\Exercise;

$exercises = Exercise::getAllRelevant();
echo "<div class='card'><a class='link-block' href='../includes/addex.inc.php'><i class='fas fa-plus-circle'></i></a></div>";
foreach ($exercises as $key => $ex) {
    echo "
                <div class='card'>
                    <div class='block'>
                        " . $ex->getName() . "
                    </div>
                    <div class='input-exc'>
                        <form class='exc' action='../includes/addset.inc.php?exid=" . $ex->getId() . "' method='post'>
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
}
