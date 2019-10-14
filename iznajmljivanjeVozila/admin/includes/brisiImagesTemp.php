<?php 
    if(isset($_SESSION["dodajVozilo"]))
    {
        $path = "imagesTemp";
            $popisSlikaZaBrisanje = scandir($path);
            $popisSlikaZaBrisanje = array_diff($popisSlikaZaBrisanje, [".", ".."]);
            if(count($popisSlikaZaBrisanje) > 0)
            {
                foreach ($popisSlikaZaBrisanje as $slika) 
                {
                    unlink($path."/".$slika);
                }
            }
        unset($_SESSION['dodajVozilo']);
    }
?>