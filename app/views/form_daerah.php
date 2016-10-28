<script src="https://maps.googleapis.com/maps/api/js?key=[API_KEY_GOOGLE_MAPS]]"></script>
<script type="text/javascript">
$(document).ready(function() {
    jQuery("#address").change(function(){
        var address = jQuery("#address").val();
        if(address != ""){
            jQuery("#carialamat").show('slow');
        }else{
            jQuery("#carialamat").hide('slow');
        }
    });
    jQuery("#bujur").change(function(){
        var bujur = jQuery("#bujur").val();
        var lintang = jQuery("#lintang").val();
        if(bujur != ""  && lintang != ""){
            jQuery("#carilatlong").show('slow');
        }else{
            jQuery("#carilatlong").hide('slow');            
        }
    });
    jQuery("#propinsi").change(function(){
        var propinsi = jQuery("#propinsi").val();
        if(propinsi!=""){
            jQuery.blockUI({
                css: { 
                    border: 'none', 
                    padding: '15px', 
                    backgroundColor: '#000', 
                    '-webkit-border-radius': '10px', 
                    '-moz-border-radius': '10px', 
                    opacity: 0.5, 
                    color: '#fff' 
                },
                message : 'Sedang Melakukan Pengecekan Data, Mohon menunggu ... '
            });
            setTimeout(function(){
                jQuery("#kota").load ($BASE_URL+'daerah/getKabkot/'+propinsi);
                jQuery("#kecamatan").val("");
                jQuery("#desa").val("");
                jQuery.unblockUI();
            },500);
        }else{
            jQuery("#kota").val("");
            return false;
        }
    });
    jQuery("#kota").change(function(){
        var kota = jQuery("#kota").val();
        var propinsi = jQuery("#propinsi").val();
        if(kota!=""){
            jQuery.blockUI({
                css: { 
                    border: 'none', 
                    padding: '15px', 
                    backgroundColor: '#000', 
                    '-webkit-border-radius': '10px', 
                    '-moz-border-radius': '10px', 
                    opacity: 0.5, 
                    color: '#fff' 
                },
                message : 'Sedang Melakukan Pengecekan Data, Mohon menunggu ... '
            });
            setTimeout(function(){
                jQuery("#kecamatan").load ($BASE_URL+'daerah/getKec/'+kota);
                jQuery("#kecamatan").val("");
                jQuery.unblockUI();
            },500);
        }else{
            jQuery("#kecamatan").val("");
            return false;
        }
    });
    jQuery("#kecamatan").change(function(){
        var kec = jQuery("#kecamatan").val();
        if(kec!=""){
            jQuery.blockUI({
                css: { 
                    border: 'none', 
                    padding: '15px', 
                    backgroundColor: '#000', 
                    '-webkit-border-radius': '10px', 
                    '-moz-border-radius': '10px', 
                    opacity: 0.5, 
                    color: '#fff' 
                },
                message : 'Sedang Melakukan Pengecekan Data, Mohon menunggu ... '
            });
            setTimeout(function(){
                jQuery("#desa").load ($BASE_URL+'daerah/getKel/'+kec);
                jQuery("#desa").val("");
                jQuery.unblockUI();
            },500);
        }else{
            jQuery("#desa").val("");
            return false;
        }
    });
});
</script>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-inverse">
            <div class="panel-body">
                <div class="table-responsive">
                <?php
                if($cek=='edit'){
                    ?>
                <form action="<?php echo base_url();?>daerah/proses_edit" enctype="multipart/form-data" method="POST" data-parsley-validate="true" name="form-wizard">
                    <?php
                }else{
                    ?>
                <form action="<?php echo base_url();?>daerah/proses_add" enctype="multipart/form-data" method="POST" data-parsley-validate="true" name="form-wizard">
                    <?php
                }
                ?>
                    <div id="wizard">
                        <div class="wizard-step-1">
                            <fieldset>
                                <legend class="pull-left width-full"><h4>Info Lokasi Berpotensi Energi Angin</h4></legend>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Provinsi * :</label>
                                        <div class="col-md-3 col-sm-3">
                                            <?php echo form_dropdown('propinsi',$option_propinsi,isset($default['propinsi']) ? $default['propinsi'] : '','id="propinsi" data-size="10" data-parsley-required="true" data-live-search="true" data-style="btn-white" class="form-control selectpicker"');?>
                                            <span style="color:red;"><?php echo form_error('propinsi');?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Kabupaten Kota * :</label>
                                        <div class="col-md-3 col-sm-3">
                                            <?php echo form_dropdown('kota',$option_kota,isset($default['kota']) ? $default['kota'] : '','class="default-select2 form-control" id="kota" data-size="10" data-parsley-required="true" data-live-search="true" data-style="btn-white"');?>
                                            <span style="color:red;"><?php echo form_error('kota');?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Kecamatan * :</label>
                                        <div class="col-md-3 col-sm-3">
                                            <?php echo form_dropdown('kecamatan',$option_kecamatan,isset($default['kecamatan']) ? $default['kecamatan'] : '','class="default-select2 form-control" id="kecamatan" data-size="10" data-parsley-required="true" data-live-search="true" data-style="btn-white"');?>
                                            <span style="color:red;"><?php echo form_error('kecamatan');?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Desa / Kelurahan * :</label>
                                        <div class="col-md-3 col-sm-3">
                                            <?php echo form_dropdown('desa',$option_desa,isset($default['desa']) ? $default['desa'] : '','class="default-select2 form-control" id="desa" data-size="10" data-parsley-required="true" data-live-search="true" data-style="btn-white"');?>
                                            <span style="color:red;"><?php echo form_error('desa');?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Nama Daerah</label>
                                            <input type="text" name="daerah" id="daerah" value="<?php echo set_value('daerah',isset($default['daerah']) ? $default['daerah'] : ''); ?>" placeholder="Masukan Nama Daerah / Tempat" class="form-control"  />
                                            <span style="color:red;"><?php echo form_error('daerah');?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Alamat</label>
                                            <input type="text" name="address" id="address" placeholder="Pencarian Berdasarkan Alamat" class="form-control"  />
                                        </div>
                                    </div>
                                    <div id="carialamat">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input type="button" class="btn btn-primary btn-xs m-r-5" value="Cari Alamat" onClick="codeAlamat()"><br>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Batas Lintang</label>
                                            <input type="text" name="lintang" placeholder="Pencarian Berdasarkan Lintang" id="lintang" class="form-control"  />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Batas Bujur</label>
                                            <input type="text" name="bujur"  placeholder="Pencarian Berdasarkan Bujur" id="bujur" class="form-control"  />
                                        </div>
                                    </div>
                                    <div id="carilatlong">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="button" class="btn btn-primary btn-xs m-r-5" value="Cari Lokasi" onClick="GotoLatLng()"><br>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div id='map_canvas'></div>
                                        </div>
                                    </div>
                                </div>
                                <legend class="pull-left width-full"><h4>Output Lokasi Berpotensi Energi Angin</h4></legend>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Batas Lintang</label>
                                            <input type="text" name="lintangO" value="<?php echo set_value('lintangO',isset($default['lintangO']) ? $default['lintangO'] : ''); ?>" id="lintangO" class="form-control"  data-parsley-required="true" data-parsley-minlength="1"/>
                                            <span style="color:red;"><small><?php echo form_error('lintangO');?></small></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Batas Bujur</label>
                                            <input type="text" name="bujurO" id="bujurO" value="<?php echo set_value('bujurO',isset($default['bujurO']) ? $default['bujurO'] : ''); ?>" class="form-control" data-parsley-required="true" data-parsley-minlength="1"/>
                                            <span style="color:red;"><small><?php echo form_error('bujurO');?></small></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Elevasi</label>
                                            <input type="text" name="elevasi" id="elevasi" title="klik di area yang dicari untuk mendapatkan ketinggian tempat (di atas permukaan laut)" class="form-control"  />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Alamat Lokasi</label>
                                            <input type="text" name="alamatbaru" value="<?php echo set_value('alamatbaru',isset($default['alamatbaru']) ? $default['alamatbaru'] : ''); ?>" id="alamatbaru" class="form-control"  data-parsley-required="true" data-parsley-minlength="1"/>
                                            <span style="color:red;"><small><?php echo form_error('alamatbaru');?></small></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="jumbotron m-b-0 text-center">
                                    <h4>Data Sudah Terisi Dengan Benar ?</h4>
                                    <p><button type="submit" class="btn btn-success">Simpan Data</button></p>
                                </div>
                            </fieldset>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
