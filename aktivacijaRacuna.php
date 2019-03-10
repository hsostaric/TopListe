<?php
include './baza.class.php';
include './funkcije.php';

?>
<!DOCTYPE html>
<html>

    <head>
        <title>Aktivacija Racuna</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/hsostaric.css" rel="stylesheet" type="text/css">
         <link href="css/hsostaric_prilagodbe.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        <script src="js/hsostaric.js" type="text/javascript"></script>
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

        </nav>

        <section>  
              <div id="google_translate_element"></div>

            <div name="greske" style="text-align: center ; color: red; font-weight: bold; line-height:2">
                <?php
               
                $nadjen = false;
                $istekao=false;
                if (isset($_POST['aktiviraj'])) {
                    $email = $_POST['Email'];
                    $aktivacijski = $_POST['aktivacija'];
                    
                    $veza = new Baza();
                    $p = 0;
                    $sqlUpit = "Select *from korisnik where Email='" . $email . "' and AktivacijskiKod ='" . $aktivacijski . "' and Aktiviran='" . $p . "'";
                    $veza->spojiDB();
                    $rezultat = $veza->selectDB($sqlUpit);
                    $sad= dohvatiVirtualnoVrijeme();
                    $datumrege=$rezultat->fetch_assoc();
                    $traje= trajanjeAktivacijskog();
                    $datumisteka=date("Y-m-d H:i:s", strtotime($datumrege['Datum_registracije'] . " + " . $traje . " hours"));
                    if($datumisteka<=$sad){
                        $istekao=true;
       
                    }
                    else{
                        $istekao=false;
                    }
                    if ($rezultat->num_rows > 0) {
                        $nadjen = true;
                    } else {
                        $nadjen = false;
                    }
                    $veza->zatvoriDB();
                    if ($nadjen==true && $istekao==false) {
                        echo"<div >Vaš račun je aktiviran, sada se možete prijaviti</div>";
                        $veza = new Baza();
                        $veza->spojiDB();
                        $pom = 1;
                        $email1 = $_POST['Email'];
                        $akt = $_POST['aktivacija'];
                        $sqlUpdate = "UPDATE korisnik SET Aktiviran='" . $pom . "' WHERE Email='" . $email1 . "' AND AktivacijskiKod='" . $akt . "'";
                        $veza->updateDB($sqlUpdate);
                        $reg = 7;
                        $sql = "Select *from korisnik where Email='" . $email1 . "'";
                        $polje = $veza->selectDB($sql);
                        $rez = $polje->fetch_assoc();
                        $korisnik = $rez['korisnicko_ime'];
                       
                        $datum = dohvatiVirtualnoVrijeme();
                        $radnja = "Uspjesna aktivacija računa";
                        $sql2 = "UPDATE korisnik SET tip_korisnika_id='" . $reg . "' WHERE Email='" . $email1 . "' AND AktivacijskiKod='" . $akt . "'";
                        $dnevnik = "Insert into `dnevnik_rada` values('default','$korisnik','$datum','$radnja')";
                        $veza->updateDB($dnevnik);
                        $veza->updateDB($sql2);
                        $veza->zatvoriDB();
                    }
                    if($nadjen==true && $istekao==true){
                         $ema = $_POST['Email'];
                        $veza= new Baza();
                        $veza->spojiDB();
                        $novidatum= dohvatiVirtualnoVrijeme();
                         
                        $sol = sha1($novidatum);
                        $upit="Select *from korisnik where Email='".$ema."'";
                        $pomloz=$veza->selectDB($upit);
                        $novish=$pomloz->fetch_assoc();
                        $Lozinka=$novish['lozinka'];
                        $aktivacijskiKodnew=md5(rand(0, 1000));
                        $KriptiranaLozinka2 = sha1($sol . '-' . $Lozinka);
                        $sql="UPDATE`korisnik` SET `Datum_registracije` = '".$novidatum."', KriptiranaLozinka='".$KriptiranaLozinka2."', AktivacijskiKod='".$aktivacijskiKodnew."' WHERE Email='".$ema."'";
                        $veza->updateDB($sql);
                        echo"Poslan vam je novi aktivacijski kod jer vam je stari istekao";
                       
                        $primatelj = $ema;
                        $posiljatelj = "hsostaric@foi.hr";
                        $predmet = "Novi aktivacijski kod";
                        $poruka = "Ovo je vaš novi aktivacijski kod i stari podaci :\n\n"
                                . "------------------------------------\n\n "
                                . "\n Aktivacijski kod: " . $aktivacijskiKodnew . "\n"
                                . "------------------------------------\n\n";
                        $zaglavlje = 'Pošiljatelj:' . $posiljatelj . "\r\n";
                        mail($primatelj, $predmet, $poruka, $zaglavlje);
                        
                        $veza->zatvoriDB();
                    }
                    else if($nadjen==false){
                        $veza = new Baza();
                        $veza->spojiDB();
                        echo "<div>Unijeli ste krive podatke, pokušajte ponovno</div>";
                        $email1 = $_POST['Email'];
                        $sql = "Select *from korisnik where Email='" . $email1 . "'";
                        $polje = $veza->selectDB($sql);
                        $rez = $polje->fetch_assoc();
                        $korisnik = $rez['korisnicko_ime'];
                        
                        $datum = dohvatiVirtualnoVrijeme();
                        $radnja = "Neuspješna aktivacija racuna";
                        $dnevnik = "Insert into `dnevnik_rada` values('default','$korisnik','$datum','$radnja')";
                        $veza->updateDB($dnevnik);
                        $veza->zatvoriDB();
                    }
                }
                ?>

            </div>
            <div class='kontenjer2'>

                <form  novalidate id='PotvrdaRacuna' method='post' name='form3' action='aktivacijaRacuna.php'>
                    <div class='row'>
                        <div class='col-25'>
                            <label class='labele' for='Email'>E-mail: </label>
                        </div>
                        <div class='col-75'>
                            <input class='textboxovi' type='email' id='Email' name='Email'  placeholder='Unesite vas mail'>
                        </div></div>

                    <div class='row'>
                        <div class='col-25'>
                            <label class='labele' for='Aktivacija'>Aktivacija:</label>
                        </div>
                        <div class='col-75'>
                            <input class='textboxovi' type='text' id='aktivacija' name='aktivacija' size='30' maxlength='56' placeholder='Unesite aktivacijski kod'>
                        </div></div>

                    <div class='row'>
                        <input name='aktiviraj' id='aktiviraj' type='submit'value=' Potvrdi '>

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

