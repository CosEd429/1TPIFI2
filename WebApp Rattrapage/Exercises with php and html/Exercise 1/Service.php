<?php
    echo "Hello world + test </br>";

    $Diff = ["EM" => 1 , "Parking Haute" => 2 , "Train Station" => 3];

    $Diff["Auchan"] = 100;
    foreach($_GET as $key => $value){
        print($key. " ". $value . "</br>");
    } 
   

?>