if($cek=='edit'){
    ?>
<script type="text/javascript">
    initialize();
    var geocoder;
    var map;
    var elevator;
    var infowindow = new google.maps.InfoWindow();
    jQuery("#carialamat").hide();
    jQuery("#carilatlong").hide();
    function sendData(){
        var lintang = jQuery("#lintangO").val();
        var bujur = jQuery("#bujurO").val();
        var alamatbaru = jQuery("#alamatbaru").val();
        jQuery.ajax({
            url : $BASE_URL+'daerah/update_lokasi',
            data : {lintang:lintang,bujur:bujur,alamatbaru:alamatbaru},
            type : 'POST',
            dataType: 'json',
            success:function(data){
                if(data.response=='true'){
                    window.location = self.location;
                }else{
                    $.gritter.add({title:"Informasi !",text: " Data Gagal Di Update !"});return false;
                }
            }
        });
    }
    function initialize() {
        var lat = jQuery("#lintangO").val();
        var longitude = jQuery("#bujurO").val();
        geocoder = new google.maps.Geocoder();
        var LatLng = new google.maps.LatLng(lat, longitude);
        var myOptions = {
            zoom: 15,
            center: LatLng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);
        marker = new google.maps.Marker({
            position: LatLng,
            title: 'Lokasi daerah',
            map: map,
            draggable: true
        });
        updateMarkerPosition(LatLng);
        geocodePosition(LatLng);
        aksi();
        elevator = new google.maps.ElevationService();
        google.maps.event.addListener(map, 'click', getElevation);
    }
    function aksi(){
        google.maps.event.addListener(marker, 'dragstart', function(){
            updateMarkerAddress('Bergeser...');
        });
        google.maps.event.addListener(marker, 'drag', function(){
            updateMarkerPosition(marker.getPosition());
        });
        google.maps.event.addListener(marker, 'dragend', function(){
            geocodePosition(marker.getPosition());
        });
    }
    function getElevation(event){
        var locations = [];
        var clickedLocation = event.latLng;
        locations.push(clickedLocation);
        var positionalRequest = {
            'locations': locations
        }
        elevator.getElevationForLocations(positionalRequest, function(results, status){
            if(status == google.maps.ElevationStatus.OK){
                if(results[0]){
                    hasil = results[0].elevation;
                    hasil2 = Math.floor((hasil - Math.floor(hasil)) * 100);
                    if(hasil2 < 50){
                        hasil = Math.floor(hasil);
                    }else{
                        hasil = Math.floor(hasil) + 1;
                    }
                    infowindow.setContent('Ketinggian lokasi ini sekitar<br>' + hasil + ' meter  di atas permukaan laut.');
                    infowindow.setPosition(clickedLocation);
                    infowindow.open(map);
                    document.getElementById("elevasi").value = results[0].elevation;
                }else{
                    alert('Hasil tidak ditemukan');
                }
            }else{
                alert('Layanan elevasi gagal, karena: ' + status);
            }
        });
    }
    function codeAlamat(){
        var address = document.getElementById("address").value;
        geocoder.geocode({ 'address': address}, function(results, status){
            if(status == google.maps.GeocoderStatus.OK){
                map.setCenter(results[0].geometry.location);
                if(marker != undefined)
                    marker.setPosition(results[0].geometry.location);
                else
                    marker = new google.maps.Marker({
                        map: map,
                        draggable: true,
                        position: results[0].geometry.location
                    });
                updateMarkerPosition(results[0].geometry.location);
                geocodePosition(results[0].geometry.location);
                aksi();
            }else{
                alert("Geocode tidak berhasil dengan alasan karena: " + status);
            }
        });
    }
    function GotoLatLng(){
        var lat = document.getElementById("lintang").value;
        var lang = document.getElementById("bujur").value;
        LatLng = new google.maps.LatLng(lat,lang);
        map.setCenter(LatLng);
        if(marker != undefined)
              marker.setPosition(LatLng);
         else
              marker = new google.maps.Marker({
                    map: map,
                    draggable: true,
                position: LatLng,
              });
        updateMarkerPosition(LatLng);
        geocodePosition(LatLng);
        aksi();
    }
    function geocodePosition(pos){
        geocoder.geocode({latLng: pos},
        function(responses){
            if(responses && responses.length > 0){
                updateMarkerAddress(responses[0].formatted_address);
            }else{
                updateMarkerAddress('Maaf, sistem tidak bisa mengenali alamat di lokasi ini.');
            }
        });
    }
    function updateMarkerStatus(str){
        document.getElementById('markerStatus').innerHTML = str;
    }
    function updateMarkerPosition(LatLng){
        document.getElementById("lintangO").value = [ LatLng.lat('') ];
        document.getElementById("bujurO").value = [ LatLng.lng('') ];
    }
    function updateMarkerAddress(str){
        document.getElementById("alamatbaru").value = str;
    }
    google.maps.event.addDomListener(window, 'load', initialize);
</script>
<style type="text/css">
    #map_canvas{
        margin: 0;
        width: 100%;
        height: 600px;
    }
