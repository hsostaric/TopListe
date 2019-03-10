
<?php
include './sesija.class.php';

include './baza.class.php';
include './funkcije.php';
$sesija = new Sesija();
$veza = new Baza();

$datum = dohvatiVirtualnoVrijeme();
$radnja="Odjava sa sustava";
$sesija::kreirajSesiju();
$dnevnik = "Insert into `dnevnik_rada` values('default','{$_SESSION["korime"]}','$datum','$radnja')";
$veza->spojiDB();
$veza->updateDB($dnevnik);
$veza->zatvoriDB();
$sesija::obrisiSesiju();
?>
<!DOCTYPE html>
<html>

    <head>
        <title>Odjava</title>';
        <meta charset="utf-8">
        <meta name="viewport" content="width = device-width, initial-scale = 1.0">
        <link href="css/hsostaric.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        <script src="js/hsostaric.js" type="text/javascript"></script>
        <meta name="Naziv" content="Odjava">
    </head>
    <body>

        <header></header>
        <section> 
            <div class="odjava">
                <p>Uspješno ste se odjavili sa stranice, za ponovno prijavljivanje pritisnite <a  href="prijava.php">ovdje</a>.</p>
            </div>
        </section>

        <footer id = "podnozje">
            <address>Kontakt:
                <a href = "mailto:hsostaric@foi.hr">Hrvoje Šoštarić</a>
            </address>

        </footer>

    </body>
</html>


