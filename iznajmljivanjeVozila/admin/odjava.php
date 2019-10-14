<?php 
session_start();
include("includes/brisiImagesTemp.php");
session_unset(); 
session_destroy();
header("Location: ../index.php");
?>