<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?=$site_name?></title>
	<link rel="stylesheet" href="resource/css/main.css"/>	
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
	    <div id="input-container" class="block">
			<h2>Data Paket</h2>
			<div style="padding:5px">
				<label class="label-input" for="weight">Berat</label><br/>
				<input id="weight" type="text" class="number-input" size="4" maxlength="4"/>
			</div>
			<div id="origin" style="padding:5px">
				<label class="label-input" for="origin-input">Dari</label><br/>
				<input id="origin-input" type="text" class="input"/>
	 		</div>
			<div id="destination" style="padding:5px">
				<label class="label-input" for="destination-input">Ke</label><br/>
				<input id="destination-input" type="text" class="input"/>
			</div>
			<div style="padding:5px">
				<!--label class="label-input">Validasi</label><br/-->
				<?php 
					// echo $recaptcha;
				?>
			</div>
			<div style="padding:5px">
				<button id="search-button" class="input">Cari</button>
			</div>
		</div>	
	</div>
	<div style="padding:5px">
		<div id="output-container" class="block">
			<div style="height:30px">
				<div style="float:left"><h2>Hasil Pencarian</h2></div>
				<div style="float:right;padding:5px">
					<span id="cheapest-filter" class="round-block">Termurah</span>
					&nbsp;
					<span id="middle-filter" class="round-block">Paling OKE</span>
					&nbsp;
					<span id="fastest-filter" class="round-block">Tercepat</span>
				</div>
			</div>
			<div id="result-container" style="margin-top:20px">
				<div id="result" style="display:none">
					<p id="origin-result" style="margin:10px"></p>
					<p id="destination-result" style="margin:10px"></p>
					<p id="weight-result" style="margin:10px"></p>
				</div>
			</div>
 			<div id="logistic-ouput-container" style="margin-top:20px"></div>
		</div>
	</div>
</div>
<!--script type="text/javascript">
  var RecaptchaOptions = { 
    theme:"<?= $theme ?>",
    lang:"<?= $lang ?>"
  };
</script-->
<!--script type="text/javascript" src="<?= $server ?>/challenge?k=<?= $key.$errorpart ?>"></script>
<noscript>
		<iframe src="<?= $server ?>/noscript?lang=<?= $lang ?>&k=<?= $key.$errorpart ?>" height="300" width="500" frameborder="0"></iframe><br/>
		<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
		<input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
</noscript-->
<script type="text/javascript" src="resource/js/jquery-1.6.4.min.js"></script>
<script type="text/javascript" src="resource/js/jquery.currency.min.js"></script>
<script type="text/javascript" src="resource/js/jquery.widedrop.js"></script>
<script type="text/javascript" src="resource/js/main.js"></script>
</body>
</html>