$(document).ready(function() { 
	$('#data-daerah').dataTable({ 
		"fnCreatedRow": 
		function( nRow, aData, iDataIndex ) { 
			var temp = $('td:eq(0)', nRow).text(); 
			var temp = temp.split('|'); 
			var no = escapeHtml(temp[0]+"."); 
			var kode = escapeHtml(temp[1]); 
			var nama = escapeHtml($('td:eq(1)', nRow).text()); 
			var kab = escapeHtml($('td:eq(2)', nRow).text()); 
			var daerahna = '<a href="javascript:void(0)" onclick="detil('+"'"+kode+"'"+',\'Data Daerah\',\'daerah\',\'detil\')" title="Detil Data">'+nama+'</a>'; 
			var action = '<center><a href="javascript:void(0)" onclick="hapus('+"'"+kode+"'"+',\'Data Daerah\',\'daerah\',\'hapus\')" data-toggle="tooltip" class="btn btn-danger btn-sm" title="Hapus Data"><i class="icon-remove icon-white"></i></a> ' + ' <a href="javascript:void(0)" onclick="edit('+"'"+kode+"'"+',\'Data Daerah\',\'daerah\',\'edit\')" data-toggle="tooltip" class="btn btn-warning btn-sm" title="Edit Data"><i class="icon-pencil icon-white"></i></a></center>'; 
			$('td:eq(0)', nRow).html(no); 
			$('td:eq(1)', nRow).html(daerahna); 
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
		"sAjaxSource": $BASE_URL+"daerah/get_data" 
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