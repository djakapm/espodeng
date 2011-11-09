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

		<form method="post" action="<?=site_url('admin/process_data')?>">
			<div style="padding:5px">
				<?php if(!empty($logistic_companies)){?>
				<label>Logistic Company</label><br/>
				<?=form_dropdown('logistic_company',$logistic_companies)?>
				<?php } ?>
			</div>
			<div style="padding:5px">
				<?php if(!empty($logistic_service_types)){?>
				<label>Logistic Service Type</label><br/>
				<?=form_dropdown('logistic_service_type',$logistic_service_types)?>
				<?php } ?>
			</div>
			<div style="padding:5px">
			<?php if(!empty($current_file)){ ?>
			<p>Current processed file: <?=$current_file?></p>
			<?php } ?>
			</div>
			<div style="padding:5px">
				<table>
				<thead>
					<tr>
						<th><?=form_checkbox('', '')?>Proses?</th>
						<th>No.</th>
						<th>Daerah</th>
						<th>Harga Per Kg</th>
						<th>Harga Per Kg Berikutnya</th>
						<th>Lama Pengiriman</th>
						<th>Daerah Tebakan</th>				
					</tr>
				</thead>
				<tbody>
				<?php
				    $no = 1;
				    $idx = 0;
					if(!empty($csv_data))
					foreach($csv_data as $csv_datum){
						$district = (empty($csv_datum[0]) ? '-' : $csv_datum[0]);
						$unit_price = (empty($csv_datum[1]) ? '-' : $csv_datum[1]);
						$next_unit_price = (empty($csv_datum[2]) ? '-' : $csv_datum[2]);
						$delivery_time = (empty($csv_datum[3]) ? '-' : $csv_datum[3]);
						$guessed_districts = $csv_datum[4];
				?>
						<?php if(!empty($guessed_districts)){?>
							<tr>
								<td><?=form_checkbox('selected_data[]',$idx,true)?></td>
								<td style="text-align:justify"><?=$no?></td>
								<td style="text-align:justify"><?=$district?></td>
								<td style="text-align:right"><?=$unit_price?></td>
								<td style="text-align:right"><?=$next_unit_price?></td>
								<td style="text-align:right"><?=$delivery_time?></td>
								<td style="text-align:right">
									<?=form_dropdown('guessed_district[]',$guessed_districts)?>
								</td>
								<?=form_hidden('unit_price[]', $unit_price)?>
								<?=form_hidden('next_unit_price[]', $next_unit_price)?>
								<?=form_hidden('delivery_time[]', $delivery_time)?>
							</tr>

						<?php 
							$idx++;
						} else{
						?>
					<tr style="background-color:red">
						<td>&nbsp;</td>
						<td style="text-align:justify"><?=$no?></td>
						<td style="text-align:justify"><?=$district?></td>
						<td style="text-align:right"><?=$unit_price?></td>
						<td style="text-align:right"><?=$next_unit_price?></td>
						<td style="text-align:right"><?=$delivery_time?></td>
						<td style="text-align:right">
							Tidak ditemukan
						</td>
					</tr>
						<?php } ?>

				<?php 
						$no++;
					} 
				?>
				</tbody>
				</table>
			</div>		
			<div style="padding:5px">
				<input type="submit" id="process-button" style="height:50px;width:30%" value="Proses Data Terpilih"/>
			</div>

		</form>
	</div>
</div>
</body>
</html>