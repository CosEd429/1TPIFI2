function inputCreation(inputName){
    let newInput = $("<input>")
    newInput.attr("placeholder" , inputName);
    newInput.attr("type", "number");
    $("body").append(newInput);
    return newInput;
}


function start(){

}