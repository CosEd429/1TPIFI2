<?php

$connection = new mysqli("localhost" , "root" , "" , "ppl");

$sql_SelectEveryone = $connection->prepare(
    "SELECT * FROM Persons"
);

$sql_SelectMinor = $connection->prepare(
    "SELECT * FROM Persons where personAge < 18"  
);


$sql_SelectMajor = $connection->prepare(
    "SELECT * FROM Persons where personAge >= 18"  
);



if(isset($_GET["people"])){
    if($_GET["people"] == "everyone"){
        $sql_SelectEveryone->execute();
        $resultEveryone = $sql_SelectEveryone->get_result();
        while($row = $resultEveryone->fetch_assoc()){
        echo $row["personName"]." ".$row["personAge"];
        echo"<br>";
    }
    }
    elseif($_GET["people"] == "minor"){
        $sql_SelectMinor->execute();
        $resultMinor = $sql_SelectMinor->get_result();
       while($row = $resultMinor->fetch_assoc()){
        echo $row["personName"]." ".$row["personAge"];
        echo"<br>";
    }
    }
    else{
        $sql_SelectMajor->execute();
        $resultMajor = $sql_SelectMajor->get_result();
        while($row = $resultMajor->fetch_assoc()){
        echo $row["personName"]." ".$row["personAge"];
        echo"<br>";
    }
    } 
    }

?>