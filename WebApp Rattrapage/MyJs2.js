$(body)
     let Classmates = ["Rafael" , "Gabriel" , "Maciej" , "Dmytro" , "Ahmad" , "Ian" , "Jona" , "Leandro" , "Edyr" , "Joy" , "Alexander"];

function body(){
    let ul = $("<ul>");


    let list = $("<ul id = 'list'>");
    

    let listingredient1 = $("<li>")
    listingredient1.html("Rafael");
 
    let listingredient2 = $("<li>")
    listingredient2.html("Gabriel");

    let listingredient3 = $("<li>");
    listingredient3.html("Maciej");

    let ulIngredient1 = $("<li>")
    ulIngredient1.html("Dmytro")
    let ulIngredient2 = $("<li>")
    ulIngredient2.html("Alexander")
    let ulIngredient3 = $("<li>")
    ulIngredient3.html("Joy")

    $("body").append(list);
    $("#list").append(listingredient1)
    $("#list").append(listingredient2)
    $("#list").append(listingredient3)
    $("body").append(ul);
    ul.append(ulIngredient1)
    ul.append(ulIngredient2)
    ul.append(ulIngredient3)
    
    let ulClass = $("<ul>")
     for (i = 0; i < Classmates.length; i++) {
        let ulClassmate = $("<li>")
        ulClassmate.html(Classmates[i]);
        ulClass.append(ulClassmate);
    }
    $("body").append(ulClass)
}
