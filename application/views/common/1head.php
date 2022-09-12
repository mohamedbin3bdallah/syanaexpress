<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>FabArtist</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="<?php echo base_url('/assets/css/materialdesignicons.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('/assets/css/simple-line-icons.css'); ?>">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="<?php echo base_url('/assets/css/style.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('/assets/css/vendor.bundle.base.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('/assets/css/vendor.bundle.addons.css'); ?>">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
  <!-- endinject -->

  <link rel="shortcut icon" href="<?php echo base_url('/assets/images/favicon.png'); ?>" />
</head>

<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
        <a class="navbar-brand brand-logo" href="javascript:void(0)"><img src="<?php echo base_url('/assets/images/g.png'); ?>" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="javascript:void(0)"><img src="images/logo-mini.svg" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center">
        <ul class="navbar-nav navbar-nav-right header-links d-none d-md-flex">
          <li class="nav-item">
            <a href="<?php echo base_url('Admin/logout'); ?>" class="nav-link"><i class="mdi mdi-calendar"></i>Logout</a>
          </li>
        </ul>
       
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>