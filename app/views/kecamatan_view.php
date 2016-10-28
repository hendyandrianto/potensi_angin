<link href="<?php echo base_url();?>vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">
<script src="<?php echo base_url();?>vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url();?>vendor/datatables-responsive/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>js/kecamatan.js"></script>
<div class="row"> <div class="col-md-12"> <div class="panel panel-inverse"> <div class="panel-heading"> <div class="panel-heading-btn"> <a href="<?php echo base_url();?><?php echo $link;?>/add" title="Tambah <?php echo $halaman;?>" class="btn btn-primary btn-xs m-r-5">Tambah Data</a> </div> </div> <div class="panel-body"> <div class="table-responsive"> <table id="data-kecamatan" class="table table-striped table-bordered nowrap" width="100%"> <thead> <tr> <th style="text-align:center" width="1%">No.</th> <th style="text-align:center" width="30%">Propinsi</th><th style="text-align:center" width="30%">Kab / Kota</th><th style="text-align:center" width="30%">Kecamatan</th> <th style="text-align:center" width="10%">Action</th> </tr> </thead> <tbody> </tbody> </table> </div> </div> </div> </div>
</div>