<?php
include './baza.class.php';
include './funkcije.php';

if (isset($_POST['g-recaptcha-response'])) {
    if (!empty($_POST['g-recaptcha-response'])) {
        $kljuc = "6LfdUVwUAAAAAFfSoobKMI5MGbB4-z-5qBMSeZEn";
        $ip = $_SERVER['REMOTE_ADDR'];
        $captcha = $_POST['g-recaptcha-response'];
        $odrediste = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$kljuc&response=$captcha&remoteip$ip");
        $polje = json_decode($odrediste, TRUE);
        if ($polje['success']) {
            echo 'Done';
        } else {
            echo 'SPam';
        }
    }
}
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Registracija</title>
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
        <meta name="Autor" content="Hrvoje Šoštarić">
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
            <a class="izgledUnutrasnjostiNavigacije" href="pocetna.php">Početna za registrirane</a>
            <a class="izgledUnutrasnjostiNavigacije" href="prijedlogPjesme.php">Predloži pjesmu</a>
            <a class='izgledUnutrasnjostiNavigacije'href='vremeplov.php'>Virtualno vrijeme</a>

        </nav>

        <section>   
              <div id="google_translate_element"></div>
            <h2 style='text-align: center;'>Registracija </h2>
            <div name="greske" style="text-align: center ; color: red; font-weight: bold; line-height:2">
                <?php
                $greske = array();
                if (isset($_POST['registrirajse'])) {
                    if (empty($_POST['ime'])) {
                        $greske[] = 'Polje za ime je prazno';
                    } else {
                        if (preg_match('/\?|\#|\!|\'/', $_POST["ime"])) {
                            $greske[] = "Polje za ime sadrži nedozvoljene znakove";
                        }
                    }
                    if (empty($_POST['prezime'])) {
                        $greske[] = 'Polje za prezime je prazno';
                    } else {
                        if (preg_match('/\?|\#|\!|\'/', $_POST["prezime"])) {
                            $greske[] = "Polje za iprezime sadrži nedozvoljene znakove";
                        }
                    }
                    if (empty($_POST['korime2'])) {
                        $greske[] = 'Polje za korisničko ime je prazno';
                    } else {
                        if (preg_match('/\?|\#|\!|\'/', $_POST['korime2'])) {
                            $greske[] = "Polje za korisničko ime sadrži nedozvoljene znakove";
                        }
                    }
                    if (empty($_POST['lozinka1'])) {
                        $greske[] = 'Polje za lozinku je prazno';
                    } else {

                        if (preg_match('/\?|\#|\!|\'/', $_POST['lozinka1'])) {
                            $greske[] = "Polje za lozinku sadrži nedozvoljene znakove";
                        }
                    }

                    if (empty($_POST['lozinka2'])) {
                        $greske[] = 'Polje za potvrdu lozinke je prazno';
                    } else {
                        if (preg_match('/\?|\#|\!|\'/', $_POST['lozinka2'])) {
                            $greske[] = "Polje za potvrdu lozinke sadrži nedozvoljene znakove";
                        }
                    }

                    if (!empty($_POST['email'])) {
                        if (preg_match('/\?|\#|\!|\'/', $_POST['email'])) {
                            $greske[] = "Polje za e-mail  sadrži nedozvoljene znakove";
                        }
                    }
                    if ($_POST['lozinka1'] != $_POST['lozinka2']) {
                        $greske[] = "Lozinka i potvrda lozinke nisu iste vrijednosti";
                    }
                    $Email = $_POST['email'];
                    $vezaBaza = new Baza();
                    $upit1 = "SELECT *FROM korisnik";
                    $vezaBaza->spojiDB();
                    $zauzetost = false;
                    $rezultat = $vezaBaza->selectDB($upit1);
                    if ($rezultat->num_rows > 0) {
                        while ($row = $rezultat->fetch_assoc()) {
                            if ($row["Email"] == $Email) {
                                $greske[] = "Zauzetost e-maila";
                                $zauzetost = true;
                            }
                        }
                    }
                    $korime=$_POST['korime2'];
                    $sql="select *from korisnik where korisnicko_ime='".$korime."'";
                    $zauz=$vezaBaza->selectDB($sql);
                    if($zauz->num_rows>0){
                         $greske[] = "Zauzetost korisničkog imena";
                    }
                    $vezaBaza->zatvoriDB();
                    if (empty($zauzetost) && empty($greske)) {
                        $vezaBaza = new Baza();
                        $Ime = $_POST['ime'];
                        $Prezime = $_POST['prezime'];
                        $Korime = $_POST['korime2'];
                        $Lozinka = $_POST['lozinka1'];
                       
                        $datum = dohvatiVirtualnoVrijeme();
                        $sol = sha1($datum);
                        $KriptiranaLozinka = sha1($sol . '-' . $Lozinka);
                        $Email = $_POST['email'];

                        $aktivacijskiKod = md5(rand(0, 1000));
                        $status = 0;

                        $vezaBaza->spojiDB();

                        $sqlUpit = "Insert into `korisnik` values('default','$Ime','$Prezime','$Email','$Korime','$Lozinka','$KriptiranaLozinka','$aktivacijskiKod','$status','0','0','6','$datum')";
                        $radnja = "Korisnik je registrirao račun";
                        $dnevnik = "Insert into `dnevnik_rada` values('default','$Korime','$datum','$radnja')";
                        $vezaBaza->updateDB($dnevnik);
                        $vezaBaza->updateDB($sqlUpit);

                        $primatelj = $Email;
                        $posiljatelj = "hsostaric@foi.hr";
                        $predmet = "Potvrda registracije";
                        $poruka = "Zahvaljujemo na registraciji, u nastavku se nalaze vaši podaci za potvrdu registracije i vaši podaci za prijavu:\n\n"
                                . "------------------------------------\n\n "
                                . "Korisnicko ime: " . $Korime . "\nLozinka: " . $Lozinka . "\n Aktivacijski kod: " . $aktivacijskiKod . "\n"
                                . "------------------------------------\n\n";
                        $zaglavlje = 'Pošiljatelj:' . $posiljatelj . "\r\n";
                        mail($primatelj, $predmet, $poruka, $zaglavlje);

                        $vezaBaza->zatvoriDB();
                        header("Location:aktivacijaRacuna.php");
                    } else {
                        $vezaBaza = new Baza();
                       
                        $radnja = "Neuspjela registracija";
                        $time = time();
                        $datum = date("Y-m-d H:i:s", $time);
                        $koris="Anoniman Korsinik";
                        $dnevnik = "Insert into `dnevnik_rada` values('default','$koris','$datum','$radnja')";
                        $vezaBaza->spojiDB();
                        $vezaBaza->updateDB($dnevnik);
                        $vezaBaza->zatvoriDB();
                    }
                }

                foreach ($greske as $red) {
                    echo $red . '<br>';
                }
                ?>
            </div>
            <div class='kontenjer2'>

                <form  novalidate id='registracija' method='post' name='form2' action='registracija.php'>
                    <div class='row'>
                        <div class='col-25'>
                            <label class='labele' for='ime'>Ime: </label>
                        </div>
                        <div class='col-75'>
                            <input class='textboxovi' type='text' id='ime' name='ime' size='20' maxlength='30' placeholder='ime'>
                        </div></div>
                    <div class='row'>
                        <div class='col-25'>
                            <label class='labele' for='prezime'>Prezime: </label>
                        </div>
                        <div class='col-75'>
                            <input class='textboxovi' type='text' id='prezime' name='prezime' size='20' maxlength='50' placeholder='prezime'  required='required'>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-25'>
                            <label class='labele' for='korime2'>Korisničko ime: </label>
                        </div>
                        <div class='col-75'>
                            <input class='textboxovi' type='text' id='korime2' name='korime2' maxlength='20' placeholder='korisničko ime'  required='required'>
                            <span id='provjera'></span>
                        </div>

                    </div>
                    <div class='row'>
                        <div class='col-25'>
                            <label class='labele' for='lozinka1'>Lozinka: </label>
                        </div>
                        <div class='col-75'>
                            <input class='textboxovi' type='password' id='lozinka1' name='lozinka1' size='15' placeholder='lozinka' required='required'>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-25'>
                            <label class='labele' for='lozinka2'>Ponovi pozinku: </label>
                        </div>
                        <div class='col-75'>
                            <input class='textboxovi' type='password' id='lozinka2' name='lozinka2' size='15'  placeholder='lozinka' required='required'>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='col-25'>
                            <label class='labele' for='email'>Email adresa: </label>
                        </div>
                        <div class='col-75'>
                            <input class='textboxovi' type='email' id='email' name='email' size='35' maxlength='35' placeholder='ime.prezime@posluzitelj.xxx' required='required'>
                        </div>
                    </div>


                    <div class='row'>
                        <div class='col-25'>
                            <label class='labele' for='kategorija'>Omijena Kategorija: </label>
                        </div>
                        <div class='col-75'>
                            <input class="obrazac" list="kategorije"  id="kategorija" name="kategorija" placeholder="Naziv kategorije">
                            <datalist id="kategorije">
                                <option value="Rock">Rock</option>
                                <option value="Pop">Pop</option>
                                <option value="Blues">Blues</option>
                                <option value="Heavy Metal">Heavy Metal</option>
                                <option value="Jazz">Jazz</option>
                            </datalist>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='col-25'>

                        </div>
                        <div class='col-75'>
                            <div class="g-recaptcha" data-sitekey="6LfdUVwUAAAAAELtB11CUoi7CwI12ufJsnQJuut3"></div>

                        </div>
                    </div>

                    <div class='row'>
                        <input name='registrirajse' type='submit'value=' Potvrdi '>

                    </div>
                </form>

        </section>

        <footer id="podnozje">
            <address>Kontakt:
                <a href="mailto:hsostaric@foi.hr">Hrvoje Šoštarić</a>
            </address>

        </footer>

    </body>
</html>
