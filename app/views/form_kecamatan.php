<script type="text/javascript">
$(document).ready(function() {
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
                jQuery("#kota").load ($BASE_URL+'kecamatan/getKabkot/'+propinsi);
                jQuery.unblockUI();
            },500);
        }else{
            $.gritter.add({title:"Informasi !",text: " Silahkan Pilih Propinsi"});
            return false;
        }
    });

});
</script>
<div class="row">
    <div class="col-md-12">
		<div class="panel panel-inverse" data-sortable-id="form-validation-2">
		    <div class="panel-body panel-form">
		        <form class="form-horizontal form-bordered" action="<?php echo $action;?>" method="post" data-parsley-validate="true">
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3">Nama Propinsi * :</label>
						<div class="col-md-3 col-sm-3">
                            <?php echo form_dropdown('propinsi',$option_propinsi,isset($default['propinsi']) ? $default['propinsi'] : '','id="propinsi" data-size="10" data-parsley-required="true" data-live-search="true" data-style="btn-white" class="form-control selectpicker"');?>
                            <span style="color:red;"><?php echo form_error('propinsi');?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3">Nama Kab / Kota * :</label>
						<div class="col-md-3 col-sm-3">
                            <?php echo form_dropdown('kota',$option_kota,isset($default['kota']) ? $default['kota'] : '','class="form-control selectpicker" id="kota" data-size="10" data-parsley-required="true" data-live-search="true" data-style="btn-white"');?>
                            <span style="color:red;"><?php echo form_error('kota');?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3">Nama Kecamatan * :</label>
						<div class="col-md-3 col-sm-3">
							<input class="form-control" type="text" id="kecamatan" minlength="1" maxlength="50" name="kecamatan" value="<?php echo set_value('kecamatan',isset($default['kecamatan']) ? $default['kecamatan'] : ''); ?>" data-type="kecamatan" placeholder="Masukan Nama Kecamatan" data-parsley-required="true" data-parsley-minlength="1" data-parsley-maxlength="50"/>
                            <span style="color:red;"><?php echo form_error('kecamatan');?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3"></label>
						<div class="col-md-3 col-sm-3">
							<button type="submit" class="btn btn-success btn-sm"><?php echo $tombolsimpan;?></button>
                      		<button type="button" onclick="history.go(-1)" class="btn btn-info btn-sm"><?php echo $tombolbatal ; ?></button>
						</div>
					</div>
		        </form>
		    </div>
		</div>
	</div>
</div>
