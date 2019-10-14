<?php 
session_start();
if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin")
{
    if(isset($_POST["slika"]))
    {
        $idVozila = trim($_POST["idVozila"]);
        $slika = trim($_POST["slika"]);

        $path = "../img/slike".$idVozila."/".$slika;
        if(file_exists($path))
        {
            unlink($path);
        }
    }
}
?>