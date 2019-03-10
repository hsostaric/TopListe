<?php
include './baza.class.php';
include './funkcije.php';
include './sesija.class.php';
$sesija = new Sesija();
$sesija::kreirajSesiju();
?>
<?php 
if(isset($_POST['prihvati']) && !empty($_POST['song'])){
    $veza= new Baza();
    $veza->spojiDB();
    $pjesmaid=$_POST['song'];
    
    $sql="Update pjesma set odobreno='1' where pjesma_id='".$pjesmaid."'";
    $veza->updateDB($sql);
    $datum= dohvatiVirtualnoVrijeme();
    $radnja="Prihvaćanje prijedloga pijesme";
    $dnevnik = "Insert into `dnevnik_rada` values('default','{$_SESSION["korime"]}','$datum','$radnja')";
    $veza->updateDB($dnevnik);
    $veza->zatvoriDB();
}

if(isset($_POST['odbij']) && !empty($_POST['song'])){
    $veza= new Baza();
    $veza->spojiDB();
    $pjesmaid=$_POST['song'];
    
    $sql="Delete from pjesma where pjesma_id='".$pjesmaid."'";
    $veza->updateDB($sql);
    $datum= dohvatiVirtualnoVrijeme();
    $radnja="Odbijanje predložene pjesme";
    $dnevnik = "Insert into `dnevnik_rada` values('default','{$_SESSION["korime"]}','$datum','$radnja')";
    $veza->updateDB($dnevnik);
    $veza->zatvoriDB();
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
            if (isset($_SESSION['id']) && $_SESSION["uloga"] == 8) {
                echo"<a class='izgledUnutrasnjostiNavigacije'href='mojeKategorije.php'>Moje Kategorije</a>";
            }
            ?>

            <a class='izgledUnutrasnjostiNavigacije'href='vremeplov.php'>Virtualno vrijeme</a>

        </nav>

        <section> 
            <div id="google_translate_element"></div>
            <div class="pozicija2">
                <h2>Stavke top liste</h2>
                <form novalidate id = 'prijedlog' method = 'post' name = 'form1' action="<?php echo $_SERVER['PHP_SELF']."?mod={$_GET['mod']}"; ?>">
                    <?php
                    $mod=$_GET['mod'];
                    $veza = new Baza();
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
                    $veza->spojiDB();
                    $topLista = $_GET['mod'];
                    $sql = "Select *from pjesma where top_lista_id='" . $topLista . "' and odobreno='1'  ORDER BY $order $sort ";
                    $rezultat = $veza->selectDB($sql);
                    $liste="Select *from top_lista where id_top_lista='".$mod."'";
                    $neke=$veza->selectDB($liste);
                    $row=$neke->fetch_assoc();
                    $veza->zatvoriDB();
                    if ($rezultat->num_rows > 0) {
                        $sort == 'DESC' ? $sort = 'ASC' : $sort = 'DESC';
                        echo"<table border = '1' id='table_id' class='display'>
                        <thead >
                        <tr>
                        <th>Naziv</th>
                        <th>Izvođač</th>
                        <th><a href='?mod=$mod&&order=broj_glasova&&sort=$sort'>Broj glasova</th>
                        <th>Datum početka glasovanja</th>
                        <th class = 'a'>Datum završetka glasovanja</th>"
                        ;
                        echo"</tr></thead><tbody>";
                        while($red=$rezultat->fetch_assoc()){
                             echo "<tr>
                        <td>" . $row['naziv'] . "</td>
                        <td class = 'a'>" . $red['izvodjac'] . "</td>
                        <td class = 'a'>" . $red['broj_glasova'] . "</td>";
                            echo"<td>" . $row['pocetak_prijedloga'] . "</td>";
                            echo"<td>" . $row['zavrsetak_prijedloga'] . "</td>";
                              echo"</tr>";
                        }
                        echo"</tbody></table>";
                    }
                    $datum= dohvatiVirtualnoVrijeme();
                    if($datum>=$row['pocetak_prijedloga'] && $datum<=$row['zavrsetak_prijedloga']){
                        echo '<br><br><hr>';
                        echo"<p>Prijedlozi pjesama</p>";
                        $veza= new Baza();
                        $veza->spojiDB();
                        $sql="select *from pjesma where odobreno='0' and top_lista_id='".$mod."'";
                        $rezultat=$veza->selectDB($sql);
                        $veza->zatvoriDB();
                        if($rezultat->num_rows>0){
                            echo"<table border=1 id='table_id' class='display'><thead>
                        <tr>
                        <th>Naziv</th>
                        <th>Izvođač</th>
                        <th>Odabrana pjesma</th>
                        </tr></thead><tbody>";
                            while($red=$rezultat->fetch_assoc()){
                                echo"<tr><td>".$red['naziv']."</td>"
                                        . "<td>".$red['izvodjac']."</td>"
                                        . "<td><input type = 'radio'  name = 'song' value = '" . $red['pjesma_id'] . "' ></td></tr>";
                            }
                            echo"</tbody></table>";
                            echo"<br>";
                            echo"<div class='row'>
                        <input  type='submit' name='prihvati' value='Prihvati pjesmu' style='background-color: blue;width: 20%;'></div>";
                            echo"<div class='row'>
                        <input  type='submit' name='odbij' value='Odbij pjesmu' style='background-color: blue;width: 20%;'></div>";
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
