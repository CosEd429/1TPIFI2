<?php

$connection = new mysqli("localhost" , "root" , "" , "people");

$sql_Select = $connection->prepare(
    "SELECT * FROM persons"
);

$sql_Select -> execute();
$result = $sql_Select->get_result();


if(isset($_GET["question"])){
    if($_GET["question"] == "name"){   
    while($row = $result->fetch_assoc()){
        echo $row["personName"]."<br>";
    }
    }
     elseif($_GET["question"] == "age")
        while($row = $result->fetch_assoc()){
        echo $row["age"]."<br>";
    }
    else{
        echo "I don't have an answer for that question";
    }
}
else{
    echo "Ask me a question";
}


?>