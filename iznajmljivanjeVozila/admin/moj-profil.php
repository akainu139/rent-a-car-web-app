<?php 
session_start();
if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin")
{
    include("includes/baza.php");
    $baza = new Baza();

    // osobni podaci
    $korisnik = $baza->dohvatiKorisnikaPoID($_SESSION["korisnikId"]);
    $ime = $korisnik["ime"];
    $prezime = $korisnik["prezime"];
    $greske = array("ime" => "", "prezime" => "", "status" => "");
    if(isset($_SESSION["spremiPodatkeUspjeh"]))
    {
        $greske["status"] = "<h4 class='my-3 alert alert-success text-center'>Podaci su uspješno ažurirani.</h4>";
        unset($_SESSION["spremiPodatkeUspjeh"]);
    }
    if(isset($_SESSION["spremiPodatkeGreske"]))
    {
        $ime = isset($_SESSION["spremiPodatkeIme"]) ? $_SESSION["spremiPodatkeIme"] : "";
        $prezime = isset($_SESSION["spremiPodatkePrezime"]) ? $_SESSION["spremiPodatkePrezime"] : "";
        $greske["ime"] = isset($_SESSION["spremiPodatkeGreske"]["ime"]) ? $_SESSION["spremiPodatkeGreske"]["ime"] : "";
        $greske["prezime"] = isset($_SESSION["spremiPodatkeGreske"]["prezime"]) ? $_SESSION["spremiPodatkeGreske"]["prezime"] : "";
        $greske["status"] = isset($_SESSION["spremiPodatkeGreske"]["status"]) ? $_SESSION["spremiPodatkeGreske"]["status"] : "";
        unset($_SESSION["spremiPodatkeIme"]);
        unset($_SESSION["spremiPodatkePrezime"]);
        unset($_SESSION["spremiPodatkeGreske"]);
    }

    // promjena lozinke
    $greske2 = array("staraLozinka" => "", "novaLozinka" => "", "novaLozinka2" => "", "status" => "");
    if(isset($_SESSION["spremiLozinkuUspjeh"]))
    {
        $greske2["status"] = "<h4 class='my-3 alert alert-success text-center'>Lozinka je uspješno ažurirana.</h4>";
    }
    if(isset($_SESSION["spremiLozinkuGreske"]))
    {
        $greske2["staraLozinka"] = isset($_SESSION["spremiLozinkuGreske"]["staraLozinka"]) ? $_SESSION["spremiLozinkuGreske"]["staraLozinka"] : "";
        $greske2["novaLozinka"] = isset($_SESSION["spremiLozinkuGreske"]["novaLozinka"]) ? $_SESSION["spremiLozinkuGreske"]["novaLozinka"] : "";
        $greske2["novaLozinka2"] = isset($_SESSION["spremiLozinkuGreske"]["novaLozinka2"]) ? $_SESSION["spremiLozinkuGreske"]["novaLozinka2"] : "";
        $greske2["status"] = isset($_SESSION["spremiLozinkuGreske"]["status"]) ? $_SESSION["spremiLozinkuGreske"]["status"] : "";
    }

    if(isset($_POST["predajSpremiPodatke"]))
    {
        $ime = trim(htmlentities($_POST["ime"]));
        $prezime = trim(htmlentities($_POST["prezime"]));

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

        if($greske["status"] == "")
        {
            $ime = ucfirst(strtolower($ime));
            $prezime = ucfirst(strtolower($prezime));
            $provjera = $baza->urediKorisnikaPoID($_SESSION["korisnikId"], $ime, $prezime);
            if($provjera)
            {
                $_SESSION["spremiPodatkeUspjeh"] = true;
                header("Location: moj-profil.php");
                exit;
            }
            else {
                $greske["status"] = "<h4 class='my-3 alert alert-danger text-center'>Dogodila se greška prilikom uređivanja.</h4>";
                $_SESSION["spremiPodatkeGreske"] = $greske;
                $_SESSION["spremiPodatkeIme"] = $ime;
                $_SESSION["spremiPodatkePrezime"] = $prezime;
                header("Location: moj-profil.php");
                exit;
            }
        }
        else {
            $greske["status"] = "<h4 class='my-3 alert alert-danger text-center'>Ispravite navedene greške i pokušajte ponovno.</h4>";
            $_SESSION["spremiPodatkeGreske"] = $greske;
            $_SESSION["spremiPodatkeIme"] = $ime;
            $_SESSION["spremiPodatkePrezime"] = $prezime;
            header("Location: moj-profil.php");
            exit;
        }
    }

    if(isset($_POST["predajSpremiLozinku"]))
    {
        $staraLozinka = trim(htmlentities($_POST["staraLozinka"]));
        $novaLozinka = trim(htmlentities($_POST["novaLozinka"]));
        $novaLozinka2 = trim(htmlentities($_POST["novaLozinka2"]));
        
        if($novaLozinka !== $novaLozinka2){
            $greske2["novaLozinka"] = "Lozinke se ne poklapaju.";
            $greske2["novaLozinka2"] = "Lozinke se ne poklapaju.";
            $greske2["status"] = "greska";
        }
        if(strlen($staraLozinka) > 50 || strlen($staraLozinka) < 8)
        {
            $greske2["staraLozinka"] = "Lozinka mora imati između 8 i 50 znakova.";
            $greske2["status"] = "greska";
        }
        if(strlen($novaLozinka) > 50 || strlen($novaLozinka) < 8)
        {
            $greske2["novaLozinka"] = "Lozinka mora imati između 8 i 50 znakova.";
            $greske2["status"] = "greska";
        }
        if(strlen($novaLozinka2) > 50 || strlen($novaLozinka2) < 8)
        {
            $greske2["novaLozinka2"] = "Lozinka mora imati između 8 i 50 znakova.";
            $greske2["status"] = "greska";
        }

        if($greske2["status"] == "")
        {
            $hashedLozinka = password_hash($novaLozinka, PASSWORD_DEFAULT);
            $provjera = $baza->promjenaLozinkeKorisnika($_SESSION["korisnikId"], $staraLozinka, $hashedLozinka);
            if($provjera)
            {
                $_SESSION["spremiLozinkuUspjeh"] = true;
                header("Location: moj-profil.php");
                exit;
            }
            else {
                $greske2["status"] = "<h4 class='my-3 alert alert-danger text-center'>Unijeli ste pogrešnu lozinku.</h4>";
                $_SESSION["spremiLozinkuGreske"] = $greske2;
                header("Location: moj-profil.php");
                exit;
            }
        }
        else {
            $greske2["status"] = "<h4 class='my-3 alert alert-danger text-center'>Ispravite navedene greške i pokušajte ponovno.</h4>";
            $_SESSION["spremiLozinkuGreske"] = $greske2;
            header("Location: moj-profil.php");
            exit;
        }
    }   
include("includes/header.php");
?>
<h1 class="h3 my-3 text-gray-800 text-center">Moj profil</h1>
<div class="container">
<ul class="nav nav-pills nav-justified mt-4">
    <li class="nav-item">
        <a class="nav-link active py-3" data-toggle="pill" id="pill1" href="#osobniPodaci">Osobni podaci</a>
    </li>
    <li class="nav-item">
        <a class="nav-link py-3" data-toggle="pill" id="pill2" href="#promjenaLozinke">Promjena lozinke</a>
    </li>
</ul>

<div class="tab-content">
    <div id="osobniPodaci" class="tab-pane active">
        <?php echo $greske["status"]; ?>
        <form action="moj-profil.php" method="post">
            <div class="row mt-4">
                <div class="col-xs-12 col-md-4 text-center">
                    <h6>Korisničko ime</h6>
                    <h6><?php echo $korisnik["korisnickoIme"]; ?></h6>
                    <hr>
                </div>
                <div class="col-xs-12 col-md-4 text-center">
                    <h6>Adresa e-pošte</h6>
                    <h6><?php echo $korisnik["email"]; ?></h6>
                    <hr>
                </div>
                <div class="col-xs-12 col-md-4 text-center">
                    <h6>Datum registracije</h6>
                    <h6><?php echo date("d.m.Y H:i:s", strtotime($korisnik["vrijemeRegistracije"])); ?></h6>
                    <hr>
                </div>
            </div>
            <div class="row mt-3">
                <div class="form-group col-md-6">
                    <label for="ime">Ime</label>
                    <input type="text" name="ime" id="ime" class="form-control <?php echo ($greske["ime"] == "" ? "":"is-invalid"); ?>" value="<?php echo $ime; ?>" required maxLength="30">
                    <div class="invalid-feedback"><?php echo $greske["ime"]; ?></div>
                </div>
                <div class="form-group col-md-6">
                    <label for="prezime">Prezime</label>
                    <input type="text" name="prezime" id="prezime" class="form-control <?php echo ($greske["prezime"] == "" ? "":"is-invalid"); ?>" value="<?php echo $prezime; ?>" required maxLength="30">
                    <div class="invalid-feedback"><?php echo $greske["prezime"]; ?></div>
                </div>
            </div>
            <button class="btn btn-success btn-icon-split" type="submit" name="predajSpremiPodatke">
                <span class="icon text-white-50">
                <i class="fas fa-check"></i>
                </span>
                <span class="text">Spremi</span>
            </button>
        </form>
    </div>

    <div id="promjenaLozinke" class="tab-pane fade">
        <?php echo $greske2["status"]; ?>
        <form action="moj-profil.php" method="post">
            <div class="row mt-3">
                <div class="form-group col-lg-4">
                    <label for="staraLozinka">Stara lozinka</label>
                    <input type="password" name="staraLozinka" id="staraLozinka" class="form-control <?php echo ($greske2["staraLozinka"] == "" ? "":"is-invalid"); ?>" required minLength="8" maxLength="50">
                    <div class="invalid-feedback"><?php echo $greske2["staraLozinka"]; ?></div>
                </div>
                <div class="form-group col-lg-4">
                    <label for="novaLozinka">Nova lozinka</label>
                    <input type="password" name="novaLozinka" id="novaLozinka" class="form-control <?php echo ($greske2["novaLozinka"] == "" ? "":"is-invalid"); ?>" required minLength="8" maxLength="50">
                    <div class="invalid-feedback"><?php echo $greske2["novaLozinka"]; ?></div>
                </div>
                <div class="form-group col-lg-4">
                    <label for="novaLozinka2">Ponovite novu lozinku</label>
                    <input type="password" name="novaLozinka2" id="novaLozinka2" class="form-control <?php echo ($greske2["novaLozinka2"] == "" ? "":"is-invalid"); ?>" required minLength="8" maxLength="50">
                    <div class="invalid-feedback"><?php echo $greske2["novaLozinka2"]; ?></div>
                </div>
            </div>
            <button class="btn btn-success btn-icon-split" type="submit" name="predajSpremiLozinku">
                <span class="icon text-white-50">
                <i class="fas fa-check"></i>
                </span>
                <span class="text">Spremi</span>
            </button>
        </form>
    </div>
</div>
</div>
    <?php
    if(isset($_SESSION["spremiLozinkuGreske"]) || isset($_SESSION["spremiLozinkuUspjeh"]))
    {
    ?>
    <script>
        document.getElementById("osobniPodaci").classList.remove("active");
        document.getElementById("promjenaLozinke").classList.add("active");
        document.getElementById("promjenaLozinke").classList.add("show");
        document.getElementById("pill1").classList.remove("active");
        document.getElementById("pill2").classList.add("active");
    </script>
    <?php
        unset($_SESSION["spremiLozinkuGreske"]);
        unset($_SESSION["spremiLozinkuUspjeh"]);    
    }
}
else{
    header("Location: index.php");
    exit;
}
?>
<?php include("includes/footer.php"); ?>