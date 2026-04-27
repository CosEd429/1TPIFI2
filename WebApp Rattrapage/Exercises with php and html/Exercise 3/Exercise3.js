$(body)

function body(){
    $("#everyone").on("click", function(){
        $.get("Exercise3.php" , {people:"everyone"} , function(data){
            $("#appearingText").html("");
            $("#appearingText").html(data);
        })
    })
     $("#minor").on("click", function(){
        $.get("Exercise3.php" , {people:"minor"} , function(data){
            $("#appearingText").html("");
            $("#appearingText").html(data);
        })
    })
    $("#major").on("click", function(){
        $.get("Exercise3.php" , {people:"major"} , function(data){
            $("#appearingText").html("");
            $("#appearingText").html(data);
        })
    })
}