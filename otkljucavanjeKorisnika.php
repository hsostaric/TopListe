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
        <script type="text/javascript" src="js/provjera.js"></script>
        <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        <script src="js/hsostaric.js" type="text/javascript"></script>

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
            <h2 style='text-align: center;'>Otkljucavanje korisnika </h2>
            <div class = 'pozicija' >
                <form  novalidate id='unblock' method='post' name='form2' action="otkljucavanjeKorisnika.php">

                    <?php
                    $veza = new Baza();
                    $veza->spojiDB();
                    $sql = "Select *from korisnik where Blokiran='1'";
                    $rezultat = $veza->selectDB($sql);
                    $veza->zatvoriDB();
                    if ($rezultat->num_rows > 0) {
                        echo "<table border='1'>
                        <thead>
                    <caption>Podaci o kandidatima</caption>
                    <tr >
                        <td>Korisničko ime</td>
                        <td>Prezime</td>
                        <td class='a'>Ime</td>
                        <td class='a'>Email</a></td>
                       <td>Odabrani korisnik</td>
                    </tr></thead><tbody>";
                        while ($red = $rezultat->fetch_assoc()) {
                            echo"<tr><td>" . $red['korisnicko_ime'] . "</td>
                            <td class='a'>" . $red['prezime'] . "</td>
                        <td class='a'>" . $red['ime'] . "</td>
                        <td class='a'>" . $red['Email'] . "</td>
                            <td ><input type = 'radio'  name = 'Radnja' value = '" . $red['id_korisnik'] . "' > </td>
                        
                    </tr>";
                            echo"
                    </div>";
                        }

                        echo"</tbody></table>";
                        echo"<div class='row'>
                        <input  type='submit' name='otkljucaj' value='Otkljucaj' style='background-color: blue;width: 10%;'></div>";
                        

                        if (isset($_POST['otkljucaj'])) {
                            $veza = new Baza();
                            $id = $_POST['Radnja'];
                            $veza->spojiDB();
                            $upitOdblokiraj = "UPDATE korisnik set Blokiran='0',Pokusaj_Prijave='0' where id_korisnik='" . $id . "'";
                            $veza->updateDB($upitOdblokiraj);
                            $radnja = "Otključavanje korisničkog računa";
                            $time = dohvatiVirtualnoVrijeme();

                            $kor = $_SESSION["korime"];
                            $dnevnik = "Insert into `dnevnik_rada` values('default','$kor','$time','$radnja')";
                            $veza->updateDB($dnevnik);
                            $veza->zatvoriDB();
                            header("Location:otkljucavanjeKorisnika.php");
                            sleep(5);
                        }
                    } else {
                        print "Nema blokiranih korisnika";
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

