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

    // VOZILA 
    function dohvatiSvaVozila($start, $limit)
    {
        $conn = $this->konekcija();
    
        $sql = "SELECT vozila.id, marka, model, opis, cijenaPoDanu, godinaProizvodnje, prijedeniKilometri, motor, brojSjedala, klimaUredaj, usb, radio, navigacija, vozila.vrijemeDodavanja FROM vozila JOIN marke ON vozila.markaId=marke.id ORDER BY vozila.id LIMIT $start, $limit;";
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

    function dohvatiBrojSvihVozila()
    {
        $conn = $this->konekcija();
    
        $sql = "SELECT COUNT(vozila.id) AS brojVozila FROM vozila;";
        $result = $conn->query($sql);
        $brojVozila=0;
        if ($result->num_rows > 0) {
            $brojVozila = $result->fetch_assoc()["brojVozila"];
        }
        
        $conn->close();
        
        return $brojVozila;
    }

    function dohvatiDostupnaVozila($vrijemeOd, $vrijemeDo, $start, $limit)
    {
        $conn = $this->konekcija();
        $stmt = $conn->prepare("SELECT DISTINCT(vozila.id), marke.marka, model, opis, cijenaPoDanu, godinaProizvodnje, prijedeniKilometri, motor, brojSjedala, klimaUredaj, usb, radio, navigacija FROM vozila JOIN marke ON marke.id=vozila.markaId WHERE NOT EXISTS (SELECT 1 FROM rezervacije WHERE rezervacije.voziloId = vozila.id AND vrijemeOd <= ? AND vrijemeDo >= ?) LIMIT ?, ?;");
        $stmt->bind_param("ssii", $vrijemeDo, $vrijemeOd, $start, $limit);
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

        $stmt = $conn->prepare("SELECT DISTINCT(vozila.id), marke.marka, model, opis, cijenaPoDanu, godinaProizvodnje, prijedeniKilometri, motor, brojSjedala, klimaUredaj, usb, radio, navigacija FROM vozila JOIN marke ON marke.id=vozila.markaId WHERE NOT EXISTS (SELECT 1 FROM rezervacije WHERE rezervacije.voziloId = vozila.id AND vrijemeOd <= ? AND vrijemeDo >= ?);");
        $stmt->bind_param("ss", $vrijemeDo, $vrijemeOd);
        $stmt->execute();
        $result = $stmt->get_result();
        $_SESSION["dostupnaVozilaIds"] = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $_SESSION["dostupnaVozilaIds"][] = $row["id"];
            }
        }
        $stmt->close();
        $conn->close();
        
        return $podaci;
    }

    function dohvatiBrojDostupnihVozila($vrijemeOd, $vrijemeDo)
    {
        $conn = $this->konekcija();

        $stmt = $conn->prepare("SELECT COUNT(DISTINCT(vozila.id)) AS brojVozila FROM vozila WHERE NOT EXISTS (SELECT 1 FROM rezervacije WHERE rezervacije.voziloId = vozila.id AND vrijemeOd <= ? AND vrijemeDo >= ?);");
        $stmt->bind_param("ss", $vrijemeDo, $vrijemeOd);
        $stmt->execute();
        $result = $stmt->get_result();
        $brojVozila = 0;
        if ($result->num_rows > 0) {
            $brojVozila = $result->fetch_assoc()["brojVozila"];
        } 
        
        $stmt->close();
        $conn->close();

        return $brojVozila;
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
    
    // KORISNICI 
    function dodajKorisnika($korisnickoIme, $ime, $prezime, $email, $hashedLozinka)
    {
        $conn = $this->konekcija();
        
        $stmt = $conn->prepare("INSERT INTO korisnici VALUES(NULL, ?, ?, ?, ?, ?, DEFAULT, DEFAULT);");
        $stmt->bind_param("sssss", $korisnickoIme, $ime, $prezime, $email, $hashedLozinka);
        $stmt->execute();
        $posljednjiId = $conn->insert_id;
        $stmt->close();
        $conn->close();

        return $posljednjiId;
    }

    function dohvatiKorisnikaPoID($id)
    {
        $conn = $this->konekcija();

        $stmt = $conn->prepare("SELECT id, korisnickoIme, ime, prezime, email, vrijemeRegistracije FROM korisnici WHERE id=?;");
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

    function provjeraKorisnickogImena($korisnickoIme)
    {
        $conn = $this->konekcija();

        $stmt = $conn->prepare("SELECT korisnickoIme FROM korisnici WHERE korisnickoIme=?;");
        $stmt->bind_param("s", $korisnickoIme);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            $korisnikPostoji = true;
        } else {
            $korisnikPostoji = false;
        }
        $stmt->close();
        $conn->close();

        return $korisnikPostoji;
    }
    
    function provjeraEmaila($email)
    {
        $conn = $this->konekcija();

        $stmt = $conn->prepare("SELECT email FROM korisnici WHERE email=?;");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            $emailPostoji = true;
        } else {
            $emailPostoji = false;
        }
        $stmt->close();
        $conn->close();

        return $emailPostoji;
    }

    function provjeraKorisnika($korisnickoIme, $lozinka)
    {
        $conn = $this->konekcija();

        $stmt = $conn->prepare("SELECT id, korisnickoIme, ime, prezime, email, lozinka, admin FROM korisnici WHERE korisnickoIme=?;");
        $stmt->bind_param("s", $korisnickoIme);
        $stmt->execute();
        $result = $stmt->get_result();
        $korisnik = null;
        if($result->num_rows > 0){
            $korisnik = $result->fetch_assoc();
            $hashedLozinka = $korisnik["lozinka"];
            $provjeraLozinke = password_verify($lozinka, $hashedLozinka);
            if(!$provjeraLozinke)
            {
                $korisnik=null;
            }
        }
        $stmt->close();
        $conn->close();

        return $korisnik;
    }

    // REZERVACIJE
    function dodajRezervaciju($korisnikId, $voziloId, $vrijemeOd, $vrijemeDo, $sveukupnoZaPlacanje, $naplatniKod)
    {
        $conn = $this->konekcija();
        
        $stmt = $conn->prepare("INSERT INTO rezervacije VALUES(NULL, ?, ?, ?, ?, ?, ?, NULL, DEFAULT, DEFAULT);");
        $stmt->bind_param("iissds", $korisnikId, $voziloId, $vrijemeOd, $vrijemeDo, $sveukupnoZaPlacanje, $naplatniKod);
        $uspjeh = $stmt->execute();
        $stmt->close();
        $conn->close();

        return $uspjeh;
    }

    function dohvatiRezervacijePoKorisniku($korisnikId, $start, $limit, $stanje="sve")
    {
        $conn = $this->konekcija();
        if($stanje == "sve")
        {
            $stmt = $conn->prepare("SELECT rezervacije.id AS rezervacijaId, vozila.id, marke.marka, model, opis, cijenaPoDanu, godinaProizvodnje, prijedeniKilometri, motor, brojSjedala, klimaUredaj, usb, radio, navigacija, vrijemeOd, vrijemeDo, sveukupnoZaPlacanje, vrijemeRezervacije FROM rezervacije JOIN vozila ON vozila.id=rezervacije.voziloId JOIN marke ON marke.id=vozila.markaId WHERE korisnikId = ? ORDER BY vrijemeRezervacije DESC LIMIT ?, ?;");
            $stmt->bind_param("iii", $korisnikId, $start, $limit);
        }
        else {
            $stmt = $conn->prepare("SELECT rezervacije.id AS rezervacijaId, vozila.id, marke.marka, model, opis, cijenaPoDanu, godinaProizvodnje, prijedeniKilometri, motor, brojSjedala, klimaUredaj, usb, radio, navigacija, vrijemeOd, vrijemeDo, sveukupnoZaPlacanje, vrijemeRezervacije FROM rezervacije JOIN vozila ON vozila.id=rezervacije.voziloId JOIN marke ON marke.id=vozila.markaId WHERE korisnikId = ? AND vremenskoStanje = ? ORDER BY vrijemeRezervacije DESC LIMIT ?, ?;");
            $stmt->bind_param("isii", $korisnikId, $stanje, $start, $limit);
        }
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

    function dohvatiBrojRezervacijaPoKorisniku($korisnikId, $stanje = "sve")
    {
        $conn = $this->konekcija();

        if($stanje == "sve")
        {
            $stmt = $conn->prepare("SELECT COUNT(rezervacije.id) AS brojRezervacija FROM rezervacije WHERE korisnikId = ?;");
            $stmt->bind_param("i", $korisnikId);
        }
        else {
            $stmt = $conn->prepare("SELECT COUNT(rezervacije.id) AS brojRezervacija FROM rezervacije WHERE korisnikId = ? AND vremenskoStanje=?;");
            $stmt->bind_param("is", $korisnikId, $stanje);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $brojRezervacija = 0;
        if ($result->num_rows > 0) {
            $brojRezervacija = $result->fetch_assoc()["brojRezervacija"];
        }
        $stmt->close();
        $conn->close();

        return $brojRezervacija;
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

    function urediVremenskoStanjeRezervacije($rezervacijaId, $stanje)
    {
        $conn = $this->konekcija();

        $stmt = $conn->prepare("UPDATE rezervacije SET vremenskoStanje = ? WHERE id = ?;");
        $stmt->bind_param("si", $stanje, $rezervacijaId);
        $uspjeh = $stmt->execute();
        $stmt->close();
        $conn->close();

        return $uspjeh;
    }
    
}
?>
