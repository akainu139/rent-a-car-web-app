<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Iznajmljivanje vozila</title>

    <!-- jquery -->
    <script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous"></script>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- datetimepicker -->
    <link rel="stylesheet" href="bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css">
    <script src="bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js"></script>
    <script src="bootstrap-datetimepicker-master/js/locales/bootstrap-datetimepicker.hr.js"></script>

    <!-- Font awesome -->
    <script src="https://kit.fontawesome.com/b1d4226e79.js"></script>
    
    <!-- Style.css --> 
    <link rel="stylesheet" href="css/style.css">

    <!-- Stripe.css -->    
    <link rel="stylesheet" href="css/stripe.css">
    
</head>
<body>

<div id="navbar">
    <nav class="navbar navbar-expand-md">
    <div class="navbarSection1">
        <a class="navbar-brand" href="index.php">Logo</a>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mx-auto navbarSection2">
        <li class="nav-item <?php if($aktivnaStranica == "pocetna") echo "active"?>">
            <a class="nav-link" href="index.php">Početna</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Vozila
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item <?php if($aktivnaStranica == "svaVozila") echo "active"?>" href="svaVozila.php">Sva vozila</a>
            <a class="dropdown-item <?php if($aktivnaStranica == "dostupnaVozila") echo "active"?>" href="dostupnaVozila.php">Dostupna vozila</a>
            </div>
        </li>
        <li class="nav-item <?php if($aktivnaStranica == "oNama") echo "active"?>">
            <a class="nav-link" href="oNama.php">O nama</a>
        </li>
        <?php 
        if(isset($_SESSION["korisnikId"]))
        {
        ?>
            <li class="nav-item <?php if($aktivnaStranica == "mojeRezervacije") echo "active"?>">
                <a class="nav-link" href="mojeRezervacije.php">Moje rezervacije</a>
            </li>
            <li class="nav-item <?php if($aktivnaStranica == "mojProfil") echo "active"?>">
                <a class="nav-link" href="mojProfil.php">Moj profil</a>
            </li>
        <?php    
        }
        ?>
        </ul>
        <ul class="navbar-nav ml-auto navbarSection3">
            <?php 
            if(isset($_SESSION["korisnikId"]))
            {
            ?>
            <li class="nav-item <?php if($aktivnaStranica == "odjava") echo "active"?>">
                <a class="nav-link" href="odjava.php">Odjava</a>
            </li>
            <?php
            }
            else {
            ?>
            <li class="nav-item <?php if($aktivnaStranica == "registracija") echo "active"?>">
                <a class="nav-link" href="registracija.php">Registracija</a>
            </li>
            <li class="nav-item <?php if($aktivnaStranica == "prijava") echo "active"?>">
                <a class="nav-link" href="prijava.php">Prijava</a>
            </li>
            <?php
            }
            ?>
        </ul>
    </div>
    </nav>
</div>

<?php 
if(isset($_SESSION["tempVozilo"]) && $aktivnaStranica != "prijava" && $aktivnaStranica != "registracija")
{
    unset($_SESSION['tempVozilo']);
}
?>

