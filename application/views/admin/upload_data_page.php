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
			<h3>Upload Data</h3>
		</div>
	</div>
	<div style="padding:5px">
	<p>
		Upload CSV or TEXT file. That contains logistic data service, in such format:<br/>
		INDONESIA#Sumatera Utara#Kota Medan#Medan#29,500#0#1<br/>
		after a successful data submission new table will be created using today date<br/>
		such as: ongkir_logistic_service_13112011
	</p>
	</div>

	<div style="padding:5px">
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
				<?php if(!empty($origin_districts)){?>
				<label>Origin District</label><br/>
				<?=form_dropdown('origin_district',$origin_districts)?>
				<?php } ?>
			</div>
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
			<p>Elapsed time: <?php echo $this->benchmark->elapsed_time();?> (seconds)</p>
			</div>
			<div style="padding:5px">
				<?=form_checkbox('replace_data','replace',true,'id="replace_data"')?>
				<label for="replace_data">Create or Replace Data</label>
			</div>
			<div style="padding:5px">
				<?=form_radio('logistic-filter','filtered',true,'id="logistic-filter-all"')?>
				<label for="logistic-filter-all">All District <?=(empty($all_district_count) ? '' : '('.$all_district_count.')')?></label>
				<?=form_radio('logistic-filter','filtered',false,'id="logistic-filter-ambigous"')?>
				<label for="logistic-filter-ambigous">Ambigous Districts <?=(empty($ambigous_district_count) ? '' : '('.$ambigous_district_count.')')?> and Unguessed Districts <?=(empty($unguessed_district_count) ? '' : '('.$unguessed_district_count.')')?></label>

			</div>
			<div style="padding:5px">
				<input type="submit" id="process-button" style="height:50px;width:30%" value="Process Selected Data"/>
				</div>			
			<div style="padding:5px">
				<table id="logistic-data" class="data" style="width:940px;table-layout:fixed">
				<thead>
					<col width="30">
					<col width="40">
					<col width="200">
					<col width="30%">
					<col width="70%">
					<col width="60">
					<col width="60">
					<col width="60">
					<tr style="height:50px">
						<th><?=form_checkbox('', '')?></th>
						<th>No.</th>
						<th>City</th>
						<th>Location</th>
						<th>Guessed Location</th>				
						<th title="Unit Price">UP(Rp)</th>
						<th title="Next Unit Price">NUP(Rp)</th>
						<th title="Delivery Time">DT(Hari)</th>
					</tr>
				</thead>
				<tbody>
				<?php
				    $no = 1;
				    $idx = 0;
				    $even = false;
					if(!empty($csv_data))
					foreach($csv_data as $csv_datum){
					    $guessed_districts = array();
						$i = 0;
						$even = ($no % 2) == 0;
						$country = (empty($csv_datum[$i]) ? '-' : $csv_datum[$i]);
						$i++;
						$state = (empty($csv_datum[$i]) ? '-' : $csv_datum[$i]);
						$i++;
						$city = (empty($csv_datum[$i]) ? '-' : $csv_datum[$i]);
						$i++;
						$district = (empty($csv_datum[$i]) ? '-' : $csv_datum[$i]);
						$i++;
						$unit_price = (empty($csv_datum[$i]) ? '0' : $csv_datum[$i]);
						$i++;
						$next_unit_price = (empty($csv_datum[$i]) ? '0' : $csv_datum[$i]);
						$i++;
						$delivery_time = (empty($csv_datum[$i]) ? '0' : $csv_datum[$i]);
						$i++;
						if(!empty($csv_datum[$i])){
							$guessed_districts = $csv_datum[$i];
							$multi_district = count($guessed_districts) > 1;							
						}
				?>
						<?php if(!empty($guessed_districts)){?>
						<?php if($multi_district){?>
							<tr class="ambigous" style="height:30px;background-color:orange">
						<?php }else{?>
							<tr class="exact" style="height:30px;background-color:<?=($even ? '#ccc' : '#fff' )?>">
						<?php }?>
								<td><?=form_checkbox('selected_data[]',$idx,true)?></td>
								<td style="text-align:left"><?=$no?></td>
								<td style="text-align:left"><?=$city?></td>
								<td style="text-align:left"><?=$district?></td>

								<?php if($multi_district){?>
									<td style="padding-top:5px">
										<?php $guessed_districts[-1] = 'Pilih nama daerah...'; ksort($guessed_districts);?>
										<?=form_dropdown('guessed_district[]',$guessed_districts)?>
										<?=form_hidden('ambigous_city[]',$city)?>
										<?=form_hidden('ambigous_district[]',$district)?>

									</td>
								<?php } else {?>
									<td style="text-align:left">
										<span style="font-size:12px"><?=$guessed_districts[key($guessed_districts)]?></span>
										<?=form_hidden('guessed_district[]',key($guessed_districts))?>
										<?=form_hidden('ambigous_city[]','')?>
										<?=form_hidden('ambigous_district[]','')?>
									</td>
								<?php } ?>

								<?=form_hidden('unit_price[]', $unit_price)?>
								<?=form_hidden('next_unit_price[]', $next_unit_price)?>
								<?=form_hidden('delivery_time[]', $delivery_time)?>
								<td style="text-align:center"><?=$unit_price?></td>
								<td style="text-align:center"><?=$next_unit_price?></td>
								<td style="text-align:center"><?=$delivery_time?></td>
							</tr>

						<?php 
							$idx++;
						} else{
						?>
					<tr style="background-color:red;height:30px">
						<td>&nbsp;</td>
						<td style="text-align:left"><?=$no?></td>
						<td style="text-align:left"><?=$city?></td>
						<td style="text-align:left"><?=$district?></td>
						<td style="text-align:left">
							Unable to guess, the district not found this row will not be processed, please fix the data first
						</td>
						<td style="text-align:center"><?=$unit_price?></td>
						<td style="text-align:center"><?=$next_unit_price?></td>
						<td style="text-align:center"><?=$delivery_time?></td>
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
				<input type="submit" id="process-button" style="height:50px;width:30%" value="Process Selected Data"/>
			</div>

		</form>
	</div>
</div>

<script type="text/javascript" src="<?=base_url()?>resource/js/jquery-1.6.4.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>resource/js/main.js"></script>
<script type="text/javascript">
	$('#logistic-filter-ambigous').click(function(){
		$('tbody > tr.exact','#logistic-data').hide();
		$('tbody > tr.ambigous','#logistic-data').show();
	});
	$('#logistic-filter-all').click(function(){
		$('tbody > tr','#logistic-data').show();
	});
</script>

</body>
</html>