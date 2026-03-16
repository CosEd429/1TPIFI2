$(body)

function body(){

    for (let i = 1; i <= 10; i++) {
        const digit = $("<button>");
        digit.html(i);
        digit.on("click" , function(){
            let message = "You clicked on button : " 
            alert(message + digit.html())
        })
        $("body").append(digit);
    }
}