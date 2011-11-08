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
<div id="container" style="width:40%;margin:auto">
	<div style="padding:5px">
		<div class="block">
			<h3>Tentang Situs Ini</h3>
			<p style="line-height:20px;margin: 10px 0 10px 0;text-align:justify">
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus semper, magna non dignissim ornare, enim mi posuere turpis, id blandit massa odio vitae nibh. Proin sollicitudin elementum mattis. Phasellus lectus massa, pharetra at ultrices vitae, iaculis sit amet risus. Vestibulum mi erat, fermentum vitae adipiscing vel, cursus eu nulla. Praesent venenatis mauris a risus sodales tincidunt. Vivamus vel viverra ipsum. Aliquam dapibus, nisi convallis cursus sodales, velit nulla molestie nulla, in suscipit urna leo et magna. Nulla augue elit, vestibulum vitae ultricies ac, mollis quis lectus. Mauris pharetra enim id nisl eleifend imperdiet. Vestibulum vel augue eget sapien accumsan aliquet ut sit amet augue. Maecenas mattis dui justo, ut suscipit velit.
			</p>
			<p style="line-height:20px;margin: 10px 0 10px 0;text-align:justify">

				Mauris malesuada aliquam velit, nec pellentesque neque tristique id. Curabitur venenatis magna non nulla ullamcorper imperdiet. Integer dapibus molestie nibh, a pharetra sapien rutrum nec. Sed arcu elit, egestas vel tincidunt ut, ultrices eget erat. Nulla euismod eros quis ante laoreet varius. Aliquam ornare justo congue nisi tincidunt auctor. Fusce ut justo ac augue interdum vehicula id sit amet augue. Phasellus justo eros, dignissim sed ultrices at, volutpat aliquam quam. Ut molestie feugiat sapien, at gravida lorem sollicitudin blandit. Donec pulvinar orci eu tortor eleifend scelerisque. Fusce porttitor accumsan nunc, eget mattis massa semper sit amet. Donec a dolor magna.
			</p>
			<p style="line-height:20px;margin: 10px 0 10px 0;text-align:justify">

				Mauris commodo dictum nisi vel laoreet. Phasellus mauris lorem, egestas nec sodales nec, porta at sem. Suspendisse pretium placerat est, vitae faucibus lectus egestas vel. Praesent faucibus felis ac nibh venenatis eleifend. Suspendisse sed justo a tortor malesuada fringilla quis et velit. Ut vel arcu orci, malesuada ultrices nibh. Morbi et enim purus. Suspendisse potenti. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Duis dolor tellus, varius vitae rhoncus in, facilisis eu eros. Cras vehicula sollicitudin elit, in gravida nibh consequat vulputate. Pellentesque a risus diam. Fusce sagittis, tortor a condimentum ullamcorper, ante sapien posuere risus, sit amet auctor sapien ligula rhoncus sapien.
			</p>
		</div>
	</div>
</div>
</body>
</html>