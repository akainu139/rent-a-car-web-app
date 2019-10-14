<?php 
session_start();
if(isset($_SESSION["korisnikId"]))
{
    $aktivnaStranica = "mojeRezervacije";
    include("includes/header.php"); 
    include("includes/baza.php");
    $baza = new Baza();
    date_default_timezone_set("Europe/Zagreb");

    $status = "";
    if(isset($_SESSION["rezervacijaOtkazana"]))
    {
        $status = '
        <div class="col-lg-12 p-0 text-center">
            <div class="mt-3 alert alert-success">
                <h5><strong>Rezervacija je uspješno otkazana.</strong></h5>
            </div>
        </div>
        ';
        unset($_SESSION["rezervacijaOtkazana"]);
    }

    if(isset($_POST["predajOtkazi"]))
    {
        $id = $_POST["id"];
        $provjera = $baza->otkaziRezervaciju($id);
        if($provjera)
        {
            require("admin/includes/keys.php");
            require_once('vendor/autoload.php');
            \Stripe\Stripe::setApiKey(STRIPE_API_KEY);

            $re = \Stripe\Refund::create([
                "charge" => $_SESSION["naplatniKod"]
            ]);
            unset($_SESSION['naplatniKod']);

            $_SESSION["rezervacijaOtkazana"] = true;
            header("Location: mojeRezervacije.php");
            exit;
        }
        else {
            $status = '
            <div class="col-lg-12 p-0 text-center">
                <div class="mt-3 alert alert-danger">
                    <h5><strong>Dogodila se greška prilikom otkazivanja vozila.</strong></h5>
                </div>
            </div>
            ';
        }
    }

    $podaciRezervacije = $baza->dohvatiRezervacijePoKorisniku($_SESSION["korisnikId"], 0, PHP_INT_MAX, "sve");
    if(count($podaciRezervacije) > 0)
    {
        foreach ($podaciRezervacije as $rezervacija) {
            if(date("Y-m-d H:i:s") < $rezervacija["vrijemeOd"])
            {
                $baza->urediVremenskoStanjeRezervacije($rezervacija["rezervacijaId"], "nadolazece");
            }
            else if(date("Y-m-d H:i:s") > $rezervacija["vrijemeDo"])
            {
                $baza->urediVremenskoStanjeRezervacije($rezervacija["rezervacijaId"], "istekle");
            }
            else {
                $baza->urediVremenskoStanjeRezervacije($rezervacija["rezervacijaId"], "aktivne");
            }
        }
    }
    else {
    ?>
        <div class="container">
        <?php echo $status; ?>
        <div class='text-center alert alert-secondary mt-4'>
            <h4>Trenutno nemate rezerviranih vozila.</h4>
        </div>
        </div>
    <?php
        exit;
    }

    if(isset($_GET["vremenskoStanje"]))
    {
        $dopusteno = ["aktivne", "nadolazece", "istekle", "sve"];
        if(in_array($_GET["vremenskoStanje"], $dopusteno))
        {
            $_SESSION["vremenskoStanje"] = $_GET["vremenskoStanje"];
        }
        else {
            $_SESSION["vremenskoStanje"] = "sve";
        }
    }
?>
<div class="container">
<?php
    echo $status;

    $vremenskoStanje = isset($_SESSION["vremenskoStanje"]) ? $_SESSION["vremenskoStanje"] : "sve";
    
    $brojRezervacija = $baza->dohvatiBrojRezervacijaPoKorisniku($_SESSION["korisnikId"], $vremenskoStanje);
    $limit = 2;
    $stranica = isset($_GET["stranica"]) ? $_GET["stranica"] : 1;

    $brojStranica = ceil($brojRezervacija / $limit);
    if($stranica < 1) $stranica = 1;
    if($stranica > $brojStranica) $stranica = $brojStranica;

    $prethodna = $stranica - 1;
    $sljedeca = $stranica + 1;

    $start = ($stranica - 1) * $limit;
    $podaciRezervacije = $baza->dohvatiRezervacijePoKorisniku($_SESSION["korisnikId"], $start, $limit, $vremenskoStanje);

    if(count($podaciRezervacije) > 0)
    {
    ?>
        <h2 class="my-3 text-center">Popis rezerviranih vozila</h2>
        <form action="" method="get" id="promjenaStanjaForm">
            <label for="vremenskoStanje">Izaberite rezervacije po vremenu</label>
            <select class="browser-default custom-select" id="vremenskoStanje" name="vremenskoStanje">
                <option value="sve" <?php echo ($vremenskoStanje == "sve" ? "selected" : ""); ?> >Sve</option>
                <option value="aktivne" <?php echo ($vremenskoStanje == "aktivne" ? "selected" : ""); ?> >Aktivne</option>
                <option value="nadolazece" <?php echo ($vremenskoStanje == "nadolazece" ? "selected" : ""); ?> >Nadolazeće</option>
                <option value="istekle" <?php echo ($vremenskoStanje == "istekle" ? "selected" : ""); ?> >Istekle</option>
            </select>
        </form>
    <?php
        foreach ($podaciRezervacije as $rezervacija) {
            if(date("Y-m-d H:i:s") < $rezervacija["vrijemeOd"])
            {
                // zuta
                $boja = "#FFFFBB";
            }
            else if(date("Y-m-d H:i:s") > $rezervacija["vrijemeDo"])
            {
                //crvena
                $boja = "#FF9999";
            }
            else {
                // zelena
                $boja = "#90EE90";
            }
        ?>
            <div class="my-4 p-3 mojeRezervacijeKartica" style="background: <?php echo $boja; ?>">
                <div class="row mt-4 w-75 mx-auto red1">
                    <div class="col-xs-12 col-sm-4 text-center">
                        <h5 class="mb-0 crveno">Vozilo</h5>
                        <h5><?php echo $rezervacija["marka"] . " " . $rezervacija["model"]; ?></h5>
                        <hr>
                    </div>
                    <div class="col-xs-12 col-sm-4 text-center">
                        <h5 class="mb-0 crveno">Sveukupno</h5>
                        <h5><?php echo $rezervacija["sveukupnoZaPlacanje"] . " kn"; ?></h5>
                        <hr>
                    </div>
                    <div class="col-xs-12 col-sm-4 text-center">
                        <h5 class="mb-0 crveno">Datum rezervacije</h5>
                        <h5><?php echo date("d.m.Y H:i", strtotime($rezervacija["vrijemeRezervacije"])); ?></h5>
                        <hr>
                    </div>
                </div>
                <div class="row mt-3 w-50 mx-auto red2">
                    <div class="col-xs-12 col-sm-6 text-center">
                        <h5 class="mb-0 crveno">Početak</h5>
                        <h5><?php echo date("d.m.Y H:i", strtotime($rezervacija["vrijemeOd"])); ?></h5>
                        <hr>
                    </div>
                    <div class="col-xs-12 col-sm-6 text-center">
                        <h5 class="mb-0 crveno">Kraj</h5>
                        <h5><?php echo date("d.m.Y H:i", strtotime($rezervacija["vrijemeDo"])); ?></h5>
                        <hr>
                    </div>
                </div>
                <a href="pregledVozila.php?id=<?php echo $rezervacija["id"]; ?>" class="predajBtn">
                    <span class="text-white-50">
                    <i class="fas fa-info-circle"></i>
                    </span>
                    <span class="text">Pregled</span>
                </a>
                <?php 
                date_default_timezone_set("Europe/Zagreb");
                $timestamp1 = strtotime($rezervacija["vrijemeOd"]);
                $timestamp2 = strtotime(date("Y-m-d H:i:s"));
                if(($timestamp1 - $timestamp2) > (2*24*60*60))
                {
                ?>
                <div>  
                    <button type="button" class="predajBtn mt-2" data-toggle="modal" data-target="#otkaziRezervacijuModal<?php echo $rezervacija['rezervacijaId']; ?>">
                        <span class="icon text-white-50">
                        <i class="fas fa-trash"></i>
                        </span>
                        <span class="text">Otkaži rezervaciju</span>
                    </button>
                </div>

                <div class="modal fade" id="otkaziRezervacijuModal<?php echo $rezervacija["rezervacijaId"]; ?>" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Otkazivanje?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Jeste li sigurni da želite otkazati vozilo <?php echo $rezervacija["marka"] . " " . $rezervacija["model"]; ?>?</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Odustani</button>
                        <form action="mojeRezervacije.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $rezervacija["rezervacijaId"]; ?>">
                        <button type="submit" class="btn btn-danger btn-icon-split" name="predajOtkazi">
                            <span class="icon text-white-50">
                            <i class="fas fa-trash"></i>
                            </span>
                            <span class="text">Otkaži rezervaciju</span>
                        </button>
                        </form>  
                    </div>
                    </div>
                </div>
                </div>
            
                <?php
                }
                ?>
            </div>
        <?php
        }
    ?>
        <nav>
            <ul class="pagination justify-content-center mt-3">
                <li class="page-item <?php echo $stranica == 1 ? "disabled":""?>"><a class="page-link" href="mojeRezervacije.php?stranica=<?php echo $prethodna; ?>">Prethodna</a></li>
                <?php
                    for ($i=1; $i <= $brojStranica; $i++) { 
                ?>
                    <li class="page-item <?php echo $stranica == $i ? "active":""?>"><a class="page-link" href="mojeRezervacije.php?stranica=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php
                    }
                ?>
                <li class="page-item <?php echo $stranica == $brojStranica ? "disabled":""?>"><a class="page-link" href="mojeRezervacije.php?stranica=<?php echo $sljedeca; ?>">Sljedeća</a></li>
            </ul>
        </nav>

    <?php
    }
    else {
    ?>
        <div class='text-center alert alert-secondary mt-4'>
            <h4>Nemate rezerviranih vozila u toj kategoriji.</h4>
            <form action="" method="get" id="promjenaStanjaForm">
                <label for="vremenskoStanje">Izaberite rezervacije po vremenu</label>
                <select class="browser-default custom-select" id="vremenskoStanje" name="vremenskoStanje">
                    <option value="sve" <?php echo ($vremenskoStanje == "sve" ? "selected" : ""); ?> >Sve</option>
                    <option value="aktivne" <?php echo ($vremenskoStanje == "aktivne" ? "selected" : ""); ?> >Aktivne</option>
                    <option value="nadolazece" <?php echo ($vremenskoStanje == "nadolazece" ? "selected" : ""); ?> >Nadolazeće</option>
                    <option value="istekle" <?php echo ($vremenskoStanje == "istekle" ? "selected" : ""); ?> >Istekle</option>
                </select>
            </form> 
        </div>
        
    <?php
    }
}
else{
    header("Location: index.php");
    exit;
}
?>

</div>
<script>
$(document).ready(function(){
    $("#vremenskoStanje").change(function(){
        $("form#promjenaStanjaForm").submit();
    });

});
</script>
<?php include("includes/footer.php"); ?>