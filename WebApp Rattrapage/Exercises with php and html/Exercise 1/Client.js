$(body)

function body(){
$("#load").on("click" , function(){
    $.get("Service.php" , { "Key1" : 1 , "Key2" : 2},function(data){
        $("#textChange").html(data)
    })
})
}