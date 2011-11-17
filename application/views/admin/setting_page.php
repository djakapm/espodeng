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
			<h3>Settings</h3>
		</div>
	</div>
	<div style="padding:5px">
	<p>
		General Application Settings
	</p>
	<form method="post" action="<?=site_url('admin/save_setting')?>">
	<table>
	<thead>
		<tr>
			<th>Registry Name</th>
			<th style="text-align:center">Numeric Value</th>
			<th style="text-align:center">String Value</th>
		</tr>
	</thead>
	<?php
		foreach($settings as $setting){
	?>
		<input type="hidden" name="registry_name[]" value="<?=$setting->registry_name?>"/>
		<tr>
		<td><?=$setting->registry_name?></td>
		<td><input type="text" style="text-transform:none" name="numeric_value[]" value="<?=$setting->numeric_value?>"/></td>
		<td><input type="text" size="100" style="text-transform:none" name="string_value[]" value="<?=$setting->string_value?>"/><td/>
		</tr>
	<?php
		}
	?>
	</table>
	</div>
	<div style="padding:5px">
		<input type="submit" style="height:50px;width:30%" value="Save Settings"/>
	</div>
	</form>
</div>
</body>
</html>