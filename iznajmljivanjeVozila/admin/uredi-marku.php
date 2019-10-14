<?php 
    session_start();

    if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin")
    {
        include("includes/brisiImagesTemp.php");
        include("includes/baza.php");
        $baza = new Baza();
        
        $greske = array("marka" => "", "status" => "");
        
        if(isset($_POST["predajPotvrdiPromjene"]))
        {
            $id = $_POST["idZaUredivanje"];
            $marka = trim(htmlentities($_POST["marka"]));
            $vrijemeDodavanja = trim(htmlentities($_POST["vrijemeDodavanja"]));

            if(!preg_match("/^[a-zA-Z0-9- ]+$/", $marka))
            {
                $greske["marka"] = "Marka vozila ne smije sadržavati posebne znakove.";
                $greske["status"] = '
                <div class="col-lg-12 p-0">
                    <div class="card mb-4 mt-3 border-bottom-danger">
                        <div class="card-body text-dark">
                            <h5><strong>Ispravite označena polja i pokušajte ponovno.</strong></h5>
                        </div>
                    </div>
                </div>
                ';
            }
            else if(strlen($marka) > 50)
            {
                $greske["marka"] = "Marka može sadržavati najviše 50 znakova.";
                $greske["status"] = '
                <div class="col-lg-12 p-0">
                    <div class="card mb-4 mt-3 border-bottom-danger">
                        <div class="card-body text-dark">
                            <h5><strong>Ispravite označena polja i pokušajte ponovno.</strong></h5>
                        </div>
                    </div>
                </div>
                ';
            }
            else {
                $provjera = $baza->urediMarkuPoID($id, $marka);
                if($provjera)
                {
                    $greske["status"] = '
                    <div class="col-lg-12 p-0">
                        <div class="card mb-4 mt-3 border-bottom-success">
                            <div class="card-body text-dark">
                                <h5><strong>Marka vozila je uspješno ažurirana.</strong></h5>
                            </div>
                        </div>
                    </div>
                    ';
                }
                else {
                    $greske["marka"] = "Trenutna marka vozila već postoji.";
                    $greske["status"] = '
                    <div class="col-lg-12 p-0">
                        <div class="card mb-4 mt-3 border-bottom-danger">
                            <div class="card-body text-dark">
                                <h5><strong>Dogodila se greška. Unesena marka već postoji.</strong></h5>
                            </div>
                        </div>
                    </div>
                    ';
                }
            }
        }
        else if(isset($_POST["predajUredi"]))
        {
            $idZaUredivanje = $_POST["idZaUredivanje"];
            $markaZaUredivanje = $baza->dohvatiMarkuPoID($idZaUredivanje);

            $id = $markaZaUredivanje["id"];
            $marka = $markaZaUredivanje["marka"];
            $vrijemeDodavanja = $markaZaUredivanje["vrijemeDodavanja"];
        }
        else {
            header("Location: upravljanje-markama.php");
            exit;
        }
    }
    else {
        header("Location: ../prijava.php");
        exit;
    }
?>

<?php 
    include("includes/header.php");

?>

<form action="uredi-marku.php" method="post" id="urediMarkuForm" class="w-75 mx-auto">

<h1 class="h3 mb-2 mt-4 text-gray-800">Uredi marku</h1>

<?php echo $greske["status"]; ?>

<div class="form-group">
    <label for="marka">Marka vozila</label>
    <input type="text" name="marka" id="marka" class="form-control <?php echo ($greske["marka"] == "" ? "":"is-invalid"); ?>" required maxLength="50" value="<?php echo $marka; ?>">
    <div class="invalid-feedback"><?php echo $greske["marka"]; ?></div>
</div>

<div class="form-group">
    <label for="vrijemeDodavanja">Vrijeme dodavanja</label>
    <input type="text" name="vrijemeDodavanja" id="vrijemeDodavanja" class="form-control" readonly value="<?php echo date("d.m.Y H:i", strtotime($vrijemeDodavanja)); ?>">
</div>

<div>
    <input type="hidden" name="idZaUredivanje" value=<?php echo $id; ?>>
</div>

<div class="tipke">
    <div>
        <a href="upravljanje-markama.php" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
            <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">Povratak</span>
        </a>
    </div>
    <div>
        <button class="btn btn-success btn-icon-split" type="submit" name="predajPotvrdiPromjene">
            <span class="icon text-white-50">
            <i class="fas fa-check"></i>
            </span>
            <span class="text">Potvrdi</span>
        </button>
    </div>
</div>

</form>

<?php include("includes/footer.php"); ?>
