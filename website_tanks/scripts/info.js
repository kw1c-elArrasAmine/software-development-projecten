/*
Auteurs:        Amine, Abel, Jort
Aanmaakdatum:   10-10-2024
*/
var winst = "Deze Pokémon wint!";
var verlies = "Deze Pokémon verliest! Probeer het nog een keer.";
var geen = "Dit is geen naam. Probeer het nog een keer";


function controleerRol() {
    var vraag = prompt("Bent u medewerker of student?").toLowerCase();
    var medew = "medewerker".toLowerCase
    var stud = "student".toLowerCase

    if( vraag == medew)
    {
       document.getElementById("medewe").innerHTML("Welkom medewerker")  
    }
    
}
