<?php
include './baza.class.php';
    include './funkcije.php';
include './sesija.class.php';
$sesija = new Sesija();
$sesija::kreirajSesiju();

if(!isset($_SESSION['uloga'])){
    header("Location:pocetak.php");
}
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
             <br><br>
            <?php
            $veza= new Baza();
            $veza->spojiDB();
            $id=$_SESSION['id'];
            $sql="Select *from oglas where korisnik_id_korisnik='".$id."'";
            $pom=$veza->selectDB($sql);
            $veza->zatvoriDB();
            if($pom->num_rows>0){
                while($red=$pom->fetch_assoc()){
                    $id=$red['id_zahtjev_za_oglas'];
                echo"<div class='responsive'><div class='gallery'>"
                . "";
                 if($red['odobren']==0){echo"<a href='urediMojOglas.php?oglas_id=$id'>";}
                echo"<img src='slike/".$red['slika']."' width='600' height='400'><div class='desc'>";
                if($red['odobren']==0){
                    echo"</a>Stanje čekanja";
                }
                else{
                    echo"Odobreno";
                }
                echo"</div></div></div>";
            }}
            else{
                echo"<div style='text-align:center;color:red;background-color:white'>"
                . "<p>Nema oglasa za prikazati</p></div>";
            }
            echo "<div class='clearfix'></div>";
            ?>

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
