$(body)

function body(){

    $.get("Exercise4.php" , {} , function(data){
        let people = $("<ul>")
        people.attr("id" , data["personID"])
        people.html(data)
        $("#list").append(people)
    })
    
}