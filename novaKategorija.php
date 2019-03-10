<?php
include './baza.class.php';
include './funkcije.php';
include './sesija.class.php';
$sesija = new Sesija();
$sesija::kreirajSesiju();
if (isset($_SESSION['id']) && $_SESSION['id'] == 9) {
    header("Location:pocetak.php");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Početna</title>';
        <meta charset="utf-8">
        <meta name="viewport" content="width = device-width, initial-scale = 1.0">
        <link href="css/hsostaric.css" rel="stylesheet" type="text/css">
         <link href="css/hsostaric_prilagodbe.css" rel="stylesheet" type="text/css">
        <meta name="Naziv" content="Početna stranica">
        <meta name="Datum posljednje promjene" content="17.03.2018.">
        <meta name="Autor" content="Hrvoje Šoštarić">
        <script type="text/javascript" src="js/provjera.js"></script>
        <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        <script src="js/hsostaric.js" type="text/javascript"></script>

    </head>
    <body>

        <header>
          <h1 class="naslovniTekst">Top Liste</h1> 

            <?php
            if (!isset($_SESSION['korime'])) {
                echo '<nav class="obicniTekst"> <a  href="prijava.php">Prijava</a></nav>';
            } else {
                echo $_SESSION['korime'];
                echo '<nav class="obicniTekst"> <a  href="odjava.php">Odjava</a></nav>';
            }
            ?>


        </header>
        <nav class="izgledNavigacije">

            <a class="izgledUnutrasnjostiNavigacije" href="pocetak.php">Početna stranica </a>
          
            <?php
            if (!isset($_SESSION['korime'])) {
                echo "<a class='izgledUnutrasnjostiNavigacije' href='prijava.php'>Prijava</a>";
                echo "<a class='izgledUnutrasnjostiNavigacije'href='registracija.php'>Registracija</a>";
            } else {


                echo "<a class='izgledUnutrasnjostiNavigacije' href='odjava.php'>Odjava</a>";
            }
            ?>



            <a class="izgledUnutrasnjostiNavigacije" href="privatno/korisnici.php">Korisnici</a>
            <a class="izgledUnutrasnjostiNavigacije" href="pocetna.php">Početna za registrirane</a
            <a class="izgledUnutrasnjostiNavigacije" href="prijedlogPjesme.php">Predloži pjesmu</a>
            <?php
            if (isset($_SESSION['id']) && $_SESSION['uloga'] >= 7) {
                echo "<a class='izgledUnutrasnjostiNavigacije' href='oglasiReg.php'>Oglasi</a> ";
            }
            ?>
            <?php
            if (isset($_SESSION['id']) && $_SESSION['uloga'] == 9) {
                echo"<a class='izgledUnutrasnjostiNavigacije'href='otkljucavanjeKorisnika.php'>Rad s korisnicima</a>";
                echo"<a class='izgledUnutrasnjostiNavigacije'href='konfiguracijaSustava.php'>Konfiguracija sustava</a>";
                echo"<a class='izgledUnutrasnjostiNavigacije'href='dnevnikRada.php'>Dnevnik</a>";
                echo"<a class='izgledUnutrasnjostiNavigacije'href='kategorije.php'>Kategorije</a>";
            }
            ?>
            <a class='izgledUnutrasnjostiNavigacije'href='vremeplov.php'>Virtualno vrijeme</a>

        </nav>

        <section> 
            <div id="google_translate_element"></div>
            <div class="pozicija2">
                <h2>Obrazac nove kategorije</h2>
                <form novalidate id = 'prijedlog' method = 'post' name = 'form1' action ='novaKategorija.php'>
                    <div class='row'>
                        <div class='col-25'>
                            <label class='labele' for='email'>Naziv kategorije: </label>
                        </div>
                        <div class='col-75'>
                            <input class='textboxovi' type='text' id='pjesma' name='kategorija'  placeholder='Unesite naziv kategorije' >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label class="labele" for="opiskategorije">Opis kategorije</label>
                        </div>
                        <div class='col-75'>
                            <textarea class="odredjeno"  rows="40" cols="60" id="nazivoglasa" name="opKat"  placeholder="Unesite opis kategorije"></textarea>
                        </div>
                    </div>

            


        <div class='row'>
            <input  type='submit' name='dodajKategoriju' value='Kreiraj' style="background-color: blue;width: 10%;">
        </div>
        <?php
        if (isset($_POST['dodajKategoriju'])) {
            $nazivKategorije = $_POST['kategorija'];
            $opisKategorije = $_POST['opKat'];
            $veza = new Baza();
            $veza->spojiDB();
            $sql = "Insert into kategorija values('default','$nazivKategorije','$opisKategorije')";
            $veza->updateDB($sql);

            $radnja = "Dodana nova kategorija";
            $datum = dohvatiVirtualnoVrijeme();
            $kor = $_SESSION["korime"];
            $dnevnik = "Insert into `dnevnik_rada` values('default','$kor','$datum','$radnja')";
            $veza->updateDB($dnevnik);
            $veza->zatvoriDB();
            header("Location:kategorije.php");
        }
        ?>  





    </form>
</div>



</section>

<footer id = "podnozje">
    <address>Kontakt:
        <a href = "mailto:hsostaric@foi.hr">Hrvoje Šoštarić</a>
    </address>

</footer>

</body>
</html>

