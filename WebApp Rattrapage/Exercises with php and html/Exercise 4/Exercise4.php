<?php

$connection = new mysqli("localhost" , "root" , "" , "ppl");

$sql_SelectNameandID = $connection->prepare(
    "Select personID , personName from persons"
)

$sql_SelectNameandID -> execute();

if(isset($_GET["list"])){
$resultEveryNameandID = $sql_SelectNameandID->get_result();
while($row = $resultEveryNameandID ->fetch_assoc()){
    print($row["personID"])
    print($row["personName"])
}
}

?>