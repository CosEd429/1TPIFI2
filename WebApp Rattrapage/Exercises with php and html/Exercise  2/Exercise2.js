$(body)

function body(){
    $("#load").on("click" , function(){
        $.get("Exercise2.php" , {question:"age"} , function(data){
         $("#changingText").html(data)
        })
    })
}