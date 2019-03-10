<?php
include './baza.class.php';
include './funkcije.php';
include './sesija.class.php';
$sesija = new Sesija();
$sesija::kreirajSesiju();

echo dohvatiVirtualnoVrijeme();

?>

<!DOCTYPE html>
<?php
if (!isset($_COOKIE['prvi_dolazak'])) {
    $traje= trajanjeKolacica();
    $pocetak= dohvatiVirtualnoVrijeme();
    $novo=date("Y-m-d H:i:s", strtotime($pocetak . " + " . $traje . " hours"));
    
    setcookie('prvi_dolazak', 'Prihvaćeni uvjeti koristenja', time()+60*60*$traje);
    echo "<script>alert('Ova stranica sprema i koristi kolačiće.')</script>";
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
            echo"admin_D7X5";
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
            <a class="izgledUnutrasnjostiNavigacije" href="index.html">O autoru</a>
            <a class="izgledUnutrasnjostiNavigacije" href="dokumentacija.html">Dokumentacija</a>
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
            <?php if(isset($_SESSION['id']) && $_SESSION['uloga']>=8){
                echo"<a class='izgledUnutrasnjostiNavigacije'href='mojeKategorije.php'>Moje Kategorije</a>";
            }?>
          <a class='izgledUnutrasnjostiNavigacije'href='vremeplov.php'>Virtualno vrijeme</a>
        </nav>

        <section> 
           <div id="google_translate_element"></div>

            <div class="pozicija2">
                <h2>Obrazac za prijedlog</h2>
                <form novalidate id = 'prijava' method = 'post' name = 'form1' action ='pocetak.php'>
                    <?php
                    $veza = new Baza();
                    $sqlUpit = "Select *FROM `kategorija`";

                    $veza->spojiDB();

                    $rezultatTablice = $veza->selectDB($sqlUpit);
                    $veza->zatvoriDB();
                    echo "<select name='RadnaMjesta' class='obrazac'>";
                    while ($polje = $rezultatTablice->fetch_assoc()) {
                        echo "<option value='" . $polje['id_kategorija'] . "'>" . $polje['naziv'] . "</option>";
                    }
                    echo "</select>";
                    ?>
                    <div class='row'>
                        <input  type='submit' name='kategorije' value='Ispiši' style="background-color: blue;width: 20%;" class="gumbSelect">
                    </div>
                    <?php
                    if (isset($_POST['kategorije'])) {
                        $veza = new Baza();
                        $veza->spojiDB();

                        $odabrana_Kategorija = $_POST['RadnaMjesta'];
                        $sqlUpit = "Select *from top_lista where kategorija_id ='" . $odabrana_Kategorija . "'";
                        $rezultatTablice2 = $veza->selectDB($sqlUpit);
                        $veza->zatvoriDB();
                        if ($rezultatTablice2->num_rows > 0) {
                            echo "<table border='1'>
                        <thead>
                    <caption>Podaci o kandidatima</caption>
                    <tr >
                        <td>Naziv</td>
                        <td>Datum početka glasovanja</td>
                        <td class='a'>Datum završetka glasovanja</td>
                    </tr></thead><tbody>";
                            while ($red = $rezultatTablice2->fetch_assoc()) {
                                echo"<tr>
               
                        <td><a href='pregledTopListe.php?lista=" . $red['naziv'] . "'>" . $red['naziv'] . "</a> </td>
                        <td class='a'>" . $red['datum_pocetka'] . "</td>
                        <td class='a'>" . $red['datum_zavrsetka'] . "</td>
                    </tr>";
                            }
                            echo"</tbody></table>";
                        } else {
                            echo 'Tražena kategorija nema top listi';
                        }
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
