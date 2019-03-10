<?php
include './baza.class.php';
include './sesija.class.php';
$sesija = new Sesija();
$sesija::kreirajSesiju();
?>

<!DOCTYPE html>
<?php
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
        <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        <script src="js/hsostaric.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/provjera.js"></script>

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
          
        </nav>

        <section>
              <div id="google_translate_element"></div>
            <?php
            ?>
            <div class="pozicija2">
                <h2>Obrazac za glasovanje</h2>
                <form novalidate id = 'prijava' method = 'post' name = 'form1' action ='glasovanjeZaPjesmu.php'>
                    <?php
                    $veza = new Baza();
                    $time = time();
                    $datum = date("Y-m-d H:i:s", $time);
                    $sqlUpit = "Select *FROM top_lista where '" . $datum . "' between datum_pocetka and datum_zavrsetka";
                    $veza->spojiDB();
                    $rezultatTablice = $veza->selectDB($sqlUpit);
                    $veza->zatvoriDB();
                    echo "<select name='toplista' class='obrazac'>";
                    while ($polje = $rezultatTablice->fetch_assoc()) {
                        echo "<option value='" . $polje['id_top_lista']."'>" . $polje['naziv'] . "</option>";
                    }
                    echo "</select>";
                    ?>
                    <div class='row'>
                        <input  type='submit' name='liste' value='Ispiši' style="background-color: blue;width: 10%;" class="gumbSelect">
                    </div>
                    <?php
                    if (isset($_POST['liste'])) {
                        $veza = new Baza();
                        $veza->spojiDB();

                        $odabrana_Kategorija = $_POST['toplista'];
                        $time = time();
                        $datum = date("Y-m-d H:i:s", $time);
                        $sqlUpit = "Select * from pjesma where top_lista_id ='" . $odabrana_Kategorija . "' and odobreno='1'";
                        $datumi = "Select * from top_lista where id_top_lista='" . $odabrana_Kategorija . "'";
                        $datumpockraj = $veza->selectDB($datumi);
                        $fin = $datumpockraj->fetch_assoc();
                        $rezultatTablice2 = $veza->selectDB($sqlUpit);
                        $veza->zatvoriDB();
                        if ($rezultatTablice2->num_rows > 0) {
                            echo "<table border='1'>
                        <thead>
                    <caption>Podaci o kandidatima</caption>
                    <tr >
                        <td>Naziv pjesme</td>
                        <td Naziv izvođača>
                        <td>Datum početka glasovanja</td>
                        <td class='a'>Datum završetka glasovanja</td>
                        <td>Broj glasova</td>
                        <td>Odabir pjesme</td>
                    </tr></thead><tbody>";
                            while ($red = $rezultatTablice2->fetch_assoc()) {
                                echo"<tr>
               
                        <td>" . $red['naziv'] . "</td>
                        <td class='a'>" . $red['izvodjac'] . "</td>
                        <td class='a'>" . $fin['datum_pocetka'] . "</td>
                        <td class='a'>".$fin['datum_zavrsetka']."</td>

                         <td class='a'>" .$red['broj_glasova']. "</td>
                              <td><input  type='radio' class='gumbSelect' name='Glasovanje' value='" . $red['pjesma_id'] . "'></td>
                                 
                    </tr>";
                            }
                            echo"</tbody></table>";
                            
                            echo"<div class='row'>"
                        . "<input type='submit' name='glasuj' value='Glasuj' style='background-color: blue;width: 10%;'>"
                                . "</div>";
                            $idsong=$_POST['Glasovanje'];
                             if (isset($_POST['glasuj'])) {
                        if (!empty($_POST['Glasovanje'])) {
                            $veza = new Baza();
                            $veza->spojiDB();
                            if (isset($_SESSION['uloga'])) {
                                $radnja = "Glasao je za pjesmu";
                                $datum = date("Y-m-d H:i:s", $time);
                                $kor = $_SESSION["korime"];
                                $dnevnik = "Insert into `dnevnik_rada` values('default','$kor','$datum','$radnja')";
                                $veza->updateDB($dnevnik);
                            }
                            
                            $sqlUpit = "Update pjesma SET broj_glasova=broj_glasova+'1' where pjesma_id='" .$idsong. "'";
                          
                            $veza->updateDB($sqlUpit);
                            $veza->zatvoriDB();

                            echo "<script>alert('Vaš glas je zabilježen')</script>";
                            header("Location:pocetak.php");
                            sleep('5');
                        } else {
                            echo "<script>alert('Morate označiti pjesmu')</script>";
                        }
                    }
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
