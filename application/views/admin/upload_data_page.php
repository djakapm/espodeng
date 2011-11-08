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
			<h3>Upload Data</h3>
			<ul>
				<li><a href="<?=site_url('admin/landing')?>">Operations</a></li>
			</ul>
		</div>
		<div class="block">
		<form method="post" action="<?=site_url('admin/process_uploaded_data')?>" enctype="multipart/form-data">
			<div style="padding:5px">
				<label for="upload-input">Upload CSV File</label><br/>
				<input id="upload-input" name="upload-input" type="file" class="input" style="padding-left:5px;"/>
			</div>		
			<div style="padding:5px">
				<input type="submit" id="upload-button" style="height:50px;width:30%" value="Upload CSV File"/>
			</div>
		</form>
		</div>
		<div style="padding:5px">
		<?php if(!empty($current_file)){ ?>
		<p>Current processed file: <?=$current_file?></p>
		<?php } ?>
		</div>
		<div style="padding:5px">
			<table>
			<?php
				if(!empty($csv_data))
				foreach($csv_data as $csv_datum){
			?>
				<tr>
					<td><?=$csv_datum[0]?></td>
					<td><?=$csv_datum[1]?></td>
					<td><?=$csv_datum[2]?></td>
					<td><?=$csv_datum[3]?></td>
				</tr>
			<?php } ?>
			</table>
		</div>		
	</div>
</div>
</body>
</html>