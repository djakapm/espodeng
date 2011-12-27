<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?= $site_name ?></title>
        <link rel="stylesheet" href="<?= base_url() ?>resource/css/main.css"/>	
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
                    <form method="post" action="<?= site_url('admin/process_uploaded_data') ?>" enctype="multipart/form-data">
                        <div style="padding:5px">
                            <label for="create_new_table">Create New Table</label><br/>
                            <?= form_checkbox("create_new_table", "1", true) ?>
                        </div>
                        <div style="padding:5px">
                            <?php if (!empty($origin_districts)) { ?>
                                <label>Origin Location</label><br/>
                                <?= form_dropdown('origin_district', $origin_districts) ?>
                            <?php } ?>
                        </div>
                        <div style="padding:5px">
                            <?php if (!empty($logistic_companies)) { ?>
                                <label>Logistic Company</label><br/>
                                <?= form_dropdown('logistic_company', $logistic_companies) ?>
                            <?php } ?>
                        </div>
                        <div style="padding:5px">
                            <?php if (!empty($logistic_service_types)) { ?>
                                <label>Logistic Service Type</label><br/>
                                <?= form_dropdown('logistic_service_type', $logistic_service_types) ?>
                            <?php } ?>
                        </div>
                        <!--
                            <div style="padding:5px">
                                <label for="show_preview">Show Preview Before Upload</label><br/>
                        <?= form_checkbox("show_preview", "1", true) ?>
                            </div>
                        -->
                        <div style="padding:5px">
                            <label for="upload-input">Upload CSV File</label><br/>
                            <input id="upload-input" name="upload-input" type="file" class="input" style="padding-left:5px;"/>
                        </div>
                        <div style="padding:5px">
                            <input type="submit" id="upload-button" style="height:50px;width:30%" value="Upload CSV File"/>
                        </div>
                    </form>
                </div>


            </div>
        </div>

    </body>
</html>