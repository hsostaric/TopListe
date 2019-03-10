<?php
include './baza.class.php';
include './sesija.class.php';
$sesija= new Sesija();
$sesija::kreirajSesiju();
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Početna</title>';
        <meta charset="utf-8">
        <meta name="viewport" content="width = device-width, initial-scale = 1.0">
        <link href="css/hsostaric.css" rel="stylesheet" type="text/css">
        <meta name="Naziv" content="Početna stranica">
        <meta name="Datum posljednje promjene" content="17.03.2018.">
        <meta name="Autor" content="Hrvoje Šoštarić">
        <script type="text/javascript" src="js/provjera.js"></script>

    </head>
    <body>

        <header>
            <h1 class="naslovniTekst"> Domaća zadaća 1</h1> 
           
            <?php if(!isset($_SESSION)){
     echo '<nav class="obicniTekst"> <a  href="prijava.php">Prijava</a></nav>';
            }
            else{
                echo $_SESSION['korime'];
                echo '<nav class="obicniTekst"> <a  href="odjava.php">Odjava</a></nav>';
            }?>
        </header>
        <nav class="izgledNavigacije">

            <a class="izgledUnutrasnjostiNavigacije" href="index.html">Početna stranica </a>
            <a class="izgledUnutrasnjostiNavigacije" href="galerija.html">Galerija slika</a>
            <a class="izgledUnutrasnjostiNavigacije" href="prijava.php">Prijava</a>
            <a class="izgledUnutrasnjostiNavigacije" href="registracija.php">Registracija</a>
            <a class="izgledUnutrasnjostiNavigacije" href="obrazac.php">Obrazac</a>
            <a class="izgledUnutrasnjostiNavigacije" href="tablica.php">Tablica</a>
            <a class="izgledUnutrasnjostiNavigacije" href="privatno/korisnici.php">Korisnici</a>
            <a class="izgledUnutrasnjostiNavigacije" href="pocetna.php">Početna za registrirane</a>

        </nav>

        <section> 

            <p>Ovo je kao početna stranica za registrirane korisnike</p>>





        </section>

        <footer id = "podnozje">
            <address>Kontakt:
                <a href = "mailto:hsostaric@foi.hr">Hrvoje Šoštarić</a>
            </address>

        </footer>

    </body>
</html>
