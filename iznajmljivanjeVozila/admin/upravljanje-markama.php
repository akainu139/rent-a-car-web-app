<?php 
    session_start();
    if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin")
    {
        $status = "";
        include("includes/brisiImagesTemp.php");

        if(isset($_SESSION["obrisanaMarka"]))
        {
            $status = '
            <div class="col-lg-12">
                <div class="card mb-4 mt-3 border-bottom-success">
                    <div class="card-body text-dark">
                        <h5><strong>Marka je uspješno obrisana.</strong></h5>
                    </div>
                </div>
            </div>
            ';
            unset($_SESSION['obrisanaMarka']);
        }

        include("includes/baza.php");
        $baza = new Baza();
        if(isset($_POST["predajObrisi"]))
        {
            $id = $_POST["id"];
            $provjera = $baza->obrisiMarkuPoID($id);
            if($provjera)
            {
                $_SESSION["obrisanaMarka"] = true;
                header("Location: upravljanje-markama.php");
                exit;
            }
            else {
                $status = '
                <div class="col-lg-12">
                    <div class="card mb-4 mt-3 border-bottom-danger">
                        <div class="card-body text-dark">
                            <h5><strong>Dogodila se greška prilikom brisanja.</strong></h5>
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
    
    $podaciMarke = $baza->dohvatiSveMarke();
?>

<!-- Page Heading -->
<h1 class="h3 my-3 text-gray-800">Upravljanje markama</h1>

<?php echo $status; ?>

<!-- DataTales Example -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h5 class="m-0 font-weight-bold text-primary">Marke</h5>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTableMarke" width="100%" cellspacing="0">
        <thead>
            <tr>
              <th>ID</th>
              <th>Marka</th>
              <th>Broj vozila</th>
              <th>Vrijeme dodavanja</th>
              <th>Akcija</th>
            </tr>
        </thead>
        <tfoot>
          <tr>
            <th>ID</th>
            <th>Marka</th>
            <th>Broj vozila</th>
            <th>Vrijeme dodavanja</th>
            <th>Akcija</th>
          </tr>
        </tfoot>
        <tbody>
        <?php 
        foreach($podaciMarke as $marka)
        {
        $brojVozila = $baza->dohvatiBrojVozilaPoMarki($marka["id"]);
        ?>
            <tr>
                <td><?php echo $marka["id"]; ?></td>
                <td><?php echo $marka["marka"]; ?></td>
                <td><?php echo $brojVozila; ?></td>
                <td><?php echo date("d.m.Y H:i", strtotime($marka["vrijemeDodavanja"])); ?></td>
                <td id="tdZaTipke" style="width:250px;">
                <div class="tipke">
                    <div class="my-auto">  
                        <button type="button" class="btn btn-danger btn-icon-split btn-sm" data-toggle="modal" data-target="#ukloniMarkuModal<?php echo $marka['id']; ?>">
                            <span class="icon text-white-50">
                            <i class="fas fa-trash"></i>
                            </span>
                            <span class="text">Ukloni</span>
                        </button>
                    </div>
                    <div class="my-auto">
                        <form action="uredi-marku.php" method="post">
                            <input type="hidden" name="idZaUredivanje" value=<?php echo $marka["id"]; ?>>
                            <button type="submit" name="predajUredi" class="btn btn-warning btn-icon-split btn-sm">
                                <span class="icon text-white-50">
                                <i class="fas fa-edit"></i>
                                </span>
                                <span class="text text-dark">Uredi</span>
                            </button>
                        </form>
                    </div>
                </div>
                </td>
            </tr>

            <?php 
                if($brojVozila > 0)
                {
                ?>
                    <div class="modal fade" id="ukloniMarkuModal<?php echo $marka["id"]; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Upozorenje</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">Za brisanje marke <?php echo $marka["marka"]; ?>, morate prvo izbrisati sva vozila sa navedenom markom.</div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="button" data-dismiss="modal">U redu</button>
                        </div>
                        </div>
                    </div>
                    </div>
                <?php
                }
                else {
                ?>
                    <div class="modal fade" id="ukloniMarkuModal<?php echo $marka["id"]; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Brisanje?</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">Jeste li sigurni da želite obrisati marku <?php echo $marka["marka"]; ?>?</div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Odustani</button>
                            <form action="upravljanje-markama.php" method="post">
                            <input type="hidden" name="id" value="<?php echo $marka["id"]; ?>">
                            <button type="submit" class="btn btn-danger btn-icon-split" name="predajObrisi">
                                <span class="icon text-white-50">
                                <i class="fas fa-trash"></i>
                                </span>
                                <span class="text">Ukloni</span>
                            </button>
                            </form>  
                        </div>
                        </div>
                    </div>
                    </div>
                <?php
                }
        }
        ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include("includes/footer.php"); ?>