</style>
    <?php
}else{
    ?>
<script type="text/javascript">
    initialize();
    var geocoder;
    var map;
    var elevator;
    var infowindow = new google.maps.InfoWindow();
    jQuery("#carialamat").hide();
    jQuery("#carilatlong").hide();
    function sendData(){
        var lintang = jQuery("#lintangO").val();
        var bujur = jQuery("#bujurO").val();
        var alamatbaru = jQuery("#alamatbaru").val();
        jQuery.ajax({
            url : $BASE_URL+'daerah/update_lokasi',
            data : {lintang:lintang,bujur:bujur,alamatbaru:alamatbaru},
            type : 'POST',
            dataType: 'json',
            success:function(data){
                if(data.response=='true'){
                    window.location = self.location;
                }else{
                    $.gritter.add({title:"Informasi !",text: " Data Gagal Di Update !"});return false;
                }
            }
        });
    }
    function initialize() {
        geocoder = new google.maps.Geocoder();
        var LatLng = new google.maps.LatLng(-7.3246168188575655, 108.21276447721561);
        var myOptions = {
            zoom: 15,
            center: LatLng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);
        marker = new google.maps.Marker({
            position: LatLng,
            title: 'Lokasi daerah',
            map: map,
            draggable: true
        });
        updateMarkerPosition(LatLng);
        geocodePosition(LatLng);
        aksi();
        elevator = new google.maps.ElevationService();
        google.maps.event.addListener(map, 'click', getElevation);
    }
    function aksi(){
        google.maps.event.addListener(marker, 'dragstart', function(){
            updateMarkerAddress('Bergeser...');
        });
        google.maps.event.addListener(marker, 'drag', function(){
            updateMarkerPosition(marker.getPosition());
        });
        google.maps.event.addListener(marker, 'dragend', function(){
            geocodePosition(marker.getPosition());
        });
    }
    function getElevation(event){
        var locations = [];
        var clickedLocation = event.latLng;
        locations.push(clickedLocation);
        var positionalRequest = {
            'locations': locations
        }
        elevator.getElevationForLocations(positionalRequest, function(results, status){
            if(status == google.maps.ElevationStatus.OK){
                if(results[0]){
                    hasil = results[0].elevation;
                    hasil2 = Math.floor((hasil - Math.floor(hasil)) * 100);
                    if(hasil2 < 50){
                        hasil = Math.floor(hasil);
                    }else{
                        hasil = Math.floor(hasil) + 1;
                    }
                    infowindow.setContent('Ketinggian lokasi ini sekitar<br>' + hasil + ' meter  di atas permukaan laut.');
                    infowindow.setPosition(clickedLocation);
                    infowindow.open(map);
                    document.getElementById("elevasi").value = results[0].elevation;
                }else{
                    alert('Hasil tidak ditemukan');
                }
            }else{
                alert('Layanan elevasi gagal, karena: ' + status);
            }
        });
    }
    function codeAlamat(){
        var address = document.getElementById("address").value;
        geocoder.geocode({ 'address': address}, function(results, status){
            if(status == google.maps.GeocoderStatus.OK){
                map.setCenter(results[0].geometry.location);
                if(marker != undefined)
                    marker.setPosition(results[0].geometry.location);
                else
                    marker = new google.maps.Marker({
                        map: map,
                        draggable: true,
                        position: results[0].geometry.location
                    });
                updateMarkerPosition(results[0].geometry.location);
                geocodePosition(results[0].geometry.location);
                aksi();
            }else{
                alert("Geocode tidak berhasil dengan alasan karena: " + status);
            }
        });
    }
    function GotoLatLng(){
        var lat = document.getElementById("lintang").value;
        var lang = document.getElementById("bujur").value;
        LatLng = new google.maps.LatLng(lat,lang);
        map.setCenter(LatLng);
        if(marker != undefined)
              marker.setPosition(LatLng);
         else
              marker = new google.maps.Marker({
                    map: map,
                    draggable: true,
                position: LatLng,
              });
        updateMarkerPosition(LatLng);
        geocodePosition(LatLng);
        aksi();
    }
    function geocodePosition(pos){
        geocoder.geocode({latLng: pos},
        function(responses){
            if(responses && responses.length > 0){
                updateMarkerAddress(responses[0].formatted_address);
            }else{
                updateMarkerAddress('Maaf, sistem tidak bisa mengenali alamat di lokasi ini.');
            }
        });
    }
    function updateMarkerStatus(str){
        document.getElementById('markerStatus').innerHTML = str;
    }
    function updateMarkerPosition(LatLng){
        document.getElementById("lintangO").value = [ LatLng.lat('') ];
        document.getElementById("bujurO").value = [ LatLng.lng('') ];
    }
    function updateMarkerAddress(str){
        document.getElementById("alamatbaru").value = str;
    }
    google.maps.event.addDomListener(window, 'load', initialize);
</script>
<style type="text/css">
    #map_canvas{
        margin: 0;
        width: 100%;
        height: 600px;
    }
</style>
    <?php
}
?>
