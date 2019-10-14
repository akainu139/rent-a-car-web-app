<?php 
    session_start();
    if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin")
    {
        include("includes/brisiImagesTemp.php");
        include("includes/baza.php");
        $baza = new Baza();

        $brojVozila = count($baza->dohvatiSvaVozila());
        $brojMarki = count($baza->dohvatiSveMarke());
        $brojKorisnika = count($baza->dohvatiSveKorisnike());
        $brojRezerviranihVozila = count($baza->dohvatiRezerviranaVozila());
        $brojOdsutnihVozila = count($baza->dohvatiOdsutnaVozila());
        $brojPrisutnihVozila = count($baza->dohvatiPrisutnaVozila());
    }
    else {
        header("Location: ../prijava.php");
        exit;
    }
?>

<?php include("includes/header.php"); ?>

<!-- Page Heading -->
<h1 class="h3 my-3 text-gray-800">Kontrolna ploča</h1>

<div class="row">

<!-- Vozila Card -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-info shadow h-100 py-2">
    <div class="card-body">
        <div class="row no-gutters align-items-center">
        <div class="col mr-2">
            <div class="text-md font-weight-bold text-info text-uppercase mb-1">Sva vozila</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $brojVozila; ?></div>
        </div>
        <div class="col-auto">
            <i class="fas fa-car fa-2x text-gray-300"></i>
        </div>
        </div>
    </div>
    <div class="card-footer text-center">
        <a href="upravljanje-vozilima.php" class="btn btn-info btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-right"></i>
            </span>
            <span class="text">Pregled</span>
        </a>
    </div>
    </div>
</div>

<!-- Marke Card -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-info shadow h-100 py-2">
    <div class="card-body">
        <div class="row no-gutters align-items-center">
        <div class="col mr-2">
            <div class="text-md font-weight-bold text-info text-uppercase mb-1">Marke</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $brojMarki; ?></div>
        </div>
        <div class="col-auto">
            <i class="fas fa-columns fa-2x text-gray-300"></i>
        </div>
        </div>
    </div>
    <div class="card-footer text-center">
        <a href="upravljanje-markama.php" class="btn btn-info btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-right"></i>
            </span>
            <span class="text">Pregled</span>
        </a>
    </div>
    </div>
</div>

<!-- Korisnici Card -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-info shadow h-100 py-2">
    <div class="card-body">
        <div class="row no-gutters align-items-center">
        <div class="col mr-2">
            <div class="text-md font-weight-bold text-info text-uppercase mb-1">Korisnici</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $brojKorisnika; ?></div>
        </div>
        <div class="col-auto">
            <i class="fas fa-users fa-2x text-gray-300"></i>
        </div>
        </div>
    </div>
    <div class="card-footer text-center">
        <a href="upravljanje-korisnicima.php" class="btn btn-info btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-right"></i>
            </span>
            <span class="text">Pregled</span>
        </a>
    </div>
    </div>
</div>

<!-- Rezervirana vozila Card -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-info shadow h-100 py-2">
    <div class="card-body">
        <div class="row no-gutters align-items-center">
        <div class="col mr-2">
            <div class="text-md font-weight-bold text-info text-uppercase mb-1">Rezervirana vozila</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $brojRezerviranihVozila; ?></div>
        </div>
        <div class="col-auto">
            <i class="fas fa-car fa-2x text-gray-300"></i>
        </div>
        </div>
    </div>
    <div class="card-footer text-center">
        <a href="rezervirana-vozila.php" class="btn btn-info btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-right"></i>
            </span>
            <span class="text">Pregled</span>
        </a>
    </div>
    </div>
</div>

<!-- Odsutna vozila Card -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-info shadow h-100 py-2">
    <div class="card-body">
        <div class="row no-gutters align-items-center">
        <div class="col mr-2">
            <div class="text-md font-weight-bold text-info text-uppercase mb-1">Odsutna vozila</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $brojOdsutnihVozila; ?></div>
        </div>
        <div class="col-auto">
            <i class="fas fa-car fa-2x text-gray-300"></i>
        </div>
        </div>
    </div>
    <div class="card-footer text-center">
        <a href="odsutna-vozila.php" class="btn btn-info btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-right"></i>
            </span>
            <span class="text">Pregled</span>
        </a>
    </div>
    </div>
</div>

<!-- Prisutna vozila Card -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-info shadow h-100 py-2">
    <div class="card-body">
        <div class="row no-gutters align-items-center">
        <div class="col mr-2">
            <div class="text-md font-weight-bold text-info text-uppercase mb-1">Prisutna vozila</div>
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $brojPrisutnihVozila; ?></div>
        </div>
        <div class="col-auto">
            <i class="fas fa-car fa-2x text-gray-300"></i>
        </div>
        </div>
    </div>
    <div class="card-footer text-center">
        <a href="prisutna-vozila.php" class="btn btn-info btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-right"></i>
            </span>
            <span class="text">Pregled</span>
        </a>
    </div>
    </div>
</div>


</div>

<?php include("includes/footer.php"); ?>