<?php
$mod = $_GET['kat'];
if (!isset($mod)) {
    header("Location:pocetak.php");
} else {
    include './baza.class.php';
    include './funkcije.php';
    include './sesija.class.php';
    $sesija = new Sesija();
    $sesija::kreirajSesiju();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Dodjela moderatora</title>';
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
                <h2>Dodijeli moderatora</h2>
                <form novalidate id = 'prijedlog' method = 'post' name = 'form1' action="<?php echo $_SERVER['PHP_SELF'] . "?kat=" . $_GET['kat'] . ""; ?>">
                    <div class='row'>
                        <div class='col-25'>
                            <label class='labele' for='email'>Korisnik: </label>
                        </div>
                        <div class='col-75'>
                            <?php
                            $veza = new Baza();
                            $veza->spojiDB();
                            $mod = $_GET['kat'];
                            $sql = "SELECT  * FROM `korisnik` k where k.id_korisnik not in(select mk.korisnik_id from moderator_kategorije mk where mk.kategorija_id='" . $mod . "' ) and Aktiviran='1' and Blokiran='0';";
                            $rezultat = $veza->selectDB($sql);
                            $veza->zatvoriDB();
                            echo "<select name='korisnici' class='obrazac'>";
                            while ($red = $rezultat->fetch_assoc()) {
                                echo"<option name='user' value='" . $red['id_korisnik'] . "'>" . $red['korisnicko_ime'] . "</option>";
                            }
                            echo "</select>";
                            ?>
                        </div>
                    </div>
                    <div class='row'>
                        <input  type='submit' name='dodajModeratora' value='Dodijeli' style="background-color: blue;width: 20%;">
                    </div>
                    <?php
                    if (isset($_POST['dodajModeratora'])) {
                        $kategorijica = $_GET['kat'];
                        $korisnikID = $_POST['korisnici'];
                        $veza = new Baza();
                        $veza->spojiDB();
                        $sql = "Select * from korisnik where id_korisnik='" . $korisnikID . "'";
                        $user = $veza->selectDB($sql);
                        $user2 = $user->fetch_assoc();
                        $osam = '8';
                        echo $korisnikID;
                        $sql2 = "UPDATE `korisnik` SET `tip_korisnika_id` = '8' WHERE `korisnik`.`id_korisnik` = '" . $korisnikID . "';";
                        $veza->updateDB($sql2);
                        $dodavanje = "Insert into moderator_kategorije values('$korisnikID','$kategorijica')";
                        $veza->updateDB($dodavanje);
                        $radnja = "Moderator je dodjeljen kategoriji";
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

