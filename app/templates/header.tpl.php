<?php if (!defined('ACCESS')) exit; ?>

<?php
function active($page)
{
  if (!isset($_GET['page']) || empty($_GET['page']))
    $_GET['page'] = 'index';

  if ($_GET['page'] == $page)
    echo 'class="nav-link active"';
  else
    echo 'class="nav-link link-dark"';
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title id="page-title"><?= $title; ?> / <?= SITE_NAME;; ?></title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

  <!-- DataTables CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.11.5/r-2.2.9/datatables.min.css" />

  <!-- Custom Styles -->
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/sidebar.css">

  <!-- jQuery -->
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Bootstrap JS -->
  <script defer src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

  <!-- DataTables JS -->
  <script defer type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.5/r-2.2.9/datatables.min.js"></script>

  <!-- Custom JS -->
  <script defer type="text/javascript" src="js/main.js"></script>
  <script defer type="text/javascript" src="js/sidebar.js"></script>

</head>

<body class="d-flex flex-column min-vh-100">

  <noscript>
    <br />
    <div class="alert alert-danger">
      <h3>Προσοχή!</h3>
      Η ιστοσελίδα μας βασίζεται κυρίως σε JavaScript.<br>
      Παρακαλούμε ενεργοποιήστε την JavaScript για την ομαλή λειτουργία του site.
    </div>
  </noscript>

  <?php if (Account::IsLoggedIn()) : ?>
    <nav id="offcanvasSidebar" class="offcanvas offcanvas-start show d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 280px;" data-bs-keyboard="false" data-bs-backdrop="true" data-bs-scroll="true" aria-labelledby="offcanvasSidebarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasSidebarLabel">
          <i class="bi bi-kanban text-deep-purple"></i>
          Menu
        </h5>
        <!-- <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button> -->
      </div>
      <div class="offcanvas-body">
        <a href="/?page=index" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
          <span class="fs-4"><?= SITE_NAME; ?></span>
        </a>
        <hr class="text-muted">
        <ul class="nav nav-pills flex-column mb-auto">
          <li>
            <a href="?page=index" <?= active('index'); ?>>
              <i class="bi bi-grid"></i>
              Αρχική
            </a>
          </li>
          <li>
            <a href="?page=announcements" <?= active('announcements'); ?>>
              <i class="bi bi-tags"></i>
              Ανακοινώσεις
            </a>
          </li>
          <li>
            <a href="?page=documents" <?= active('documents'); ?>>
              <i class="bi bi-file-earmark-pdf"></i>
              Έγγραφα
            </a>
          </li>
          <li>
            <a href="?page=projects" <?= active('projects'); ?>>
              <i class="bi bi-server"></i>
              Εργασίες
            </a>
          </li>
          <?php if (Account::IsTutor()) : ?>
            <li>
              <a href="?page=manage-users" <?= active('manage-users'); ?>>
                <i class="bi bi-people"></i>
                Διαχείριση χρηστών
              </a>
            </li>
          <?php endif; ?>
        </ul>
        <hr class="text-muted">

        <div class="dropdown">
          <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
            <strong><?= Account::getAccount()->firstname; ?></strong>
          </a>
          <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser">
            <li>
              <a class="dropdown-item" href="/?page=settings">
                <i class="bi bi-gear-wide-connected"></i>
                Ρυθμίσεις
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item" href="/?page=logout" style="color: #a94442;">
                <i class="bi bi-box-arrow-left"></i>
                Αποσύνδεση
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  <?php endif; ?>

  <main class="container">
    <nav class="navbar top-navbar navbar-light bg-light mb-2 mt-2">
      <!-- <a data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar" class="btn border-0 bg-dark" id="menu-btn"><i class="bi bi-distribute-vertical text-white"></i></a> -->

      <div class="container-fluid">
        <div class="d-flex">
          <a data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar" class="btn border-0 bg-dark" id="sidebarCollapse">
            <i class="bi bi-distribute-vertical text-white"></i>
          </a>
          <a href="/?page=index" class="d-flex align-items-center px-2 link-dark text-decoration-none">
            <span class="fs-4"><?= SITE_NAME; ?></span>
          </a>
        </div>

        <div class="d-flex">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="btn btn-outline-info" href="/?page=contact">
              <i class="bi bi-chat-dots"></i>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>