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
            if(isset($_SESSION['id']) &&  $_SESSION["uloga"]==8){
                echo"<a class='izgledUnutrasnjostiNavigacije'href='mojeKategorije.php'>Moje Kategorije</a>";
            }
            ?>
            
            <a class='izgledUnutrasnjostiNavigacije'href='vremeplov.php'>Virtualno vrijeme</a>

        </nav>

        <section> 
            <div id="google_translate_element"></div>
            <div class="pozicija2">
                <h2>Moje kategorije</h2>
                <form novalidate id = 'prijedlog' method = 'post' name = 'form1' action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class='row'>
                        <div class='col-25'>
                            <label class='labele' for='email'>Moje Kategorije</label>
                        </div>
                        <div class='col-75'>
                            <?php
                            $veza = new Baza();
                            $veza->spojiDB();
                            $id=$_SESSION['id'];
                            $sql = "SELECT  * FROM moderator_kategorije mk, kategorija k where mk.kategorija_id=k.id_kategorija and mk.korisnik_id='".$id."';";
                            $rezultat = $veza->selectDB($sql);
                            $veza->zatvoriDB();
                            echo "<select name='kategorije' class='obrazac'>";
                            while ($red = $rezultat->fetch_assoc()) {
                                echo"<option name='user' value='" . $red['id_kategorija'] . "'>" . $red['naziv'] . "</option>";
                            }
                            echo "</select>";
                            ?>
                        </div>
                    </div>
                   
                    <div class='row'>
                        <input  type='submit' name='prikaziListe' value='Prikazi listu' >
                    </div>
                    <br>
                    <div class='row'>
                        <input  type='submit' name='dodajListu' value='Dodaj novu listu' >
                    </div>
                   
                    <?php
                    if(isset($_POST['dodajListu'])){
                        $vrsta=$_POST['kategorije'];
                        header("Location:noveTopListe.php?kategorija=".$vrsta."");
                    }
                    if(isset($_POST['prikaziListe'])){
                        $id=$_POST['kategorije'];
                        $upit="Select *from top_lista where kategorija_id='".$id."'";
                        $veza= new Baza();
                        $veza->spojiDB();
                        $final=$veza->selectDB($upit);
                        if($final->num_rows>0){
                            echo"<br><br>";
                             echo "<table border='1' class='display' id='table_id'>
                        <thead>
                  
                    <tr >
                        <th>Naziv</th>
                        <th>Datum početka glasovanja</th>
                        <th class='a'>Datum završetka glasovanja</th>
                        <th class='a'>Datum početka prijedloga</th>
                        <th class='a'>Datum zavrsetka prijedloga</th>
                    </tr></thead><tbody>";
                             while($red=$final->fetch_assoc()){
                                echo"<tr><td><a href='stavkeListe.php?mod=".$red['id_top_lista']."'>".$red['naziv']."</td>"
                                        . "<td>".$red['datum_pocetka']."</td>"
                                        . "<td>".$red['datum_zavrsetka']."</td>"
                                        . "<td>".$red['pocetak_prijedloga']."</td>"
                                        . "<td>".$red['zavrsetak_prijedloga']."</td></tr>"; 
                             }
                             echo"</tbody></table>";
                        }else{
                        echo"<div style='color:red; text-align:center;'>Trazena kategorija nema top lista</div>";
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
