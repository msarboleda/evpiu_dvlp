<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="EV Development">
  <!-- Favicon icon -->
  <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
  <title><?php echo $this->config->item('site_title'); ?></title>
  <!-- Bootstrap Core CSS -->
  <link href="<?php echo $this->config->item('assets_path').'themes/elaadmin/css/lib/bootstrap/bootstrap.min.css'; ?>" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="<?php echo $this->config->item('assets_path').'themes/elaadmin/css/helper.css'; ?>" rel="stylesheet">
  <link href="<?php echo $this->config->item('assets_path').'themes/elaadmin/css/style.css'; ?>" rel="stylesheet">
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
  <!-- Main wrapper -->