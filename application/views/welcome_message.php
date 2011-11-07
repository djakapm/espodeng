<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?=$site_name?></title>
	<link rel="stylesheet" href="resource/css/main.css"/>	
	<link rel="stylesheet" href="resource/css/jquery-ui-1.8.9.custom.css"/>	
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
		<div style="float:left"><h1><a href="#" style="text-decoration:none"><?=$site_title?></a></h1></div>
		<div id="info" style="float:right"></div>		
	</div>
</div>
<div id="container" style="width:40%;margin:auto">
	<div style="padding:5px">
		<form id="input-form">
	    <div id="input-container" class="block">
			<h3>Data Paket</h3>
			<div id="origin" style="padding:5px">
				<label for="origin-input">Dari</label><br/>
				<input id="origin-input" name="origin-input" type="text" class="input" style="padding-left:5px;"/>
	 		</div>
			<div id="destination" style="padding:5px">
				<label for="destination-input">Ke</label><br/>
				<input id="destination-input" name="destination-input" type="text" class="input" style="padding-left:5px;"/>
			</div>
			<div style="padding:5px">
				<label for="weight">Berat</label><br/>
				<input id="weight" name="weight" type="text" value="1" class="number-input" style="text-align:center" size="4" maxlength="4"/>
			</div>
			<div style="padding:5px">
				<label class="label-input">Validasi</label><br/>
				<?php 
					echo $recaptcha;
				?>
			</div>
			<div style="padding:5px">
				<button id="search-button" style="height:50px;width:100%"><span style="font-size:large;color:white">Cari</span></button>
			</div>
		</div>	
	</div>
	<div style="padding:5px">
		<div id="output-container" class="block">
			<div style="height:30px">
				<div style="float:left"><h3>Hasil Pencarian</h3></div>
				<div style="float:right;padding:5px">
					<span id="cheapest-filter" class="round-block">Termurah</span>
					&nbsp;
					<span id="middle-filter" class="round-block">Paling OKE</span>
					&nbsp;
					<span id="fastest-filter" class="round-block">Tercepat</span>
				</div>
			</div>
 			<div id="logistic-ouput-container" style="margin-top:20px"></div>
		</div>
	</div>
</div>
<script type="text/javascript" src="resource/js/jquery-1.6.4.min.js"></script>
<script type="text/javascript" src="resource/js/jquery.jsonSuggest-2.min.js"></script>
<script type="text/javascript" src="resource/js/jquery.currency.min.js"></script>
<script type="text/javascript" src="resource/js/main.js"></script>
</body>
</html>