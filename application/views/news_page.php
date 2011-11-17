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
		<div id="news-container" class="block">
			<h3>Berita</h3>
			<p style="text-transform:none;line-height:20px;margin: 10px 0 10px 0;text-align:justify">Saat ini kami hanya melayani data pengiriman dari Jakarta.</p>
			<p style="text-transform:none;line-height:20px;margin: 10px 0 10px 0;text-align:justify">Support untuk daerah asal lainnya segera.</p>
		</div>
	</div>
	<div style="padding:5px">
		<div class="block">
			<h3>Anda dapat berpartisipasi</h3>
			<p style="text-transform:none;line-height:20px;margin: 10px 0 10px 0;text-align:justify">Anda dapat mengirimkan request data pengiriman kepada kami. Silahkan kirimkan email ke admin@palingoke.info nama daerah asal dan tujuan selengkap-lengkapnya juga sertakan nama perusahaan jasa pengiriman yang Anda kehendaki.</p>
		</div>
	</div>
	<div style="padding:5px">
		<div class="block">
			<h3>Kritik dan saran</h3>
			<p style="text-transform:none;line-height:20px;margin: 10px 0 10px 0;text-align:justify">Anda dapat mengirimkan kritik dan saran. Silahkan kirimkan email ke admin@palingoke.info nama kritik dan saran Anda. Jika Anda melihat adanya kesalahan data pengiriman Anda juga dapat melaporkannya kepada kami melalui admin@palingoke.info.</p>
		</div>
	</div>
</div>
</body>
</html>