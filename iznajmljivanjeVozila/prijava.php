<?php 
    session_start();
    $aktivnaStranica = "prijava";
    include("includes/header.php"); 

    if(isset($_SESSION["korisnikId"]))
    {
        header("Location: index.php");
        exit;
    }

    include("includes/baza.php");
    $baza = new Baza();

    $korisnickoIme = "";
    $greske = array("korisnickoIme" => "", "lozinka" => "","status" => "");

    if(isset($_POST["predajPrijavu"]))
    {
        $korisnickoIme = trim(htmlentities($_POST["korisnickoIme"]));
        $lozinka = htmlentities($_POST["lozinka"]);

        if(strlen($korisnickoIme) > 20 || strlen($korisnickoIme) < 3)
        {
            $greske["korisnickoIme"] = "Korisničko ime mora imati između 8 i 20 znakova.";
            $greske["status"] = "greska";
        }
        if(strlen($lozinka) > 50 || strlen($lozinka) < 8)
        {
            $greske["lozinka"] = "Lozinka mora imati između 8 i 50 znakova.";
            $greske["status"] = "greska";
        }

        if($greske["status"] == "")
        {
            $korisnik = $baza->provjeraKorisnika($korisnickoIme, $lozinka);
            if($korisnik != null)
            {
                $_SESSION["korisnikId"] = $korisnik["id"];
                $_SESSION["email"] = $korisnik["email"];
                $_SESSION["korisnickoIme"] = $korisnik["korisnickoIme"];
                if($korisnik["admin"] == 1)
                {
                    $_SESSION["role"] = "admin";
                    header("Location: admin/index.php");
                    exit;
                }

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
                $greske["status"] = "<h4 class='my-3 alert alert-danger text-center'>Korisničko ime ili lozinka ne odgovaraju.</h4>";
            }
        }
        else {
            $greske["status"] = "<h4 class='my-3 alert alert-danger text-center'>Ispravite navedene pogreške i pokušajte ponovno.</h4>";
        }
    }

?>
<div class="prijavaBG">
<form action="prijava.php" method="post" class="p-3" id="prijavaForm">
    <h1 class="text-center">Prijava</h1>
    <h4 class="text-center">Unesite svoje podatke</h4>
    <?php echo $greske["status"]; ?>

    <div class="form-group">
        <label for="korisnickoIme">Korisničko ime</label>
        <input type="text" name="korisnickoIme" id="korisnickoIme" class="form-control <?php echo ($greske["korisnickoIme"] == "" ? "":"is-invalid"); ?>" minLength="3" maxLength="20" value="<?php echo $korisnickoIme ?>" required>
        <div class="invalid-feedback"><?php echo $greske["korisnickoIme"]; ?></div>
    </div>

    <div class="form-group">
        <label for="lozinka">Lozinka</label>
        <input type="password" name="lozinka" id="lozinka" class="form-control <?php echo ($greske["lozinka"] == "" ? "":"is-invalid"); ?>" maxLength="50" required>
        <div class="invalid-feedback"><?php echo $greske["lozinka"]; ?></div>
    </div>

    <div>
        <input type="submit" name="predajPrijavu" class="predajBtn" value="Prijavi se">
    </div>

    <h5 class="p-2 mt-2 text-center">Ako nemate korisnički račun, registrirajte se <a href='registracija.php'>ovdje</a></h5>
</form>
</div>

<?php include("includes/footer.php"); ?>