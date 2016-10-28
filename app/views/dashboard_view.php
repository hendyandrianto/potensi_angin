<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Lokasi Potensi Energi Angin</title>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/css/jquery-ui.min.css">
    <link href="<?php echo base_url();?>vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="<?php echo base_url();?>vendor/morrisjs/morris.css" rel="stylesheet">
    <link href="<?php echo base_url();?>vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url();?>vendor/font-awesome/css/huruf-awesome.min.css" rel="stylesheet" type="text/css">
    <script src="<?php echo base_url();?>vendor/jquery/jquery.js"></script>
    <script src="<?php echo base_url();?>vendor/jquery/jquery-ui.min.js"></script>
    <script src="<?php echo base_url();?>vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>vendor/metisMenu/metisMenu.min.js"></script>
    <script src="<?php echo base_url();?>dist/js/sb-admin-2.js"></script>
    <script src="<?php echo base_url();?>js/bootbox.min.js"></script>
    <script src="<?php echo base_url();?>js/jquery.blockUI.js"></script>
    <script src="<?php echo base_url();?>js/app.js"></script>
    
</head>

<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo base_url();?>">Potensi Angin</a>
            </div>
            <?php
            $this->load->view('menu_atas');
            $this->load->view('menu_samping');
            ?>
        </nav>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $halaman;?></h1>
                </div>
            </div>
            <?php
            $this->load->view($content);
            ?>
        </div>
    </div>
    <div style="text-align:center">
        &copy;&nbsp;2016 - CODER[01]
    </div>
</body>
</html>
