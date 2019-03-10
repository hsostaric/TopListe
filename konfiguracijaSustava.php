<?php
include './funkcije.php';
include './sesija.class.php';
$sesija = new Sesija();
$sesija::kreirajSesiju();
?>
<!DOCTYPE html>
<html>

    <head>
        <title>Rad s korisnicima</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width = device-width, initial-scale = 1.0">
        <link href="css/hsostaric.css" rel="stylesheet" type="text/css">
         <link href="css/hsostaric_prilagodbe.css" rel="stylesheet" type="text/css">
        <meta name="Naziv" content="Početna stranica">
        <meta name="Datum posljednje promjene" content="17.03.2018.">
        <meta name="Autor" content="Hrvoje Šoštarić">
        <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        <script src="js/hsostaric.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/provjera.js"></script>

    </head>
    <body>

        <header>
            <h1 class="naslovniTekst">Top Liste</h1> 

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

            <a class="izgledUnutrasnjostiNavigacije" href="prijedlogPjesme.php">Predloži pjesmu</a>
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
            <h2 style='text-align: center;'>Konfiguracija sustava </h2>
            <div class = 'pozicija' >
                <form  novalidate id='konfiguracija' method='post' name='form2' action="konfiguracijaSustava.php">
                    <div class='row'>
                        <div class='col-25'>
                            <label class='labele' for='sesija'>Trajanje sesije(h): </label>
                        </div>
                        <div class='col-75'>
                            <input class='textboxovi' type='text' id='sesija' name='sesija'  placeholder='Unesite trajanje sesije(h)' value='<?php echo trajanjeSesije(); ?>' >
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-25'>
                            <label class='labele' for='kolacic'>Trajanje kolačića(h): </label>
                        </div>
                        <div class='col-75'>
                            <input class='textboxovi' type='text' id='kolacic' name='kolacic'  placeholder='Unesite trajanje kolačića(h)' value='<?php echo trajanjeKolacica() ?>' >
                        </div>
                    </div>

                    <div class='row'>
                        <div class='col-25'>
                            <label class='labele' for='lozinka'>Duljina nove lozinke: </label>
                        </div>
                        <div class='col-75'>
                            <input class='textboxovi' type='text' id='lozinka' name='lozinka'  placeholder='Unesite duljinu lozinke' value='<?php echo duljinaLozinke(); ?>' >
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-25'>
                            <label class='labele' for='pokusaj'>Broj pokušaja neispravne prijave </label>
                        </div>
                        <div class='col-75'>
                            <input class='textboxovi' type='text' id='pokusaj' name='pokusaj'  placeholder='Unesite novi broj pokušaja' value='<?php echo brojPokusaja(); ?>' >
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-25'>
                            <label class='labele' for='aktivacija'>Trajanje aktivacijskog koda(h):</label>
                        </div>
                        <div class='col-75'>
                            <input class='textboxovi' type='text' id='aktivacija' name='aktivacija'  placeholder='Unesite trajanje aktivacijskog koda' value='<?php echo trajanjeAktivacijskog(); ?>' >
                        </div>
                    </div>

                    <div class='row'>
                        <input  type='submit' name='konfiguriraj' value='Ažuriraj'>
                    </div>
                    <?php
                    if ((isset($_POST['konfiguriraj']))) {
                        if (!empty($_POST['sesija']) && !empty($_POST['kolacic']) && !empty($_POST['lozinka']) && !empty($_POST['pokusaj']) && !empty($_POST['aktivacija'])) {
                            $veza = new Baza();
                            $veza->spojiDB();
                            $sesija = $_POST['sesija'];
                            $kolacic = $_POST['kolacic'];
                            $duljina = $_POST['lozinka'];
                            $pokusaji = $_POST['pokusaj'];
                            $akt = $_POST['aktivacija'];
                            $sql = "UPDATE Konfiguracija SET trajanje_Sesije='" . $sesija . "',trajanjeKolacica='" . $kolacic . "',duljinaNoveLozinke='" . $duljina . "',brojPokusaja='" . $pokusaji . "',trajanjeAktivacijskog='" . $akt . "' where id_Konfiguracija='1'";
                            $veza->updateDB($sql);
                            $radnja = "Promjena konfiguracije sustava";
                            $time = dohvatiVirtualnoVrijeme();

                            $kor = $_SESSION["korime"];
                            $dnevnik = "Insert into `dnevnik_rada` values('default','$kor','$time','$radnja')";
                            $veza->updateDB($dnevnik);


                            $veza->zatvoriDB();


                            echo "<br><div style='color:red;text-align:center;background-color:white;padding:8px;'>Sustav je ažuriran</div>";
                        } else {
                            echo "<br><div style='color:red;text-align:center;background-color:white;padding:8px;'>Neuspješno ažuriranje sustava !!!</div>";
                        }
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

