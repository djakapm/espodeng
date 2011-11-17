<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?=$site_name?></title>
	<link rel="stylesheet" href="<?=base_url()?>resource/css/main.css"/>	
	<link rel="stylesheet" href="<?=base_url()?>resource/css/jquery-ui-1.8.9.custom.css"/>	
</head>
<body>
<div id="top-fixed-container">
	<div id="top-fixed-section-one">
		<div id="main-menu-container">
			<ul id="main-menu">
				<li><a href="<?=base_url()?>"><?=$site_name?></a></li>
				<li><a href="<?=site_url('welcome/disclaimer')?>">Disclaimer</a></li>
				<li><a href="<?=site_url('welcome/news')?>">Berita</a></li>
				<li><a href="<?=base_url()?>">Contact Us</a></li>
				<li><a href="<?=base_url()?>">About</a></li>
			</ul>
		</div>
		<div id="copyright-container">
			<span>copyright &copy <?=$site_name?> 2011</span>
		</div>
	</div>
	<div id="site-title-container">
		<div style="float:left">
			<img alt="palingoke.info logo" src="<?=base_url()?>resource/img/logo_.png" width="296" height="79"/> 
			<!--h1><a href="#" style="text-decoration:none"-->
			<?php //echo $site_title ?>
			<!--/a></h1-->
		</div>
		<div id="info" style="float:right"></div>		
	</div>
</div>
<div id="container" style="width:40%;margin:auto">
	<div style="padding:5px">
		<div class="block">
			<h3>Disclaimer</h3>
			<p style="text-transform:none;line-height:20px;margin: 10px 0 10px 0;color:red;text-align:justify">Berat minimum paket adalah 1(satu) kg.</p> 
			<p style="text-transform:none;line-height:20px;margin: 10px 0 10px 0;color:red;text-align:justify">Pembulatan bobot paket ditentukan oleh perusahaan logistik.</p> 
			<p style="text-transform:none;line-height:20px;margin: 10px 0 10px 0;color:red;text-align:justify">Perubahan harga ongkir dapat terjadi sewaktu-waktu tanpa pemberitahuan sebelumnya.</p> 
		</div>
	</div>
</div>
</body>
</html>