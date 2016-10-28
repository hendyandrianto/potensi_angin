$(document).ready(function() { 
	// TableManageResponsive.init(); 
	$('#data-provinsi').dataTable({ 
		"fnCreatedRow": 
		function( nRow, aData, iDataIndex ) { 
			var temp = $('td:eq(0)', nRow).text(); 
			var temp = temp.split('|'); 
			var no = escapeHtml(temp[0]+"."); 
			var kode = escapeHtml(temp[1]); 
			var nama = escapeHtml($('td:eq(1)', nRow).text()); 
			var kabkot = escapeHtml($('td:eq(2)', nRow).text()); 
			var kec = escapeHtml($('td:eq(3)', nRow).text()); 
			var desa = escapeHtml($('td:eq(4)', nRow).text()); 
			var action = '<center><a href="javascript:void(0)" onclick="hapus('+"'"+kode+"'"+',\'Data provinsi\',\'provinsi\',\'hapus\')" data-toggle="tooltip" class="btn btn-danger btn-sm" title="Hapus Data"><i class="icon-remove icon-white"></i></a> ' + ' <a href="javascript:void(0)" onclick="edit('+"'"+kode+"'"+',\'Data provinsi\',\'provinsi\',\'edit\')" data-toggle="tooltip" class="btn btn-warning btn-sm" title="Edit Data"><i class="icon-pencil icon-white"></i></a></center>'; 
			$('td:eq(0)', nRow).html(no); 
			$('td:eq(1)', nRow).html(nama); 
			$('td:eq(2)', nRow).html(kabkot); 
			$('td:eq(3)', nRow).html(kec); 
			$('td:eq(4)', nRow).html(desa); 
			$('td:eq(5)', nRow).html(action); 
			$('td:eq(0),td:eq(5),td:eq(4),td:eq(3),td:eq(2)', nRow).css('text-align','center'); 
		}, 
		"bAutoWidth": false, 
		"aoColumns": [ 
		{ "sWidth": "1%" }, 
		{ "sWidth": "30%" }, 
		{ "sWidth": "20%" }, 
		{ "sWidth": "20%" }, 
		{ "sWidth": "20%" }, 
		{ "sWidth": "10%" } ], 
		"bProcessing": false, 
		"bServerSide": true, 
		"responsive":false, 
		"sAjaxSource": $BASE_URL+"provinsi/get_data" 
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