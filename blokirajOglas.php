<?php
include './baza.class.php';
include './funkcije.php';

include './sesija.class.php';
$sesija = new Sesija();
$sesija::kreirajSesiju();
?>
<?php
                    if (isset($_POST['predajZahtjev'])) {
                        $veza = new Baza();
                        $veza->spojiDB();
                        $idOglas = $_POST['blokiraniOglas'];
                        $razlog = $_POST['razlogBloka'];
                        $id = $_SESSION['id'];
                        $sql = "INSERT INTO zahtjev_za_blokiranje values('default','$razlog','$id','$idOglas')";
                        $veza->updateDB($sql);
                       
                        $sad = dohvatiVirtualnoVrijeme();
                        $radnja = "Poslao zahtjev za blokiranjem aktivnog oglasa";
                        $kor = $_SESSION["korime"];
                        $dnevnik = "Insert into `dnevnik_rada` values('default', '$kor', '$sad', '$radnja')";
                        $veza->updateDB($dnevnik);
                        $veza->zatvoriDB();
                       header("Location:oglasiReg.php");
                    }
                    ?>

<!DOCTYPE html>


<html>
    <head>
        <title>Blokiranje oglasa</title>;
        <meta charset="utf-8">
        <meta name="viewport" content="width = device-width, initial-scale = 1.0">
        <link href="css/hsostaric.css" rel="stylesheet" type="text/css">
        <meta name="Naziv" content="Početna stranica">
        <meta name="Datum posljednje promjene" content="17.03.2018.">
        <meta name="Autor" content="Hrvoje Šoštarić">
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
            <a class="izgledUnutrasnjostiNavigacije" href="pocetna.php">Početna za registrirane</a>
            <a class="izgledUnutrasnjostiNavigacije" href="prijedlogPjesme.php">Predloži pjesmu</a>
            <?php
            if (isset($_SESSION['id']) && $_SESSION['id'] >= 7) {
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
                <h2>Obrazac za blokiranje oglasa</h2>
                <form novalidate id = 'prijava'  method = 'post' name = 'form1' action ='blokirajOglas.php'>

                    <div class="row">
                        <div class="col-25">
                            <label class="labele" for="nazivoglasa">Naziv oglasa </label>
                        </div>
                        <div class="col-75">
                            <?php
                            $veza = new Baza();
                            $veza->spojiDB();
                            $datum = dohvatiVirtualnoVrijeme();
                            $sql = "Select *from oglas where odobren='1' and blokiran='0' and '$datum' between pocetak_prikazivanja and zavrsetak_prikazivanja";
                            $rezultat = $veza->selectDB($sql);
                            $veza->zatvoriDB();
                            echo "<select name='blokiraniOglas' class='obrazac'>";
                            while ($red = $rezultat->fetch_assoc()) {
                                echo"<option name='opcija' value='" . $red['id_zahtjev_za_oglas'] . "'>" . $red['naziv'] . "</option>";
                            }
                            echo "</select>";
                            ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-25">
                            <label class="labele" for="opisoglasa">Razlog blokiranja</label>
                        </div>

                        <div class='col-75'>
                            <textarea class="odredjeno"  rows="40" cols="60" id="opisoglasa" name="razlogBloka"  placeholder="Unesite razlog blokiranja"></textarea>
                        </div>
                    </div>




                    <div class="row">
                        <input name="predajZahtjev" type="submit" value="Potvrdi" id="potvrda">
                    </div>
                    



            </div>


        </form>



    </section>

    <footer id = "podnozje">
        <address>Kontakt:
            <a href = "mailto:hsostaric@foi.hr">Hrvoje Šoštarić</a>
        </address>

    </footer>

</body>
</html>
