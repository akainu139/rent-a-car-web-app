<?php 
    session_start();
    if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin")
    {
        $status = "";
        include("includes/brisiImagesTemp.php");

        if(isset($_SESSION["promjenaStanjaKorisnika"]))
        {
            $status = '
            <div class="col-lg-12">
                <div class="card mb-4 mt-3 border-bottom-success">
                    <div class="card-body text-dark">
                        <h5><strong>Korisniku je uspješno ažurirano stanje.</strong></h5>
                    </div>
                </div>
            </div>
            ';
            unset($_SESSION['promjenaStanjaKorisnika']);
        }

        if(isset($_SESSION["obrisanKorisnik"]))
        {
            $status = '
            <div class="col-lg-12">
                <div class="card mb-4 mt-3 border-bottom-success">
                    <div class="card-body text-dark">
                        <h5><strong>Korisnik je uspješno obrisan.</strong></h5>
                    </div>
                </div>
            </div>
            ';
            unset($_SESSION['obrisanKorisnik']);
        }

        include("includes/baza.php");
        $baza = new Baza();
        if(isset($_POST["predajObrisi"]))
        {
            $id = $_POST["id"];
            $provjera = $baza->obrisiKorisnikaPoID($id);
            if($provjera)
            {
                $_SESSION["obrisanKorisnik"] = true;
                header("Location: upravljanje-korisnicima.php");
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
        
        if(isset($_POST["predajStanjeKorisnika"]))
        {
            $id = $_POST["id"];
            $stanje = !$_POST["stanje"];
            $provjera = $baza->urediStanjeKorisnika($id, $stanje);
            if($provjera)
            {
                $_SESSION["promjenaStanjaKorisnika"] = true;
                header("Location: upravljanje-korisnicima.php");
                exit;
            }
            else {
                $status = '
                <div class="col-lg-12">
                    <div class="card mb-4 mt-3 border-bottom-danger">
                        <div class="card-body text-dark">
                            <h5><strong>Dogodila se greška prilikom postavljanja admina.</strong></h5>
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

    $podaciKorisnici = $baza->dohvatiSveKorisnike();
?>

<!-- Page Heading -->
<h1 class="h3 my-3 text-gray-800">Upravljanje korisnicima</h1>

<?php echo $status; ?>

<!-- DataTales Example -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h5 class="m-0 font-weight-bold text-primary">Korisnici</h5>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTableKorisnici" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Korisničko ime</th>
            <th>Ime</th>
            <th>Prezime</th>
            <th>E-pošta</th>
            <th>Admin</th>
            <th>Vrijeme registracije</th>
            <th>Akcija</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th>ID</th>
            <th>Korisničko ime</th>
            <th>Ime</th>
            <th>Prezime</th>
            <th>E-pošta</th>
            <th>Admin</th>
            <th>Vrijeme registracije</th>
            <th>Akcija</th>
          </tr>
        </tfoot>
        <tbody>
            <?php 
                foreach($podaciKorisnici as $korisnik)
                {
                ?>
                    <tr>
                        <td><?php echo $korisnik["id"]; ?></td>
                        <td><?php echo $korisnik["korisnickoIme"]; ?></td>
                        <td><?php echo $korisnik["ime"]; ?></td>
                        <td><?php echo $korisnik["prezime"]; ?></td>
                        <td><?php echo $korisnik["email"]; ?></td>
                        <td><?php echo $korisnik["admin"] == 1 ? "Da":"Ne"; ?></td>
                        <td><?php echo date("d.m.Y H:i", strtotime($korisnik["vrijemeRegistracije"])); ?></td>
                        <td id="tdZaTipke" style="width:250px;">
                        <div class="tipke">
                            <div class="my-auto">
                                <button type="button" class="btn btn-danger btn-icon-split btn-sm" data-toggle="modal" data-target="#ukloniKorisnikaModal<?php echo $korisnik['id']; ?>">
                                    <span class="icon text-white-50">
                                    <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text">Ukloni</span>
                                </button>
                            </div>

                        <?php 
                        if($korisnik["admin"] == 0)
                        {
                        ?>
                            <div class="my-auto">
                                <button type="button" class="btn btn-primary btn-icon-split btn-sm" style="width:140px" data-toggle="modal" data-target="#dodijeliAdminModal<?php echo $korisnik['id']; ?>">
                                    <span class="icon text-white-50">
                                    <i class="fas fa-tools"></i>
                                    </span>
                                    <span class="text w-100">Dodijeli admin</span>
                                </button>
                            </div>
                        <?php
                        }
                        else {
                        ?>  
                            <div class="my-auto">
                                <button type="button" class="btn btn-danger btn-icon-split btn-sm" style="width:140px" data-toggle="modal" data-target="#ukloniAdminModal<?php echo $korisnik['id']; ?>">
                                    <span class="icon text-white-50">
                                    <i class="fas fa-tools"></i>
                                    </span>
                                    <span class="text w-100">Ukloni admin</span>
                                </button>
                            </div>
                        <?php
                        }
                        ?>
                        </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="ukloniKorisnikaModal<?php echo $korisnik['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Brisanje?</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">Jeste li sigurni da želite obrisati korisnika <?php echo $korisnik['korisnickoIme']; ?>?</div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Odustani</button>
                            <form action="upravljanje-korisnicima.php" method="post">
                            <input type="hidden" name="id" value="<?php echo $korisnik['id']; ?>">
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
                    if($korisnik["admin"] == 0)
                    {
                    ?>
                    <div class="modal fade" id="dodijeliAdminModal<?php echo $korisnik['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Dodijeliti admin?</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">Jeste li sigurni da želite korisniku <?php echo $korisnik["korisnickoIme"]; ?> dodijeliti admin?</div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Odustani</button>
                            <form action="upravljanje-korisnicima.php" method="post">
                            <input type="hidden" name="id" value="<?php echo $korisnik['id']; ?>">
                            <input type="hidden" name="stanje" value=<?php echo $korisnik["admin"]; ?>>
                            <button type="submit" class="btn btn-primary btn-icon-split" name="predajStanjeKorisnika">
                                <span class="icon text-white-50">
                                <i class="fas fa-tools"></i>
                                </span>
                                <span class="text">Dodijeli admin</span>
                            </button>
                            </form>  
                        </div>
                        </div>
                    </div>
                    </div>
                    <?php
                    }
                    else {
                    ?>
                    <div class="modal fade" id="ukloniAdminModal<?php echo $korisnik['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Ukloniti admin?</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">Jeste li sigurni da želite korisniku <?php echo $korisnik["korisnickoIme"]; ?> ukloniti admin?</div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Odustani</button>
                            <form action="upravljanje-korisnicima.php" method="post">
                            <input type="hidden" name="id" value="<?php echo $korisnik['id']; ?>">
                            <input type="hidden" name="stanje" value=<?php echo $korisnik["admin"]; ?>>
                            <button type="submit" class="btn btn-danger btn-icon-split" name="predajStanjeKorisnika">
                                <span class="icon text-white-50">
                                <i class="fas fa-tools"></i>
                                </span>
                                <span class="text">Ukloni admin</span>
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