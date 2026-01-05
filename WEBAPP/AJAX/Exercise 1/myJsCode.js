$(start);

function start() {
    let selectOfCountries = $("<select>");
    $("body").append(selectOfCountries);
    let btnShowCities = $("<button>");
    btnShowCities.html("Show Cities");
    $("body").append(btnShowCities);
    $.get()
}