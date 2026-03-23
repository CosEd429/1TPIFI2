$(body)

let check = true;

function body(){
    let newInput = $("<input>")
    newInput.attr("placeholder", "type new option")
 
    let addButton = $("<button>")
    addButton.html("Add Text")
    addButton.on("click" , function(){
        if(newInput.val() == ""){
            alert("This is empty. Please type something.")
        }
        else
        {
            if(check == true){
               $("#Edyr").html("")
               let newOption = $("<option>")
                newOption.html(newInput.val())
                $("#Edyr").append(newOption)
                check = false;
            }
            else{
            let newOption = $("<option>")
            newOption.html(newInput.val())
            $("#Edyr").append(newOption)
            }
            
        }

    })
       $("body").append(newInput , addButton)
}