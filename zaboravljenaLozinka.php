<?php
include './baza.class.php';
include './funkcije.php';
?>
<!DOCTYPE html>
<html>

    <head>
        <title>Resetiranje lozinke</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/hsostaric.css" rel="stylesheet" type="text/css">
         <link href="css/hsostaric_prilagodbe.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script> 
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script src="js/hsostaric_jquery.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/hsostaric.js"></script>
        <meta name="Naziv" content="Početna stranica">
        <meta name="Datum posljednje promjene" content="17.03.2018.">
        <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        <script src="js/hsostaric.js" type="text/javascript"></script>
        <meta name="Autor" content="Hrvoje Šoštarić">


    </head>
    <body>

        <header>

          <h1 class="naslovniTekst">Top Liste</h1> 

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
            <a class="izgledUnutrasnjostiNavigacije" href="pocetna.php">Početna za registrirane</a>

        </nav>

        <section>   

            <div name="greske" style="text-align: center ; color: red; font-weight: bold; line-height:2">
                <?php
                if (isset($_POST['aktiviraj'])) {

                    function RandomLozinka($VelicinaLozinke) {
                        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $VelicinaLozinke);
                    }

                    $korisnickoIme = $_POST['Korime'];
                    $email = $_POST['E-mail'];
                    $veza = new Baza();
                    $veza->spojiDB();
                    $sql = "Select *from korisnik where Email='" . $email . "' and korisnicko_ime='" . $korisnickoIme . "'";
                    $rezultat = $veza->selectDB($sql);
                    $veza->zatvoriDB();
                    if ($rezultat->num_rows > 0) {
                        $korisnik = $rezultat->fetch_assoc();
                        if ($korisnik['Aktiviran'] == 1 && $korisnik['Blokiran'] == 0) {
                            $novaLozinka = RandomLozinka(duljinaLozinke());
                            $sol = sha1($korisnik['Datum_registracije']);
                            $kriptirano = sha1($sol . '-' . $novaLozinka);
                            $veza = new Baza();
                            $veza->spojiDB();
                            $upit = "UPDATE `korisnik` SET `lozinka` = '" . $novaLozinka . "', "
                                    . "`KriptiranaLozinka` = '" . $kriptirano . "' WHERE `id_korisnik` = '" . $korisnik['id_korisnik'] . "'";
                            $veza->updateDB($upit);
                            $radnja = "Zahtjev za resetiranjem lozinke";
                            
                            $datum = dohvatiVirtualnoVrijeme();
                            $kor = $_POST['Korime'];
                            $dnevnik = "Insert into `dnevnik_rada` values('default','$kor','$datum','$radnja')";
                            $veza->updateDB($dnevnik);

                            $veza->zatvoriDB();
                            $primatelj = $email;
                            $posiljatelj = "hsostaric@foi.hr";
                            $predmet = "Zaboravljena Lozinka";
                            $poruka = "U nastavku se nalaze podaci vaše prijave:\n\n"
                                    . "------------------------------------\n\n "
                                    . "Korisnicko ime: " . $korisnickoIme . "\nLozinka: " . $novaLozinka . "\n"
                                    . "------------------------------------\n\n";
                            $zaglavlje = 'Pošiljatelj:' . $posiljatelj . "\r\n";
                            mail($primatelj, $predmet, $poruka, $zaglavlje);

                            header("Location:prijava.php");
                        } else {
                            echo 'Računu nije moguće promjeniti lozinku';
                            $veza= new Baza();
                            $radnja = "Neuspješan zahtjev za novu lozinku";
                            $time = time();
                            $datum = date("Y-m-d H:i:s", $time);
                            $kor = $_POST['Korime'];
                            $dnevnik = "Insert into `dnevnik_rada` values('default','$kor','$datum','$radnja')";
                            $veza->spojiDB();
                            $veza->updateDB($dnevnik);
                            $veza->zatvoriDB();
                        }
                    } else {
                        echo 'Neispravni podaci';
                    }
                }
                ?>
            </div>
            <div class='kontenjer2'>

                <form  novalidate id='PotvrdaRacuna' method='post' name='form3' action='zaboravljenaLozinka.php'>
                    <div class='row'>
                        <div class='col-25'>
                            <label class='labele' for='korime'>Korisničko ime: </label>
                        </div>
                        <div class='col-75'>
                            <input class='textboxovi' type='text' id='Korime' name='Korime'  placeholder='Unesite korisničko ime'>
                        </div></div>

                    <div class='row'>
                        <div class='col-25'>
                            <label class='labele' for='Aktivacija'>E-mail:</label>
                        </div>
                        <div class='col-75'>
                            <input class='textboxovi' type='email' id='E-mail' name='E-mail'  placeholder='Unesite e-mail'>
                        </div></div>

                    <div class='row'>
                        <input name='aktiviraj' id='generiraj' type='submit'value='Pošalji '>

                    </div></div>
        </form>

    </section>

    <footer id="podnozje">
        <address>Kontakt:
            <a href="mailto:hsostaric@foi.hr">Hrvoje Šoštarić</a>
        </address>

    </footer>

</body>
</html>

