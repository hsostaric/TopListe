<?php
include './baza.class.php';
include './sesija.class.php';
$sesija = new Sesija();
include './funkcije.php';
$sesija::kreirajSesiju();
if( isset($_SESSION['id']) && $_SESSION['id']==9){
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
            <a class="izgledUnutrasnjostiNavigacije" href="galerija.html">Galerija slika</a>
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
                <h2>Obrazac prijedloga</h2>
                <form novalidate id = 'prijedlog' method = 'post' name = 'form1' action ='prijedlogPjesme.php'>
                    <div class='row'>
                        <div class='col-25'>
                            <label class='labele' for='email'>Naziv pijesme: </label>
                        </div>
                        <div class='col-75'>
                            <input class='textboxovi' type='text' id='pjesma' name='pjesma'  placeholder='Unesite naslov skladbe' >
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-25'>
                            <label class='labele' for='izvodjač'>Naziv izvođača: </label>
                        </div>
                        <div class='col-75'>
                            <input class='textboxovi' type='text' id='izvodjač' name='autor'  placeholder='Unesite naziv izvođača' >
                        </div>
                    </div>

                    <div class='row'>
                        <div class='col-25'>
                            <label class='labele' for='padajući'>Popis Top Lista: </label>
                        </div>
                        <div class='col-75'>
                            <select name='topListe' class='obrazac'>
                                <?php
                                $veza = new Baza();
                                $veza->spojiDB();
                                $time = time();
                                $datum = date("Y-m-d H:i:s", $time);
                                $upit = "Select *from top_lista where '" . $datum . "' between pocetak_prijedloga and zavrsetak_prijedloga";
                                $rezultat = $veza->selectDB($upit);
                                $veza->zatvoriDB();
                                if ($rezultat->num_rows > 0) {
                                    while ($polje = $rezultat->fetch_assoc()) {
                                        echo "<option value='" . $polje['id_top_lista'] . "'>" . $polje['naziv'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>


                    <div class='row'>
                        <input  type='submit' name='dodaj' value='Dodaj' style="background-color: blue;width: 10%;">
                    </div>
                    <?php
                    if (isset($_POST['dodaj'])) {
                        $idliste = $_POST['topListe'];
                        $nazivPjesme = $_POST['pjesma'];
                        $izvodjac = $_POST['autor'];
                        $sql = "";
                        $veza = new Baza();
                        $veza->spojiDB();
                        if (!isset($_SESSION["uloga"])) {
                            $sql = "INSERT INTO `pjesma` (`pjesma_id`, `broj_glasova`, `top_lista_id`, `naziv`, `izvodjac`, `odobreno`) "
                                    . "VALUES ('default','0','$idliste','$nazivPjesme','$izvodjac','0')";
                            $veza->updateDB($sql);
                        } else {
                            $user = $_SESSION['id'];
                            $sql = "INSERT INTO `pjesma`"
                                    . "VALUES ('default','0','$idliste','$nazivPjesme','$izvodjac','0','$user')";
                            $veza->updateDB($sql);
                            $radnja = "Prijedlog pjesme";
                            $datum = date("Y-m-d H:i:s", $time);
                            $kor = $_SESSION["korime"];
                            $dnevnik = "Insert into `dnevnik_rada` values('default','$kor','$datum','$radnja')";
                            $veza->updateDB($dnevnik);
                        }

                        $veza->zatvoriDB();
                        echo "<script>alert('Pjesma je dodana')</script>";
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



