<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?=$site_name?></title>
	<link rel="stylesheet" href="../../resource/css/main.css"/>	
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
		<div style="float:left"><h1 style="color:#000"><a href="#" style="text-decoration:none"><?=$site_title?></a></h1></div>
		<div id="info" style="float:right"></div>		
	</div>
</div>
<div id="container" style="width:40%;margin:auto">
	<div style="padding:5px">
		<div class="block">
			<h3>Disclaimer</h3>
			<p style="line-height:20px;margin: 10px 0 10px 0;color:red">Berat minimum paket adalah 1(satu) kg.</p> 
			<p style="line-height:20px;margin: 10px 0 10px 0;color:red">Pembulatan bobot paket ditentukan oleh perusahaan logistik.</p> 
			<p style="line-height:20px;margin: 10px 0 10px 0;color:red">Perubahan harga ongkir dapat terjadi sewaktu-waktu tanpa pemberitahuan sebelumnya.</p> 
		</div>
	</div>
</div>
</body>
</html>