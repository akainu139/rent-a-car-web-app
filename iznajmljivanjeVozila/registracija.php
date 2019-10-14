<?php 
    session_start();
    $aktivnaStranica = "registracija";
    include("includes/header.php"); 

    if(isset($_SESSION["korisnikId"]))
    {
        header("Location: index.php");
        exit;
    }

    include("includes/baza.php");
    $baza = new Baza();

    $korisnickoIme = "";
    $ime = "";
    $prezime = "";
    $email = "";

    $greske = array("korisnickoIme" => "", "ime" => "","prezime" => "","email" => "","lozinka" => "","lozinka2" => "","status" => "");

    if(isset($_POST["predajRegistraciju"]))
    {
        $korisnickoIme = trim(htmlentities($_POST["korisnickoIme"]));
        $ime = trim(htmlentities($_POST["ime"]));
        $prezime = trim(htmlentities($_POST["prezime"]));
        $email = trim(htmlentities($_POST["email"]));
        $lozinka = htmlentities($_POST["lozinka"]);
        $lozinka2 = htmlentities($_POST["lozinka2"]);

        if(!preg_match("/^[a-zA-Z0-9-_]+$/", $korisnickoIme)){
            $greske["korisnickoIme"] = "Korisničko ime mora biti jedna riječ te smije sadržavati samo brojeve, slova te znakove \"-\" i \"_\"."; 
            $greske["status"] = "greska";
        }
        if(strlen($korisnickoIme) < 3 || strlen($korisnickoIme) > 20){
            $greske["korisnickoIme"] = "Korisničko ime mora imati između 3 i 20 znakova."; 
            $greske["status"] = "greska";
        }
        if(!preg_match("/^[a-zA-Z- ]+$/", $ime)){
            $greske["ime"] = "Ime smije sadržavati samo slova i znak \"-\".";
            $greske["status"] = "greska";
        }
        if(strlen($ime) > 30){
            $greske["ime"] = "Ime ne smije sadržavati više od 30 znakova.";
            $greske["status"] = "greska";
        }
        if(!preg_match("/^[a-zA-Z- ]+$/", $prezime)){
            $greske["prezime"] = "Prezime smije sadržavati samo slova i znak \"-\".";
            $greske["status"] = "greska";
        }
        if(strlen($prezime) > 30){
            $greske["prezime"] = "Prezime ne smije sadržavati više od 30 znakova.";
            $greske["status"] = "greska";
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $greske["email"] = "Adresa e-pošte nije ispravna.";
            $greske["status"] = "greska";
        }
        $provjeraEmaila = $baza->provjeraEmaila($email);
        if($provjeraEmaila){
            $greske["email"] = "Adresa e-pošte je zauzeta.";
            $greske["status"] = "greska";
        }
        if($lozinka !== $lozinka2){
            $greske["lozinka"] = "Lozinke se ne poklapaju.";
            $greske["lozinka2"] = "Lozinke se ne poklapaju.";
            $greske["status"] = "greska";
        }
        if(strlen($lozinka) > 50 || strlen($lozinka) < 8){
            $greske["lozinka"] = "Lozinka mora imati između 8 i 50 znakova.";
            $greske["status"] = "greska";
        }
        if(strlen($lozinka2) > 50 || strlen($lozinka2) < 8){
            $greske["lozinka2"] = "Lozinka mora imati između 8 i 50 znakova.";
            $greske["status"] = "greska";
        }
        $provjeraKorisnickogImena = $baza->provjeraKorisnickogImena($korisnickoIme);
        if($provjeraKorisnickogImena){
            $greske["korisnickoIme"] = "Korisničko ime je zauzeto.";
            $greske["status"] = "greska";
        }

        if($greske["status"] == "")
        {
            $hashedLozinka = password_hash($lozinka, PASSWORD_DEFAULT);
            $ime = ucfirst(strtolower($ime));
            $prezime = ucfirst(strtolower($prezime));

            $posljednjiIdKorisnika = $baza->dodajKorisnika($korisnickoIme, $ime, $prezime, $email, $hashedLozinka);
            if($posljednjiIdKorisnika)
            {
                $_SESSION["korisnikId"] = $posljednjiIdKorisnika;
                $_SESSION["korisnickoIme"] = $korisnickoIme;
                $_SESSION["email"] = $email;
                if(isset($_SESSION["tempVozilo"]))
                {
                    $id = $_SESSION["tempVozilo"];
                    unset($_SESSION['tempVozilo']);
                    header("Location: detaljno.php?id=$id");
                    exit;
                }

                header("Location: index.php");
                exit;
            }
            else {
                $greske["status"] = "<h4 class='my-3 alert alert-danger text-center'>Došlo je do pogreške tijekom registracije.</h4>";
            }
        }
        else {
            $greske["status"] = "<h4 class='my-3 alert alert-danger text-center'>Ispravite navedene pogreške i pokušajte ponovno.</h4>";
        }
    }

