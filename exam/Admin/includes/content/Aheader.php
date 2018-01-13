<?php
  require_once 'core/init.php';
  $filename = basename($_SERVER['PHP_SELF'], '.php');
  $active = "class='active'";
  $user = new User();
  if(!$user->isLoggedIn() || $user->data()->status == 0) {
    Session::flash('home', 'You need to login to view the requested page');
    Redirect::to('login.php');
  }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ValenceWeb | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../css/Admin.min.css">
  <!-- Admin Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="plugins/morris/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <link rel="stylesheet" href="../css/daterangepicker.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">
  <style type="text/css">
    .ba {
      display: inline-block;
      margin-left: 1em;
    }
  </style>
  <script type="text/javascript" src="../scripts/jQuery.min.js"></script>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="dashboard.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>E</b>xam</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Valence</b>WEB</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Notifications: style can be found in dropdown.less -->
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo (empty($user->data()->pic_src)) ? 'img/avatar-male.png' : "img/{$user->data()->pic_src}"?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $user->data()->name ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo (empty($user->data()->pic_src)) ? 'img/avatar-male.png' : "img/{$user->data()->pic_src}"?>" class="img-circle" alt="User Image">

                <p>
                  <?php echo $user->data()->name ?> - Web Developer
                  <small>Member since <?php $date = new dateTime($user->data()->created); echo $date->format("d-M-Y"); ?></small>
                </p>
              </li>
             
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="profile.php" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="_logout.php" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <!--<li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>-->
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo (empty($user->data()->pic_src)) ? 'img/avatar-male.png' : "img/{$user->data()->pic_src}"?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $user->data()->name ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li <?php if($filename == 'dashboard') echo $active ?>>
          <a href="dashboard.php">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        <li class="treeview <?php if($filename == 'createtest' || $filename == 'viewtest') echo 'active' ?>">
          <a href="#">
            <i class="fa fa-file-text"></i>
            <span>Tests</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="createtest.php"><i class="fa fa-circle-o text-red"></i> Create</a></li>
            <li><a href="viewtest.php"><i class="fa fa-circle-o text-yellow"></i> View</a></li>
          </ul>
        </li>
        <li <?php if($filename == 'broadcast') echo $active ?>>
          <a href="broadcast.php">
            <i class="fa fa-envelope"></i> <span>Send Broadcast</span>
          </a>
        </li>
        <li <?php if($filename == 'results') echo $active ?>>
          <a href="results.php">
            <i class="fa fa-search"></i> <span>View Results</span>
          </a>
        </li>
        <?php 
          if($user->hasPermission('admin')) {
        ?>

        <li <?php if($filename == 'register') echo $active ?>>
          <a href="Aregister.php">
            <i class="fa fa-user"></i> <span>Register</span>
          </a>
        </li>

        <li <?php if($filename == 'init') echo $active ?>>
          <a href="init.php">
            <i class="fa fa-gears"></i> <span>Visitors area setting</span>
          </a>
        </li>

        <?php } ?>
        <li>
          <a href="_logout.php">
            <i class="fa fa-power-off"></i> <span>Logout</span>
          </a>
        </li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
