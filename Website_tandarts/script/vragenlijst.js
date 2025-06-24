

function Resultaatvragenlijst() {
    var score = 0; // Variabele 'score' wordt ingesteld op 0 om de juiste antwoorden te tellen.
    var antwoorden = document.querySelectorAll("input[type='radio']:checked"); 
    var i = 0; // Teller voor de while-loop

    while (i < antwoorden.length) {
        if (antwoorden[i].value === "goed") {  
            score++;  
        }
        i++; // Verhoog de teller om naar het volgende antwoord te gaan
    }
    
  
    var resultaatTekst = ""; // Variabele 'resultaatTekst' wordt aangemaakt om de juiste boodschap weer te geven.
  
    // Controleert de behaalde score en wijst de juiste boodschap toe.
    if (score === 4) {
        resultaatTekst = "Fantastisch! Je tanden zijn in topconditie!";
    } else if (score === 3) {
        resultaatTekst = "Goed bezig! Maar er is nog ruimte voor verbetering.";
    } else if (score === 2) {
        resultaatTekst = "Let op! Je tandverzorging kan beter.";
    } else if (score == 1) {
        resultaatTekst = "Je tandgezondheid is slecht. Bezoek een tandarts en verbeter je routine!";
    }
  
    document.getElementById("resultaat").textContent = resultaatTekst;  
    // Zoekt het HTML-element met id "resultaat" en zet daar de gegenereerde tekst in.
  }
  