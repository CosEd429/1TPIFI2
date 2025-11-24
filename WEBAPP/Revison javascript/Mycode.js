//Vanilla JS version
// alert("Hey John!")
// function changeH1(){
// document.getElementById("myMessage").innerHTML = "Hello world from Javascript"
// }

// addEventListener("load",changeH1);

//JQuery version:

$(start);

function start() {
  /*
    ADD html to the DOM tree:
    <label> Please type A: <input id="NumberA" /></label>
    <label> Please type B: <input id= "NumberB"/></label>
    <button id = "Add">Add</button>
    <div id = "Result"></div>
    */
  //<label>Please type A:</label>
  //$("#myMessage").on("click", changeH1);
  let LabelElementA = $("<label>");
  LabelElementA.html("Please type A:");
  $("body").append(LabelElementA);
  let InputA = $("<input>");
  InputA.attr("id", "NumberA");
  InputA.attr("type" , "number");
  $("body").append(InputA);

   let LabelElementB = $("<label>");
   LabelElementB.html("Please type B:");
   $("body").append(LabelElementB);
   let InputB = $("<input>");
   InputB.attr("id", "NumberB");
   InputB.attr("type" , "number");
    $("body").append(InputB);

    let buttonElement = $("<button>");
    buttonElement.html("Add");
    buttonElement.attr("id", "Add");
    $("body").append(buttonElement);

    let divElement = $("<div>");
    divElement.attr("id" , "Result");
    $("body").append(divElement);
}

function changeH1() {
  //instead of changing the #myMessage I would like to read its contents
  // display those as an alert message
  // $("#myMessage").html("Hello world from jQuery");
  alert($("#myMessage").html());
}

$(button);

function button() {
  $("button:nth-of-type(1)").on("click", increment);
  $("button:nth-of-type(2)").on("click", decrease);
}

function increment() {
  var num = $(".counter").html();
  $(".counter").html(parseInt(num) + 1);
}

function decrease() {
  var num = $(".counter").html();
  $(".counter").html(parseInt(num) - 1);
}

function AddFunction() {
  let NumA = Number($("#NumberA").val());
  let NumB = Number($("#NumberB").val());
  $("#Result").html(NumA + NumB);
}
