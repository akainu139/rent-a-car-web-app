<?php 

require("bazaConfig.php");

class Baza {

    private function konekcija()
    {
        $conn = new mysqli(SERVER, USERNAME, PASSWORD, DBNAME);
        $conn->set_charset('utf8');
        if ($conn->connect_error) {
            die("Greška kod spajanja: " . $conn->connect_error);
        } 
        return $conn;
    }

    // KORISNICI 
    function dohvatiSveKorisnike()
    {
        $conn = $this->konekcija();

        $sql = "SELECT id, korisnickoIme, ime, prezime, email, admin, vrijemeRegistracije FROM korisnici;";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $podaci[] = $row;
            }
        } else {
            $podaci = [];
        }
        $conn->close();
        return $podaci;
    }
    
    function dohvatiKorisnikaPoID($id)
    {
        $conn = $this->konekcija();

        $stmt = $conn->prepare("SELECT id, korisnickoIme, ime, prezime, email, admin, vrijemeRegistracije FROM korisnici WHERE id=?;");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            $pronadeniKorisnik = $result->fetch_assoc();
        } else {
            $pronadeniKorisnik = null;
        }
        $stmt->close();
        $conn->close();

        return $pronadeniKorisnik;
    }

    function obrisiKorisnikaPoID($id)
    {
        $conn = $this->konekcija();

        $stmt = $conn->prepare("DELETE FROM korisnici WHERE id = ?;");
        $stmt->bind_param("i", $id);
        $uspjeh = $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM rezervacije WHERE korisnikId = ?;");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        $conn->close();
        return $uspjeh;
    }

    function urediKorisnikaPoID($id, $ime, $prezime)
    {
        $conn = $this->konekcija();

        $stmt = $conn->prepare("UPDATE korisnici SET ime = ?, prezime = ? WHERE id = ?;");
        $stmt->bind_param("ssi", $ime, $prezime, $id);
        $uspjeh = $stmt->execute();
        $stmt->close();
        $conn->close();

        return $uspjeh;
    }

    function promjenaLozinkeKorisnika($id, $staraLozinka, $novaLozinka)
    {
        $conn = $this->konekcija();

        $stmt = $conn->prepare("SELECT lozinka FROM korisnici WHERE id=?;");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $uspjeh = false;
        if($result->num_rows > 0){
            $hashedLozinka = $result->fetch_assoc()["lozinka"];
            $provjeraLozinke = password_verify($staraLozinka, $hashedLozinka);
        }
        $stmt->close();
        if($provjeraLozinke)
        {
            $stmt = $conn->prepare("UPDATE korisnici SET lozinka = ? WHERE id = ?;");
            $stmt->bind_param("si", $novaLozinka, $id);
            $uspjeh = $stmt->execute();
            $stmt->close();
        }
        
        $conn->close();

        return $uspjeh;
    }

    function urediStanjeKorisnika($id, $stanje)
    {
        $conn = $this->konekcija();

        $stmt = $conn->prepare("UPDATE korisnici SET admin = ? WHERE id = ?;");
        $stmt->bind_param("ii", $stanje, $id);
        $uspjeh = $stmt->execute();
        $stmt->close();
        $conn->close();

        return $uspjeh;
    }

    // MARKE 
    function dohvatiSveMarke()
    {
        $conn = $this->konekcija();
    
        $sql = "SELECT id, marka, vrijemeDodavanja FROM marke;";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $podaci[] = $row;
            }
        } else {
            $podaci = [];
        }
        $conn->close();
        
        return $podaci;
    }

    function dohvatiMarkuPoID($id)
    {
        $conn = $this->konekcija();

        $stmt = $conn->prepare("SELECT id, marka, vrijemeDodavanja FROM marke WHERE id=?;");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            $pronadenaMarka = $result->fetch_assoc();
        } else {
            $pronadenaMarka = null;
        }
        $stmt->close();
        $conn->close();

        return $pronadenaMarka;
    }

    function obrisiMarkuPoID($id)
    {
        $conn = $this->konekcija();

        $stmt = $conn->prepare("DELETE FROM marke WHERE id = ?;");
        $stmt->bind_param("i", $id);
        $uspjeh = $stmt->execute();
        $stmt->close();
        $conn->close();

        return $uspjeh;
    }

    function urediMarkuPoID($id, $marka)
    {
        $conn = $this->konekcija();

        $stmt = $conn->prepare("UPDATE marke SET marka = ? WHERE id = ?;");
        $stmt->bind_param("si", $marka, $id);
        $uspjeh = $stmt->execute();
        $stmt->close();
        $conn->close();

        return $uspjeh;
    }
    
    function dodajMarku($novaMarka)
    {
        $conn = $this->konekcija();
        
        $stmt = $conn->prepare("INSERT INTO marke VALUES(NULL, ?, DEFAULT);");
        $stmt->bind_param("s", $novaMarka);
        $uspjeh = $stmt->execute();
        $stmt->close();
        $conn->close();

        return $uspjeh;
    }

    // VOZILA
    function dohvatiSvaVozila()
    {
        $conn = $this->konekcija();
    
        $sql = "SELECT vozila.id, marka, model, opis, cijenaPoDanu, godinaProizvodnje, prijedeniKilometri, motor, brojSjedala, klimaUredaj, usb, radio, navigacija, vozila.vrijemeDodavanja FROM vozila JOIN marke ON vozila.markaId=marke.id ORDER BY vozila.id;";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $podaci[] = $row;
            }
        } else {
            $podaci = [];
        }
        $conn->close();
        
        return $podaci;
    }

    function dohvatiVoziloPoID($id)
    {
        $conn = $this->konekcija();

        $stmt = $conn->prepare("SELECT vozila.id, markaId, marka, model, opis, cijenaPoDanu, godinaProizvodnje, prijedeniKilometri, motor, brojSjedala, klimaUredaj, usb, radio, navigacija, vozila.vrijemeDodavanja FROM vozila JOIN marke ON vozila.markaId=marke.id WHERE vozila.id=?;");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            $pronadenoVozilo = $result->fetch_assoc();
        } else {
            $pronadenoVozilo = null;
        }
        $stmt->close();
        $conn->close();

        return $pronadenoVozilo;
    }

    function dohvatiBrojVozilaPoMarki($id)
    {
        $conn = $this->konekcija();
    
        $stmt = $conn->prepare("SELECT COUNT(marke.id) AS brojVozila FROM marke JOIN vozila ON marke.id = vozila.markaId WHERE vozila.markaId = ?;");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $brojVozila=0;
        if ($result->num_rows > 0) {
            $brojVozila = $result->fetch_assoc()["brojVozila"];
        }
        
        $stmt->close();
        $conn->close();
        
        return $brojVozila;
    }

    function obrisiVoziloPoID($id)
    {
        $conn = $this->konekcija();

        $stmt = $conn->prepare("DELETE FROM vozila WHERE id = ?;");
        $stmt->bind_param("i", $id);
        $uspjeh = $stmt->execute();
        $stmt->close();
        $conn->close();

        return $uspjeh;
    }

    function urediVoziloPoID($id, $markaId, $model, $opis, $cijenaPoDanu, $godinaProizvodnje, $prijedeniKilometri, $motor, $brojSjedala, $klimaUredaj, $usb, $radio, $navigacija)
    {
        $conn = $this->konekcija();
      
        $stmt = $conn->prepare("UPDATE vozila SET markaId=?, model=?, opis=?, cijenaPoDanu=?, godinaProizvodnje=?, prijedeniKilometri=?, motor=?, brojSjedala=?, klimaUredaj=?, usb=?, radio=?, navigacija=? WHERE id = ?;");
        $stmt->bind_param("issdiisiiiiii", $markaId, $model, $opis, $cijenaPoDanu, $godinaProizvodnje, $prijedeniKilometri, $motor, $brojSjedala, $klimaUredaj, $usb, $radio, $navigacija, $id);
        $uspjeh = $stmt->execute();
        $stmt->close();
        $conn->close();

        return $uspjeh;
    }
    
    function dodajVozilo($markaId, $model, $opis, $cijenaPoDanu, $godinaProizvodnje, $prijedeniKilometri, $motor, $brojSjedala, $klimaUredaj, $usb, $radio, $navigacija)
    {
        $conn = $this->konekcija();
        
        $stmt = $conn->prepare("INSERT INTO vozila VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, DEFAULT);");
        $stmt->bind_param("issdiisiiiii", $markaId, $model, $opis, $cijenaPoDanu, $godinaProizvodnje, $prijedeniKilometri, $motor, $brojSjedala, $klimaUredaj, $usb, $radio, $navigacija);
        $uspjeh = $stmt->execute();
        $stmt->close();
        $conn->close();

        return $uspjeh;
    }

    function dohvatiIDPosljednjegVozila()
    {
        $conn = $this->konekcija();
    
        $sql = "SELECT MAX(id) as id FROM vozila;";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $id = $result->fetch_assoc()["id"];
        } else {
            $id = null;
        }
        $conn->close();
        
        return $id;
    }

    // rezervacije
    function dohvatiRezerviranaVozila()
    {
        $conn = $this->konekcija();

        $stmt = $conn->prepare("SELECT vozila.id AS voziloId, rezervacije.id, korisnickoIme, ime, prezime, marke.marka, model, vrijemeOd, vrijemeDo, odsutno, vrijemeRezervacije FROM rezervacije JOIN vozila ON vozila.id=rezervacije.voziloId JOIN marke ON marke.id=vozila.markaId JOIN korisnici ON korisnici.id = rezervacije.korisnikId;");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $podaci[] = $row;
            }
        } else {
            $podaci = [];
        }
        $stmt->close();
        $conn->close();

        return $podaci;
    }

    function dohvatiOdsutnaVozila()
    {
        $conn = $this->konekcija();

        $stmt = $conn->prepare("SELECT rezervacije.id, korisnickoIme, ime, prezime, marke.marka, model, vrijemeOd, vrijemeDo, vrijemeRezervacije FROM rezervacije JOIN vozila ON vozila.id=rezervacije.voziloId JOIN marke ON marke.id=vozila.markaId JOIN korisnici ON korisnici.id = rezervacije.korisnikId WHERE odsutno = 1;");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $podaci[] = $row;
            }
        } else {
            $podaci = [];
        }
        $stmt->close();
        $conn->close();

        return $podaci;
    }

    function dohvatiPrisutnaVozila()
    {
        $conn = $this->konekcija();

        $stmt = $conn->prepare("SELECT rezervacije.id, korisnickoIme, ime, prezime, marke.marka, model, vrijemeOd, vrijemeDo, vrijemeRezervacije FROM rezervacije JOIN vozila ON vozila.id=rezervacije.voziloId JOIN marke ON marke.id=vozila.markaId JOIN korisnici ON korisnici.id = rezervacije.korisnikId WHERE odsutno = 0;");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $podaci[] = $row;
            }
        } else {
            $podaci = [];
        }
        $stmt->close();
        $conn->close();

        return $podaci;
    }

    function dohvatiBrojRezerviranihVozilaPoID($id)
    {
        $conn = $this->konekcija();
    
        $stmt = $conn->prepare("SELECT COUNT(rezervacije.voziloId) AS brojVozila FROM rezervacije JOIN vozila ON rezervacije.voziloId = vozila.id WHERE rezervacije.voziloId = ?;");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $brojVozila=0;
        if ($result->num_rows > 0) {
            $brojVozila = $result->fetch_assoc()["brojVozila"];
        }
        
        $stmt->close();
        $conn->close();
        
        return $brojVozila;
    }

    function otkaziRezervaciju($id)
    {
        $conn = $this->konekcija();

        $stmt = $conn->prepare("SELECT naplatniKod FROM rezervacije WHERE id = ?;");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $_SESSION["naplatniKod"] = "";
        if ($result->num_rows > 0) {
            $_SESSION["naplatniKod"] = $result->fetch_assoc()["naplatniKod"];
        }
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM rezervacije WHERE id = ?;");
        $stmt->bind_param("i", $id);
        $uspjeh = $stmt->execute();
        $stmt->close();
        $conn->close();

        return $uspjeh;
    }

    function dohvatiRezervacijePoVozilu($id)
    {
        $conn = $this->konekcija();

        $stmt = $conn->prepare("SELECT vrijemeOd, vrijemeDo, odsutno FROM rezervacije WHERE voziloId = ?;");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $podaci[] = $row;
            }
        } else {
            $podaci = [];
        }
        $stmt->close();
        $conn->close();
        
        return $podaci;
    }

    function urediStanjeVozila($id, $stanje)
    {
        $conn = $this->konekcija();

        $stmt = $conn->prepare("UPDATE rezervacije SET odsutno = ? WHERE id = ?;");
        $stmt->bind_param("ii", $stanje, $id);
        $uspjeh = $stmt->execute();
        $stmt->close();
        $conn->close();

        return $uspjeh;
    }

}
?>
