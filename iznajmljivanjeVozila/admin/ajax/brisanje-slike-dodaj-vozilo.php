<?php 
session_start();
if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin")
{
    if(isset($_POST["slika"]))
    {
        $slika = trim($_POST["slika"]);

        $path = "../imagesTemp/".$slika;
        if(file_exists($path))
        {
            unlink($path);
        }
    }
}
?> 
