<?php 
    session_start();
    $aktivnaStranica = "dostupnaVozila";
    include("includes/header.php"); 
?>

<div class="container">

<?php 
if(isset($_SESSION["vrijemeOd"]) && isset($_SESSION["vrijemeDo"]))
{
    include("includes/baza.php");
    $baza = new Baza();
    
    include("includes/funkcije.php");
    $vrijemeOd = $_SESSION["vrijemeOd"];
    $vrijemeDo = $_SESSION["vrijemeDo"];
    
    if(!validateDate($vrijemeOd) || !(validateDate($vrijemeDo)))
    {
        header("Location: index.php");
        exit;
    }
?>

    <h2 class="my-3 text-center">Popis dostupnih vozila od <?php echo $vrijemeOd . " do " . $vrijemeDo; ?></h2>
    <h4 class="mb-3 alert alert-info text-center">Za promjenu termina, vratite se na <a href="index.php">početnu.</a></h4>

<?php
    $vrijemeOd = date("Y-m-d H:i:s", strtotime($vrijemeOd));
    $vrijemeDo = date("Y-m-d H:i:s", strtotime($vrijemeDo));
    
    $brojVozila = $baza->dohvatiBrojDostupnihVozila($vrijemeOd, $vrijemeDo);
    $limit = 2;
    $stranica = isset($_GET["stranica"]) ? $_GET["stranica"] : 1;

    $brojStranica = ceil($brojVozila / $limit);
    if($stranica < 1) $stranica = 1;
    if($stranica > $brojStranica) $stranica = $brojStranica;

    $prethodna = $stranica - 1;
    $sljedeca = $stranica + 1;

    $start = ($stranica - 1) * $limit;
    $podaciVozila = $baza->dohvatiDostupnaVozila($vrijemeOd, $vrijemeDo, $start, $limit);

if(count($podaciVozila) > 0)
{
    echo '<div class="row popis-slika">';
    foreach ($podaciVozila as $vozilo) {
        ?>
        <div class="card col-xl-12 p-0 kartica">
            <div class="card-body">
                <h2><?php echo $vozilo["marka"] . " " . $vozilo["model"]; ?></h2>
                <h5><?php echo "Godina proizvodnje: " . $vozilo["godinaProizvodnje"]; ?></h5>
                <h5 class="text-capitalize"><?php echo $vozilo["motor"]; ?></h5>
                <h5><?php echo $vozilo["brojSjedala"] . " sjedala"; ?></h5>
                <h5><?php echo $vozilo["cijenaPoDanu"] . " kn/dan"; ?></h5>
                <a href="detaljno.php?id=<?php echo $vozilo["id"]; ?>" style="width:150px;" class="predajBtn mx-0">
                    <span class="text-white-50">
                    <i class="fas fa-info-circle"></i>
                    </span>
                    <span class="text">Pregled</span>
                </a>
            </div>
            <?php
                $path = "admin/img/slike".$vozilo["id"];
                if(file_exists($path))
                {
                    $popisSlika = scandir($path);
                    $popisSlika = array_diff($popisSlika, [".", ".."]);
                    if(empty($popisSlika))
                    {
                        $slika = "img/no-image.png";
                    }
                    else {
                        $slika = reset($popisSlika);
                        $slika = $path."/".$slika;
                    }
                }
            ?>
                <img class="card-img-top img-fluid vozilaSlike slika" src="<?php echo $slika; ?>" alt="vozilo">
        </div>
    <?php
    }
    echo '</div>';
    ?>
    <nav>
        <ul class="pagination justify-content-center mt-3">
            <li class="page-item <?php echo $stranica == 1 ? "disabled":""?>"><a class="page-link" href="dostupnaVozila.php?stranica=<?php echo $prethodna; ?>">Prethodna</a></li>
            <?php
                for ($i=1; $i <= $brojStranica; $i++) { 
            ?>
                <li class="page-item <?php echo $stranica == $i ? "active":""?>"><a class="page-link" href="dostupnaVozila.php?stranica=<?php echo $i; ?>"><?php echo $i; ?></a></li>
            <?php
                }
            ?>
            <li class="page-item <?php echo $stranica == $brojStranica ? "disabled":""?>"><a class="page-link" href="dostupnaVozila.php?stranica=<?php echo $sljedeca; ?>">Sljedeća</a></li>
        </ul>
    </nav>
    <?php
    }
    else {
        echo "<div class='text-center alert alert-secondary mt-4'><h4>Nažalost, nema raspoloživih vozila za odabrani termin.</h4></div>";
    }
}
else {
    echo "<div class='text-center alert alert-secondary mt-4'><h4>Niste odabrali termin iznajmljivanja vozila.</h4><h5>Odaberite termin <a href='index.php'>ovdje</a>.</h5></div>";
    exit;
}
?>

</div>
<?php include("includes/footer.php"); ?>