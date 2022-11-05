<?php
$configfile = 'config.php';
mysqli_set_charset($connect, "utf8-mb4");
if (!file_exists($configfile)) {
    echo '<meta http-equiv="refresh" content="0; url=install" />';
    exit();
}

session_start();
include "config.php";

//Data Sanitization
$_GET  = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

$query = mysqli_query($connect, "SELECT * FROM `settings` LIMIT 1");
$row   = mysqli_fetch_assoc($query);

if (!isset($_SESSION['sec-username'])) {
    $logged = 'No';
} else {
    
    $username = $_SESSION['sec-username'];
    
    $querych = mysqli_query($connect, "SELECT * FROM `users` WHERE username='$username' LIMIT 1");
    if (mysqli_num_rows($querych) == 0) {
        $logged = 'No';
    } else {
        $rowu   = $querych->fetch_array();
        $logged = 'Yes';
    }
}

$user_id = $rowu['id'];

function zaman($zaman){

	$zaman_farki = time()-$zaman;
	$saniye = $zaman_farki;
	$dakika = round($zaman_farki/60);
	$saat = round($zaman_farki/3600);
	$gün = round($zaman_farki/86400);
	$hafta = round($zaman_farki/604800);
	$ay = round($zaman_farki/2419200);
	$yil = round($zaman_farki/29030400);

	if($saniye <= 59)
	{
		if($saniye == 0)
		{
			return "Şimdi";
		}
		else
		{
			return $saniye." saniye önce";
		}
	}
	else if($dakika <= 59)
	{
		return $dakika." dakika önce";
	}
	else if($saat <= 23)
	{
		return $saat." saat önce";
	}
	else if($gün <= 6)
	{
		return $gün." gün önce";	
	}
	else if($hafta <= 3)
	{
		return $hafta." hafta önce";
	}
	else if($ay <= 11)
	{
		return $ay." ay önce";
	}
	else
	{
		return $yil." yıl önce";
	}
}

