$(body)

function body(){
    let Colors =  ["red" ,"blue" , "yellow" , "none"]

    let outsideDiv = $("<div>")
    outsideDiv.attr("class" , "out")

    let outsideDiv2 = $("<div>")
    outsideDiv2.attr("class" , "out")

    let outsideDiv3 = $("<div>")
    outsideDiv3.attr("class" , "out")

    let outsideDiv4 = $("<div>")
    outsideDiv4.attr("class" , "out")

    let redDiv = $("<div>")
    redDiv.attr("class" , "box red")
    let redDivInput = $("<input>")
    redDivInput.attr("disabled", "disabled");
    redDivInput.attr("value", "0");

    let blueDiv = $("<div>")
    blueDiv.attr("class" , "box blue")
    let blueDivInput = $("<input>")
    blueDivInput.attr("disabled", "disabled");
    blueDivInput.attr("value", "0");

    let yellowDiv = $("<div>")
    yellowDiv.attr("class" , "box yellow")
    let yellowDivInput = $("<input>")
    yellowDivInput.attr("disabled", "disabled");
    yellowDivInput.attr("value", "0");

    let colorlessDiv = $("<div>")
    colorlessDiv.attr("class" , "box none")
    let colorlessDivInput = $("<input>")
    colorlessDivInput.attr("disabled", "disabled");
    colorlessDivInput.attr("value", "0");

    $("body").append(outsideDiv);
    $(outsideDiv).append(redDiv);
    $(outsideDiv).append(redDivInput);

    $("body").append(outsideDiv2);
    $(outsideDiv2).append(blueDiv);
    $(outsideDiv2).append(blueDivInput);

    $("body").append(outsideDiv3);
    $(outsideDiv3).append(yellowDiv);
    $(outsideDiv3).append(yellowDivInput);

    $("body").append(outsideDiv4);
    $(outsideDiv4).append(colorlessDiv);
    $(outsideDiv4).append(colorlessDivInput);

    $("body").append("<br>");

    for (i = 0; i < 10;i++) {
        let colorlessRows = $("<div>");
        colorlessRows.attr("class", "box none")
        colorlessRows.on("click" , function (){
                colorlessRows.attr("class" ,"box "+ Colors[0])
            

        })
        $("body").append(colorlessRows);
    }
    $("body").append("<br>");

     for (i = 0; i < 10;i++) {
        let colorlessRows = $("<div>");
        colorlessRows.attr("class", "box none")
        $("body").append(colorlessRows);
    }
    $("body").append("<br>");

    for (i = 0; i < 10;i++) {
        let colorlessRows = $("<div>");
        colorlessRows.attr("class", "box none")
        $("body").append(colorlessRows);
    }
    $("body").append("<br>");

     for (i = 0; i < 10;i++) {
        let colorlessRows = $("<div>");
        colorlessRows.attr("class", "box none")
        $("body").append(colorlessRows);
    }
     $("body").append("<br>");

     for (i = 0; i < 10;i++) {
        let colorlessRows = $("<div>");
        colorlessRows.attr("class", "box none")  
        $("body").append(colorlessRows);
    }
}