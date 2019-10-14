<?php 
    session_start();
    $aktivnaStranica = "pocetna";
    include("includes/header.php");
    include("includes/baza.php");
    
    $greske = array("vrijemeOd" => "", "vrijemeDo" => "","status" => "");
    if(isset($_SESSION["rezervacijaUspjeh"]))
    {
        if (($key = array_search($_SESSION["rezervacijaUspjeh"], $_SESSION["dostupnaVozilaIds"])) !== false) {
            unset($_SESSION["dostupnaVozilaIds"][$key]);
        }
        echo "<div class='text-center alert alert-success m-0'><h3>Vaše vozilo je uspješno rezervirano.</h3></div>";
        unset($_SESSION['rezervacijaUspjeh']);
    }

    if(isset($_POST["predajVrijeme"]))
    {
        include("includes/funkcije.php");
        $vrijemeOd = htmlentities($_POST["vrijemeOd"]);
        $vrijemeDo = htmlentities($_POST["vrijemeDo"]);
        
        if(!validateDate($vrijemeOd))
        {
            $greske["vrijemeOd"] = "Nepravilan format vremena.";
            $greske["status"] = "greska";
        }
        if(!(validateDate($vrijemeDo)))
        {
            $greske["vrijemeDo"] = "Nepravilan format vremena.";
            $greske["status"] = "greska";
        }

        $timestamp1 = strtotime($vrijemeOd);
        $timestamp2 = strtotime($vrijemeDo);
        if(($timestamp2 - $timestamp1) < 3600)
        {
            $greske["vrijemeDo"] = "Vrijeme kraja mora biti veće od vremena početka za najmanje 1 sat.";
            $greske["status"] = "greska";
        }
        if($greske["status"] == "")
        {
            $_SESSION["vrijemeOd"] = $vrijemeOd;
            $_SESSION["vrijemeDo"] = $vrijemeDo;
            header("Location: dostupnaVozila.php");
            exit;
        }
    }
    ?>

    <div id="headerBG">
    <div class="zatamnjenjeBG">
        <form action="index.php" method="post" id="traziVozilaForm" class="container d-flex justify-content-center align-items-center h-100">
        <div class="row w-100">
            <div class="col-md-12 text-center mb-2">
                <h4>Odaberite željeni termin za iznajmljivanje vozila.</h4>
            </div>
            
            <div class="form-group col-md-6">
                <label for="vrijemeOd">Vrijeme od</label>
                <input type="text" name="vrijemeOd" id="vrijemeOd" class="form-control <?php echo ($greske["vrijemeOd"] == "" ? "":"is-invalid"); ?>" onkeydown="event.preventDefault()" autocomplete="off" required>
                <div class="invalid-feedback"><?php echo $greske["vrijemeOd"]; ?></div>
            </div>

            <div class="form-group col-md-6">
                <label for="vrijemeDo">Vrijeme do</label>
                <input type="text" name="vrijemeDo" id="vrijemeDo" class="form-control <?php echo ($greske["vrijemeDo"] == "" ? "":"is-invalid"); ?>" onkeydown="event.preventDefault()" autocomplete="off" required>
                <div class="invalid-feedback"><?php echo $greske["vrijemeDo"]; ?></div>
            </div>

            <div class="col-md-12 text-center mt-2">
                <input type="submit" name="predajVrijeme" class="predajBtn" value="Traži vozila">
            </div>
        </div>
        </form>
    </div>
    </div>


<script>
    $(document).ready(function(){
        var today = new Date();
        $('#vrijemeOd').datetimepicker({
            format: 'dd.mm.yyyy hh:ii',
            language: 'hr',
            autoclose: true,
            todayBtn: true,
            startDate : today
        }).on('changeDate', function(ev){
            $('#vrijemeDo').datetimepicker('setStartDate', ev.date);
            $('#vrijemeDo').focus();
        });
        
        $('#vrijemeDo').datetimepicker({
            format: 'dd.mm.yyyy hh:ii',
            language: 'hr',
            autoclose: true,
            todayBtn: true,
            startDate : today
        }).on('changeDate', function(ev){
            $('#vrijemeOd').datetimepicker('setEndDate', ev.date);
        });
    });
</script>

<?php include("includes/footer.php"); ?>