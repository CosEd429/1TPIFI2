$(body)

function body(){
    let newInput = $("<input>")
    newInput.attr("type", "number");
    newInput.val(10)
    let Addition = $("<button>")
    Addition.html("+")
    Addition.on("click" , function (){
        newInput = newInput + 1
    })
    

    let Substraction = $("<button>")
    Substraction.html("-")
    Substraction.on("click" , function(){
    newInput = newInput - 1
    $("input").val(newInput)
        
    })

    $("body").append(newInput)
    $("body").append(Addition)
    $("body").append(Substraction)
}