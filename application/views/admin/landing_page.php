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
				<li><a href="<?=site_url('admin/landing')?>">Home</a></li>
				<li><a href="<?=site_url('admin/rebuild_reference_data_landing')?>">Rebuild Reference Data</a></li>
				<li><a href="<?=site_url('admin/rebuild_location_landing')?>">Rebuild Locations</a></li>
				<li><a href="<?=site_url('admin/upload_data')?>">Upload Data</a></li>
				<li><a href="<?=site_url('admin/setting')?>">Settings</a></li>
				<li><a href="<?=site_url('admin/login')?>">Logout</a></li>
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
<div id="container" style="width:75%;margin:auto">
	<div style="padding:5px">
		<div class="block">
			<h3>Operations</h3>
			<ul>
				<li><a href="<?=site_url('admin/rebuild_reference_data_landing')?>">Rebuild Reference Data</a></li>
				<li><a href="<?=site_url('admin/rebuild_location_landing')?>">Rebuild Locations</a></li>
				<li><a href="<?=site_url('admin/upload_data')?>">Upload Data</a></li>
				<li><a href="<?=site_url('admin/setting')?>">Settings</a></li>
				<li><a href="<?=site_url('admin/login')?>">Logout</a></li>
			</ul>
		</div>
	</div>
</div>
</body>
</html>