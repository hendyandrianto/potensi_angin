<div class="row">
    <div class="col-md-12">
		<div class="panel panel-inverse" data-sortable-id="form-validation-2">
		    <div class="panel-body panel-form">
		        <form class="form-horizontal form-bordered" action="<?php echo $action;?>" method="post" data-parsley-validate="true">
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3">Nama * :</label>
						<div class="col-md-3 col-sm-3">
                            <input class="form-control" type="text" id="nama" minlength="1" maxlength="50" name="nama" value="<?php echo set_value('nama',isset($default['nama']) ? $default['nama'] : ''); ?>" data-type="nama" placeholder="Masukan Nama User" data-parsley-required="true" data-parsley-minlength="1" data-parsley-maxlength="50"/>
                            <span style="color:red;"><?php echo form_error('nama');?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3">Username * :</label>
						<div class="col-md-3 col-sm-3">
                            <input class="form-control" type="text" id="username" minlength="1" maxlength="50" name="username" value="<?php echo set_value('username',isset($default['username']) ? $default['username'] : ''); ?>" data-type="username" placeholder="Masukan Username" data-parsley-required="true" data-parsley-minlength="1" data-parsley-maxlength="50"/>
							<span style="color:red;"><?php echo form_error('username');?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3">Password * :</label>
						<div class="col-md-3 col-sm-3">
                            <input class="form-control" type="password" id="password" minlength="1" maxlength="50" name="password" value="<?php echo set_value('password',isset($default['password']) ? $default['password'] : ''); ?>" data-type="password" placeholder="Masukan password" data-parsley-required="true" data-parsley-minlength="1" data-parsley-maxlength="50"/>
							<span style="color:red;"><?php echo form_error('password');?></span>
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
