<?php
include './baza.class.php';
$veza= new Baza();

$korisnickoIme= $_POST['korime2'];
$sql="Select count(*) as broj from korisnik where korisnicko_ime = '".$korisnickoIme."'";
$veza->spojiDB();
$rezultat=$veza->selectDB($sql);
$red=$rezultat->fetch_assoc();
$broj=$red['broj'];
$veza->zatvoriDB();
echo $broj;