?>
<script>
    document.body.style.backgroundImage = "url('img/prijavaBG.jpg')";
    document.body.style.backgroundRepeat = "no-repeat";
    document.body.style.backgroundAttachment = "fixed";
    document.body.style.backgroundPosition = "center";
    document.body.style.backgroundSize = "cover";
</script>
<form action="registracija.php" method="post" class="mt-3 p-3" id="registracijaForm">
    <h1 class="text-center">Registracija</h1>
    <h4 class="text-center">Unesite svoje podatke</h4>
    <?php echo $greske["status"]; ?>

    <div class="form-group">
        <label for="korisnickoIme">Korisničko ime</label>
        <input type="text" name="korisnickoIme" id="korisnickoIme" class="form-control <?php echo ($greske["korisnickoIme"] == "" ? "":"is-invalid"); ?>" minLength="3" maxLength="20" value="<?php echo $korisnickoIme ?>" required>
        <div class="invalid-feedback"><?php echo $greske["korisnickoIme"]; ?></div>
    </div>

    <div class="form-group">
        <label for="ime">Ime</label>
        <input type="text" name="ime" id="ime" class="form-control <?php echo ($greske["ime"] == "" ? "":"is-invalid"); ?>" maxLength="30" value="<?php echo $ime ?>" required>
        <div class="invalid-feedback"><?php echo $greske["ime"]; ?></div>
    </div>

    <div class="form-group">
        <label for="prezime">Prezime</label>
        <input type="text" name="prezime" id="prezime" class="form-control <?php echo ($greske["prezime"] == "" ? "":"is-invalid"); ?>" maxLength="30" value="<?php echo $prezime ?>" required>
        <div class="invalid-feedback"><?php echo $greske["prezime"]; ?></div>
    </div>

    <div class="form-group">
        <label for="email">Adresa e-pošte</label>
        <input type="email" name="email" id="email" class="form-control <?php echo ($greske["email"] == "" ? "":"is-invalid"); ?>" maxLength="120" value="<?php echo $email ?>" required>
        <div class="invalid-feedback"><?php echo $greske["email"]; ?></div>
    </div>

    <div class="form-group">
        <label for="lozinka">Lozinka</label>
        <input type="password" name="lozinka" id="lozinka" class="form-control <?php echo ($greske["lozinka"] == "" ? "":"is-invalid"); ?>" maxLength="50" required>
        <div class="invalid-feedback"><?php echo $greske["lozinka"]; ?></div>
    </div>

    <div class="form-group">
        <label for="lozinka2">Ponovite lozinku</label>
        <input type="password" name="lozinka2" id="lozinka2" class="form-control <?php echo ($greske["lozinka2"] == "" ? "":"is-invalid"); ?>" maxLength="50" required>
        <div class="invalid-feedback"><?php echo $greske["lozinka2"]; ?></div>
    </div>

    <div>
        <input type="submit" name="predajRegistraciju" class="predajBtn" value="Registriraj se">
    </div>

    <h5 class="p-2 mt-2 text-center">Ako već imate korisnički račun, prijavite se <a href='prijava.php'>ovdje</a></h5>
</form>
<?php include("includes/footer.php"); ?>