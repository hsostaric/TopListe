<?php
include './baza.class.php';
include './funkcije.php';
include './sesija.class.php';
if (!isset($_SERVER["HTTPS"]) || strtolower($_SERVER["HTTPS"]) != "on") {
    $adresa = 'https://' . $_SERVER["SERVER_NAME"] .
            $_SERVER["REQUEST_URI"];
    header("Location: $adresa");
    exit();
}

if (isset($_POST['prijavime'])) {
    $korime = $_POST['korimeprijava'];
    $Lozinka = $_POST['lozinkaprijava'];
    $sqlUser = "Select *from korisnik where korisnicko_ime='" . $korime . "'";
    $veza = new Baza();
    $veza->spojiDB();
    $kor = $veza->selectDB($sqlUser);
    $veza->zatvoriDB();
    if ($kor->num_rows > 0) {
        $veza = new Baza();
        $veza->spojiDB();
        $dohvacenUser = $kor->fetch_assoc();
        $datumrege = $dohvacenUser['Datum_registracije'];
        $sol = sha1($datumrege);
        $KriptiranaLozinka = sha1($sol . '-' . $Lozinka);

        $upit = "Select *from korisnik where korisnicko_ime='" . $korime . "' and KriptiranaLozinka='" . $KriptiranaLozinka . "'";
        $rezultat = $veza->selectDB($upit);
        $veza->zatvoriDB();
        if ($rezultat->num_rows > 0) {
            $korisnik = $rezultat->fetch_assoc();
            if ($korisnik["Aktiviran"] == 1 && $korisnik['Blokiran'] == 0) {
                $veza = new Baza();
                $sqlUpit = "Update korisnik SET Pokusaj_Prijave='0' WHERE korisnicko_ime='" . $korime . "'";
                $veza->spojiDB();
                $radnja = "Uspjesna prijava";
              
                $datum = dohvatiVirtualnoVrijeme();
                $koris = $korisnik['korisnicko_ime'];
                $dnevnik = "Insert into `dnevnik_rada` values('default','$koris','$datum','$radnja')";
                $veza->updateDB($dnevnik);

                $sq = "Select *from korisnik where korisnicko_ime='" . $korime . "'";
                $uloga = $veza->selectDB($sq);
                $ul = $uloga->fetch_assoc();

                $veza->updateDB($sqlUpit);
                $veza->zatvoriDB();
                $sesija = new Sesija();
                $sesija::kreirajSesiju();
                $_SESSION["korime"] = $korime;
                $_SESSION["uloga"] = $ul['tip_korisnika_id'];
                $_SESSION['id'] = $ul['id_korisnik'];
                if (!isset($_COOKIE['korime'])) {
                    setcookie("korime", $_SESSION["korime"], time() + 60 * 60 * 24);
                } else {
                    if (!empty($_COOKIE['korime'])) {
                        setcookie("korime", '');
                        setcookie("korime", $_SESSION["korime"], time() + 60 * 60 * 24);
                    }
                }
                header("Location: pocetak.php");
            } else {
                echo 'Racun Vam nije aktiviran ili je zaključan';
                $veza = new Baza();

                $veza->spojiDB();
                $radnja = "Nemoguća prijava";
              
                $datum = dohvatiVirtualnoVrijeme();
                $kor = $_POST['korimeprijava'];
                $dnevnik = "Insert into `dnevnik_rada` values('default','$kor','$datum','$radnja')";
                $veza->updateDB($dnevnik);
                $veza->zatvoriDB();
            }
        } else {
            $baza = new Baza();
            $baza->spojiDB();
            $sqlUpit = "Select *from korisnik where korisnicko_ime='" . $korime . "'";
            $rezultat2 = $baza->selectDB($sqlUpit);
            $baza->zatvoriDB();
            if ($rezultat2->num_rows > 0) {
                $polje = $rezultat2->fetch_assoc();
                if ($polje['Pokusaj_Prijave'] < brojPokusaja()) {
                    $baza = new Baza();
                    $baza->spojiDB();
                    $kor = $_POST['korimeprijava'];
                    $radnja = "Netočan unos lozinke";
                
                    $datum = dohvatiVirtualnoVrijeme();

                    $dnevnik = "Insert into `dnevnik_rada` values('default','$kor','$datum','$radnja')";
                    $baza->updateDB($dnevnik);
                    echo "<script>alert('Pogresna prijava')</script>";
                    $novo = $polje['Pokusaj_Prijave'] + 1;

                    $sqlUpdate = "Update korisnik SET Pokusaj_Prijave='" . $novo . "' WHERE korisnicko_ime='" . $korime . "'";
                    $baza->updateDB($sqlUpdate);
                    $baza->zatvoriDB();
                }
                if ($polje['Pokusaj_Prijave'] == brojPokusaja() && $polje['Blokiran'] == 0) {
                    $baza = new Baza();
                    $sqlUpdate = "Update korisnik SET Blokiran='1' WHERE korisnicko_ime='" . $korime . "'";
                    $baza->spojiDB();
                    $kor = $_POST['korimeprijava'];
                    $radnja = "Zaključavanje korisničkog računa";
                  
                    $datum = dohvatiVirtualnoVrijeme();
                    $dnevnik = "Insert into `dnevnik_rada` values('default','$kor','$datum','$radnja')";
                    $baza->updateDB($dnevnik);
                    $baza->updateDB($sqlUpdate);
                    $baza->zatvoriDB();
                }
            }
        }
    } else {
        echo 'Krivi podaci za prijavu';
    }
}

if (isset($_SESSION['korime'])) {
    echo $_SESSION['korime'];
}
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Prijava</title>
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
            <nav class="obicniTekst"> <a  href="prijava.php">Prijava</a></nav>
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
            <a class="izgledUnutrasnjostiNavigacije" href="prijedlogPjesme.php">Glasovanje za pjesmu</a>
            <a class='izgledUnutrasnjostiNavigacije'href='vremeplov.php'>Virtualno vrijeme</a>
        </nav>

        <section> 
  <div id="google_translate_element"></div>
            <h2 style='text-align: center;'>Prijava </h2>
            <div class = 'pozicija' >
                <form novalidate id = 'prijava' method = 'post' name = 'form1' action = 'prijava.php'>
                    <div class = 'row'>
                        <div class = 'col-25'>
                            <label for = 'korime'>Korisničko ime: </label>
                        </div>
                        <div class = 'col-75'>
                            <input type = 'text' id = 'korime' name = 'korimeprijava' placeholder = 'korisničko ime' autofocus = 'autofocus' required = 'required' <?php
            if (isset($_COOKIE['korime']) && !empty($_COOKIE['korime'])) {
                echo "value=" . $_COOKIE['korime'];
            }
?>></div>
                    </div>
                    <div class = 'row'>
                        <div class = 'col-25'>
                            <label for = 'lozinka'>Lozinka: </label>
                        </div>


                        <div class = 'col-75'>
                            <input type = 'password' id = 'lozinka' name = 'lozinkaprijava'  placeholder = 'lozinka' required = 'required'></div></div>
                    <div class = 'row'>
                        <input type = 'checkbox' name = 'zapamti' value = '1'> Zapamti me<br>
                    </div>
                    <div class = 'row'>
                        <input type = 'submit' name = 'prijavime' value = ' Prijavi se '>
                        <input type = 'reset' value = ' Inicijaliziraj '><br>
                        <nav class="obicniTekst"> <a  href="zaboravljenaLozinka.php">Zaboravili ste lozinku ?</a><br>
                            <nav class="obicniTekst"> <a  href="aktivacijaRacuna.php">Aktivacija Računa</a></nav>
                        </nav>
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
