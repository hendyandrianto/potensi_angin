<script type="text/javascript" src="<?php echo base_url();?>highcharts/highcharts.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>highcharts/themes/skies.js"></script>
<script type="text/javascript">
    function get_data() {
        var tgl = jQuery("#tanggal").val();
        if(tgl!=""){
            jQuery.ajax({
                url:'<?php echo base_url(); ?>daerah/get_cuaca/'+tgl,         
                type:'POST',
                success:function(data){ 
                    $("#div_cuaca").html(data);
                },
                beforeSend:function(){
                    $("#div_cuaca").html("<img src='<?php echo base_url();?>images/loading_spinner.gif'>");
                }
            });     
        }else{
            alert("Pastikan Tanggal Sudah Terpilih");
        }
    }
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
            categories: ['01:00', '02:00', '03:00', '04:00', '05:00', '06:00',
                    '07:00', '08:00', '09:00', '10:00', '11:00', '12:00',
                    '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00',
                    '20:00', '21:00', '22:00', '22:00', '23:00', '24:00']
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
            categories: ['01:00', '02:00', '03:00', '04:00', '05:00', '06:00',
                    '07:00', '08:00', '09:00', '10:00', '11:00', '12:00',
                    '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00',
                    '20:00', '21:00', '22:00', '22:00', '23:00', '24:00']
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
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Informasi Daerah <?php echo $daerah;?>
            </div>
            <div class="panel-body">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#lokasi" data-toggle="tab">Info Lokasi & Grafik</a>
                    </li>
                    <li><a href="#perjam" data-toggle="tab">Data History</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="lokasi">
                        <h4>Informasi Lokasi</h4>
                        <head><?php echo $map['js']; ?></head>
                        <body><?php echo $map['html']; ?></body>
                        <div id="grafik-angin"></div>
                        <div id="grafik-temp"></div>
                    </div>
                    <div class="tab-pane fade" id="perjam">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                    <br/>
                                <div class="panel-body">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#now" data-toggle="tab">Histori Hari Ini</a>
                                        </li>
                                        <li><a href="#search" data-toggle="tab">Pencarian Data</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade in active" id="now">
                                            <h4>Informasi Cuaca Perjam Tanggal : <?php echo date("d-m-Y");?></h4>
                                            <br/>
                                            <?php
                                            date_default_timezone_set('Asia/Jakarta');
                                            $now = date("Y-m-d");
                                            $datajam1 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '1' GROUP BY id ORDER BY waktu DESC");
                                            if(count($datajam1->result())>0){
                                                $key = $datajam1->row();
                                                echo "<br>";
                                                echo "<b>1." . "&nbsp; Jam : 01:00:00</b>";
                                                echo "<br>";
                                                echo "Cuaca  <b>".$key->cuaca.' </b><br>';
                                                echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
                                                echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
                                                echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
                                                echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
                                                echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
                                                echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
                                                echo "<img src='".base_url()."images/icon/$key->foto'>";
                                            }else{
                                                echo "<h5>1. Data Tanggal Tersebut Pada Jam 01:00:00 Tidak Ditemukan</h5>";
                                            }
                                            $datajam2 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '2' GROUP BY id ORDER BY waktu DESC");
                                            if(count($datajam2->result())>0){
                                                $key = $datajam2->row();
                                                echo "<br>";
                                                echo "<b>2." . "&nbsp; Jam : 02:00:00</b>";
                                                echo "<br>";
                                                echo "Cuaca  <b>".$key->cuaca.' </b><br>';
                                                echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
                                                echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
                                                echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
                                                echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
                                                echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
                                                echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
                                                echo "<img src='".base_url()."images/icon/$key->foto'>";
                                            }else{
                                                echo "<h5>2. Data Tanggal Tersebut Pada Jam 02:00:00 Tidak Ditemukan</h5>";
                                            }
                                            $datajam3 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '3' GROUP BY id ORDER BY waktu DESC");
                                            if(count($datajam3->result())>0){
                                                $key = $datajam3->row();
                                                echo "<br>";
                                                echo "<b>3." . "&nbsp; Jam : 03:00:00</b>";
                                                echo "<br>";
                                                echo "Cuaca  <b>".$key->cuaca.' </b><br>';
                                                echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
                                                echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
                                                echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
                                                echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
                                                echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
                                                echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
                                                echo "<img src='".base_url()."images/icon/$key->foto'>";
                                            }else{
                                                echo "<h5>3. Data Tanggal Tersebut Pada Jam 03:00:00 Tidak Ditemukan</h5>";
                                            }
                                            $datajam4 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '4' GROUP BY id ORDER BY waktu DESC");
                                            if(count($datajam4->result())>0){
                                                $key = $datajam4->row();
                                                echo "<br>";
                                                echo "<b>4." . "&nbsp; Jam : 04:00:00</b>";
                                                echo "<br>";
                                                echo "Cuaca  <b>".$key->cuaca.' </b><br>';
                                                echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
                                                echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
                                                echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
                                                echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
                                                echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
                                                echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
                                                echo "<img src='".base_url()."images/icon/$key->foto'>";
                                            }else{
                                                echo "<h5>4. Data Tanggal Tersebut Pada Jam 04:00:00 Tidak Ditemukan</h5>";
                                            }
                                            $datajam5 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '5' GROUP BY id ORDER BY waktu DESC");
                                            if(count($datajam5->result())>0){
                                                $key = $datajam5->row();
                                                echo "<br>";
                                                echo "<b>5." . "&nbsp; Jam : 05:00:00</b>";
                                                echo "<br>";
                                                echo "Cuaca  <b>".$key->cuaca.' </b><br>';
                                                echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
                                                echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
                                                echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
                                                echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
                                                echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
                                                echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
                                                echo "<img src='".base_url()."images/icon/$key->foto'>";
                                            }else{
                                                echo "<h5>5. Data Tanggal Tersebut Pada Jam 05:00:00 Tidak Ditemukan</h5>";
                                            }
                                            $datajam6 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '6' GROUP BY id ORDER BY waktu DESC");
                                            if(count($datajam6->result())>0){
                                                $key = $datajam6->row();
                                                echo "<br>";
                                                echo "<b>6." . "&nbsp; Jam : 06:00:00</b>";
                                                echo "<br>";
                                                echo "Cuaca  <b>".$key->cuaca.' </b><br>';
                                                echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
                                                echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
                                                echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
                                                echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
                                                echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
                                                echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
                                                echo "<img src='".base_url()."images/icon/$key->foto'>";
                                            }else{
                                                echo "<h5>6. Data Tanggal Tersebut Pada Jam 06:00:00 Tidak Ditemukan</h5>";
                                            }
                                            $datajam7 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '7' GROUP BY id ORDER BY waktu DESC");
                                            if(count($datajam7->result())>0){
                                                $key = $datajam7->row();
                                                echo "<br>";
                                                echo "<b>7." . "&nbsp; Jam : 07:00:00</b>";
                                                echo "<br>";
                                                echo "Cuaca  <b>".$key->cuaca.' </b><br>';
                                                echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
                                                echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
                                                echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
                                                echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
                                                echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
                                                echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
                                                echo "<img src='".base_url()."images/icon/$key->foto'>";
                                            }else{
                                                echo "<h5>7. Data Tanggal Tersebut Pada Jam 07:00:00 Tidak Ditemukan</h5>";
                                            }
                                            $datajam8 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '8' GROUP BY id ORDER BY waktu DESC");
                                            if(count($datajam8->result())>0){
                                                $key = $datajam8->row();
                                                echo "<br>";
                                                echo "<b>8." . "&nbsp; Jam : 08:00:00</b>";
                                                echo "<br>";
                                                echo "Cuaca  <b>".$key->cuaca.' </b><br>';
                                                echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
                                                echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
                                                echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
                                                echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
                                                echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
                                                echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
                                                echo "<img src='".base_url()."images/icon/$key->foto'>";
                                            }else{
                                                echo "<h5>8. Data Tanggal Tersebut Pada Jam 08:00:00 Tidak Ditemukan</h5>";
                                            }
                                            $datajam9 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '9' GROUP BY id ORDER BY waktu DESC");
                                            if(count($datajam9->result())>0){
                                                $key = $datajam9->row();
                                                echo "<br>";
                                                echo "<b>9." . "&nbsp; Jam : 09:00:00</b>";
                                                echo "<br>";
                                                echo "Cuaca  <b>".$key->cuaca.' </b><br>';
                                                echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
                                                echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
                                                echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
                                                echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
                                                echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
                                                echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
                                                echo "<img src='".base_url()."images/icon/$key->foto'>";
                                            }else{
                                                echo "<h5>9. Data Tanggal Tersebut Pada Jam 09:00:00 Tidak Ditemukan</h5>";
                                            }
                                            $datajam10 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '10' GROUP BY id ORDER BY waktu DESC");
                                            if(count($datajam10->result())>0){
                                                $key = $datajam10->row();
                                                echo "<br>";
                                                echo "<b>10." . "&nbsp; Jam : 10:00:00</b>";
                                                echo "<br>";
                                                echo "Cuaca  <b>".$key->cuaca.' </b><br>';
                                                echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
                                                echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
                                                echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
                                                echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
                                                echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
                                                echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
                                                echo "<img src='".base_url()."images/icon/$key->foto'>";
                                            }else{
                                                echo "<h5>10. Data Tanggal Tersebut Pada Jam 10:00:00 Tidak Ditemukan</h5>";
                                            }
                                            $datajam11 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '11' GROUP BY id ORDER BY waktu DESC");
                                            if(count($datajam11->result())>0){
                                                $key = $datajam11->row();
                                                echo "<br>";
                                                echo "<b>11." . "&nbsp; Jam : 111:00:00</b>";
                                                echo "<br>";
                                                echo "Cuaca  <b>".$key->cuaca.' </b><br>';
                                                echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
                                                echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
                                                echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
                                                echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
                                                echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
                                                echo "<img src='".base_url()."images/icon/$key->foto'>";
                                            }else{
                                                echo "<h5>11. Data Tanggal Tersebut Pada Jam 11:00:00 Tidak Ditemukan</h5>";
                                            }
                                            $datajam12 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '12' GROUP BY id ORDER BY waktu DESC");
                                            if(count($datajam12->result())>0){
                                                $key = $datajam12->row();
                                                echo "<br>";
                                                echo "<b>12." . "&nbsp; Jam : 12:00:00</b>";
                                                echo "<br>";
                                                echo "Cuaca  <b>".$key->cuaca.' </b><br>';
                                                echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
                                                echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
                                                echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
                                                echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
                                                echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
                                                echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
                                                echo "<img src='".base_url()."images/icon/$key->foto'>";
                                            }else{
                                                echo "<h5>12. Data Tanggal Tersebut Pada Jam 12:00:00 Tidak Ditemukan</h5>";
                                            }
                                            $datajam13 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '13' GROUP BY id ORDER BY waktu DESC");
                                            if(count($datajam13->result())>0){
                                                $key = $datajam13->row();
                                                echo "<br>";
                                                echo "<b>13." . "&nbsp; Jam : 13:00:00</b>";
                                                echo "<br>";
                                                echo "Cuaca  <b>".$key->cuaca.' </b><br>';
                                                echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
                                                echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
                                                echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
                                                echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
                                                echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
                                                echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
                                                echo "<img src='".base_url()."images/icon/$key->foto'>";
                                            }else{
                                                echo "<h5>13. Data Tanggal Tersebut Pada Jam 13:00:00 Tidak Ditemukan</h5>";
                                            }
                                            $datajam14 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '14' GROUP BY id ORDER BY waktu DESC");
                                            if(count($datajam14->result())>0){
                                                $key = $datajam14->row();
                                                echo "<br>";
                                                echo "<b>14." . "&nbsp; Jam : 14:00:00</b>";
                                                echo "<br>";
                                                echo "Cuaca  <b>".$key->cuaca.' </b><br>';
                                                echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
                                                echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
                                                echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
                                                echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
                                                echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
                                                echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
                                                echo "<img src='".base_url()."images/icon/$key->foto'>";
                                            }else{
                                                echo "<h5>14. Data Tanggal Tersebut Pada Jam 14:00:00 Tidak Ditemukan</h5>";
                                            }
                                            $datajam15 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '15' GROUP BY id ORDER BY waktu DESC");
                                            if(count($datajam15->result())>0){
                                                $key = $datajam15->row();
                                                echo "<br>";
                                                echo "<b>15." . "&nbsp; Jam : 15:00:00</b>";
                                                echo "<br>";
                                                echo "Cuaca  <b>".$key->cuaca.' </b><br>';
                                                echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
                                                echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
                                                echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
                                                echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
                                                echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
                                                echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
                                                echo "<img src='".base_url()."images/icon/$key->foto'>";
                                            }else{
                                                echo "<h5>15. Data Tanggal Tersebut Pada Jam 15:00:00 Tidak Ditemukan</h5>";
                                            }
                                            $datajam16 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '16' GROUP BY id ORDER BY waktu DESC");
                                            if(count($datajam16->result())>0){
                                                $key = $datajam16->row();
                                                echo "<br>";
                                                echo "<b>16." . "&nbsp; Jam : 16:00:00</b>";
                                                echo "<br>";
                                                echo "Cuaca  <b>".$key->cuaca.' </b><br>';
                                                echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
                                                echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
                                                echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
                                                echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
                                                echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
                                                echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
                                                echo "<img src='".base_url()."images/icon/$key->foto'>";
                                            }else{
                                                echo "<h5>16. Data Tanggal Tersebut Pada Jam 16:00:00 Tidak Ditemukan</h5>";
                                            }
                                            $datajam17 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '17' GROUP BY id ORDER BY waktu DESC");
                                            if(count($datajam17->result())>0){
                                                $key = $datajam17->row();
                                                echo "<br>";
                                                echo "<b>17." . "&nbsp; Jam : 17:00:00</b>";
                                                echo "<br>";
                                                echo "Cuaca  <b>".$key->cuaca.' </b><br>';
                                                echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
                                                echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
                                                echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
                                                echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
                                                echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
                                                echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
                                                echo "<img src='".base_url()."images/icon/$key->foto'>";
                                            }else{
                                                echo "<h5>17. Data Tanggal Tersebut Pada Jam 17:00:00 Tidak Ditemukan</h5>";
                                            }
                                            $datajam18 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '18' GROUP BY id ORDER BY waktu DESC");
                                            if(count($datajam18->result())>0){
                                                $key = $datajam18->row();
                                                echo "<br>";
                                                echo "<b>18." . "&nbsp; Jam : 18:00:00</b>";
                                                echo "<br>";
                                                echo "Cuaca  <b>".$key->cuaca.' </b><br>';
                                                echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
                                                echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
                                                echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
                                                echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
                                                echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
                                                echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
                                                echo "<img src='".base_url()."images/icon/$key->foto'>";
                                            }else{
                                                echo "<h5>18. Data Tanggal Tersebut Pada Jam 18:00:00 Tidak Ditemukan</h5>";
                                            }
                                            $datajam19 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '19' GROUP BY id ORDER BY waktu DESC");
                                            if(count($datajam19->result())>0){
                                                $key = $datajam19->row();
                                                echo "<br>";
                                                echo "<b>19." . "&nbsp; Jam : 19:00:00</b>";
                                                echo "<br>";
                                                echo "Cuaca  <b>".$key->cuaca.' </b><br>';
                                                echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
                                                echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
                                                echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
                                                echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
                                                echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
                                                echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
                                                echo "<img src='".base_url()."images/icon/$key->foto'>";
                                            }else{
                                                echo "<h5>19. Data Tanggal Tersebut Pada Jam 19:00:00 Tidak Ditemukan</h5>";
                                            }
                                            $datajam20 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '20' GROUP BY id ORDER BY waktu DESC");
                                            if(count($datajam20->result())>0){
                                                $key = $datajam20->row();
                                                echo "<br>";
                                                echo "<b>20." . "&nbsp; Jam : 20:00:00</b>";
                                                echo "<br>";
                                                echo "Cuaca  <b>".$key->cuaca.' </b><br>';
                                                echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
                                                echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
                                                echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
                                                echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
                                                echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
                                                echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
                                                echo "<img src='".base_url()."images/icon/$key->foto'>";
                                            }else{
                                                echo "<h5>20. Data Tanggal Tersebut Pada Jam 20:00:00 Tidak Ditemukan</h5>";
                                            }
                                            $datajam21 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '21' GROUP BY id ORDER BY waktu DESC");
                                            if(count($datajam21->result())>0){
                                                $key = $datajam21->row();
                                                echo "<br>";
                                                echo "<b>21." . "&nbsp; Jam : 21:00:00</b>";
                                                echo "<br>";
                                                echo "Cuaca  <b>".$key->cuaca.' </b><br>';
                                                echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
                                                echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
                                                echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
                                                echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
                                                echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
                                                echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
                                                echo "<img src='".base_url()."images/icon/$key->foto'>";
                                            }else{
                                                echo "<h5>21. Data Tanggal Tersebut Pada Jam 21:00:00 Tidak Ditemukan</h5>";
                                            }
                                            $datajam22 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '22' GROUP BY id ORDER BY waktu DESC");
                                            if(count($datajam22->result())>0){
                                                $key = $datajam22->row();
                                                echo "<br>";
                                                echo "<b>22." . "&nbsp; Jam : 22:00:00</b>";
                                                echo "<br>";
                                                echo "Cuaca  <b>".$key->cuaca.' </b><br>';
                                                echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
                                                echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
                                                echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
                                                echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
                                                echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
                                                echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
                                                echo "<img src='".base_url()."images/icon/$key->foto'>";
                                            }else{
                                                echo "<h5>22. Data Tanggal Tersebut Pada Jam 22:00:00 Tidak Ditemukan</h5>";
                                            }
                                            $datajam23 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '23' GROUP BY id ORDER BY waktu DESC");
                                            if(count($datajam23->result())>0){
                                                $key = $datajam23->row();
                                                echo "<br>";
                                                echo "<b>23." . "&nbsp; Jam : 23:00:00</b>";
                                                echo "<br>";
                                                echo "Cuaca  <b>".$key->cuaca.' </b><br>';
                                                echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
                                                echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
                                                echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
                                                echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
                                                echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
                                                echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
                                                echo "<img src='".base_url()."images/icon/$key->foto'>";
                                            }else{
                                                echo "<h5>23. Data Tanggal Tersebut Pada Jam 23:00:00 Tidak Ditemukan</h5>";
                                            }
                                            $datajam24 = $this->db->query("SELECT * FROM view_cuaca WHERE tanggal = '$now' AND id = '$id' AND HOUR(waktu) = '24' GROUP BY id ORDER BY waktu DESC");
                                            if(count($datajam24->result())>0){
                                                $key = $datajam24->row();
                                                echo "<br>";
                                                echo "<b>24." . "&nbsp; Jam : 24:00:00</b>";
                                                echo "<br>";
                                                echo "Cuaca  <b>".$key->cuaca.' </b><br>';
                                                echo "Temperatur .<b>".$key->temperatur .'° </b><br>';
                                                echo "Temperatur Maksimal .<b>".$key->temp_max .'° </b><br>';
                                                echo "Temperatur Minimal .<b>".$key->temp_min .'° </b><br>';
                                                echo "Tekanan Angin  .<b>".$key->tekanan_angin .'° </b><br>';
                                                echo "Kecapatan Angin  .<b>".$key->kecepatan_angin .'° </b><br>';  
                                                echo "Kelembaban  .<b>".$key->kelembaban .'° </b><br>';  
                                                echo "<img src='".base_url()."images/icon/$key->foto'>";
                                            }else{
                                                echo "<h5>24. Data Tanggal Tersebut Pada Jam 24:00:00 Tidak Ditemukan</h5>";
                                            }
                                            ?>
                                        </div>
                                        <div class="tab-pane fade" id="search">
                                            <h4>Pencarian Data</h4>
                                            <div class="form-group">
                                                <label>Tanggal</label>
                                                <select onchange="return get_data()" id="tanggal" name="tanggal" class="form-control">
                                                <option value="">Pilih Tanggal</option>
                                                <?php
                                                $tanggal = $this->db->query("SELECT tanggal FROM view_cuaca GROUP BY tanggal")->result();
                                                if(count($tanggal)>0){
                                                    foreach ($tanggal as $key) {
                                                        ?>
                                                            <option value="<?php echo date("Y-m-d",strtotime($key->tanggal));?>"><?php echo date("d-m-Y",strtotime($key->tanggal));?></option>
                                                        <?php
                                                    }
                                                }else{
                                                    ?>
                                                    <option>Tidak Ada Record</option>
                                                    <?php
                                                }
                                                ?>
                                                </select>
                                            </div>
                                            <div id="div_cuaca"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
</div>
