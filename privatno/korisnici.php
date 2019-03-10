<?php
include '../baza.class.php';
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Popis korisnika</title>';
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../css/hsostaric.css" rel="stylesheet" type="text/css">
        <meta name="Naziv" content="Početna stranica">
        <meta name="Datum posljednje promjene" content="17.03.2018.">
        <meta name="Autor" content="Hrvoje Šoštarić">
        <script type="text/javascript" src="js/provjera.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>   
        <script src="../js/hsostaric_jquery.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
        <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>

    </head>
    <body>

        <header>
            <h1 class="naslovniTekst"> Domaća zadaća 1</h1> 

            <nav class="obicniTekst"> <a  href="prijava.php">Prijava</a></nav>
        </header>
        <nav class="izgledNavigacije">

            <a class="izgledUnutrasnjostiNavigacije" href="../pocetak.php">Početna stranica </a>
            <a class="izgledUnutrasnjostiNavigacije" href="galerija.html">Galerija slika</a>
            <?php
            if (!isset($_SESSION['korime'])) {
                 echo "<a class='izgledUnutrasnjostiNavigacije' href='../prijava.php'>Prijava</a>";
                echo "<a class='izgledUnutrasnjostiNavigacije'href='../registracija.php'>Registracija</a>";
            } else {

                
                echo "<a class='izgledUnutrasnjostiNavigacije' href='../odjava.php'>Odjava</a>";
            }
            ?>
           
            
           
            <a class="izgledUnutrasnjostiNavigacije" href="korisnici.php">Korisnici</a>
        <?php
            if (isset($_SESSION['id']) && $_SESSION['uloga'] == 9) {
                echo"<a class='izgledUnutrasnjostiNavigacije'href='otkljucavanjeKorisnika.php'>Rad s korisnicima</a>";
            }
            ?>

        </nav>

        <section> 

            <h2 style='text-align: center;'>Popis korisnika </h2>";
            <div class='pozicija' > 


                <?php
                
                $veza = new Baza();

                if(isset($_GET['order'])){
                     $order=$_GET['order'];
                 }
                 else{
                     $order='Uloga';
                 }
                 
                 if(isset($_GET['sort'])){
                     $sort= $_GET['sort'];
                 }
                 else{
                     $sort='ASC';
                 }
                
                $veza->spojiDB();
                $sql = "SELECT `korisnicko_ime`,`prezime`,`ime`,`Email`,`lozinka`,\n"
                        . "(SELECT `tip_korisnika`.`naziv` AS `Uloga` \n"
                        . "FROM `tip_korisnika` \n"
                        . "WHERE `tip_korisnika`.`id_tip_korisnika` = `korisnik`.`tip_korisnika_id` ) as `Uloga`\n"
                        . " FROM `korisnik` ORDER BY $order $sort";
                $moje = $veza->selectDB($sql);

                if ($moje->num_rows > 0) {
                     $sort=='DESC' ? $sort='ASC':$sort ='DESC';
                    echo "<table border='1' id='table_id' class='display'>
                        <thead>
                    
                        <tr >
                        <th><a href='?order=korisnicko_ime&&sort=$sort'>Korisničko ime</th>
                        <th>Prezime</th>
                        <th class='a'>Ime</th>
                        <th class='a'>Email</th>
                        <th  class='a'>Lozinka</th>
                        <th  class='a'><a href='?order=Uloga&&sort=$sort'>Vrsta korisnika</th>
                    </tr></thead><tbody>";
                    while ($row = $moje->fetch_assoc()) {
                        echo"<tr><td>" . $row['korisnicko_ime'] . "</td>
                        
                        <td class='a'>" . $row['prezime'] . "</td>
                        <td class='a'>" . $row['ime'] . "</td>
                        <td class='a'>" . $row['Email'] . "</td>
                        <td class='a'>" .$row['lozinka']. "</td>
                        <td class='a'>" . $row['Uloga'] . "</td>
                    </tr>";
                    }
                    echo"</tbody></table>";
                } else {
                    print 'Nema zapisa u bazi';
                }



                $veza->zatvoriDB();
                ?>


            </div>





        </section>

        <footer id="podnozje">
            <address>Kontakt:
                <a href="mailto:hsostaric@foi.hr">Hrvoje Šoštarić</a>
            </address>

        </footer>

    </body>
</html>


