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
				<li><a href="<?=site_url('welcome/contactus')?>">Contact Us</a></li>
				<li><a href="<?=site_url('welcome/about')?>">About</a></li>
			</ul>
		</div>
		<div id="copyright-container">
			<span>copyright &copy <?=$site_name?> 2011</span>
		</div>
	</div>
	<div id="site-title-container">
		<div style="float:left">
			<img alt="palingoke.info logo" src="<?=base_url()?>resource/img/logo_.png" width="100" height="100"/> 
			<h1><a href="#" style="text-decoration:none"><?=$site_title?></a></h1>
		</div>
		<div id="info" style="float:right"></div>		
	</div>
</div>
<div id="container" style="width:75%;margin:auto">
	<div style="padding:5px">
		<div class="block">
			<h3>Upload State, City and District Data</h3>
			<ul>
				<li><a href="<?=site_url('admin/landing')?>">Operations</a></li>
				<li><a href="<?=site_url('admin/login')?>">Logout</a></li>
			</ul>
		</div>
	</div>
	<div style="padding:5px">
	<p>
		Upload CSV or TEXT file. That contains district, city and state data, in such format:<br/>
		INDONESIA#D.I. ACEH#KAB. ACEH BARAT#Kec. Kaway XVI
	</p>
	</div>

	<div style="padding:5px">
		<div class="block">
		<form method="post" action="<?=site_url('admin/rebuild_reference_data')?>" enctype="multipart/form-data">
			<div style="padding:5px">
				<?php if(!empty($last_rebuilt_date)){?>
				<p style="font-size:large">Last rebuilt: <?=$last_rebuilt_date?></p>
				<?php } ?>

				<label for="upload-input">Upload CSV File</label><br/>
				<input id="upload-input" name="upload-input" type="file" class="input" style="padding-left:5px;"/>
			</div>		
			<div style="padding:5px">
				<input type="submit" id="upload-button" style="height:50px;width:30%" value="Rebuild Reference Data"/>
			</div>
		</form>
		</div>


	</div>
</div>
</body>
</html>