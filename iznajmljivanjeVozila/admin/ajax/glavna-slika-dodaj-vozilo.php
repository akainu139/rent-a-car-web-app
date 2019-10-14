<?php  
session_start();
if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin")
{
        $stariNaziv = ltrim(trim($_POST["slika"]), '0');

        $popisSlika = scandir("../imagesTemp");
        $popisSlika = array_diff($popisSlika, [".", ".."]);
        if(count($popisSlika) > 0)
        {
            foreach ($popisSlika as $slika)
            {
                if($slika[0] == "0")
                {
                    $novo = ltrim($slika, '0');
                    rename("../imagesTemp/".$slika,"../imagesTemp/".$novo);
                }
            }
            rename("../imagesTemp/".$stariNaziv,"../imagesTemp/"."0".$stariNaziv);
        }

        $popisSlika = scandir("../imagesTemp");
        $popisSlika = array_diff($popisSlika, [".", ".."]);
        if(count($popisSlika) > 0)
        {
            echo '<div class="row provjeraZaBrojSlika">';
            foreach ($popisSlika as $slika) 
            {
            ?>
                <div class="m-2 mt-3 card obrisiDiv<?php echo $slika; ?>">
                    <img class="card-img-top img-fluid vozilaSlike" src="<?php echo "imagesTemp/" . $slika ?>" alt="vozilo">
                    <div class="card-body text-center">
                        <button type="button" class="btn btn-danger btn-icon-split tipkaZaBrisanje" id="<?php echo $slika; ?>" data-toggle="modal" data-target="#ukloniSlikuVozilaModal" value="<?php echo '../imagesTemp/' . $slika; ?>">
                            <span class="icon text-white-50">
                            <i class="fas fa-trash"></i>
                            </span>
                            <span class="text">Ukloni</span>
                        </button>
                        <button type="button" class="btn btn-primary btn-icon-split glavnaSlika" value="<?php echo $slika; ?>">
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
<script src="js/brisanjeSlikeUrediVozilo.js"></script>
<script src="js/glavnaSlikaDodajVozilo.js"></script>
