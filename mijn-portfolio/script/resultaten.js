

// Array met technische vakken
var vakken = ["Realiseren", "Computervaardigheden", "Testen en verbeteren", "Plannen en ontwerpen"];
var cijfers = [7.4, 7.7, 7.1, 7.5];
var huiswerk = [10, 9.0, 7.8, 8.2];
var project = [7.1, 6.6, 6.7, 7.0];


// Functie om de tabellen te tonen
function ShowTabel() {
    for (var i = 0; i < vakken.length; i++) {
        document.getElementById("vakkenLijst").innerHTML += "<li>" + vakken[i] + " = " + cijfers[i] + "</li>";
        document.getElementById("huiswerkLijst").innerHTML += "<li>" + vakken[i] + " = " + huiswerk[i] + "</li>";
        document.getElementById("projectLijst").innerHTML += "<li>" + vakken[i] + " = " + project[i] + "</li>";
    }
}


// Functie om het gemiddelde van drie cijfers te berekenen
function ShowGemiddelde(cijfer, huiswerkCijfer, projectCijfer) {
    return ((cijfer + huiswerkCijfer + projectCijfer) / 3).toFixed(1);
}

// Functie om de gemiddelden te berekenen en weer te geven
function bereken() {
    // Maak de gemiddelde lijst leeg voordat je nieuwe items toevoegt
    document.getElementById("gemiddeldeLijst").innerHTML = "";

    for (var i = 0; i < vakken.length; i++) {
        // Bereken het gemiddelde van het vak
        var gemiddelde = ShowGemiddelde(cijfers[i], huiswerk[i], project[i]);

        // Voeg het gemiddelde toe aan de lijst
        document.getElementById("gemiddeldeLijst").innerHTML += "<li>" + vakken[i] + " = " + gemiddelde + "</li>";
    }


   
}



