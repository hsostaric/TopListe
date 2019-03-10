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
                <h2>Nova top lista</h2>
                <form novalidate id = 'prijedlog' method = 'post' name = 'form1' action ='<?php echo"noveTopListe.php?kategorija=".$_GET['kategorija'].""?>'>
                    <div class='row'>
                        <div class='col-25'>
                            <label class='labele' for='email'>Naziv top liste: </label>
                        </div>
                        <div class='col-75'>
                            <input class='textboxovi' type='text' id='pjesma' name='nazivListe'  placeholder='Unesite naslov skladbe' >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label class="labele" for="url">Početak predlaganja pjesama</label>
                        </div>
                        <div class="col-75">
                            <input class="textboxovi" type="datetime-local" id="planiraniPocetak" name="pocetakPredlaganja" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label class="labele" for="url">Završetak predlaganja pjesama</label>
                        </div>
                        <div class="col-75">
                            <input class="textboxovi" type="datetime-local" id="zavrseniPocetak" name="zavrsetakPredlaganja" >
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-25">
                            <label class="labele" for="url">Početak glasovanja pjesama</label>
                        </div>
                        <div class="col-75">
                            <input class="textboxovi" type="datetime-local" id="planiraniPocetak" name="pocetakGlasovanja" >
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-25">
                            <label class="labele" for="url">Zavrsetak glasovanja pjesama</label>
                        </div>
                        <div class="col-75">
                            <input class="textboxovi" type="datetime-local" id="planiraniPocetak" name="zavrsetakGlasovanja" >
                        </div>
                    </div>




                    <div class='row'>
                        <input  type='submit' name='dodaj' value='Dodaj top listu' style="background-color: blue;width: 30%;">
                    </div>

                    <?php
                    if (isset($_POST['dodaj'])) {
                        $idKat = $_GET['kategorija'];
                        $naziv = $_POST['nazivListe'];
                        $pocetakPrijedloga = date("Y-m-d H:i:s", strtotime($_POST['pocetakPredlaganja']));
                        $zavrsetakPrijedloga = date("Y-m-d H:i:s", strtotime($_POST['zavrsetakPredlaganja']));
                        $pocetakGlasovanja = date("Y-m-d H:i:s", strtotime($_POST['pocetakGlasovanja']));
                        $zavrsetakGlasovanja = date("Y-m-d H:i:s", strtotime($_POST['zavrsetakGlasovanja']));
                        $veza = new Baza();
                        $veza->spojiDB();
                        $sql = "Insert into top_lista values('default','$naziv','$pocetakGlasovanja','$zavrsetakGlasovanja','$pocetakPrijedloga','$zavrsetakPrijedloga','$idKat');";
                        $veza->updateDB($sql);
                        $datum = dohvatiVirtualnoVrijeme();
                        $radnja = "Kreiranje nove top liste";
                        $dnevnik = "Insert into `dnevnik_rada` values('default','{$_SESSION["korime"]}','$datum','$radnja')";
                        $veza->updateDB($dnevnik);
                        $veza->zatvoriDB();
                       
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

