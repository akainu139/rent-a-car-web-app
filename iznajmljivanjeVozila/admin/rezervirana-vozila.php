<?php 
    session_start();
    if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin")
    {
        include("includes/brisiImagesTemp.php");
        date_default_timezone_set("Europe/Zagreb");
        
        if(isset($_POST["predajKalendar"]))
        {
            $_SESSION["voziloId"] = $_POST["id"];
            header("Location: kalendar-zauzetosti.php");
            exit;            
        }

        $status = "";
        if(isset($_SESSION["promjenaStanjaVozila"]))
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
            unset($_SESSION['promjenaStanjaVozila']);
        }

        include("includes/baza.php");
        $baza = new Baza();
        if(isset($_POST["predajStanjeVozila"]))
        {
            $id = $_POST["id"];
            $stanje = !$_POST["stanje"];
            $provjera = $baza->urediStanjeVozila($id, $stanje);
            if($provjera)
            {
                $_SESSION["promjenaStanjaVozila"] = true;
                header("Location: rezervirana-vozila.php");
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

        if(isset($_SESSION["rezervacijaOtkazana"]))
        {
            $status = '
            <div class="col-lg-12 p-0 text-center">
                <div class="mt-3 alert alert-success">
                    <h5><strong>Rezervacija je uspješno otkazana.</strong></h5>
                </div>
            </div>
            ';
            unset($_SESSION['rezervacijaOtkazana']);
        }

        if(isset($_POST["predajOtkazi"]))
        {
            $id = $_POST["id"];
            $provjera = $baza->otkaziRezervaciju($id);
            if($provjera)
            {
                require("includes/keys.php");
                require_once('../vendor/autoload.php');
                \Stripe\Stripe::setApiKey(STRIPE_API_KEY);
    
                $re = \Stripe\Refund::create([
                    "charge" => $_SESSION["naplatniKod"]
                ]);
                
                unset($_SESSION['naplatniKod']);

                $_SESSION["rezervacijaOtkazana"] = true;
                header("Location: rezervirana-vozila.php");
                exit;
            }
            else {
                $status = '
                <div class="col-lg-12 p-0 text-center">
                    <div class="mt-3 alert alert-danger">
                        <h5><strong>Dogodila se greška prilikom otkazivanja vozila.</strong></h5>
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

    $rezerviranaVozila = $baza->dohvatiRezerviranaVozila();
?>

<!-- Page Heading -->
<h1 class="h3 my-3 text-gray-800">Pregled rezerviranih vozila</h1>

<?php echo $status; ?>

<!-- DataTales Example -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h5 class="m-0 font-weight-bold text-primary">Rezervirana vozila</h5>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTableRezerviranaVozila" width="100%" cellspacing="0">
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
            <th>Stanje vozila</th>
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
            <th>Stanje vozila</th>
            <th>Vrijeme rezervacije</th>
            <th>Akcija</th>
          </tr>
        </tfoot>
        <tbody>
            <?php 
                foreach($rezerviranaVozila as $rezerviranoVozilo)
                {
                ?>
                    <tr>
                        <td><?php echo $rezerviranoVozilo["id"]; ?></td>
                        <td><?php echo $rezerviranoVozilo["korisnickoIme"]; ?></td>
                        <td><?php echo $rezerviranoVozilo["ime"]; ?></td>
                        <td><?php echo $rezerviranoVozilo["prezime"]; ?></td>
                        <td><?php echo $rezerviranoVozilo["marka"]; ?></td>
                        <td><?php echo $rezerviranoVozilo["model"]; ?></td>
                        <td><?php echo date("d.m.Y H:i", strtotime($rezerviranoVozilo["vrijemeOd"])); ?></td>
                        <td><?php echo date("d.m.Y H:i", strtotime($rezerviranoVozilo["vrijemeDo"])); ?></td>
                        <td><?php echo $rezerviranoVozilo["odsutno"] == 0 ? "Prisutno":"Odsutno"; ; ?></td>
                        <td><?php echo date("d.m.Y H:i", strtotime($rezerviranoVozilo["vrijemeRezervacije"])); ?></td>
                        <td id="tdZaTipke">
                        <div class="tipke">
                            <div class="my-auto">
                                <form action="rezervirana-vozila.php" method="post">
                                    <input type="hidden" name="id" value=<?php echo $rezerviranoVozilo["id"]; ?>>
                                    <input type="hidden" name="stanje" value=<?php echo $rezerviranoVozilo["odsutno"]; ?>>
                                    <?php 
                                    if($rezerviranoVozilo["odsutno"] == 1)
                                    {
                                    ?>
                                    <button type="submit" name="predajStanjeVozila" class="btn btn-secondary btn-sm" style="width:115px;">
                                    <span class="text">Vozilo vraćeno</span>
                                    <?php 
                                    } else {
                                    ?>
                                    <button type="submit" name="predajStanjeVozila" class="btn btn-primary btn-sm" style="width:115px;">
                                    <span class="text">Vozilo preuzeto</span>
                                    <?php
                                    }
                                    ?>
                                    </button>
                                </form>
                            </div>

                            <div class="my-auto">  
                                <form action="rezervirana-vozila.php" method="post">
                                    <input type="hidden" name="id" value=<?php echo $rezerviranoVozilo["voziloId"]; ?>>
                                    <button type="submit" name="predajKalendar" class="btn btn-info btn-icon-split btn-sm">
                                        <span class="icon text-white-50">
                                        <i class="far fa-calendar-alt"></i>
                                        </span>
                                        <span class="text">Kalendar</span>
                                    </button>
                                </form>
                            </div>

                            <?php 
                            if(date("Y-m-d H:i:s") < $rezerviranoVozilo["vrijemeOd"])
                            {
                            ?>
                            <div class="my-auto">  
                                <button type="button" class="btn btn-danger btn-sm" style="width:130px;" data-toggle="modal" data-target="#otkaziRezervacijuModal<?php echo $rezerviranoVozilo['id']; ?>">
                                    <span class="text">Otkaži rezervaciju</span>
                                </button>
                            </div>

                            <div class="modal fade" id="otkaziRezervacijuModal<?php echo $rezerviranoVozilo["id"]; ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Otkazivanje?</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">Jeste li sigurni da želite otkazati vozilo <?php echo $rezerviranoVozilo["marka"] . " " . $rezerviranoVozilo["model"]; ?>?</div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Odustani</button>
                                    <form action="rezervirana-vozila.php" method="post">
                                    <input type="hidden" name="id" value="<?php echo $rezerviranoVozilo["id"]; ?>">
                                    <button type="submit" class="btn btn-danger btn-icon-split" name="predajOtkazi">
                                        <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                        </span>
                                        <span class="text">Otkaži rezervaciju</span>
                                    </button>
                                    </form>  
                                </div>
                                </div>
                            </div>
                            </div>

                            <?php
                            }
                            ?>

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