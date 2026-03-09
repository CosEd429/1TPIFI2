$(body)

function body(){
let i = 23;
let thirdButton = $("<button>")
thirdButton.html(i)
thirdButton.on("click" , function (){
    let number = parseInt(thirdButton.html()) + 1;
    thirdButton.html(number)
    
    
})
thirdButton.on("dblclick" , function (){
    let number = parseInt(thirdButton.html()) - 1;
    thirdButton.html(number)
    
    
})
$("body").append(thirdButton)

}