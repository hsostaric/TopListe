<?php
include './baza.class.php';
include './sesija.class.php';
$sesija = new Sesija();
$sesija::kreirajSesiju();
?>
<!DOCTYPE html>
<?php
if (!isset($_COOKIE['prvi_dolazak'])) {
    setcookie('prvi_dolazak', 'Prihvaćeni uvjeti koristenja', time() + 60 * 60 * 24 * 2);
    echo "<script>alert('Ova stranica sprema kolačiće.')</script>";
}
?>
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
            <a class='izgledUnutrasnjostiNavigacije'href='vremeplov.php'>Virtualno vrijeme</a>

        </nav>

        <section> 
            <div id="google_translate_element"></div>
            <div style="margin: 2em 2em 4em 2em;width: 90%">
                <?php
                $veza = new Baza();
                $veza->spojiDB();
                if (isset($_GET['order'])) {
                    $order = $_GET['order'];
                } else {
                    $order = 'vrijeme_izvrsavanja';
                }

                if (isset($_GET['sort'])) {
                    $sort = $_GET['sort'];
                } else {
                    $sort = 'ASC';
                }

                $sql = "SELECT korisnicko_ime, vrijeme_izvrsavanja,`akcija` FROM dnevnik_rada ORDER BY $order $sort";

                $rezultat = $veza->selectDB($sql);
                $veza->zatvoriDB();
                if ($rezultat->num_rows > 0) {
                    $sort == 'DESC' ? $sort = 'ASC' : $sort = 'DESC';
                    echo "<table id='table_id' border=1 class='display'>
                    <thead>
                   <tr><th><a href='?order=korisnicko_ime&&sort=$sort'>Korisničko ime</th>
                   <th><a href='?order=vrijeme_izvrsavanja&&sort=$sort'>Datum radnje</th>
                   <th>Akcija</th></tr></thead><tbody>";
                    while ($red = $rezultat->fetch_assoc()) {
                        echo"<tr><td>" . $red['korisnicko_ime'] . "</td>"
                        . "<td>" . $red['vrijeme_izvrsavanja'] . "</td>"
                        . "<td>" . $red['akcija'] . "</td></tr>";
                    }
                    echo"</tbody></table>";
                }
                ?>


            </div>



        </section>

        <footer id = "podnozje">
            <address>Kontakt:
                <a href = "mailto:hsostaric@foi.hr">Hrvoje Šoštarić</a>
            </address>

        </footer>

    </body>
</html>

