<?php 
    session_start();

    if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin")
    {
        include("includes/brisiImagesTemp.php");
        date_default_timezone_set("Europe/Zagreb");
        include("includes/baza.php");
        $baza = new Baza();

        $greske = array("markaId" => "", "model" => "","opis" => "","cijenaPoDanu" => "","godinaProizvodnje" => "","prijedeniKilometri" => "","brojSjedala" => "","motor" => "","slike" => "", "status"=>"");
        
        if(isset($_POST["predajPotvrdiPromjene"]))
        {
            $id = $_POST["idZaUredivanje"];
            $markaId = $_POST['marke'];
            $model = trim(htmlentities($_POST["model"]));
            $opis = trim(htmlentities($_POST["opis"]));
            $cijenaPoDanu = trim(htmlentities($_POST["cijenaPoDanu"]));
            $godinaProizvodnje = trim(htmlentities($_POST["godinaProizvodnje"]));
            $prijedeniKilometri = trim(htmlentities($_POST["prijedeniKilometri"]));
            $motor = $_POST['motor'];
            $brojSjedala = trim(htmlentities($_POST["brojSjedala"]));
            $klimaUredaj = isset($_POST["klimaUredaj"]) ? 1 : 0;
            $usb = isset($_POST["usb"]) ? 1 : 0;
            $radio = isset($_POST["radio"]) ? 1 : 0;
            $navigacija = isset($_POST["navigacija"]) ? 1 : 0;    
            $vrijemeDodavanja = trim(htmlentities($_POST["vrijemeDodavanja"]));

            if($markaId == -1)
            {
                $greske["markaId"] = "Morate odabrati marku vozila.";
                $greske["status"] = "greska";
            }
            if(!preg_match("/^[a-zA-Z0-9- ]+$/", $model)){
                $greske["model"] = "Model vozila smije sadržavati samo brojeve, slova te znakove \"-\" i \"_\".";
                $greske["status"] = "greska";
            }
            if(!preg_match("/^[0-9.]+$/", $cijenaPoDanu) || $cijenaPoDanu <= 0){
                $greske["cijenaPoDanu"] = "Cijena vozila mora biti u numeričkom formatu i mora iznositi vrijednost veću od 0.";
                $greske["status"] = "greska";
            }
            if(!preg_match("/^[0-9]+$/", $godinaProizvodnje) || $godinaProizvodnje < 1971 || $godinaProizvodnje > date('Y')){
                $greske["godinaProizvodnje"] = "Godina proizvodnje vozila mora iznositi vrijednost veću od 1970, a manju od " . (date('Y')+1) . ".";               
                $greske["status"] = "greska";
            }
            if(!preg_match("/^[0-9]+$/", $prijedeniKilometri)){
                $greske["prijedeniKilometri"] = "Prijeđeni kilometri moraju biti u numeričkom formatu.";
                $greske["status"] = "greska";
            }
            if($brojSjedala <= 0 || $brojSjedala >= 10)
            {
                $greske["brojSjedala"] = "Broj sjedala mora iznositi vrijednost veću od 0, a manju od 10.";
                $greske["status"] = "greska";
            }
            if(!preg_match("/^[0-9]+$/", $brojSjedala)){
                $greske["brojSjedala"] = "Broj sjedala vozila mora biti u numeričkom formatu.";
                $greske["status"] = "greska";
            }
            if($motor == -1)
            {
                $greske["motor"] = "Morate odabrati motor.";
                $greske["status"] = "greska";
            }

            if($greske["status"] == "")
            {
                $provjera = $baza->urediVoziloPoID($id, $markaId, $model, $opis, $cijenaPoDanu, $godinaProizvodnje, $prijedeniKilometri, $motor, $brojSjedala, $klimaUredaj, $usb, $radio, $navigacija);
                if($provjera)
                {
                    $greske["status"] = '
                    <div class="col-lg-12 p-0">
                        <div class="card mb-4 mt-3 border-bottom-success">
                            <div class="card-body text-dark">
                                <h5><strong>Vozilo je uspješno ažurirano.</strong></h5>
                            </div>
                        </div>
                    </div>
                    ';
                }
                else {
                    $greske["status"] = '
                    <div class="col-lg-12 p-0">
                        <div class="card mb-4 mt-3 border-bottom-danger">
                            <div class="card-body text-dark">
                                <h5><strong>Dogodila se greška prilikom uređivanja vozila.</strong></h5>
                            </div>
                        </div>
                    </div>
                    ';
                }
            }
            else {
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
        }
        else if(isset($_POST["predajUredi"]))
        {
            $idZaUredivanje = $_POST["idZaUredivanje"];
            $voziloZaUredivanje = $baza->dohvatiVoziloPoID($idZaUredivanje);

            $id = $voziloZaUredivanje["id"];
            $markaId = $voziloZaUredivanje["markaId"];
            $model = $voziloZaUredivanje["model"];
            $opis = $voziloZaUredivanje["opis"];
            $cijenaPoDanu = $voziloZaUredivanje["cijenaPoDanu"];
            $godinaProizvodnje = $voziloZaUredivanje["godinaProizvodnje"];
            $prijedeniKilometri = $voziloZaUredivanje["prijedeniKilometri"];
            $motor = $voziloZaUredivanje["motor"];
            $brojSjedala = $voziloZaUredivanje["brojSjedala"];
            $klimaUredaj = $voziloZaUredivanje["klimaUredaj"];
            $usb = $voziloZaUredivanje["usb"];
            $radio = $voziloZaUredivanje["radio"];
            $navigacija = $voziloZaUredivanje["navigacija"];
            $vrijemeDodavanja = $voziloZaUredivanje["vrijemeDodavanja"];
        }
        else{
            header("Location: upravljanje-vozilima.php");
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

    $podaciMarke = $baza->dohvatiSveMarke();
?>

<h1 class="h3 mb-2 mt-4 text-gray-800 text-center">Uredi vozilo</h1>
<?php echo $greske["status"]; ?>

<form action="" method="post" id="predajSlike" data-idvozilaform="<?php echo $id; ?> ">
    <label for="slike">Slike</label>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="slike[]" id="slike" multiple accept="image/*">
        <label class="custom-file-label" for="slike">Pritisnite za odabir slika</label>
    </div>
</form>
<div id="pregledSlika"></div>

<?php 
$path = "img/slike".$id;
if(file_exists($path))
{
    $popisSlika = scandir($path);
    $popisSlika = array_diff($popisSlika, [".", ".."]);

    if(count($popisSlika) > 0)
    {
        echo '<div class="row mt-3 provjeraZaBrojSlika">';
        foreach ($popisSlika as $slika) {
            ?>
            <div class="m-2 mt-3 card obrisiDiv<?php echo $slika; ?>">
                <img class="card-img-top img-fluid vozilaSlike" src="<?php echo $path . "/" . $slika ?>" alt="vozilo">
                <div class="card-body text-center">
                    <button type="button" class="btn btn-danger btn-icon-split tipkaZaBrisanje" id="<?php echo $slika; ?>" data-idvozila="<?php echo $id; ?> " data-toggle="modal" data-target="#ukloniSlikuVozilaModal" value="<?php echo $path . '/' . $slika; ?>">
                        <span class="icon text-white-50">
                        <i class="fas fa-trash"></i>
                        </span>
                        <span class="text">Ukloni</span>
                    </button>
                    <button type="button" class="btn btn-primary btn-icon-split glavnaSlika" data-idvozila="<?php echo $id; ?> " value="<?php echo $slika; ?>">
                        <span class="icon text-white-50">
                        <i class="fa fa-image"></i>
                        </span>
                        <span class="text">Glavna slika</span>
                    </button>
                </div>
            </div>
        <?php 
        }
        echo '</div>';
    }
}
?>

<form action="uredi-vozilo.php" method="post" id="urediVoziloForm" enctype="multipart/form-data" class="mt-3">

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="marke">Marka vozila</label>
            <select name="marke" id="marke" class="form-control <?php echo ($markaId == -1 ? "is-invalid":""); ?>">
            <option value="-1">Odaberite marku</option>
            <?php
            foreach($podaciMarke as $marka)
            {
            ?>
                <option value="<?php echo $marka["id"]; ?>" <?php echo ($markaId == $marka["id"] ? "selected" : ""); ?>><?php echo $marka["marka"] ?></option>
            <?php
            }
            ?>
            </select>
            <div class="invalid-feedback"><?php echo $greske["markaId"]; ?></div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="model">Model vozila</label>
            <input type="text" name="model" id="model" class="form-control <?php echo ($greske["model"] == "" ? "":"is-invalid"); ?>" required value="<?php echo $model; ?>">
            <div class="invalid-feedback"><?php echo $greske["model"]; ?></div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="motor">Motor</label>
            <select name="motor" id="motor"  class="form-control <?php echo ($motor == -1 ? "is-invalid":""); ?>">
            <option value="-1">Odaberite motor</option>
                <option value="benzin" <?php echo ($motor == "benzin" ? "selected" : ""); ?>>Benzin</option>
                <option value="dizel" <?php echo ($motor == "dizel" ? "selected" : ""); ?>>Dizel</option>
                <option value="hibrid" <?php echo ($motor == "hibrid" ? "selected" : ""); ?>>Hibrid</option>
                <option value="električni" <?php echo ($motor == "električni" ? "selected" : ""); ?>>Električni</option>
            </select>
            <div class="invalid-feedback"><?php echo $greske["motor"]; ?></div>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="opis">Opis vozila</label>
    <textarea name="opis" id="opis" class="form-control <?php echo ($greske["opis"] == "" ? "":"is-invalid"); ?>" rows="7"><?php echo $opis; ?></textarea>
    <div class="invalid-feedback"><?php echo $greske["opis"]; ?></div>
</div>

<div class="row">
    <div class="form-group col-md-12 col-lg-6 col-xl-3"> 
        <label for="cijenaPoDanu">Cijena po danu</label>
        <input type="number" name="cijenaPoDanu" id="cijenaPoDanu" class="form-control <?php echo ($greske["cijenaPoDanu"] == "" ? "":"is-invalid"); ?>" required min="1" step="0.01" value="<?php echo $cijenaPoDanu; ?>">
        <div class="invalid-feedback"><?php echo $greske["cijenaPoDanu"]; ?></div>
    </div>

    <div class="form-group col-md-12 col-lg-6 col-xl-3">
        <label for="godinaProizvodnje">Godina proizvodnje</label>
        <input type="number" name="godinaProizvodnje" id="godinaProizvodnje" class="form-control <?php echo ($greske["godinaProizvodnje"] == "" ? "":"is-invalid"); ?>" required min="1971" max="<?php echo date('Y'); ?>" value="<?php echo $godinaProizvodnje; ?>">
        <div class="invalid-feedback"><?php echo $greske["godinaProizvodnje"]; ?></div>
    </div>

    <div class="form-group col-md-12 col-lg-6 col-xl-3">
        <label for="prijedeniKilometri">Prijeđeni kilometri</label>
        <input type="number" name="prijedeniKilometri" id="prijedeniKilometri" class="form-control <?php echo ($greske["prijedeniKilometri"] == "" ? "":"is-invalid"); ?>" required min="0" value="<?php echo $prijedeniKilometri; ?>">
        <div class="invalid-feedback"><?php echo $greske["prijedeniKilometri"]; ?></div>
    </div>

    <div class="form-group col-md-12 col-lg-6 col-xl-3">
        <label for="brojSjedala">Broj sjedala</label>
        <input type="number" name="brojSjedala" id="brojSjedala" class="form-control <?php echo ($greske["brojSjedala"] == "" ? "":"is-invalid"); ?>" required step="1" min="0" value="<?php echo $brojSjedala ?>">
        <div class="invalid-feedback"><?php echo $greske["brojSjedala"]; ?></div>
    </div>
</div>

<h6>Oprema</h6>
<div class="form-check">
    <input type="checkbox" name="klimaUredaj" id="klimaUredaj" class="form-check-input" <?php echo ($klimaUredaj == 1 ? "checked" : ""); ?> >
    <label for="klimaUredaj" class="form-check-label">Klima uređaj</label>
</div>

<div class="form-check">
    <input type="checkbox" name="usb" id="usb" class="form-check-input" <?php echo ($usb == 1 ? "checked" : ""); ?> >
    <label for="usb" class="form-check-label">USB</label>
</div>

<div class="form-check">
    <input type="checkbox" name="radio" id="radio" class="form-check-input" <?php echo ($radio == 1 ? "checked" : ""); ?> >
    <label for="radio" class="form-check-label">Radio</label>
</div>

<div class="form-check">
    <input type="checkbox" name="navigacija" id="navigacija" class="form-check-input" <?php echo ($navigacija == 1 ? "checked" : ""); ?> >   
    <label for="navigacija" class="form-check-label">Navigacija</label>
</div>

<div class="form-group mt-3">
    <label for="vrijemeDodavanja">Vrijeme dodavanja</label>
    <input type="text" name="vrijemeDodavanja" id="vrijemeDodavanja" class="form-control" readonly value="<?php echo date("d.m.Y H:i", strtotime($vrijemeDodavanja)); ?>">
</div>

<div>
    <input type="hidden" name="idZaUredivanje" value="<?php echo $id; ?>">
</div>

<hr>
<div class="tipke">
    <div>
        <a href="upravljanje-vozilima.php" class="btn btn-primary btn-icon-split">
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


<!-- Ukloni sliku vozila Modal -->
<div class="modal fade" id="ukloniSlikuVozilaModal" aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Brisanje?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">Jeste li sigurni da želite obrisati sliku?</div>
    <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Odustani</button>
        <input type="hidden" name="idZaBrisanje" id="idZaBrisanje" value="">
        <button type="submit" class="btn btn-danger btn-icon-split" id="obrisiSliku" name="">
            <span class="icon text-white-50">
            <i class="fas fa-trash"></i>
            </span>
            <span class="text">Ukloni</span>
        </button>
    </div>
    </div>
</div>
</div>


<?php include("includes/footer.php"); ?>

<script src="js/predajSlikeUrediVozilo.js"></script>
<script src="js/brisanjeSlikeUrediVozilo.js"></script>
<script src="js/glavnaSlikaUrediVozilo.js"></script>

