<?php  
session_start();
if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin")
{
    if(isset($_FILES["slike"]["name"][0]))
    {
        $idvozila = trim($_POST["idvozila"]);
        $poruka = "";
        require("../includes/crop.php");
        for($i=0; $i < count($_FILES["slike"]["name"]); $i++)
        {
            $naziv = trim(htmlentities($_FILES["slike"]["name"][$i]));
            $temp = $_FILES["slike"]["tmp_name"][$i];
            $error = $_FILES["slike"]["error"][$i];
            $size = $_FILES['slike']['size'][$i];
            
            $ekstenzija = explode(".",$naziv);
            $dopusteneEkstenzije = ["jpg", "jpeg", "png"];
            if(in_array(strtolower(trim(end($ekstenzija))), $dopusteneEkstenzije))
            {
                if($error == 0 && $size != 0)
                {
                    // crop
                    /* 
                    $noviNazivSlike = uniqid("",true);
                    $putanja = "../img/slike".$idvozila."/".$noviNazivSlike.".".end($ekstenzija);
                    move_uploaded_file($temp, $putanja);
                    
                    $greska = image_resize($putanja, $putanja , 400, 400, 1);
                    if($greska !== true)
                    {
                        echo "Datoteka " . $naziv . " " . $greska . "<br>";
                        unlink($putanja);
                    }
                    */
                    
                    $noviNazivSlike = uniqid("",true);
                    move_uploaded_file($temp, "../img/slike".$idvozila."/".$noviNazivSlike.".".end($ekstenzija));
                    
                }
                else {
                    $poruka .= "Datoteka " . $naziv . " je prevelika ili nije ispravna. Datoteka mora biti jpg, jpeg ili png formata." . "<br>";
                }                
            }
            else {
                $poruka .= "Datoteka " . $naziv . " nije dodana. Datoteka mora biti jpg, jpeg ili png formata." . "<br>";
            }
        }
        echo $poruka;
        
        $popisSlika = scandir("../img/slike".$idvozila);
        $popisSlika = array_diff($popisSlika, [".", ".."]);
        if(count($popisSlika) > 0)
        {
            echo '<div class="row provjeraZaBrojSlika">';
            foreach ($popisSlika as $slika) 
            {
            ?>
                <div class="m-2 mt-3 card obrisiDiv<?php echo $slika; ?>">
                    <img class="card-img-top img-fluid vozilaSlike" src="<?php echo "img/slike".$idvozila."/".$slika ?>" alt="vozilo">
                    <div class="card-body text-center">
                        <button type="button" class="btn btn-danger btn-icon-split tipkaZaBrisanje" id="<?php echo $slika; ?>" data-idvozila="<?php echo $idvozila; ?> " data-toggle="modal" data-target="#ukloniSlikuVozilaModal" value="<?php echo "../img/slike".$idvozila."/".$slika; ?>">
                            <span class="icon text-white-50">
                            <i class="fas fa-trash"></i>
                            </span>
                            <span class="text">Ukloni</span>
                        </button>
                        <button type="button" class="btn btn-primary btn-icon-split glavnaSlika" data-idvozila="<?php echo $idvozila; ?> " value="<?php echo $slika; ?>">
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
}
?>

<script src="js/brisanjeSlikeUrediVozilo.js"></script>
<script src="js/glavnaSlikaUrediVozilo.js"></script>
