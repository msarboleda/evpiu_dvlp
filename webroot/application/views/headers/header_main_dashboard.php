<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="EV Development">
  <!-- Favicon icon -->
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $this->config->item('assets_path').'themes/elaadmin/images/favicon.png'; ?>">
  <title><?php echo $this->config->item('site_title'); ?></title>
  <!-- Bootstrap Core CSS -->
  <link href="<?php echo $this->config->item('assets_path').'themes/elaadmin/css/lib/bootstrap/bootstrap.min.css'; ?>" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="<?php echo $this->config->item('assets_path').'themes/elaadmin/css/helper.css'; ?>" rel="stylesheet">
  <link href="<?php echo $this->config->item('assets_path').'themes/elaadmin/css/style.css'; ?>" rel="stylesheet">
  <?php print_r(print_additional_css()); ?>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:** -->
  <!--[if lt IE 9]>
  <script src="https:**oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https:**oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body class="fix-header fix-sidebar">
  <!-- Preloader - style you can find in spinners.css -->
  <div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
		<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
  </div>
  <!-- Main wrapper  -->
  <div id="main-wrapper">
    <!-- header header  -->
    <div class="header">
      <nav class="navbar top-navbar navbar-expand-md navbar-light">
        <!-- Logo -->
        <div class="navbar-header">
          <a class="navbar-brand" href="<?php echo site_url('auth/index'); ?>">
            <!-- Logo icon -->
            <b><img src="<?php echo $this->config->item('assets_path').'themes/elaadmin/images/logo.png'; ?>" alt="homepage" class="dark-logo" /></b>
            <!--End Logo icon -->
          </a>
        </div>
        <!-- End Logo -->
        <div class="navbar-collapse">
          <!-- toggle and nav items -->
          <ul class="navbar-nav mr-auto mt-md-0">
            <!-- This is  -->
            <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted  " href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
            <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted  " href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
          </ul>
          <!-- User profile and search -->
          <ul class="navbar-nav my-lg-0">
            <!-- Notificaciones -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-muted" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-bell"></i>
            		<div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
            	</a>
              <!--<div class="dropdown-menu dropdown-menu-right mailbox animated zoomIn">-->
                <!--<ul>-->
                  <!--<li>
                    <div class="drop-title">Notifications</div>
                  </li>-->
                  <!--<li>-->
                    <!--<div class="message-center">
                      <!-- Message -->
                      <!--<a href="#">
                          <div class="btn btn-danger btn-circle m-r-10"><i class="fa fa-link"></i></div>
                          <div class="mail-contnet">
                              <h5>This is title</h5> <span class="mail-desc">Just see the my new admin!</span> <span class="time">9:30 AM</span>
                          </div>
                      </a>-->
                      <!-- Message -->
                      <!--<a href="#">
                          <div class="btn btn-success btn-circle m-r-10"><i class="ti-calendar"></i></div>
                          <div class="mail-contnet">
                              <h5>This is another title</h5> <span class="mail-desc">Just a reminder that you have event</span> <span class="time">9:10 AM</span>
                          </div>
                      </a>-->
                      <!-- Message -->
                      <!--<a href="#">
                          <div class="btn btn-info btn-circle m-r-10"><i class="ti-settings"></i></div>
                          <div class="mail-contnet">
                              <h5>This is title</h5> <span class="mail-desc">You can customize this template as you want</span> <span class="time">9:08 AM</span>
                          </div>
                      </a>-->
                      <!-- Message -->
                      <!--<a href="#">
                          <div class="btn btn-primary btn-circle m-r-10"><i class="ti-user"></i></div>
                          <div class="mail-contnet">
                              <h5>This is another title</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span>
                          </div>
                      </a>-->
                    <!--</div>-->
                  <!--</li>-->
                  <!--<li>
                    <a class="nav-link text-center" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a>
                  </li>-->
                <!--</ul>-->
              <!--</div>-->
            </li>
            <!-- Fin Notificaciones -->
            <!-- Profile -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?php echo $this->config->item('assets_path').'themes/elaadmin/images/users/user.png'; ?>" alt="user" class="profile-pic" /></a>
              <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                <ul class="dropdown-user">
                  <li><a href="<?php echo site_url('auth/change_password'); ?>"><i class="ti-lock"></i> Cambiar contraseña</a></li>
                  <li><a href="<?php echo site_url('auth/logout'); ?>"><i class="fa fa-power-off"></i> Cerrar sesión</a></li>
                </ul>
              </div>
            </li>
          </ul>
        </div>
      </nav>
    </div>
    <!-- End header header -->
    <!-- Left Sidebar  -->
    <div class="left-sidebar">
      <!-- Sidebar scroll-->
      <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
          <nav class="sidebar-nav">
            <ul id="sidebarnav">
              <li class="nav-devider"></li>
              <li class="nav-label">INICIO</li>
              <li> <a href="<?php echo site_url('auth/index'); ?>" aria-expanded="false"><i class="fa fa-tachometer"></i><span class="hide-menu">Dashboard</span></a>
              </li>
              <li class="nav-label">Módulos</li>
              <?php foreach ($Categorias as $Categoria): ?>
              <li> <a class="has-arrow" href="javascript:void(0)" aria-expanded="false"><i class="<?php echo $Categoria->Icono; ?>"></i><span class="hide-menu"><?php echo $Categoria->NomCategoria; ?> <span class="label label-rounded label-primary pull-right"><?php echo $Categoria->Modulos; ?></span></span></a>
                <ul aria-expanded="false" class="collapse">
                  <?php foreach ($Modulos as $Modulo): ?>
                  <?php if ($Categoria->CodCategoria === $Modulo->CodCategoria) { ?> 
                  <li><a href="<?php echo site_url($Modulo->Ruta); ?>"><i class="<?php echo $Modulo->Icono; ?>"></i> <?php echo $Modulo->NomModulo; ?></a></li>
                  <?php } endforeach; ?>
                </ul>
              </li>
              <?php endforeach; ?>    
            </ul>
          </nav>
          <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
      </div>
      <!-- End Left Sidebar  -->
      <!-- Page wrapper  -->
      <div class="page-wrapper">
        <!-- Bread crumb -->
        <div class="row page-titles">
          <div class="col-md-5 align-self-center">
            <h3 class="text-primary"><?php echo $module_name; ?></h3> 
          </div>
          <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="javascript:void(0)">Inicio</a></li>
              <li class="breadcrumb-item active"><?php echo $module_name; ?></li>
            </ol>
          </div>
        </div>
        <!-- End Bread crumb -->
        <!-- Container fluid  -->
        <div class="container-fluid">
            <!-- Start Page Content -->