<?php
class Sesija {

    const KOSARICA = "kosarica";
    const SESSION_NAME = "prijava_sesija";

    static function kreirajSesiju() {
        session_name(self::SESSION_NAME);

        if (session_id() == "") {
            session_start();
        }
    }

    static function kreirajKorisnika($korisnik) {
        self::kreirajSesiju();
        $_SESSION['Email'] = $korisnik['Email'];
        $_SESSION['id_korisnik'] = $korisnik['id_korisnik'];
        $_SESSION['tip_korisnika_id'] = $korisnik['tip_korisnika_id'];
        $_SESSION['Pokusaj_Prijave'] = $korisnik['kriviPokusaj'];
        $_SESSION['korisnicko_ime'] = $korisnik['korisnicko_ime'];
    }

    static function kreirajKosaricu($kosarica) {
        self::kreirajSesiju();
        $_SESSION[self::KOSARICA] = $kosarica;
    }

    static function dajKorisnika() {
        self::kreirajSesiju();
        if (isset($_SESSION[self::KORISNIK])) {
            $korisnik = $_SESSION[self::KORISNIK];
        } else {
            return null;
        }
        return $korisnik;
    }

    static function dajKosaricu() {
        self::kreirajSesiju();
        if (isset($_SESSION[self::KOSARICA])) {
            $kosarica = $_SESSION[self::KOSARICA];
        } else {
            return null;
        }
        return $kosarica;
    }

//      Odjavljuje korisnika tj. bri�e sesiju
    static function obrisiSesiju() {
            session_unset();
            session_destroy();
    }

}
