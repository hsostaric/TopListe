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
            <div class="pozicija2">
                <h2>Obrazac zahtjeva za oglas</h2>
                <form novalidate id = 'prijava' enctype="multipart/form-data" method = 'post' name = 'form1' action ='predajZahtjev.php'>
                    <div class="row">
                        <div class="col-75">
                            <input class="obrazac" type="hidden" name="MAX_FILE_SIZE" value='3000000'  />
                            Odaberi sliku: <input name="userfile" type="file" />
                        </div>
                        <div class="col-25">
                            <label for="odabir">Odabir datoteke</label>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label class="labele" for="nazivoglasa">Naziv oglasa </label>
                        </div>
                        <div class="col-75">
                            <input class="textboxovi" type="text" id="nazivoglasa" name="nazivoglasa"  placeholder="Unesite naziv oglasa">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-25">
                            <label class="labele" for="opisoglasa">Opis</label>
                        </div>

                        <div class='col-75'>
                            <textarea class="odredjeno"  rows="40" cols="60" id="nazivoglasa" name="opisoglasa"  placeholder="Unesite opis oglasa oglasa"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-25">
                            <label class="labele" for="url">Url oglasa</label>
                        </div>
                        <div class="col-75">
                            <input class="textboxovi" type="text" id="urloglasa" name="urlOglasa"  placeholder="Unesite naziv oglasa">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-25">
                            <label class="labele" for="url">Početak aktivnosti oglasa</label>
                        </div>
                        <div class="col-75">
                            <input class="textboxovi" type="datetime-local" id="planiraniPocetak" name="pocetakPrikazivanja" >
                        </div>
                    </div>
                   

                    <div class="row">
                        <div class="col-25">
                            <label  for="ogranicavanje">Vrsta oglasa</label>
                        </div>
                        <div class="col-75">

                            <?php
                            $veza = new Baza();
                            $veza->spojiDB();
                            $sql = "Select *from vrsta_oglasa";
                            $rezultat = $veza->selectDB($sql);
                            echo"<select name='vrstaOglasa' class='obrazac'>";
                            while ($red = $rezultat->fetch_assoc()) {
                                 echo "<option name='Vrsta' value='" . $red['id_vrsta_oglasa'] . "'>" . $red['Naziv'] . "</option>";
                            }
                            echo"</select>";
                            ?>
                        </div>

                    </div>

                    <div class="row">
                        <input name="predajOglas" type="submit" value="Predaj oglas" id="potvrda">
                    </div>
                    <?php
                    if (isset($_POST['predajOglas'])) {
                        $naziv = $_POST['nazivoglasa'];
                        $opisOglasa = $_POST['opisoglasa'];
                        $urlOglasa = $_POST['urlOglasa'];
                        $pom=$_POST['pocetakPrikazivanja'];
                        $vrstaOglasa=$_POST['vrstaOglasa'];
                        $pocetak = date("Y-m-d H:i:s", strtotime($pom));
                       
                        $veza= new Baza();
                        $veza->spojiDB();
                        $sql="Select *from vrsta_oglasa where id_vrsta_oglasa ='".$vrstaOglasa."'";
                        $traje=$veza->selectDB($sql);
                        
                        $trajanje = $traje->fetch_assoc();
                        $kraj = date("Y-m-d H:i:s", strtotime($pocetak. " + ".$trajanje['trajanje_prikazivanja']." hours"));
                        
                        echo $_SESSION['id'];
                        $slika = $_FILES['userfile']['name'];
                        $korisnikid = $_SESSION['id'];
                        echo $slika."  ".$pocetak."   ".$kraj."   ".$urlOglasa."   ".$slika."  ".$korisnikid."   ".$opisOglasa;
                        $veza->spojiDB();
                       
                        
                         $radnja = "Predan zahtjev za novi oglas";
                         $datum = dohvatiVirtualnoVrijeme();
                         $kor = $_SESSION["korime"];
                          $dnevnik = "Insert into `dnevnik_rada` values('default','$kor','$datum','$radnja')";
                            $veza->updateDB($dnevnik);
                        
                        $sql = "INSERT INTO oglas values('default','$naziv','$vrstaOglasa','$korisnikid','$opisOglasa','$urlOglasa','$pocetak ','$kraj','$slika','0','0','0');";
                        $veza->updateDB($sql);
                        $veza->zatvoriDB();
                        $userfile = $_FILES['userfile']['tmp_name'];
                        $userfile_name = $_FILES['userfile']['name'];
                        $userfile_size = $_FILES['userfile']['size'];
                        $userfile_type = $_FILES['userfile']['type'];
                        $userfile_error = $_FILES['userfile']['error'];
                        if ($userfile_error > 0) {
                            echo 'Problem: ';
                            switch ($userfile_error) {
                                case 1: echo 'Veličina veća od ' . ini_get('upload_max_filesize');
                                    break;
                                case 2: echo 'Veličina veća od ' . $_POST["MAX_FILE_SIZE"] . 'B';
                                    break;
                                case 3: echo 'Datoteka djelomično prenesena';
                                    break;
                                case 4: echo 'Datoteka nije prenesena';
                                    break;
                            }
                            exit;
                        }

                        $upfile = 'slike/' . $userfile_name;

                        if (is_uploaded_file($userfile)) {
                            if (!move_uploaded_file($userfile, $upfile)) {
                                echo 'Problem: nije moguće prenijeti datoteku na odredište';
                                exit;
                            }
                        } else {
                            echo 'Problem: mogući napad prijenosom. Datoteka: ' . $userfile_name;
                            exit;
                        }

                        echo 'Datoteka uspješno prenesena<br /><br />';
                       
                    }
                    ?>



            </div>

        </div>


    </form>



</section>

<footer id = "podnozje">
    <address>Kontakt:
        <a href = "mailto:hsostaric@foi.hr">Hrvoje Šoštarić</a>
    </address>

</footer>

</body>
</html>
