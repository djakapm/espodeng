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
<div id="container" style="width:40%;margin:auto">
	<div style="padding:5px">
	    <div class="block">
			<h3>Summary</h3>
			<ul>
				<li><?=$table_info?></li>
			</ul>
		</div>	
		<div class="block">
			<h5>Unknown Districts</h5>
			<ol style="list-style-type:decimal">
				<?php foreach($unknown_districts_data as $unknown_district_datum){?>
				<li><?=$unknown_district_datum?></li>
				<?php }?>
			</ol>		
		</div>
	</div>
	<div style="padding:5px">
	<button style="height:50px;width:30%" onclick="location='<?=site_url('admin/upload_data')?>'">Done</button>
	</div>
</div>
</body>
</html>