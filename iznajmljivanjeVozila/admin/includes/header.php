<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Admin - iznajmljivanje vozila</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page => tables.html-->
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <!-- Style.css -->
  <link rel="stylesheet" href="css/style.css">

</head>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

  <!-- Sidebar -->
  <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
      <div class="sidebar-brand-icon">
        <i class="fas fa-tools"></i>
      </div>
      <div class="sidebar-brand-text mx-3">Admin</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
      <a class="nav-link" href="index.php">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Kontrolna ploča</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Vozila Collapse Menu -->
    <li class="nav-item">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVozila" aria-expanded="true" aria-controls="collapseVozila">
        <i class="fas fa-car"></i>
        <span>Vozila</span>
      </a>
      <div id="collapseVozila" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <a class="collapse-item" href="dodaj-vozilo.php">Dodaj novo vozilo</a>
          <a class="collapse-item" href="upravljanje-vozilima.php">Upravljanje vozilima</a>
          <a class="collapse-item" href="rezervirana-vozila.php">Rezervirana vozila</a>
          <a class="collapse-item" href="odsutna-vozila.php">Odsutna vozila</a>
          <a class="collapse-item" href="prisutna-vozila.php">Prisutna vozila</a>
        </div>
      </div>
    </li>

     <!-- Nav Item - Marke Collapse Menu -->
     <li class="nav-item">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMarke" aria-expanded="true" aria-controls="collapseMarke">
        <i class="fas fa-columns"></i>
        <span>Marke</span>
      </a>
      <div id="collapseMarke" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <a class="collapse-item" href="dodaj-marku.php">Dodaj novu marku</a>
          <a class="collapse-item" href="upravljanje-markama.php">Upravljanje markama</a>
        </div>
      </div>
    </li>

     <!-- Nav Item - Korisnici -->
     <li class="nav-item">
      <a class="nav-link" href="upravljanje-korisnicima.php">
        <i class="fas fa-users"></i>
        <span>Korisnici</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

     <!-- Nav Item - Profil -->
     <li class="nav-item">
      <a class="nav-link" href="moj-profil.php">
        <i class="fas fa-user-cog"></i>
        <span>Profil</span></a>
    </li>

    <!-- Nav Item - Odjava -->
    <li class="nav-item">
      <a class="nav-link" href="#" data-toggle="modal" data-target="#odjavaModal">
        <i class="fas fa-sign-out-alt"></i>
        <span>Odjava</span></a>
    </li>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

  </ul>
  <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Begin Page Content -->
        <div class="container-fluid">