<?php
include './baza.class.php';
include './funkcije.php';
include './sesija.class.php';
$sesija = new Sesija();
$sesija::kreirajSesiju();
?>

<!DOCTYPE html>


<html>
    <head>
        <title>Vrste oglasa</title>';
        <meta charset="utf-8">
        <meta name="viewport" content="width = device-width, initial-scale = 1.0">
        <link href="css/hsostaric.css" rel="stylesheet" type="text/css">
         <link href="css/hsostaric_prilagodbe.css" rel="stylesheet" type="text/css">
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

                <h2>Obrazac kreiranja vrste oglasa</h2>
                <form novalidate id = 'prijava' enctype="multipart/form-data" method = 'post' name = 'form1' action ='<?php $_SERVER['PHP_SELF'] ?>'>

                    <?php
                    if (isset($_POST['predajOglas'])) {
                        $naziv = $_POST['nazivVrste'];
                        $cijena = $_POST['cijenaOglasa'];
                        $trajanjeOglasa = $_POST['trajanjeOglasa'];
                        $pozicija = $_POST['pozicijaOglasa'];
                        $brzina = $_POST['brzinaOglasa'];
                        $veza = new Baza();
                        $veza->spojiDB();
                        $unos = "Insert into vrsta_oglasa values('default','$naziv','$trajanjeOglasa','$brzina','$cijena','$pozicija')";
                        $veza->updateDB($unos);
                        $radnja = "Kreirana nova pozicija oglasa";
                        $datum = dohvatiVirtualnoVrijeme();
                        $kor = $_SESSION["korime"];
                        $dnevnik = "Insert into `dnevnik_rada` values('default','$kor','$datum','$radnja')";
                        $veza->updateDB($dnevnik);
                        $veza->zatvoriDB();
                       
                    }
                    ?>

                    <div class="row">
                        <div class="col-25">
                            <label class="labele" for="nazivoglasa">Naziv vrste </label>
                        </div>
                        <div class="col-75">
                            <input class="textboxovi" type="text" id="nazivVrste" name="nazivVrste"  placeholder="Unesite naziv vrste">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-25">
                            <label class="labele" for="opisoglasa">Cijena oglasa</label>
                        </div>

                        <div class='col-75'>
                            <input class="textboxovi" type="text" id="cijenaOglasa" name="cijenaOglasa"  placeholder="Unesite cijenu oglasa">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-25">
                            <label class="labele" for="url">Trajanje oglasa u satima</label>
                        </div>
                        <div class="col-75">
                            <input class="textboxovi" type="text" id="trajanjeOglasa" name="trajanjeOglasa"  placeholder="Unesite trajanje oglasa">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label class="labele" for="url">Brzina izmjene oglasa</label>
                        </div>
                        <div class="col-75">
                            <input class="textboxovi" type="text" id="brzinaOglasa" name="brzinaOglasa"  placeholder="Unesite brzinu izmjene oglasa">
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-25">
                            <label  for="ogranicavanje">Pozicija oglasa</label>
                        </div>
                        <div class="col-75">

                            <?php
                            $veza = new Baza();
                            $veza->spojiDB();
                            $id = $_SESSION['id'];
                            $sql = "Select *from pozicija_prikaza where korisnik_id='" . $id . "'";

                            $rezultat = $veza->selectDB($sql);
                            echo"<select name='pozicijaOglasa' class='obrazac'>";
                            while ($red = $rezultat->fetch_assoc()) {
                                echo "<option value='" . $red['id_pozicija_prikaza'] . "'>" . $red['id_pozicija_prikaza'] . "</option>";
                            }
                            echo"</select>";
                            ?>
                        </div>


                    </div>

                    <div class="row">
                        <input name="predajOglas" type="submit" value="Predaj oglas" id="potvrda">
                    </div>




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

