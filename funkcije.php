<?php

require_once './baza.class.php';

function dohvatiVirtualnoVrijeme() {
    $veza = new Baza();
    $veza->spojiDB();
    $sql = "SELECT pomakSati FROM `Konfiguracija`";
    $rezultat = $veza->selectDB($sql);
    $red = $rezultat->fetch_assoc();
    $brojSati = $red['pomakSati'];
    $veza->zatvoriDB();
    $trenutno = time();
    $virtualno = $trenutno + ($brojSati * 3600);
    return date("Y-m-d H:i:s", $virtualno);
}
function trajanjeSesije() {
    $veza = new Baza();
    $veza->spojiDB();
    $sql = "SELECT trajanje_Sesije FROM `Konfiguracija`";
    $rez = $veza->selectDB($sql);
    $red = $rez->fetch_assoc();
    $trajanje_Sesije = $red['trajanje_Sesije'];
    $veza->zatvoriDB();
    return $trajanje_Sesije;
}

function trajanjeKolacica() {
    $veza = new Baza();
    $veza->spojiDB();
    $sql = "SELECT trajanjeKolacica FROM `Konfiguracija`";
    $rez = $veza->selectDB($sql);
    $red = $rez->fetch_assoc();
    $trajanje_Kolacica = $red['trajanjeKolacica'];
    $veza->zatvoriDB();
    return $trajanje_Kolacica;
}

function duljinaLozinke() {
    $veza = new Baza();
    $veza->spojiDB();
    $sql = "SELECT duljinaNoveLozinke FROM `Konfiguracija`";
    $rez = $veza->selectDB($sql);
    $red = $rez->fetch_assoc();
    $duljinaLozinke = $red['duljinaNoveLozinke'];
    $veza->zatvoriDB();
    return $duljinaLozinke;
}
function brojPokusaja() {
    $veza = new Baza();
    $veza->spojiDB();
    $sql = "SELECT brojPokusaja FROM `Konfiguracija`";
    $rez = $veza->selectDB($sql);
    $red1 = $rez->fetch_assoc();
    $brojPokusaja = $red1['brojPokusaja'];
    $veza->zatvoriDB();
    return $brojPokusaja;
}

function trajanjeAktivacijskog() {
    $veza = new Baza();
    $veza->spojiDB();
    $sql = "SELECT trajanjeAktivacijskog FROM `Konfiguracija`";
    $rez = $veza->selectDB($sql);
    $red = $rez->fetch_assoc();
    $trajanje = $red['trajanjeAktivacijskog'];
    $veza->zatvoriDB();
    return $trajanje;
}


