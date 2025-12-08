$(start);


function inputCreation(inputName) {
    let newInput = $("<input>")
    newInput.attr("placeholder", inputName);
    newInput.attr("type", "t");
    $("body").append(newInput);
    return newInput;
}


function start() {
    let value = inputCreation("value");
    $("body").append("<br>");
    // Create digit buttons 0–9
    for (let i = 0; i <= 9; i++) {
        const digit = $("<button>");
        digit.html(i);
        $("body").append(digit);
    }
    $("body").append("<br>");
    let addition = $("<button>").html("+");
    $("body").append(addition);
    let substraction = $("<button>").html("-");
    $("body").append(substraction);
    let equal = $("<button>").html("=");
    $("body").append(equal);
    let div = $("<div>");
    $("body").append(div);
    let clear = $("<button>").html("clear");
    $("body").append(clear);
}
