<?php
include './funkcije.php';
include './sesija.class.php';
$sesija = new Sesija();
$sesija::kreirajSesiju();

echo dohvatiVirtualnoVrijeme();
if (isset($_SESSION['uloga']) && $_SESSION['uloga'] < 8) {
    header("Location:oglasiReg.php");
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
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>   
        <script src="js/hsostaric_jquery.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
        <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
    </head>
    <body>

        <header>
           <h1 class="naslovniTekst">Top Liste</h1> 

            <?php
            if (!isset($_SESSION['korime'])) {
                echo '<nav class="obicniTekst"> <a  href="prijava.php">Prijava</a></nav>';
            } else {


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
            <?php
            if (isset($_SESSION['id']) && $_SESSION['uloga'] >= 8) {
                echo"<a class='izgledUnutrasnjostiNavigacije'href='mojeKategorije.php'>Moje Kategorije</a>";
            }
            ?>
            <a class='izgledUnutrasnjostiNavigacije'href='vremeplov.php'>Virtualno vrijeme</a>
        </nav>

        <section> 
            <div id="google_translate_element"></div>

            <div class="pozicija2">
                <h2>Popis zahtjeva za oglase</h2>
                <form novalidate id = 'zahtjevi' method = 'post' name = 'form1' action ='<?php echo $_SERVER['PHP_SELF']?>'>
                    <?php
                    $veza = new Baza();
                    $veza->spojiDB();
                    $id = $_SESSION['id'];
                    $datum = dohvatiVirtualnoVrijeme();
                    $sql = "SELECT * FROM `oglas` as o, vrsta_oglasa as vo, pozicija_prikaza as pp where o.vrsta_oglasa_id = vo.id_vrsta_oglasa and 
                      pp.id_pozicija_prikaza = vo.pozicija_prikaza_id and o.odobren='0' and o.blokiran='0' and '" . $datum . "'<=o.pocetak_prikazivanja and pp.korisnik_id='" . $id . "'";
                    $rezultat = $veza->selectDB($sql);
                    $veza->zatvoriDB();
                    if ($rezultat->num_rows > 0) {
                        echo "<table border='1' id='table_id' class='display'>
                        <thead>
          
                        <tr>
                        <th>Naziv oglasa</th>
                        <th>Vrsta oglasa</th>
     
                        <th class='a'>Pozicija oglasa</th>
                        <th class='a'>Odabrani oglas</th>
                        
                    </tr></thead><tbody>";
                        while ($red = $rezultat->fetch_assoc()) {
                            echo"<tr><td>" . $red['naziv'] . "</td>
                        <td class='a'>" . $red['Naziv'] . "</td>
                        <td class='a'>" . $red['id_pozicija_prikaza'] . "</td>
                        <td class='a'><input type = 'radio'  name = 'Odabrani' value = '" . $red['id_zahtjev_za_oglas'] . "' ></td>
                       
                    </tr>";
                        }
                        echo"</tbody></table>";
                        echo "<br>";
                        echo"<div class='row'><input name='odobriOglas' type='submit' value='Prihvati oglas' id='potvrda'></div>";
                        echo"<div class='row'><input name='odbiOglas' type='submit' value='Odbijanje oglasa' id='potvrda'></div>";
                    } else {
                        echo"Trenutačno nema zahtjeva za vaše pozicije";
                    }
                    ?>
                    <?php 
                    if(isset($_POST['odobriOglas'])){
                        $veza= new Baza();
                        $id=$_POST['Odabrani'];
                        $veza->spojiDB();
                        $sql="Update oglas set odobren='1' where id_zahtjev_za_oglas='".$id."'";
                        $veza->updateDB($sql);
                        
                         $radnja = "Odobren zahtjev za oglas";
                         $datum = dohvatiVirtualnoVrijeme();
                         $kor = $_SESSION["korime"];
                          $dnevnik = "Insert into `dnevnik_rada` values('default','$kor','$datum','$radnja')";
                          $veza->updateDB($dnevnik);
                        
                        $veza->zatvoriDB();
                    }
                   
                    ?>

                </form>



        </section>

        <footer id = "podnozje">
            <address>Kontakt:
                <a href = "mailto:hsostaric@foi.hr">Hrvoje Šoštarić</a>
            </address>

        </footer>

    </body>
</html>
