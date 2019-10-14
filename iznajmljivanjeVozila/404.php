<?php 
session_start();
if (strpos($_SERVER["REQUEST_URI"], 'admin') !== false && isset($_SESSION["role"]) && $_SESSION["role"] == "admin") {
    include("admin/includes/header.php"); 
    ?>
        <div class="container">
            <div class='text-center alert alert-secondary mt-4'>
                <h4>Tražena stranica ne postoji.</h4>
                <h5>Povratak na <a href='index.php'>kontrolnu ploču</a>.</h5>
            </div>
        </div>
    <?php
    include("admin/includes/footer.php"); 
}
else {
    $aktivnaStranica = "";
    include("includes/header.php"); 
?>
    <div class="container">
        <div class='text-center alert alert-secondary mt-4'>
            <h4>Tražena stranica ne postoji.</h4>
            <h5>Povratak na <a href='index.php'>početnu</a>.</h5>
        </div>
    </div>
<?php
include("includes/footer.php"); 
}
?>
