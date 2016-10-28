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
<script type="text/javascript" src="<?php echo base_url();?>highcharts/highcharts.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>highcharts/themes/skies.js"></script>
<script type="text/javascript">
    jQuery(function(){
    new Highcharts.Chart({
        chart: {
            renderTo: 'grafik-angin',
            type: 'line',
        },
        title: {
            text: 'Grafik Kecepatan Angin',
            x: -20
        },
        subtitle: {
            text: '',
            x: -20
        },
        xAxis: {
            categories: ['01', '02', '03', '04', '05', '06',
                    '07', '08', '09', '10', '11', '12',
                    '13', '14', '15', '16', '17', '18', '19',
                    '20', '21', '22', '22', '23', '00']
        },
        yAxis: {
            title: {
                text: 'Kecepatan Angin (m/s)'
            }
        },
        series: [{
            name: 'Kecepatan (m/s)',
            data: <?php echo json_encode($grafikkecepatanangin); ?>
        }]
    });
    new Highcharts.Chart({
        chart: {
            renderTo: 'grafik-temp',
            type: 'line',
        },
        title: {
            text: 'Grafik Temperatur & Kelembaban',
            x: -20
        },
        subtitle: {
            text: '',
            x: -20
        },
        xAxis: {
            categories: ['01', '02', '03', '04', '05', '06',
                    '07', '08', '09', '10', '11', '12',
                    '13', '14', '15', '16', '17', '18', '19',
                    '20', '21', '22', '22', '23', '00']
        },
        yAxis: {
            title: {
                text: 'Kecepatan Angin (m/s)'
            }
        },
        series: [{
            name: 'Temperatur (Co)',
            data: <?php echo json_encode($grafiktemperatur); ?>
        },{
            name: 'Kelembaban (%)',
            data: <?php echo json_encode($grafikkelembaban); ?>
        }]
    });
}); 
</script>
<style type="text/css">
	.body{
		background: blue;
	}
</style>
<div class="row">
    <div class="col-lg-12">
		<div id="grafik-angin"></div>
		<div id="grafik-temp"></div>
	</div>
</div>