<?php
if (!isset($_GET['lista'])) {
    header("Location:pocetak.php");
} else {

    include './funkcije.php';
    include './sesija.class.php';
    $sesija = new Sesija();
    $sesija::kreirajSesiju();
}
?>
<?php
if (isset($_POST['glasuj']) && !empty($_POST['Glasovanje'])) {
    $pjesmaId = $_POST['Glasovanje'];
    $veza = new Baza();
    $veza->spojiDB();
    if (isset($_SESSION['uloga'])) {
        $radnja = "Glasao je za pjesmu";
        $datum = dohvatiVirtualnoVrijeme();
        $kor = $_SESSION["korime"];
        $dnevnik = "Insert into `dnevnik_rada` values('default', '$kor', '$datum', '$radnja')";
        $veza->updateDB($dnevnik);
    }
    $sqlUpit = "Update pjesma SET broj_glasova = broj_glasova+'1' where pjesma_id = '" . $pjesmaId . "'";
    $veza->updateDB($sqlUpit);
    $veza->zatvoriDB();
    header("Location:pocetak.php");
}
?>

<!DOCTYPE html>

<html>
    <head>
        <title>Pregled top listi</title>';
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
                <form  novalidate id='registracija' method='post' name='form2' action="<?php echo $_SERVER['PHP_SELF'] . "?lista=" . $_GET['lista'] . ""; ?>">
                    <h2>Pregled pjesama</h2>
                    <?php
                    $mod = $_GET['lista'];
                    $veza = new Baza();
                    $veza->spojiDB();
                    $sqlupit = "Select *from top_lista where naziv = '" . $mod . "'";
                    $rez = $veza->selectDB($sqlupit);
                    $red = $rez->fetch_assoc();
                    if (isset($_GET['order'])) {
                        $order = $_GET['order'];
                    } else {
                        $order = 'broj_glasova';
                    }

                    if (isset($_GET['sort'])) {
                        $sort = $_GET['sort'];
                    } else {
                        $sort = 'DESC';
                    }
                    $sql = "Select *from pjesma where top_lista_id = '" . $red['id_top_lista'] . "' and odobreno = '1'  ORDER BY $order $sort";
                    $datum = dohvatiVirtualnoVrijeme();
                    $final = $veza->selectDB($sql);
                    $veza->zatvoriDB();
                    if ($final->num_rows > 0) {
                        $sort == 'DESC' ? $sort = 'ASC' : $sort = 'DESC';
                        echo"<table border = '1' id='table_id' class='display'>
                        <thead >
                        <tr>
                        <th>Naziv</th>
                        <th>Izvođač</th>
                        <th><a href='?lista=$mod&&order=broj_glasova&&sort=$sort'>Broj glasova</th>
                        <th>Datum početka glasovanja</th>
                        <th class = 'a'>Datum završetka glasovanja</th>"
                        ;
                        if ($datum <= $red['zavrsetak_prijedloga'] && $datum >= $red['pocetak_prijedloga']) {
                            echo"<th>Odabir pjesme</th>";
                        }
                        echo"</tr></thead><tbody>";
                        while ($row = $final->fetch_assoc()) {
                            echo "<tr>
                        <td>" . $row['naziv'] . "</td>
                        <td class = 'a'>" . $row['izvodjac'] . "</td>
                        <td class = 'a'>" . $row['broj_glasova'] . "</td>";
                            echo"<td>" . $red['pocetak_prijedloga'] . "</td>";
                            echo"<td>" . $red['zavrsetak_prijedloga'] . "</td>";
                            if ($datum <= $red['zavrsetak_prijedloga'] && $datum >= $red['pocetak_prijedloga']) {
                                echo"<td ><input type = 'radio' class = 'gumbSelect' name = 'Glasovanje' value = '" . $row['pjesma_id'] . "' > </td>";
                            }
                            echo"</tr>";
                        }echo"</tbody></table>";


                        if ($datum <= $red['zavrsetak_prijedloga'] && $datum >= $red['pocetak_prijedloga']) {
                            echo"<div class = 'row'>
                        <div class = 'col-75'>
                        <input type = 'submit' name = 'glasuj' value = 'Glasuj' style = 'background-color: blue;width: 30%;padding-left: 4%;'>
                        </div>
                        </div>";
                        }
                    } else {
                        echo 'Nema rezultata za traženu listu';
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
