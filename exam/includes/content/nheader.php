<?php
	require_once 'core/init.php';
	$info = Info::get();
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $info->title; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="valence solution by CEO Okoro Efe Christopher" />
	<meta name="author" content="okoro Efe Christopher" />
  	<!-- Facebook and Twitter integration -->
	<meta property="og:title" content=""/>
	<meta property="og:image" content=""/>
	<meta property="og:url" content=""/>
	<meta property="og:site_name" content=""/>
	<meta property="og:description" content=""/>
	<meta name="twitter:title" content="" />
	<meta name="twitter:image" content="" />
	<meta name="twitter:url" content="" />
	<meta name="twitter:card" content="" />

	<!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
	<link href='https://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>
	<!-- <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,400italic,700' rel='stylesheet' type='text/css'> -->
	
	<!-- Animate.css -->
	<link rel="stylesheet" href="css/animate.css">
	<!-- Icomoon Icon Fonts-->
	<link rel="stylesheet" href="css/icomoon.css">
	<!-- Bootstrap  -->
	<link rel="stylesheet" href="css/bootstrap.css">
	<!-- Flexslider  -->
	<link rel="stylesheet" href="css/flexslider.css">
	<!-- Theme style  -->
	<link rel="stylesheet" href="css/style2.css">
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">


	<!-- Modernizr JS -->
	<script src="scripts/modernizr-2.6.2.min.js"></script>
	<!-- FOR IE9 below -->
	<!--[if lt IE 9]>
	<script src="js/respond.min.js"></script>
	<![endif]-->

	</head>
	<body>	
	<div id="fh5co-page" data-spy="scroll">
	<header id="fh5co-header" role="banner">
		<div class="container">
			<div class="row">
				<div class="header-inner">
					<h1><a href="index.php"><?php $header = explode('|', $info->header); echo $header[0];?><span><?php echo $header[1]; ?></span></a></h1>
					<nav role="navigation">
						<ul>
						<?php
							if(basename($_SERVER['PHP_SELF'], '.php') == 'index') {
						?>
							<li><a class="page-scroll" href="#apply">Apply</a></li>
							<li><a class="page-scroll" href="#status">Ongoing Exams</a></li>
							<li class="cta"><a href="#contact"><i class="icon-phone"></i> Contact Us</a></li>
							<li class="page-scroll"><a href="#about">About</a></li>
						<?php } else {?>

							<li><a class="page-scroll" href="index.php#apply">Apply</a></li>
							<li><a class="page-scroll" href="index.php#status">Ongoing Exams</a></li>
							<li class="cta"><a href="index.php#contact"><i class="icon-phone"></i> Contact Us</a></li>
							<li class="page-scroll"><a href="index.php#about">About</a></li>
						<?php } ?>
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</header>