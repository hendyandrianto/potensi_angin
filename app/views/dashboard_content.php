<script type="text/javascript">
    function generate_cuaca(){
        jQuery.ajax({
            url:'<?php echo base_url(); ?>dashboard/generate_cuaca/',         
            type:'POST',
            success:function(data){ 
                $("#div_cuaca").html(data);
            },
            beforeSend:function(){
                $("#div_cuaca").html("<img src='<?php echo base_url();?>images/loading_spinner.gif'>");
            }
        });     
    }
</script>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Lokasi Potensi Energi Angin
            </div>
            <div class="panel-body">
                <head><?php echo $map['js']; ?></head>
                <body><?php echo $map['html']; ?></body>
            </div>
        </div>
    </div>
</div>