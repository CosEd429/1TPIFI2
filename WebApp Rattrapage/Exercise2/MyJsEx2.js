$(body)

let options = ["A" , "B" , "C" , "D" , "E" , "F" , "G"]

function body(){
    let input = $("<input type = 'text' >")
    $("body").append(input)

    let div = $("<div>")
    let pText = $("<p>")
    pText.html("This is written from inside the div.")
    $("body").append(div)
    $(div).append(pText)



    let selectListArray = $("<select>")
      for (i = 0; i < options.length; i = i + 2) {
        let optionsarray = $("<option>")
        optionsarray.html(options[i]);
        selectListArray.append(optionsarray);
    }
    $("body").append(selectListArray)

    let button = $("<button> Click on me</button>");
    button.attr("id" , "changeling")
    button.on("click",  function(){
    button.html("I was clicked")
    })
     $("body").append(button);

    let alertButton = button.html()
    //alert(alertButton)

    let buttonWithoutID = $("<button>")
    buttonWithoutID.html("I will not change")
    $("body").append(buttonWithoutID)


}


