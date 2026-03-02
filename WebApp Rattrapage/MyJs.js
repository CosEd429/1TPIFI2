$(Bla)

function Bla(){
    //alert("Hello");
    let input = $("<input type = 'text'>");
    $("body").append(input);

    let pTag = $("<p>");
    pTag.html("Hello");
    $("body").append(pTag);

    let button = $("<button>")
    button.html("Click on me")
    $("body").append(button);

    $("body").append("<br>");

    let aLink = $("<a href = 'https://www.w3schools.com/'>");
    aLink.html("W3schools a tag link");
    $("body").append(aLink);

    let DivEdyr = $("<div id = 'Div1'>");
    $("body").append(DivEdyr);

    let h1 = $("<h1>")
    h1.html("Hi");
    $(DivEdyr).append(h1);
}