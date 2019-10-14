<?php 
    session_start();
    if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin")
    {
        include("includes/brisiImagesTemp.php");
        include("includes/baza.php");
        $baza = new Baza();

        $marka = "";
        $greske = array("marka" => "", "status"=>"");

        if(isset($_SESSION["dodajMarkuUspjeh"]))
        {
            $greske["status"] = '
            <div class="col-lg-12 p-0">
                <div class="card mb-4 mt-3 border-bottom-success">
                    <div class="card-body text-dark">
                        <h5><strong>Marka je uspješno dodana.</strong></h5>
                    </div>
                </div>
            </div>
            ';
            unset($_SESSION['dodajMarkuUspjeh']);
        }

        if(isset($_POST["predajDodaj"]))
        {
            $marka = trim(htmlentities($_POST["marka"]));

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
                $provjera = $baza->dodajMarku($marka);
                if($provjera)
                {
                    $_SESSION["dodajMarkuUspjeh"] = true;
                    header("Location: dodaj-marku.php");
                    exit;
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
    }
    else {
        header("Location: ../prijava.php");
        exit;
    }
  
?>

<?php include("includes/header.php"); ?>

<form action="dodaj-marku" method="post" id="dodajMarkuForm" class="w-75 mx-auto">

<h1 class="h3 mb-2 mt-4 text-gray-800">Dodaj marku</h1>

<?php echo $greske["status"]; ?>

<div class="form-group">
    <label for="marka">Marka vozila</label>
    <input type="text" name="marka" id="marka" class="form-control <?php echo ($greske["marka"] == "" ? "":"is-invalid"); ?>" maxLength="50" value="<?php echo $marka ?>" required>
    <div class="invalid-feedback"><?php echo $greske["marka"]; ?></div>
</div>
<div class="tipke">
    <div>
        <a href="upravljanje-markama.php" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
            <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text w-100">Marke</span>
        </a>
    </div>
    <div>
        <button class="btn btn-success btn-icon-split" type="submit" name="predajDodaj">
            <span class="icon text-white-50">
            <i class="fas fa-check"></i>
            </span>
            <span class="text w-100">Potvrdi</span>
        </button>
    </div>
</div>

</form>

<?php include("includes/footer.php"); ?>