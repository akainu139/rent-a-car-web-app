<?php 
session_start();
$aktivnaStranica = "";
include("includes/header.php");
?>
<div class="container">

<?php 
include("includes/baza.php");
$baza = new Baza(); 

if(isset($_POST["predajRezervaciju"]))
{
    $id = $_POST["id"];
    $cijenaPoDanu = $_POST["cijenaPoDanu"];
    $marka = $_POST["marka"];
    $model = $_POST["model"];
        
    if(isset($_SESSION["korisnikId"]))
    {
        $_SESSION["voziloId"] = $id;
        $_SESSION["cijenaPoDanu"] = $cijenaPoDanu;
        $_SESSION["voziloMarkaModel"] = $marka . " " . $model;

        header("Location: placanje.php");
        exit;
    }
    else {
        $_SESSION["tempVozilo"] = $id;
        header("Location: prijava.php");
        exit;
    }
}

if(isset($_GET["id"]))
{
    $id = $_GET["id"];
    if(!isset($_SESSION["dostupnaVozilaIds"]) || !in_array($id, $_SESSION["dostupnaVozilaIds"]))
    {
        echo "<div class='text-center alert alert-secondary mt-4'><h4>Traženo vozilo nije dostupno.</h4><h5>Povratak na <a href='dostupnaVozila.php'>dostupna vozila</a>.</h5></div>";
        exit;
    }

    $vrijemeOd = $_SESSION["vrijemeOd"];
    $vrijemeDo = $_SESSION["vrijemeDo"];
    $vozilo = $baza->dohvatiVoziloPoID($id);

    $pocetak = strtotime($_SESSION["vrijemeOd"]);
    $kraj = strtotime($_SESSION["vrijemeDo"]);
    $razlikaDani = $kraj - $pocetak;
    $sveukupnoZaPlacanje = ceil($razlikaDani/(60 * 60 * 24)) * $vozilo["cijenaPoDanu"]; 
    $_SESSION["sveukupnoZaPlacanje"] = $sveukupnoZaPlacanje;
}
else {
    echo "<div class='text-center alert alert-secondary mt-4'><h4>Traženo vozilo nije dostupno.</h4><h5>Povratak na <a href='dostupnaVozila.php'>dostupna vozila</a>.</h5></div>";
    exit;
}
?>

<ul class="nav nav-pills nav-justified detaljno mt-4">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="pill" href="#opis">Opis</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="pill" href="#oprema">Oprema</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="pill" href="#slike">Slike</a>
    </li>
</ul>

<div class="tab-content">
    <div id="opis" class="tab-pane active">
        <div class="row mt-4">
            <div class="col-xs-12 col-sm-6 col-xl-4 text-center">
                <h5 class="mb-0 crveno">Vozilo</h5>
                <h5><?php echo $vozilo["marka"] . " " . $vozilo["model"]; ?></h5>
                <hr>
            </div>
            <div class="col-xs-12 col-sm-6 col-xl-4 text-center">
                <h5 class="mb-0 crveno">Godina proizvodnje</h5>
                <h5><?php echo $vozilo["godinaProizvodnje"]; ?></h5>
                <hr>
            </div>
            <div class="col-xs-12 col-sm-6 col-xl-4 text-center">
                <h5 class="mb-0 crveno">Motor</h5>
                <h5 class="text-capitalize"><?php echo $vozilo["motor"]; ?></h5>
                <hr>
            </div>
            <div class="col-xs-12 col-sm-6 col-xl-4 text-center">
                <h5 class="mb-0 crveno">Broj sjedala</h5>
                <h5><?php echo $vozilo["brojSjedala"]; ?></h5>
                <hr>
            </div>
            <div class="col-xs-12 col-sm-6 col-xl-4 text-center">
                <h5 class="mb-0 crveno">Kilometraža</h5>
                <h5><?php echo $vozilo["prijedeniKilometri"]; ?></h5>
                <hr>
            </div>
            <div class="col-xs-12 col-sm-6 col-xl-4 text-center">
                <div>
                    <h5 class="mb-0 crveno">Cijena po danu</h5>
                    <h5><?php echo $vozilo["cijenaPoDanu"] . " kn"; ?></h5>
                    <hr>
                </div>
            </div>
        </div>
        <div class="row mt-1 text-center">
            <div class="col-sm-12 col-xl-12">
                <h5 class="mb-0 crveno">Opis</h5>
                <h5><?php echo $vozilo["opis"]; ?></h5>
                <hr>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-xs-12 col-md-4 col-xl-4 text-center">
                <h5 class="mb-0 crveno">Početak</h5>
                <h5><?php echo date("d.m.Y H:i", strtotime($vrijemeOd)); ?></h5>
                <hr>
            </div>
            <div class="col-xs-12 col-md-4 col-xl-4 text-center">
                <h5 class="mb-0 crveno">Kraj</h5>
                <h5><?php echo date("d.m.Y H:i", strtotime($vrijemeDo)); ?></h5>
                <hr>
            </div>
            <div class="col-xs-12 col-md-4 col-xl-4 text-center">
                <h5 class="mb-0 crveno">Sveukupno</h5>
                <h5><?php echo number_format($sveukupnoZaPlacanje, 2, ',', '') . " kn"; ?></h5>
                <hr>
            </div>
        </div>
    </div>

    <div id="oprema" class="tab-pane fade">
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th class="text-center" colspan="2">Oprema</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Klima uređaj</td>
                    <td><?php echo $vozilo["klimaUredaj"] == 1 ? "Da" : "Ne"; ?></td>
                </tr>
                <tr>
                    <td>USB</td>
                    <td><?php echo $vozilo["usb"] == 1 ? "Da" : "Ne"; ?></td>
                </tr>
                <tr>
                    <td>Radio</td>
                    <td><?php echo $vozilo["radio"] == 1 ? "Da" : "Ne"; ?></td>
                </tr>
                <tr>
                    <td>Navigacija</td>
                    <td><?php echo $vozilo["navigacija"] == 1 ? "Da" : "Ne"; ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="slike" class="tab-pane fade">
    <?php
    $path = "admin/img/slike".$id;
    if(file_exists($path))
    {
        $popisSlika = scandir($path);
        $popisSlika = array_diff($popisSlika, [".", ".."]);

        if(count($popisSlika) > 0)
        {
            echo "<h3 class='text-center mt-3'>Slike</h3>";
            echo '<div class="row">';
            foreach ($popisSlika as $slika) {
            ?>
            <div class="card mx-auto my-3">
                <img class="card-img-top img-fluid vozilaSlike" src="<?php echo $path."/".$slika ?>" alt="vozilo">
            </div>
            <?php
            }
            echo '</div>';
        }
        else {
            echo '<h5 class="mt-3 text-center">Trenutno nema slika za ovo vozilo.</h5>';
        }
    }
    ?>
    </div>
</div>
<form action="detaljno.php" method="post" id="rezervacijaForm" class="mb-3">
    <input type="hidden" name="id" value="<?php echo $id ?>">
    <input type="hidden" name="cijenaPoDanu" value="<?php echo $vozilo["cijenaPoDanu"]; ?>">
    <input type="hidden" name="marka" value="<?php echo $vozilo["marka"]; ?>">
    <input type="hidden" name="model" value="<?php echo $vozilo["model"]; ?>">
    <button type="submit" name="predajRezervaciju" class="predajBtn">Rezerviraj</button>
</form>
    
</div>
<?php include("includes/footer.php"); ?>