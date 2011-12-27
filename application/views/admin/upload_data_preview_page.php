<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?= $site_name ?></title>
        <link rel="stylesheet" href="<?= base_url() ?>resource/css/main.css"/>
        <link rel="stylesheet" href="<?= base_url() ?>resource/css/admin.css"/>	
        <link rel="stylesheet" href="<?= base_url() ?>resource/css/jquery-ui-1.8.9.custom.css"/>	
    </head>
    <body>
        <div id="top-fixed-container">
            <div id="top-fixed-section-one">
                <div id="main-menu-container">
                    <ul id="main-menu">
                        <li><a href="<?= site_url('admin/landing') ?>">Home</a></li>
                        <li><a href="<?= site_url('admin/rebuild_reference_data_landing') ?>">Rebuild Reference Data</a></li>
                        <li><a href="<?= site_url('admin/rebuild_location_landing') ?>">Rebuild Locations</a></li>
                        <li><a href="<?= site_url('admin/upload_data') ?>">Upload Data</a></li>
                        <li><a href="<?= site_url('admin/setting') ?>">Settings</a></li>
                        <li><a href="<?= site_url('admin/login') ?>">Logout</a></li>
                    </ul>
                </div>
                <div id="copyright-container">
                    <span>copyright &copy <?= $site_name ?> 2011</span>
                </div>
            </div>
            <div id="site-title-container">
                <div style="float:left">
                    <img alt="palingoke.info logo" src="<?= base_url() ?>resource/img/logo_.png" width="296" height="79"/> 
                    <!--h1><a href="#" style="text-decoration:none"-->
                    <?php //echo $site_title ?>
                    <!--/a></h1-->
                </div>
                <div id="info" style="float:right"></div>		
            </div>
        </div>
        
        
        <div id="container" style="width:75%;margin:auto">
            
            <? 
            $rows = $query->result(); 
            if (!empty($rows)) {
            ?>
            
            <div style="padding:5px">
                <div class="block">
                    <h3>Preview Uploaded Data</h3>
                </div>
            </div>


            <div style="padding:5px">
                <div class="block">

                    <form method="post" action="<?= site_url('admin/update_selected_data') ?>">

                        <div style="padding:5px">
                            <label for="logistic-filter-all">All District <?= (empty($all_district_count) ? '' : '(' . $all_district_count . ')') ?></label>
                            <label for="logistic-filter-ambigous">Ambigous Districts <?= (empty($ambigous_district_count) ? '' : '(' . $ambigous_district_count . ')') ?> and Unguessed Districts <?= (empty($unguessed_district_count) ? '' : '(' . $unguessed_district_count . ')') ?></label>

                        </div>
                        <div style="padding:5px">
                            <input type="submit" id="process-button" style="height:50px;width:30%" value="Process Selected Data"/>
                        </div>			
                        <div style="padding:5px">
                            
                            
                            <?= $this->pagination->create_links() ?>
                            <table id="logistic-data" class="data" style="width:940px;table-layout:fixed">
                                <thead>
<!--                                <col width="5%">-->
                                <tr style="height:50px">
<!--                                    <th><?= form_checkbox('check_all', '') ?></th>-->
                                    <th width="30px">No.</th>
                                    <th width="180px">City</th>
                                    <th width="180px">Location</th>
                                    <th width="230px">Guessed Location</th>				
                                    <th title="Unit Price">UP(Rp)</th>
                                    <th title="Next Unit Price">NUP(Rp)</th>
                                    <th title="Delivery Time">DT(Hari)</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($rows as $row) {
                                        $class = 'exact';
                                        if ($row->lookup_status == 2) {
                                            $class = 'ambigous';
                                        } else if ($row->lookup_status == 0) {
                                            $class = 'nomatch';
                                        }
                                        ?>
                                        <tr class="<?=$class?>">
<!--                                            <td><?= form_checkbox("selected_data[$row->id]", $row->id, true) ?></td>-->
                                            <td><?= $no++ ?></td>
                                            <td><?= $row->city ?></td>
                                            <td><?= $row->district ?></td>
                                            <td>
                                                <?php
                                                    if ($row->lookup_status == 2) {
                                                        // show guessed location
                                                        $opts = array(-1=>'Pilih Lokasi');
                                                        $guessed_options = explode("#", $row->guessed_options);
                                                        foreach($guessed_options as $opt) {
                                                            if (!empty($opt)) {
                                                                $a = explode("=", $opt);
                                                                $opts[$opt]=$a[1];
                                                            }
                                                        }
                                                        echo form_dropdown("selection[$row->id]", $opts);
                                                    } else if ($row->lookup_status == 0) {
                                                        // NOT FOUND. Show suggestion
                                                        echo form_input("selection[$row->id]","",'class="location-input"');
                                                    } else if ($row->lookup_status == 1) {
                                                        // EXACT MATCH
                                                        echo $row->guessed_location_name;
                                                    }
                                                ?>
                                            </td>
                                            <td><?= $row->price_per_kg ?></td>
                                            <td><?= $row->price_next_kg ?></td>
                                            <td><?= $row->delivery_time ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?= $this->pagination->create_links() ?>
                        </div>		
                        <div style="padding:5px">
                            <input type="submit" id="process-all-match" name="" style="height:50px;width:30%" value="Process Selected Data"/>
                        </div>
                    </form>
                </div>
            <? } else { ?>
                <p>Done Uploading</p>
            <? } ?>
            </div>

            <script type="text/javascript" src="<?=base_url()?>resource/js/jquery-1.6.4.min.js"></script>
            <script type="text/javascript" src="<?=base_url()?>resource/js/jquery.jsonSuggest-2.min.mod.js"></script>
            <script type="text/javascript" src="<?=base_url()?>resource/js/jquery.currency.min.js"></script>
            <script type="text/javascript" src="<?=base_url()?>resource/js/admin.js"></script>

    </body>
</html>