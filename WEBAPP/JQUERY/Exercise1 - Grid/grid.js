$(start);

function inputCreation(inputName) {
    let newInput = $("<input>");
    newInput.attr("placeholder", inputName);
    newInput.attr("type", "number");
    $("body").append(newInput);
    return newInput;
}

function validation(numRows, numCols, targetX, targetY) {
    if (targetX < 1) return false;
    if (targetX > numCols) return false;
    if (targetY < 1) return false;
    if (targetY > numRows) return false;

    return true;
}

function start() {
    let Rows = inputCreation("Rows");
    let Columns = inputCreation("Columns");
    $("body").append("<br>");
    let TargetX = inputCreation("TargetX");
    let TargetY = inputCreation("TargetY");
    $("body").append("<br>");
    let buttonCreate = $("<button>");
    buttonCreate.html("Create Grid");
    $("body").append(buttonCreate);

    buttonCreate.on("click", function () {
        r = Number(Rows.val());
        c = Number(Columns.val());
        tx = Number(TargetX.val());
        ty = Number(TargetY.val());
        if (!validation(r, c, tx, ty)) {
            alert("Bad inputs");
        }
        else {
            // alert("Good inputs.")
            let myTable = $("<table>");
            $("body").html("");
            for(let i = 1; i <=r; i++ ){
                let newRow = $("<tr>");
                for(let j = 1; j<=c; j++){
                    let newCell = $("<td>");
                    newCell.html("click");
                    newCell.on("click" , function(){
                        if(i == ty && j == tx) alert("yes");
                        else alert("no");
                    });
                    newRow.append(newCell);
                }
                myTable.append(newRow);
                $("body").append(myTable)
                
            }
            let resetButton = $("<button>");
                $("body").append(resetButton);
                resetButton.html("Reset");
                resetButton.on("click" , function(){
                    $("body").html("");
                    start();
                });
        }
    });
}