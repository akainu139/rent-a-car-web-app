<?php 
session_start();
$aktivnaStranica = "";
include("includes/header.php");
?>

<div class="container">
<?php 

$ime = "";
$prezime = "";
$greske = array("ime" => "", "prezime" => "", "status" => "");
if(isset($_SESSION["greskePlacanje"]))
{
    $ime = isset($_SESSION["imePlacanje"]) ? $_SESSION["imePlacanje"] : "";
    $prezime = isset($_SESSION["prezimePlacanje"]) ? $_SESSION["prezimePlacanje"] : "";
    $greske["ime"] = isset($_SESSION["greskePlacanje"]["ime"]) ? $_SESSION["greskePlacanje"]["ime"] : "";
    $greske["prezime"] = isset($_SESSION["greskePlacanje"]["prezime"]) ? $_SESSION["greskePlacanje"]["prezime"] : "";
    $greske["status"] = "<h4 class='my-3 alert alert-danger text-center'>Ispravite navedene greške i pokušajte ponovno.</h4>";
    unset($_SESSION["greskePlacanje"]);
    unset($_SESSION["imePlacanje"]);
    unset($_SESSION["prezimePlacanje"]);
}

if(isset($_POST["ime"]) && isset($_POST["prezime"]) && isset($_POST["stripeToken"]))
{
    $ime = trim(htmlentities($_POST["ime"]));
    $prezime = trim(htmlentities($_POST["prezime"]));
    $stripeToken = trim(htmlentities($_POST["stripeToken"]));
    
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
        include("includes/baza.php");
        $baza = new Baza();

        $sveukupnoZaPlacanje = number_format($_SESSION["sveukupnoZaPlacanje"], 2, ',', '');
        $iznos = (explode(",",$sveukupnoZaPlacanje));
        $iznos = $iznos[0].$iznos[1];

        require("admin/includes/keys.php");
        require_once('vendor/autoload.php');
        \Stripe\Stripe::setApiKey(STRIPE_API_KEY);
    
        $customer = \Stripe\Customer::create(array(
            "email" => $_SESSION["email"],
            "source" => $stripeToken
        ));

        $charge = \Stripe\Charge::create(array(
            "amount" => $iznos,
            "currency" => "hrk",
            "description" => $_SESSION["voziloMarkaModel"],
            "customer" => $customer->id
        ));
        
        $korisnikId = $_SESSION["korisnikId"];
        $voziloId = $_SESSION["voziloId"];
        $vrijemeOd = $_SESSION["vrijemeOd"];
        $vrijemeDo = $_SESSION["vrijemeDo"];
        $sveukupnoZaPlacanje = $_SESSION['sveukupnoZaPlacanje'];

        $vrijemeOd = date("Y-m-d H:i:s", strtotime($vrijemeOd));
        $vrijemeDo = date("Y-m-d H:i:s", strtotime($vrijemeDo));
        $provjera = $baza->dodajRezervaciju($korisnikId, $voziloId, $vrijemeOd, $vrijemeDo, $sveukupnoZaPlacanje, $charge["id"]);
        if($provjera)
        {
            $_SESSION["rezervacijaUspjeh"] = $_SESSION['voziloId'];
            unset($_SESSION['sveukupnoZaPlacanje']);
            unset($_SESSION['voziloId']);
            header("Location: index.php");
            exit;
        }
        else {
            $greske["status"] = "<h4 class='my-3 alert alert-danger text-center'>Dogodila se greška prilikom rezervacije.</h4>";
        }
    }
    else {
        $_SESSION["greskePlacanje"] = $greske;
        $_SESSION["imePlacanje"] = $ime;
        $_SESSION["prezimePlacanje"] = $prezime;
        header("Location: placanje.php");
        exit;
    }
}

$sveukupnoZaPlacanje = "";
if(isset($_SESSION["korisnikId"]) && isset($_SESSION["voziloId"]))
{
    $sveukupnoZaPlacanje = $_SESSION["sveukupnoZaPlacanje"];
?>

<div class="my-4 p-3 mojeRezervacijeKartica">
    <div class="row mt-4 w-75 mx-auto">
        <div class="col-sm-12 col-xl-4 text-center">
            <h5 class="mb-0 crveno">Vozilo</h5>
            <h5><?php echo $_SESSION["voziloMarkaModel"]; ?></h5>
            <hr>
        </div>
        <div class="col-sm-12 col-xl-4 text-center">
            <h5 class="mb-0 crveno">Početak</h5>
            <h5><?php echo $_SESSION["vrijemeOd"]; ?></h5>
            <hr>
        </div>
        <div class="col-sm-12 col-xl-4 text-center">
            <h5 class="mb-0 crveno">Kraj</h5>
            <h5><?php echo $_SESSION["vrijemeDo"]; ?></h5>
            <hr>
        </div>
    </div>
    <div class="row mt-3 w-50 mx-auto">
        <div class="col-sm-12 col-xl-6 text-center">
            <h5 class="mb-0 crveno">Cijena vozila po danu</h5>
            <h5><?php echo $_SESSION["cijenaPoDanu"] . " kn"; ?></h5>
            <hr>
        </div>
        <div class="col-sm-12 col-xl-6 text-center">
            <h5 class="mb-0 crveno">Sveukupno</h5>
            <h5><?php echo number_format($sveukupnoZaPlacanje, 2, ',', '') . " kn"; ?></h5>
            <hr>
        </div>
    </div>
</div>

<?php echo $greske["status"]; ?>

<form action="placanje.php" method="post" id="payment-form">
  <h3 class="w-100 text-center mb-4">Podaci za plaćanje</h3>
  <div class="form-row">
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

    <label for="card-element">Kreditna ili debitna kartica</label>
    <div id="card-element" class="form-control">
      <!-- A Stripe Element will be inserted here. -->
    </div>

    <!-- Used to display form errors. -->
    <div id="card-errors" role="alert"></div>
  </div>
  <button class="mt-2 predajBtn">Plati</button>
</form>
<script src="https://js.stripe.com/v3/"></script>
<script src="js/stripe.js"></script>

<?php
}
if($sveukupnoZaPlacanje == "")
{
    header("Location: index.php");
    exit;
}
?>

</div>
<?php include("includes/footer.php"); ?>