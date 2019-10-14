<?php 
    session_start();
    if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin")
    {
        include("includes/brisiImagesTemp.php");
        
        $status = "";
        if(isset($_SESSION["promjenaStanjaVozilaPrisutna"]))
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
            unset($_SESSION['promjenaStanjaVozilaPrisutna']);
        }

        include("includes/baza.php");
        $baza = new Baza();
        if(isset($_POST["predajStanjeVozila"]))
        {
            $id = $_POST["id"];
            $provjera = $baza->urediStanjeVozila($id, 1);
            if($provjera)
            {
                $_SESSION["promjenaStanjaVozilaPrisutna"] = true;
                header("Location: prisutna-vozila.php");
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

    $prisutnaVozila = $baza->dohvatiPrisutnaVozila();
?>

<!-- Page Heading -->
<h1 class="h3 my-3 text-gray-800">Pregled prisutnih vozila</h1>

<?php echo $status; ?>

<!-- DataTales Example -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h5 class="m-0 font-weight-bold text-primary">Prisutna vozila</h5>
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
            foreach($prisutnaVozila as $prisutnoVozilo)
            {
            ?>
                <tr>
                    <td><?php echo $prisutnoVozilo["id"]; ?></td>
                    <td><?php echo $prisutnoVozilo["korisnickoIme"]; ?></td>
                    <td><?php echo $prisutnoVozilo["ime"]; ?></td>
                    <td><?php echo $prisutnoVozilo["prezime"]; ?></td>
                    <td><?php echo $prisutnoVozilo["marka"]; ?></td>
                    <td><?php echo $prisutnoVozilo["model"]; ?></td>
                    <td><?php echo date("d.m.Y H:i", strtotime($prisutnoVozilo["vrijemeOd"])); ?></td>
                    <td><?php echo date("d.m.Y H:i", strtotime($prisutnoVozilo["vrijemeDo"])); ?></td>
                    <td><?php echo date("d.m.Y H:i", strtotime($prisutnoVozilo["vrijemeRezervacije"])); ?></td>
                    <td>
                    <div>
                        <div class="my-auto">  
                            <form action="prisutna-vozila.php" method="post">
                                <input type="hidden" name="id" value=<?php echo $prisutnoVozilo["id"]; ?>>
                                <button type="submit" name="predajStanjeVozila" class="btn btn-primary btn-sm">
                                    <span class="text">Vozilo preuzeto</span>
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