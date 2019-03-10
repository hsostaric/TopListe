<?php
include './baza.class.php';
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
        <meta name="Naziv" content="Početna stranica">
        <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        <script src="js/hsostaric.js" type="text/javascript"></script>
        <meta name="Datum posljednje promjene" content="17.03.2018.">
        <meta name="Autor" content="Hrvoje Šoštarić">


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
            <?php
            ?>
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
                            <label  for="ogranicavanje">Duljina trajanja</label>
                        </div>
                        <div class="col-75">
                            <input name="daniTrajanja" class="obrazac" type="number" id="ogranicavanje" min=1 max=1000>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-25">
                            <label  for="ogranicavanje">Vrsta oglasa</label>
                        </div>
                        <div class="col-75">

                        </div>

                    </div>

                    <div class="row">
                        <input name="predajOglas" type="submit" value="Predaj oglas" id="potvrda">
                    </div>
                    <?php
                    
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
