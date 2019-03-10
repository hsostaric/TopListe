<?php
include './baza.class.php';
include './sesija.class.php';
$sesija = new Sesija();
$sesija::kreirajSesiju();
include './funkcije.php';
if (!isset($_SESSION) && $_SESSION['id'] != 9) {
    header("Location:pocetak.php");
}
echo dohvatiVirtualnoVrijeme();
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

            <div style="margin: 2em 2em 4em 2em;width: 80%">
                <form novalidate id = 'prijedlog' method = 'post' name = 'form1' action='kategorije.php'>
                
                    <?php
                    $veza = new Baza();
                    $veza->spojiDB();
                    $sql = "Select * from kategorija";
                    $rez = $veza->selectDB($sql);
                    $veza->zatvoriDB();
                    if ($rez->num_rows > 0) {
                        echo"<table id='tablica' border='1'>
                        <thead>
                    <caption>Popis kategorija</caption>
                    <tr>
                        <th>Izvođač</th>
                        <th>Naziv</th>
                        <th >Opis</th>
                        <th >Odabrana kategorija</th></tr><tbody>";
                        while ($red = $rez->fetch_assoc()) {
                            echo"<tr><td>" . $red['id_kategorija'] . "</td>"
                            . "<td>" . $red['naziv'] . "</td>"
                            . "<td>" . $red['opis'] . "</td>"
                            . "<td><input type = 'radio'  name = 'modKat' value = '" . $red['id_kategorija'] . "'></td></tr>";
                        }
                    }
                    
                    echo"</tbody></table><br><br>";
                  
                    ?>
                    <div class='row'>
                        <a href='novaKategorija.php'><input  type='submit' name='dodaj' value='Nova Kategorija' style='background-color: blue;width: 20%;'></a>
                    </div><div class='row'>
                        <input type='submit' name='moderator' value='Dodijeli moderatora' style='background-color: blue;width: 20%;'>
                        
                        <?php 
                        if(isset($_POST['dodaj'])){
                            header("Location:novaKategorija.php");
                        }
                        if(isset($_POST['moderator']) && !empty($_POST['modKat'])){
                            $Ka=$_POST['modKat'];
                           $lokacija="moderatorKategorije.php?kat=".$Ka."";
                           header("Location:".$lokacija."");
                          
                        }?>
                    </div>



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


