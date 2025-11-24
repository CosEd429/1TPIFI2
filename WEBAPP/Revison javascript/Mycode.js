//Vanilla JS version
// alert("Hey John!")
// function changeH1(){
// document.getElementById("myMessage").innerHTML = "Hello world from Javascript"
// }

// addEventListener("load",changeH1);

//JQuery version:


$(start);

function start() {
    $("#myMessage").on("click", changeH1);
}

function changeH1() {
   //instead of changing the #myMessage I would like to read its contents 
   // display those as an alert message
   // $("#myMessage").html("Hello world from jQuery");
   alert($("#myMessage").html());
}

$(button);

function button(){
    $("button:nth-of-type(1)").on("click" , increment)
    $("button:nth-of-type(2)").on("click" , decrease)
}

function increment(){
    var num = $(".counter").html();
    $(".counter").html(parseInt(num)+ 1);
}

function decrease(){
    var num = $(".counter").html();
    $(".counter").html(parseInt(num)- 1);
}