addEventListener("load", function (event) {
    KontrolaListBoxa();
    Ocisti();
    googleTranslateElementInit();

});

function KontrolaListBoxa() {
    var lista = document.getElementById("kategorija");
    var opcije = document.getElementById("kategorije");
    var opcije2 = opcije.options;
    lista.addEventListener("keyup", function (event) {

        for (var i = 0; i < opcije2.length; i++) {
            if (opcije2[i].value === lista.value) {
                //  alert(opcije2[i].value+"===" +lista.value);
                lista.style.backgroundColor = "green";
                break;

            } else {
                lista.style.backgroundColor = "red";

            }

        }
    }, false);

}





function Ocisti() {
    var lista = document.getElementById("kategorija");
    lista.addEventListener("keyup", function (event) {
        if (lista.value.length === 0)
            lista.style.backgroundColor = "white";

    }, false);
}
function googleTranslateElementInit() {
    new google.translate.TranslateElement({pageLanguage: 'hr', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}
