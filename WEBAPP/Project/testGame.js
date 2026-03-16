$(body)

function body() {
    let table = $("<table>");


    player = 1

    let X = "X"
    let O = "O"
    for (let i = 1; i <= 3; i++) {
        let row = $("<tr>");
        for (let j = 1; j <= 3; j++) {
           
            let cell = $("<td>")
            cell.attr( "class" , "cell"); 
            cell.on("click" , function(){
                if(player == 1){
                    cell.html(X)
                    player = 2
                }
                else{
                    cell.html(O)
                    player = 1
                }
                
            })
            row.append(cell);
        }
        table.append(row);
    }
    $("body").append(table);


}
