<?php 
    session_start();
    if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin")
    {
        $status = "";
        include("includes/brisiImagesTemp.php");

        if(isset($_SESSION["obrisanoVozilo"]))
        {
            $status = '
            <div class="col-lg-12">
                <div class="card mb-4 border-bottom-success">
                    <div class="card-body text-dark">
                        <h5><strong>Vozilo je uspješno obrisano.</strong></h5>
                    </div>
                </div>
            </div>
            ';
            unset($_SESSION['obrisanoVozilo']);
        }

        include("includes/baza.php");
        $baza = new Baza();
        if(isset($_POST["predajObrisi"]))
        {
            $id = $_POST["id"];
            $provjera = $baza->obrisiVoziloPoID($id);
            if($provjera)
            {
                $path = "img/slike".$id;
                if(file_exists($path))
                {
                    $popisSlika = scandir($path);
                    $popisSlika = array_diff($popisSlika, [".", ".."]);

                    if(count($popisSlika) > 0)
                    {
                        foreach ($popisSlika as $slika) {
                            unlink($path . "/" . $slika);
                        }
                    }
                    rmdir($path);
                }
                $_SESSION["obrisanoVozilo"] = true;
                header("Location: upravljanje-vozilima.php");
                exit;
            }
            else {
                $status = '
                <div class="col-lg-12">
                    <div class="card mb-4 border-bottom-danger">
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
    
    $podaciVozila = $baza->dohvatiSvaVozila();
?>

<!-- Page Heading -->
<h1 class="h3 my-3 text-gray-800">Upravljanje vozilima</h1>

<!-- Ispis poruke nakon pritiska tipke -->
<?php echo $status; ?>

<!-- DataTales Example -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h5 class="m-0 font-weight-bold text-primary">Vozila</h5>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered text-dark" id="dataTableVozila" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Marka</th>
                <th>Model</th>
                <th>Cijena po danu</th>
                <th>Prijeđeni kilometri</th>
                <th>Motor</th>
                <th>Broj sjedala</th>
                <th>Vrijeme dodavanja</th>
                <th>Akcija</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Marka</th>
                <th>Model</th>
                <th>Cijena po danu</th>
                <th>Prijedeni kilometri</th>
                <th>Motor</th>
                <th>Broj sjedala</th>
                <th>Vrijeme dodavanja</th>
                <th>Akcija</th>
            </tr>
        </tfoot>
        <tbody>
        <?php 
        foreach($podaciVozila as $vozilo)
        {
        $brojRezerviranihVozila = $baza->dohvatiBrojRezerviranihVozilaPoID($vozilo["id"]);
        ?>
            <tr>
                <td><?php echo $vozilo["id"]; ?></td>
                <td><?php echo $vozilo["marka"]; ?></td>
                <td><?php echo $vozilo["model"]; ?></td>
                <td><?php echo $vozilo["cijenaPoDanu"]; ?></td>
                <td><?php echo $vozilo["prijedeniKilometri"]; ?></td>
                <td><?php echo $vozilo["motor"]; ?></td>
                <td><?php echo $vozilo["brojSjedala"]; ?></td>
                <td><?php echo date("d.m.Y H:i", strtotime($vozilo["vrijemeDodavanja"])); ?></td>
                <td id="tdZaTipke" style="width:200px;">
                <div class="tipke">
                    <div class="my-auto">
                        <button type="button" class="btn btn-danger btn-icon-split btn-sm" data-toggle="modal" data-target="#ukloniVoziloModal<?php echo $vozilo['id']; ?>">
                            <span class="icon text-white-50">
                            <i class="fas fa-trash"></i>
                            </span>
                            <span class="text">Ukloni</span>
                        </button>
                    </div>
                    <div class="my-auto">
                        <form action="uredi-vozilo.php" method="post">
                            <input type="hidden" name="idZaUredivanje" value=<?php echo $vozilo["id"]; ?>>
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
            if($brojRezerviranihVozila > 0)
            {
            ?>
                <div class="modal fade" id="ukloniVoziloModal<?php echo $vozilo["id"]; ?>" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Upozorenje</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Nemogućnost brisanja vozila <?php echo $vozilo["marka"] . " " . $vozilo["model"]; ?> zbog postojećoh rezervacija.</div>
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
            <div class="modal fade" id="ukloniVoziloModal<?php echo $vozilo["id"]; ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Brisanje?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Jeste li sigurni da želite obrisati vozilo <?php echo $vozilo["marka"] . " " . $vozilo["model"]; ?>?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Odustani</button>
                    <form action="upravljanje-vozilima.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $vozilo["id"]; ?>">
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