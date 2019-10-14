<?php 
session_start();
$aktivnaStranica = "";
include("includes/header.php");
?>
<div class="container">

<?php 
include("includes/baza.php");
$baza = new Baza(); 

if(isset($_GET["id"]))
{
    $id = $_GET["id"];
    $vozilo = $baza->dohvatiVoziloPoID($id);

    if($vozilo == null)
    {
        echo "<div class='text-center alert alert-secondary mt-4'><h4>Traženo vozilo nije dostupno.</h4><h5>Povratak na <a href='dostupnaVozila.php'>dostupna vozila</a>.</h5></div>";
        exit;
    }
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
        <div class="row mt-4 red1">
            <div class="col-xs-12 col-sm-6 col-md-4 text-center">
                <h5 class="mb-0 crveno">Vozilo</h5>
                <h5><?php echo $vozilo["marka"] . " " . $vozilo["model"]; ?></h5>
                <hr>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 text-center">
                <h5 class="mb-0 crveno">Godina proizvodnje</h5>
                <h5><?php echo $vozilo["godinaProizvodnje"]; ?></h5>
                <hr>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 text-center">
                <h5 class="mb-0 crveno">Motor</h5>
                <h5 class="text-capitalize"><?php echo $vozilo["motor"]; ?></h5>
                <hr>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 text-center">
                <h5 class="mb-0 crveno">Broj sjedala</h5>
                <h5><?php echo $vozilo["brojSjedala"]; ?></h5>
                <hr>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 text-center">
                <h5 class="mb-0 crveno">Kilometraža</h5>
                <h5><?php echo $vozilo["prijedeniKilometri"]; ?></h5>
                <hr>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 text-center">
                <h5 class="mb-0 crveno">Cijena</h5>
                <h5><?php echo $vozilo["cijenaPoDanu"] . " kn/dan"; ?></h5>
                <hr>
            </div>
        </div>
        <div class="row mt-1 text-center red2">
            <div class="col-xs-12">
                <h5 class="mb-0" style="color:red;">Opis</h5>
                <h5><?php echo $vozilo["opis"]; ?></h5>
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
    
</div>
<?php include("includes/footer.php"); ?>