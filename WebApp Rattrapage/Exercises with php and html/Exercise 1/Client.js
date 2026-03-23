$(body)

function body(){
$("#load").on("click" , function(){
    $.get("Service.php" , function(data){
        $("#textChange").html(data)
    })
})
}