function head()
{
    include "config.php";
    
    if (!isset($_SESSION['sec-username'])) {
        $logged = 'No';
    } else {
        
        $username = $_SESSION['sec-username'];
        
        $querych = mysqli_query($connect, "SELECT * FROM `users` WHERE username='$username' LIMIT 1");
        if (mysqli_num_rows($querych) == 0) {
            $logged = 'No';
        } else {
            $rowu   = $querych->fetch_array();
            $logged = 'Yes';
        }
    }
?>

<!DOCTYPE html>
<html lang="TR">
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="https://code.jquery.com/jquery-3.6.1.min.js" type="text/javascript"></script>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<head>
<?php
    $run  = mysqli_query($connect, "SELECT * FROM `settings`");
    $site = mysqli_fetch_assoc($run);
?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

<?php
    // SEO Titles
    if (basename($_SERVER['SCRIPT_NAME']) == 'contact.php') {
        $pagetitle = 'İletişim';
    } else if (basename($_SERVER['SCRIPT_NAME']) == 'login.php') {
        $pagetitle = 'Kayıt Ol';
    } else if (basename($_SERVER['SCRIPT_NAME']) == 'myprofile.php') {
        $pagetitle = 'Profilim';
    } else if (basename($_SERVER['SCRIPT_NAME']) == 'universiteler.php') {
        $pagetitle = 'Üniversiteler';
    } else if (basename($_SERVER['SCRIPT_NAME']) == 'sorucevap.php') {
        $pagetitle = 'Soru Cevap';
    } else if (basename($_SERVER['SCRIPT_NAME']) == 'kategori.php') {
        
		$id = (int) $_GET['katid'];
        
		if (empty($id)) {
			echo '<meta http-equiv="refresh" content="0; url=index.php">';
			exit;
		}
			
		$runpt = mysqli_query($connect, "SELECT * FROM `sorucevapkat` WHERE kat_id='$id'");
		if (mysqli_num_rows($runpt) == 0) {
			echo '<meta http-equiv="refresh" content="0; url=index.php">';
			exit;
		}
		
		$rowpt = mysqli_fetch_assoc($runpt);
		$pagetitle = $rowpt['kat_adi'];
	
    } else if (basename($_SERVER['SCRIPT_NAME']) == 'universitelerc.php') {
        
		$uniid = (int) $_GET['uniid'];

		if (empty($uniid)) {
			echo '<meta http-equiv="refresh" content="0; url=index.php">';
			exit;
		}

		$runq = mysqli_query($connect, "SELECT * FROM `universite` WHERE universite_id='$uniid'");
		if (mysqli_num_rows($runq) == 0) {
			echo '<meta http-equiv="refresh" content="0; url=index.php">';
			exit;
		}
		
		$uniad = mysqli_fetch_assoc($runq);
        $pagetitle = $uniad['name'];
		
    }else if (basename($_SERVER['SCRIPT_NAME']) == 'user.php') {
        
		$user = $_GET['username'];

		if (empty($user)) {
			echo '<meta http-equiv="refresh" content="0; url=index.php">';
			exit;
		}

		$runq = mysqli_query($connect, "SELECT * FROM `users` WHERE username='$user'");
		$row = mysqli_fetch_assoc($runq);
        $pagetitle = $row['username'];
		
    }else if (basename($_SERVER['SCRIPT_NAME']) == 'search.php') {
        $word      = $_GET['q'];
        $pagetitle = 'Sonuçlar: "' . $word . '"';
    }
    
	if (basename($_SERVER['SCRIPT_NAME']) == 'index.php') {
        echo '<title>' . $site['sitename'] . '</title>';
        $mt3 = "mt-3";
    } else {
        $mt3 = "";
        echo '<title>' . $pagetitle . ' - ' . $site['sitename'] . '</title>';
    }
?>
        
        <meta name="description" content="Üniversitelilerin Buluşma Noktası" />
		<meta name="keywords" content="forum,uni,universite,forumuni,uniforum,uni forum,forum uni,forum universite" />
        <meta name="author" content="oğuzun biri" />
        <meta name="robots" content="index, follow, all" />
        <link rel="shortcut icon" href="assets/img/favicon.png" type="image/png" />
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		
        <!-- Bootstrap 5 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
        
		<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5727447479671642"
     crossorigin="anonymous"></script>
		
		<!-- Font Awesome 6 -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

		<script type="text/javascript" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/123941/masonry.js"></script>
		<script type="text/javascript" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/123941/imagesLoaded.js"></script>

<?php
if($site['theme'] != "Bootstrap 5") {
    echo '
        <!-- Bootstrap 5 Theme -->
        <link href="assets/css/themes/'. strtolower($site['theme']) .'/bootstrap.min.css" type="text/css" rel="stylesheet"/>
';
}
?>
        <style>
			a:link {
				text-decoration: none;
			}

			a:visited {
				text-decoration: none;
			}
			
			.rating {
			width: 120px;
			display: flex;
			height: 24px;
			position: relative;
			background-color: gray;
			}

			.rating progress.rating-bg {
			-webkit-appearance: none;
			-moz-appearence: none;
			appearance: none;
			border: none;
			display: inline-block;
			height: 24px;
			width: 100%;
			color: orange;
			}

			.rating progress.rating-bg::-webkit-progress-value {
			background-color: orange;
			}

			.rating progress.rating-bg::-moz-progress-bar {
			background-color: orange;
			}

			.rating svg {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			}

			.ratingp {
				display: flex;
				flex-direction: row-reverse;
				justify-content: center
			}

			.ratingp>input {
				display: none
			}

			.ratingp>label {
				position: relative;
				width: 1em;
				font-size: 6vw;
				cursor: pointer;
				color: gray;
			}

			.ratingp>label::before {
				content: "\2605";
				position: absolute;
				opacity: 0;
				color: #FFD600;
			}

			.ratingp>label:hover:before,
			.ratingp>label:hover~label:before {
				opacity: 1 !important
			}

			.ratingp>input:checked~label:before {
				opacity: 1
			}

			.ratingp:hover>input:checked~label:before {
				opacity: 0.4
			}


			.bildsayi
			{
				position:absolute; 
				font-size:8px; 
				background:red; 
				border-radius:100px; 
				top:-2px; 
				right:-1px; 
				font-weight:700;
				padding:1px 5px; 
				color:white;
			}

			.girisyap
			{
				padding:0 25px;
				margin-top:40%;
			}

			.kayitol
			{
				padding:0 50px;
				margin-top:12%;
			}

			.kayitgonder
			{
				text-align:center;
			}

			.baslikgiris
			{
				padding:15px;
			}

			.soruinde
			{
				font-size:15px;
				font-weight:400;
				color:black;
			}

			.soruindex::first-letter
			{
				text-transform:uppercase;
			}

			.soruindex
			{
				font: normal 700 20px/25px "Noto Sans",Arial,sans-serif;
			}

			.soruindexbild{
				float:left;
				font: normal 600 15px/20px "Noto Sans",Arial,sans-serif;
			}
			
			.kategoriler
			{
				border:none;
				padding:20px;
			}
			
			.sorusayi
			{
				background:blue; 
				color:white; 
				border-radius:15px; 
				padding-left:10px; 
				padding-right:10px; 
				font-size:15px; 
				font-weight:600;
			}
			
			.containermen{
				text-decoration: none;
				bottom:0;
				height: 60px;
				width: 100%;
				display: none;
				justify-content: space-around;
				align-items: center;
				background: #fff;
				padding:0 0.4m;
				z-index: 2;
				color: #000;
				position: fixed;
				border-top: 0.1px solid grey;
			}
			
			.containermen::before{
				content: **;
				position: absolute;
				top: 5%;
				left: -2.5%;
				width: 105%;
				height: 112%;
				background: linear-gradient(to right, #272727, #2a2a2a);
				z-index: 1;
				transition: background 4s;
			}
			
			.containermen:hover::before{
				background: linear-gradient(to right, #9d50bb, #6e48aa);
			}
			
			
			.box a 
			{
				text-decoration: none;
				color: black;
			}
			
			.containerust{
				top:0;
				height: 60px;
				width: 100%;
				display: none;
				justify-content: space-around;
				align-items: center;
				background: #fff;
				padding:0 0.4m;
				color: #000;
				border-bottom: 0.1px  solid grey;
				position: fixed;
				z-index: 1;
			}
			
			
			.box{
				width: 40px;
				height: 40px;
				display: flex;
				justify-content: center;
				align-items: center;
				border-radius: 50px;
				cursor: pointer;
				transition: transform 0.4s;
			}
			
			.containerustprofile
			{
				top:0;
				height: 60px;
				width: 100%;
				display: none;
				background: #fff;
				color: #000;
				border-bottom: 0.1px  solid grey;
				position: fixed;
				padding:12px;
				z-index: 1;
			}
			
			.containerustprofile p
			{
				float:left;
				font-size:19px;
				padding:3px;
			}
			
			.containerustprofile button
			{
				float:right;
			}
			
			.cevap:hover
			{
				color:grey;
			}

			.anibtn
			{
				display:none;
				margin-left:-520px;
			}
			
			#deger
			{
				min-width:100%;
				max-height:100%;
				position:absolute;
				background:white;
				margin-left:-22;
				padding:30px;
				z-index: 1;
			}
			
			.tikladeg
			{
				border-radius:15px;
				border:1px solid darkgreen;
				color:darkgreen;
				padding:5 0;
				width: 45%;
			}

			#degerof
			{
				position: relative;
			}

			#degerlen
			{
				visibility: hidden;
				width: 90%;
				background-color: black;
				color: #fff;
				text-align: center;
				bottom: 110%;
				opacity:0%;
				left:5%;
				border-radius: 6px;
				transition: opacity 1s;
				position: absolute;
				z-index: 1;
				padding-left:15px;
				padding-right:15px;
				padding-bottom:50px;
			}

			#degerof:hover #degerlen
			{
				visibility: visible;
				opacity:90%;
			}

			.cardmob{
				position:relative; 
				margin:-10px 10px;
			}
			
			.gon
			{
				border-radius:15px;
				box-shadow: 0 5px 10px rgb(0 0 0 / 0.3);
			}

			.iptalyanit
			{
				margin-bottom:7;
				cursor: pointer;
			}
		
			@media only screen and (max-width: 1000px) {
				#deger
				{
					width:91%;
					height:100%;
					border-radius:15px;
					position:fixed;
					background:white;
					top:60;
					left:25;
				}

				body
				{
					background-color:#F5F5F5;
				}
				
				.selectde:focus
				{
					border:none;
					outline:none;
				}

				.bildarka
				{
					position:relative;
					margin:-10px 10px; 
					width:95%; 
					border-radius:15px; 
					box-shadow: 3px 3px 3px #d3d3d3;
				}

				.containermen
				{
					display:flex;
				}
				
				.containerust
				{
					display:flex;
				}
				
				.containerustprofile
				{
					display:block;
				}
				
				.masnav
				{
					display:none;
				}
				
				.ust
				{
					display: none;
				}
				
				.footer
				{
					display: none;
				}
				
				.cevapla
				{
					text-decoration: none;
					bottom:60px;
					left:10;
					height: 80px;
					width: 100%;
					position: fixed;
					padding:0 13px;
					background:white;
					margin-left:-10px;
				}

				.iptalyanit
				{
					bottom:115; 
					left:23; 
					position:fixed; 
					font-size:14px;
				}
				
				#cevap
				{
					width:70%;
					float:left;
					height: 25px;
				}
				
				#cevapbtn
				{
					width:25%;
					float:right;
				}
				
				.anibtn
				{
					text-decoration: none;
					bottom:70px;
					height: 60px;
					width: 60px;
					display: block;
					right: 15;
					align-items:center;
					border-radius:100px;
					color:white;
					position:fixed;
					border:none;
					background:#0d6efd;
				}

				.anibtnkesfet
				{
					text-decoration: none;
					bottom:70px;
					height: 60px;
					width: 60px;
					display: block;
					right: 20;
					align-items:center;
					border-radius:100px;
					color:white;
					position:fixed;
					border:none;
					background:#0d6efd;
				}
				
				.animenu
				{
					width:100%;
					height:100%;
					border-radius:15px;
					height:100%;
					padding:55px;
					position:fixed;
					background:white;
					top:10;
					left:0;
				}
				
				.animenu textarea
				{
					width:100%;
					height:30%;
				}
				
				.animenu p
				{
					width:60px;
					float:left;
					cursor:pointer;
				}
				.sidebars{
					display:none;
				}

				.cardmob{
					width:95%; 
				}
			}

			.yanitla
			{
				border:1px solid blue;
				border-radius:5px;
				padding:2px;
				background:white;
				color:blue;
				transition:0.3s;
			}

			#loading {
				position: fixed;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				display: flex;
				align-items:center;
				justify-content:center;
				background-color: #fff;
				z-index: 1;
			} 

			.yanitla:hover
			{
				background:blue;
				color:white;
			}

			.feather {
				width: 16px;
				height: 16px;
				vertical-align: text-bottom;
			}

			.sidebars
			{
				background: darkred;
			}

			@media (max-width: 1000px) {
			  .sidebar {
				position: fixed;
				top: 0;
				top: 4.5rem;
				right: 0;
				bottom: 0;
				left: 0;
				z-index: 100;
				padding: 48px 0 0;
				box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
			}
			}

			
        </style>
        
</head>

<body>

	<?
	
	function sidebar(){
	?>
		<div class="col sidebars">
			sidebar alanı
		</div>
	<?
	}
	
	?>

	<div id="loading">
		<img src="assets/img/loading-78.webp" width="100" alt="Yükleniyor..." />
	</div> 

	<header style="padding-bottom:17px;">
		<? if (basename($_SERVER['SCRIPT_NAME']) == 'myprofile.php' && $logged != "No") {
		?>
		<div class="containerustprofile" >
			<header class="navbar navbar-light flex-md-nowrap p-0">
				<p><? echo $username; ?></p>
				<button style="float:right; margin-top:-10px;" class="navbar-toggler position-relative d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Menu">
					<span style="color:black; float:right;" class="fas fa-bars"></span>
				</button>
			</header>
			<center>
			<nav id="sidebarMenu" style="margin-top:-15px;" class=" d-md-block bg-light text-dark sidebar collapse">
					<?php
						if ($rowu['role'] == 'Admin' || $rowu['role'] == 'Editor') {
						?>
							<a class="nav-link text-black" href="admin">
								Admin Panel
							</a>
						<?php
						}
					?>
					<a class="nav-link text-black" href="contact.php">
						İletişim
					</a>
					<a class="nav-link text-black" href="logout.php">
						Çıkış Yap
					</a>
					<br /><br />
			</nav>
			</center>
		</div>
		<? } else { ?>
			<div class="containerust" >
				<div class="box" style="float: left; margin-left:-80px;"><a href="index.php"><img src="assets/img/logo.png" width="100px"></a></div>
				<div class="box" style="float: right; margin-right:-110px;"><a href="robot.php"><i class="fas fa-robot"></i></a></div>
			</div><br/>
		<? } ?>
		<div class="ust">
			<div class="container d-flex flex-wrap justify-content-center">
				<center>
				<a href="index.php" style="padding:-25px;" class="d-flex align-items-center text-white mb-3 mb-md-0 me-md-auto text-decoration-none">
				  <span class="fs-4"><img src="http://forumuni.com/assets/img/logo.png" width="200px"></span></a></center>

				<!---<form class="col-12 col-lg-auto mb-3 mb-lg-0" action="search.php" method="GET">
				<div class="input-group">
					<input type="search" class="form-control" placeholder="Search" name="q" required>
					<span class="input-group-btn">
						<button class="btn btn-dark" type="submit"><i class="fa fa-search"></i></button>
					</span>
				</div>
				</form>---->
			</div>
		</div>
	</header>
	
	<nav class="py-2 bg-light">
		<div class="container d-flex flex-wrap">
			<ul class="nav masnav me-auto">
				<?php
					$runq = mysqli_query($connect, "SELECT * FROM `menu`");
					while ($row = mysqli_fetch_assoc($runq)) {
						
						echo '<li class="nav-item"><a href="' . $row['path'] . '" class="nav-link link-dark px-2';
						if (basename($_SERVER['SCRIPT_NAME']) == $row['path']) {
						echo 'active px-2 text-secondary';
						}
						echo '"><i class="fa ' . $row['fa_icon'] . '"></i> ' . $row['page'] . '</a></li>';

					}
				?>
			</ul>
			<ul class="nav masnav">
				<?php
					if ($logged == 'No') {
				?>
				<li class="nav-item"><a href="login.php" style="color:white;" class="nav-link btn btn-primary px-2"><i class="fas fa-sign-in-alt"></i> Kayıt Ol</a></li>
				<?php
				} 
				else {
					$avatarcek = mysqli_query($connect,"select avatar from users where username = '$username'");
					$avatar = mysqli_fetch_array($avatarcek);
				?>
				<li class="nav-item dropdown">
					<a href="#" class="nav-link link-dark dropdown-toggle" data-bs-toggle="dropdown"><img src="<? echo $avatar["avatar"]; ?>" class="rounded-circle" width="35" /> <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a class="dropdown-item" href="profilim"><i class="fas fa-user"></i> Profilim</a></li>
							<?php
							if ($rowu['role'] == 'Admin' || $rowu['role'] == 'Editor') {
								?>
								<li><a class="dropdown-item" href="admin.php" target="_blank"><i class="fas fa-toolbox"></i> Admin Panel</a></li>
								<?php
							}
							?>  
						<li role="separator" class="divider"></li>
						<li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Çıkış Yap</a></li>
					</ul>
				</li>
				<?php
				}
				?>
			</ul>
		</div>
	</nav>
    

	<?php
	?>
    </ul>
  </div>
</nav>
    

    
    <div class="container">
			<?php 
			}
			?>
    </div>
		<?
	

	function footer()
	{
		include "config.php";
		
		$run  = mysqli_query($connect, "SELECT * FROM `settings`");
		$site = mysqli_fetch_assoc($run);
	?><br/><br/><br/>
	<footer class="footer border-top bg-dark text-light px-4 py-5 mt-3">
		<div class="row">
		<div class="col-md-2">
        
		</div>
		<center><div class="col-md-6">
        <img src="http://forumuni.com/assets/img/logo1.png" width="225" /><br/>
		<?php
			$runq = mysqli_query($connect, "SELECT * FROM `settings`");
			while ($row = mysqli_fetch_assoc($runq)) {
				echo $row['description'];
			}

			if (!isset($_SESSION['sec-username'])) {
				$logged = 'No';
			} else {
				
				$username = $_SESSION['sec-username'];
				
				$querych = mysqli_query($connect, "SELECT * FROM `users` WHERE username='$username' LIMIT 1");
				if (mysqli_num_rows($querych) == 0) {
					$logged = 'No';
				} else {
					$rowu   = $querych->fetch_array();
					$logged = 'Yes';
				}
			}
?>
		</div></center>
	</footer>
	<div class="containermen">
		<? /*echo $_SERVER['REQUEST_URI'];*/ ?>
		<div class="box"><a href="anasayfa"><i style="font-size:24px;" class="<? if (basename($_SERVER['SCRIPT_NAME']) == 'index.php') { ?>bi bi-house-door-fill <? } else { ?> bi bi-house-door<? } ?>"></i></a></div>
		<div class="box"><a href="kesfet"><i style="font-size:21px;" class="<? if (basename($_SERVER['SCRIPT_NAME']) == 'universiteler.php') { ?>bi bi-binoculars-fill <? } else { ?> bi bi-search<? } ?>"></i></a></div>
		<div class="box"><a href="sorucevap"><i style="font-size:24px;" class="<? if (basename($_SERVER['SCRIPT_NAME']) == 'sorucevap.php') { ?>bi bi-question-square-fill <? } else { ?> bi bi-question-square<? } ?>"></i></a></div>
		<? if($logged == 'Yes'){ ?>
		<div class="box">
			<a href="bildirimler">
				<i style="position:relative;font-size:24px;" class="<? if (basename($_SERVER['SCRIPT_NAME']) == 'bildirimler.php') { ?>bi bi-bell-fill <? } else { ?> bi bi-bell<? } ?>">
				
				<? 
					$user_id = $rowu["id"];

					$bild = mysqli_query($connect,"SELECT count(*) as bildirim_id FROM bildirimler where kime_user = '$user_id' and okundu = 0 and user_id != '$user_id'");
					$bild = mysqli_fetch_array($bild);

					if($bild["bildirim_id"] != 0){
				?>
					<span class="bildsayi"><? echo $bild["bildirim_id"]; ?></span>	
				<? } ?>

				</i>
			</a>
		</div>
		<? } ?>
		<? if(!isset($_SESSION['sec-username'])){ ?>
		<div class="box"><a href="girisyap"><i style="font-size:24px;" class="<? if (basename($_SERVER['SCRIPT_NAME']) == 'login.php') { ?>bi bi-person-fill <? } else { ?> bi bi-person<? } ?>"></i></a></div>
		<? } else { 
			
			$username = $_SESSION['sec-username'];
			
			$pp = mysqli_query($connect,"select * from users where username = '$username'");
			$avatar = mysqli_fetch_array($pp);
		
		?>
		<div class="box">
			<a href="profilim">	
				<img class="rounded-circle" style="<? if (basename($_SERVER['SCRIPT_NAME']) == 'myprofile.php') { ?>border:2px solid grey;<? }?> padding:1px;" src="<? echo $avatar["avatar"]; ?>" width="25" height="25" />
			</a>
		</div>
		<? } ?>
		
	</div>
	<script type="text/javascript">
		//bildirim
		$(document).ready(function(){

			$(".kayitol").css("display","none");

			<!---- Anılar ve Sorular ---->
			
			$("#aniform").css("display","none");
			$("#soruform").css("display","none");
			$("#anilardiv").css("display","none");
			$("#sorulnk").addClass("active");
			$("#duzenle").css("display","none");
			$("#deger").css("display","none");

			$(".input").keyup(function(){
				
				// Veriyi alalım
				var value = $(this).val()
				var data = "value="+value
				
				
				$.ajax({
					
					type: "POST",
					url: "ajax_islemler.php?option=kesfet",
					data: data,
					success: function(e){
					
						if(value == '')
						{
							$("#kesfetanilar").css("display","block");
							$("#uniara").css("display","none");
							$("#userara").css("display","none");
							$("#uniarabtn").css("display","none");
							$("#userarabtn").css("display","none");
						}
						
						else
						{
							$("#kesfetanilar").css("display","none");
							$("#uniarabtn").addClass("active");
							$("#userarabtn").removeClass("active");	
							$('#sonuclar').html(e)
						}
					}	
					
				})
				
			});

			$(".yanitla").on('click',function(){
				var cevapid = $(this).attr("title");
				$.ajax({
					type:"POST",
					url:"ajax_islemler.php?option=yanitla",
					data:{"cevap":cevapid},
					success:function(e)
					{
						$(".cevapladiv").html(e);
					}
				})
			});

			$("#MemberUni").change(function(){
				var uniid = $(this).val();
				$.ajax({
					type:"POST",
					url:"ajax_islemler.php?option=fakulteliste",
					data:{"uni":uniid},
					success:function(e)
					{
						$("#MemberFak").html(e);
					}
				})
			});

			$("#MemberUnia").change(function(){
				var uniid = $(this).val();
				$.ajax({
					type:"POST",
					url:"ajax_islemler.php?option=fakulteliste",
					data:{"uni":uniid},
					success:function(e)
					{
						$("#MemberFaka").html(e);
					}
				})
			});
			
			$("#unilidegil").change(function(){
				if ($(this).is(':checked')) {
					$("#unidegil").css("display","none");
				}
				else
				{
					$("#unidegil").css("display","block");
				}
			})
			
			$('#kat').change(function() {
				var kat = $(this).val();
				if(kat == 2) {
					$(".uni").css("display","block");
					$(".fak").css("display","none");
					$(".bol").css("display","none");
				}
				else if(kat == 3)
				{
					$(".uni").css("display","block");
					$(".fak").css("display","block");
					$(".bol").css("display","none");
				}
				else if(kat == 4)
				{
					$(".uni").css("display","none");
					$(".fak").css("display","none");
					$(".bol").css("display","block");
				}
				else
				{
					$(".uni").css("display","none");
					$(".fak").css("display","none");
					$(".bol").css("display","none");
				}
			});
			
			$("#sorusel").val(1);
			
			$('#listsel').change(function(){
				var filtre = $(this).val();
				$.ajax({
					type:"POST",
					url:"ajax_islemler.php?option=anasayfafiltre",
					data:{"filtre":filtre},
					success:function(e)
					{
						$(".allfilter").html(e);
					}
				})
			});

			$("#sorusel").css("width","100%");
			
			$('#sorusel').change(function(){
				var sorusel = $(this).val();
				
				$.ajax({
					type:"POST",
					url:"ajax_islemler.php?option=sorucevkategori",
					data:{"sorucevfilter":sorusel},
					success:function(e)
					{

						$(".sorucevfilt").html(e);

						$("#unima").change(function(){
							if ($(this).is(':checked')) {
								$("#uniuser").css("display","block");
								$("#uninone").css("display","none");
							}
							else
							{
								$("#uniuser").css("display","none");
								$("#uninone").css("display","block");
							}
						})
					
						$("#fakima").change(function(){
							if ($(this).is(':checked')) {
								$("#fakuser").css("display","block");
								$("#faknone").css("display","none");
							}
							else
							{
								$("#fakuser").css("display","none");
								$("#faknone").css("display","block");
							}
						})
					
						$("#bolima").change(function(){
							if ($(this).is(':checked')) {
								$("#boluser").css("display","block");
								$("#bolnone").css("display","none");
							}
							else
							{
								$("#boluser").css("display","none");
								$("#bolnone").css("display","block");
							}
						})
					}
				})
			});


			<!---- Rezleme ---->

			$('.rezle').click(function(){
				const rezle_button = this;
				var soruid = $(this).attr("title");
				$.ajax({
					type:"POST",
					url:"ajax_islemler.php?option=rezle",
					data:{"soru":soruid},
					success:function(e)
					{
						$(rezle_button).html(e);
					}
				})
			});
			
			$('.bildarka').on('click', function(){
				var bildirimid = $(this).attr("title");
				$.ajax({
					type:"POST",
					url:"ajax_islemler.php?option=bildoku",
					data:{"bild":bildirimid},
					success:function(e)
					{
						
					}
				})
			});
		});
			
		function yanitlakapat() 
		{
			$.ajax({
				url:"ajax_islemler.php?option=yanitlakapat",
				success:function(e)
				{
					$(".cevapladiv").html(e);
				}
			})
		}
		
		function uyelik()
		{
			$(".girisyap").css("display","none");
			$(".kayitol").css("display","block");
		}

		function giris()
		{
			$(".girisyap").css("display","block");
			$(".kayitol").css("display","none");
		}

		function duzenle()
		{
			$("#duzenle").css("display","block");
			$("#profil").css("display","none");
		}
		
		function degerlendir()
		{
			$("#deger").css("display","block");
		}
		
		function closedege()
		{
			$("#deger").css("display","none");
		}
		
		function iptalp()
		{
			$("#duzenle").css("display","none");
			$("#profil").css("display","block");
		}
		
		$("#sorubtn").css("display","block");
		$("#anibtn").css("display","none");

		function anilarac()
		{
			$("#anilnk").addClass("active");
			$("#sorulnk").removeClass("active");
			$("#anilardiv").css("display","block");
			$("#sorulardiv").css("display","none");
			$("#sorubtn").css("display","none");
			$("#anibtn").css("display","block");
		}
		
		function sorularac()
		{
			$("#sorulnk").addClass("active");
			$("#anilnk").removeClass("active");
			$("#anilardiv").css("display","none");
			$("#sorulardiv").css("display","block");
			$("#sorubtn").css("display","block");
			$("#anibtn").css("display","none");
		}
		
		function aniac()
		{
			$("#aniform").css("display","block");
			$("#anibtn").css("display","none");
		}
		
		function anikapa()
		{
			$("#aniform").css("display","none");
			$("#anibtn").css("display","block");		
		}
		
		function soruac()
		{
			$("#soruform").css("display","block");
			$("#sorubtn").css("display","none");
		}
		
		function sorukapa()
		{
			$("#soruform").css("display","none");
			$("#sorubtn").css("display","block");		
		}
		
		<!---- Anılar ve Sorular ---->
		
		<!---- Üniversite Ara ---->
		
		function uniara()
		{
			$("#userara").css("display","none");
			$("#uniara").css("display","block");
			$("#uniarabtn").addClass("active");
			$("#userarabtn").removeClass("active");			
		}
		
		function userara()
		{
			$("#userarabtn").addClass("active");
			$("#uniarabtn").removeClass("active");	
			$("#uniara").css("display","none");
			$("#userara").css("display","block");		
		}
		
		<!---- Üniversite Ara ---->
		
		function displaySelection()
		{
			var mySelect = document.getElementById("someSelectElement");
			var mySelection = mySelect.selectedIndex;
			alert(mySelection);
		}
		
		
		
	</script>
	<script type="text/javascript">

		(function( $ ) {

		  var $container = $('.masonry-container');
		  $container.imagesLoaded( function () {
			$container.masonry({
			  columnWidth: '.item',
			  itemSelector: '.item'
			});
		  });

		})(jQuery);


	</script>
	<script>
    window.addEventListener('load', fg_load)

    function fg_load() {
        document.getElementById('loading').style.display = 'none'
    }
</script>
	</div>
    </div>
	<?php
}
?>
    </div>
    </div>
</body>

</html>
