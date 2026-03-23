$(body)

function body (){
    
    let initialButton = $("<button>")
    initialButton.html("start")
    initialButton.on("click" , function (){
        let inputRow = $("<input>")
         $("body").html("")
        for(i = 10 ; i < 60 ; i = i + 10){
            let button = $("<button>")
            button.html(i)
            button.on("click" , function(){
                inputRow.val("");
                buttonparsed = parseInt(button.html())
                inputRow.val(buttonparsed)
                $("body").append(inputRow)
            })
            $("body").append(button) 
        }
        $("body").append(inputRow)
        
    })
    $("body").append(initialButton)
}


// Without using a for loop
        /* $("body").html("")
        let inputRow = $("<input>")
        let button10 = $("<button>")
        button10.html("10")
        button10.on("click" , function(){
            inputRow.val("");
            button10parsed = parseInt(button10.html());
            inputRow.val(button10parsed)
            $("body").append(inputRow)
        })
        let button20 = $("<button>")
        button20.html("20")
        button20.on("click" , function(){
            inputRow.val("");
            button20parsed = parseInt(button20.html());
            inputRow.val(button20parsed)
            $("body").append(inputRow)
        })
        let button30 = $("<button>")
        button30.html("30")
        button30.on("click" , function(){
            inputRow.val("");
            button30parsed = parseInt(button30.html());
            inputRow.val(button30parsed)
            $("body").append(inputRow)
        })
        let button40 = $("<button>")
        button40.html("40")
        button40.on("click" , function(){
            inputRow.val("");
            button40parsed = parseInt(button40.html());
            inputRow.val(button40parsed)
            $("body").append(inputRow)
        })
        let button50 = $("<button>")
        button50.html("50")
        button50.on("click" , function(){
            inputRow.val("");
            button50parsed = parseInt(button50.html());
            inputRow.val(button50parsed)
            $("body").append(inputRow)
        }) 
        $("body").append(inputRow , button10 , button20 , button30 , button40 , button50);*/
