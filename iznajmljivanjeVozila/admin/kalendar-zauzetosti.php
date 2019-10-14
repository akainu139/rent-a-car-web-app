<?php
session_start();
if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin")
{
    
include("includes/brisiImagesTemp.php");
date_default_timezone_set('Europe/Zagreb');

if (isset($_GET['ym'])) {
    $ym = $_GET['ym'];
} else {
    $ym = date('Y-m');
}

$timestamp = strtotime($ym . '-01'); 
if ($timestamp === false) {
    $ym = date('Y-m');
    $timestamp = strtotime($ym . '-01');
}

$today = date('Y-m-d');
$title = date('F, Y', $timestamp);
$prev = date('Y-m', strtotime('-1 month', $timestamp));
$next = date('Y-m', strtotime('+1 month', $timestamp));
$day_count = date('t', $timestamp);
$str = date('N', $timestamp);
$weeks = [];
$week = '';
$week .= str_repeat('<td></td>', $str - 1);
for ($day = 1; $day <= $day_count; $day++, $str++) {
    if($day < 10)
    {
      $date = $ym . '-0' . $day;
    }
    else {
      $date = $ym . '-' . $day;
    }
    $datumi[] = $date;
    
    if ($today == $date) {
      $week .= "<td class='today text-center' id='dan$day'>";
    } else {
        $week .= "<td class='text-center' id='dan$day'>";
    }
 
    $week .= $day . '</td>';
    if ($str % 7 == 0 || $day == $day_count) {
        if ($day == $day_count && $str % 7 != 0) {
            $week .= str_repeat('<td></td>', 7 - $str % 7);
        }
        $weeks[] = '<tr>' . $week . '</tr>';
        $week = '';
    }
}
include("includes/header.php");
?>
<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
<link rel="stylesheet" href="css/kalendar.css">
<?php
        $mjesec = explode(",", $title)[0];
        $godina = explode(",", $title)[1];
        switch ($mjesec) {
            case 'January':
                $mjesec = "Siječanj";
                break;
            case 'February':
                $mjesec = "Veljača";
                break;
            case 'March':
                $mjesec = "Ožujak";
                break;
            case 'April':
                $mjesec = "Travanj";
                break;
            case 'May':
                $mjesec = "Svibanj";
                break;
            case 'June':
                $mjesec = "Lipanj";
                break;
            case 'July':
                $mjesec = "Srpanj";
                break;
            case 'August':
                $mjesec = "Kolovoz";
                break;
            case 'September':
                $mjesec = "Rujan";
                break;
            case 'October':
                $mjesec = "Listopad";
                break;
            case 'November':
                $mjesec = "Studeni";
                break;
            case 'December':
                $mjesec = "Prosinac";
                break;
            default:
                $mjesec = $title;
                break;
        }
    ?>
    <div class="container">
        <ul class="list-inline">
            <li class="list-inline-item"><a href="?ym=<?= $prev; ?>" class="btn btn-link">&lt; prethodni</a></li>
            <li class="list-inline-item"><span class="title"><?= $mjesec . ", " . $godina; ?></span></li>
            <li class="list-inline-item"><a href="?ym=<?= $next; ?>" class="btn btn-link">sljedeći &gt;</a></li>
        </ul>

        <div class="row">
            <div class="col-6">
            <div class="d-flex">
                <div class="zauzeto mb-1 mr-1" style="width:25px; height:25px;"></div>
                <div>Vozilo rezervirano</div>
            </div>
            <div class="d-flex">
                <div class="zauzetoPotvrdeno mr-1" style="width:25px; height:25px;"></div>
                <div>Vozilo preuzeto</div>
            </div>
            </div>
            <div class="col-6">
                <p class="text-right py-3 px-0 m-0" ><a href="kalendar-zauzetosti.php">Danas</a></p>
            </div>
        </div>
        <div class="scroll">
        <table class="table table-bordered table-bordered">
            <thead>
                <tr>
                    <th>Pon</th>
                    <th>Uto</th>
                    <th>Sri</th>
                    <th>Čet</th>
                    <th>Pet</th>
                    <th>Sub</th>
                    <th>Ned</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($weeks as $week) {
                        echo $week;
                    }
                ?>
            </tbody>
        </table>
        </div>
        <a href="rezervirana-vozila.php" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
            <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">Povratak</span>
        </a>
    </div>

<?php 
    include("includes/baza.php");
    $baza = new Baza();
    if(isset($_SESSION["voziloId"]))
    {
        $id = $_SESSION["voziloId"];
    }
    else {
        header("Location: rezervirana-vozila.php");
        exit;
    }
    $zauzetostVozila = $baza->dohvatiRezervacijePoVozilu($id);
    
    for($i=0; $i < count($datumi); $i++) { 
    for ($j=0; $j < count($zauzetostVozila); $j++) { 
        if(date("Y-m-d", strtotime($zauzetostVozila[$j]["vrijemeOd"])) <= $datumi[$i] && $datumi[$i] <= date("Y-m-d", strtotime($zauzetostVozila[$j]["vrijemeDo"])))
        {
            if($zauzetostVozila[$j]["odsutno"] == 1)
            {
            ?>
            <script>
                document.getElementById("dan<?php echo $i+1; ?>").classList.add("zauzetoPotvrdeno");
            </script>
            <?php
            }
            else {
            ?>
            <script>
                document.getElementById("dan<?php echo $i+1; ?>").classList.add("zauzeto");
            </script>
            <?php
            }
        }
    }
    }
?>

<?php 
include("includes/footer.php");
} else {
    header("Location: ../prijava.php");
    exit;
}
?>
