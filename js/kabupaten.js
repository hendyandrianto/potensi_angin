$(document).ready(function() { 
	$('#data-kabupaten').dataTable({ 
		"fnCreatedRow": 
		function( nRow, aData, iDataIndex ) { 
			var temp = $('td:eq(0)', nRow).text(); 
			var temp = temp.split('|'); 
			var no = escapeHtml(temp[0]+"."); 
			var kode = escapeHtml(temp[1]); 
			var nama = escapeHtml($('td:eq(1)', nRow).text()); 
			var kab = escapeHtml($('td:eq(2)', nRow).text()); 
			var stts = escapeHtml($('td:eq(3)', nRow).text()); 
			var action = '<center><a href="javascript:void(0)" onclick="hapus('+"'"+kode+"'"+',\'Data Kabupaten\',\'kabkot\',\'hapus\')" data-toggle="tooltip" class="btn btn-danger btn-sm" title="Hapus Data"><i class="icon-remove icon-white"></i></a> ' + ' <a href="javascript:void(0)" onclick="edit('+"'"+kode+"'"+',\'Data Kabupaten\',\'kabkot\',\'edit\')" data-toggle="tooltip" class="btn btn-warning btn-sm" title="Edit Data"><i class="icon-pencil icon-white"></i></a></center>'; 
			if(stts=="1"){ 
				var status = '<center><a href="javascript:void(0)" onclick="rbstatus(\'aktif\','+"'"+kode+"'"+',\'Data Kabupaten\',\'kabkot\',\'ubah_status\')" data-toggle="tooltip" class="btn btn-info btn-sm" title="Status Aktif"><i class="fa fa-unlock icon-white"></i></a>'; 
			}else{ 
				var status = '<center><a href="javascript:void(0)" onclick="rbstatus(\'inaktif\','+"'"+kode+"'"+',\'Data Kabupaten\',\'kabkot\',\'ubah_status\')" data-toggle="tooltip" class="btn btn-danger btn-sm" title="Status NonAktif"><i class="fa fa-lock icon-white"></i></a>'; 
			} 
			$('td:eq(0)', nRow).html(no); 
			$('td:eq(1)', nRow).html(nama); 
			$('td:eq(2)', nRow).html(kab); 
			$('td:eq(3)', nRow).html(action); 
			$('td:eq(0),td:eq(3),td:eq(3)', nRow).css('text-align','center'); 
		}, 
		"bAutoWidth": false, 
		"aoColumns": [ 
		{ "sWidth": "1%" }, 
		{ "sWidth": "20%" }, 
		{ "sWidth": "60%" }, 
		{ "sWidth": "10%" } ], 
		"bProcessing": false, 
		"bServerSide": true, 
		"responsive":false, 
		"sAjaxSource": $BASE_URL+"kabkot/get_data" 
	}); 
	$('#data-table').each(function(){ 
		var datatable = $(this); 
		var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select'); 
	});
});
function escapeHtml(text) {
  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}