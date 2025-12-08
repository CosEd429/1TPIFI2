$(start);
let Options = ["Carrots", "Potatoes", "Rice", "Milk"];
function start() {
    let currentRecipeList = 0;
    let recipeName = $("<input>");
    recipeName.attr("type", "text");
    $("body").append(recipeName);
    let createRecipe = $("<button>");
    createRecipe.html("Create Recipe");
    createRecipe.on("click", function(){
        let newRecipe = $("<div>")
        newRecipe.append(recipeName.val());
        $("body").append(newRecipe);
        let newRecipeIngredients = $("<ul>");
        newRecipe.append(newRecipeIngredients);
       currentRecipeList = newRecipeIngredients;
    })
    $("body").append(createRecipe);
    let ingredientList = $("<select>");
    for (i = 0; i < Options.length; i++) {
        let newIngredient = $("<option>")
        newIngredient.attr("id", Options[i]);
        newIngredient.html(Options[i]);
        ingredientList.append(newIngredient);
    }
    $("body").append("<br>");
    $("body").append(ingredientList);
    let quantity = $("<input>");
    quantity.attr("type", "number");
    $("body").append(quantity);
    let addToRecipe = $("<button>");
    addToRecipe.html("Add to recipe");
    $("body").append(addToRecipe);

    addToRecipe.on("click" , function(){
        if(currentRecipeList !=0){
            let newIngredient = $("<li>");
            newIngredient.html(ingredientList.val()  + " - " + quantity.val());
            currentRecipeList.append(newIngredient);
            quantity.val("");
        }
        else{
            alert("Error: Recipe was not created");
        }
    })





}