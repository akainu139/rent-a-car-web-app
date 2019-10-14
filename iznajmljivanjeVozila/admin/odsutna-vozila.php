<?php 
    session_start();
    if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin")
    {
        include("includes/brisiImagesTemp.php");

        $status = "";
        if(isset($_SESSION["promjenaStanjaVozilaOdsutna"]))
        {
            $status = '
            <div class="col-lg-12">
                <div class="card mb-4 mt-3 border-bottom-success">
                    <div class="card-body text-dark">
                        <h5><strong>Stanje vozila je uspješno ažurirano.</strong></h5>
                    </div>
                </div>
            </div>
            ';
            unset($_SESSION['promjenaStanjaVozilaOdsutna']);
        }

        include("includes/baza.php");
        $baza = new Baza();
        if(isset($_POST["predajStanjeVozila"]))
        {
            $id = $_POST["id"];
            $provjera = $baza->urediStanjeVozila($id, 0);
            if($provjera)
            {
                $_SESSION["promjenaStanjaVozilaOdsutna"] = true;
                header("Location: odsutna-vozila.php");
                exit;
            }
            else {
                $status = '
                <div class="col-lg-12">
                    <div class="card mb-4 mt-3 border-bottom-danger">
                        <div class="card-body text-dark">
                            <h5><strong>Dogodila se greška prilikom promjene stanja vozila.</strong></h5>
                        </div>
                    </div>
                </div>
                ';
            }
        }
    }
    else {
        header("Location: ../prijava.php");
        exit;
    }
?>

<?php 
    include("includes/header.php");

    $odsutnaVozila = $baza->dohvatiOdsutnaVozila();
?>

<!-- Page Heading -->
<h1 class="h3 my-3 text-gray-800">Pregled odsutnih vozila</h1>

<?php echo $status; ?>

<!-- DataTales Example -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h5 class="m-0 font-weight-bold text-primary">Odsutna vozila</h5>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTableOdsutnaVozila" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Korisničko ime</th>
            <th>Ime</th>
            <th>Prezime</th>
            <th>Marka</th>
            <th>Model</th>
            <th>Od</th>
            <th>Do</th>
            <th>Vrijeme rezervacije</th>
            <th>Akcija</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th>ID</th>
            <th>Korisničko ime</th>
            <th>Ime</th>
            <th>Prezime</th>
            <th>Marka</th>
            <th>Model</th>
            <th>Od</th>
            <th>Do</th>
            <th>Vrijeme rezervacije</th>
            <th>Akcija</th>
          </tr>
        </tfoot>
        <tbody>
            <?php 
            foreach($odsutnaVozila as $odsutnoVozilo)
            {
            ?>
                <tr>
                    <td><?php echo $odsutnoVozilo["id"]; ?></td>
                    <td><?php echo $odsutnoVozilo["korisnickoIme"]; ?></td>
                    <td><?php echo $odsutnoVozilo["ime"]; ?></td>
                    <td><?php echo $odsutnoVozilo["prezime"]; ?></td>
                    <td><?php echo $odsutnoVozilo["marka"]; ?></td>
                    <td><?php echo $odsutnoVozilo["model"]; ?></td>
                    <td><?php echo date("d.m.Y H:i", strtotime($odsutnoVozilo["vrijemeOd"])); ?></td>
                    <td><?php echo date("d.m.Y H:i", strtotime($odsutnoVozilo["vrijemeDo"])); ?></td>
                    <td><?php echo date("d.m.Y H:i", strtotime($odsutnoVozilo["vrijemeRezervacije"])); ?></td>
                    <td>
                    <div>
                        <div class="my-auto">  
                            <form action="odsutna-vozila.php" method="post">
                                <input type="hidden" name="id" value=<?php echo $odsutnoVozilo["id"]; ?>>
                                <button type="submit" name="predajStanjeVozila" class="btn btn-secondary btn-sm">
                                    <span class="text">Vozilo vraćeno</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include("includes/footer.php"); ?>