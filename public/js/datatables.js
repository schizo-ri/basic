$( function () {

	$('table.display').DataTable( {
		"language": {
			"search": "",
			"searchPlaceholder": "Search"
        },
		"lengthMenu": [ 10, 25, 50, 75, 100 ],
		"order": [[ 0, "asc" ]],
		"pageLength": 50,
		"paging": false,
		"searching": true,
		"ordering": true,
		"info":     true,
		"lengthChange": true,
		"columnDefs": [ {
			"targets"  : 'no-sort',
			"orderable": false,
			"order": []
		  }],
		stateSave: true,
		dom: 'Bfrtip',
		buttons: [
			'copyHtml5',
			{
				extend: 'print',
				customize: function ( win ) {
					$(win.document.body).find('h1').addClass('title_print');
					$(win.document.body).find('table').addClass('table_print');
					$(win.document.body).find('table tr td').addClass('row_print');
					$(win.document.body).addClass('body_print');
					$(win.document.body).find('table tr th').addClass('hrow_print');
					$(win.document.body).find('table tr th:last-child').addClass('not_print');
					$(win.document.body).find('table tr td:last-child').addClass('not_print');
				}
			},
			{
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4',
				exportOptions: {
					columns: 'th:not(.not-export-column)'
				}
            },
			{
				extend: 'excelHtml5',
				autoFilter: true,
				exportOptions: {
					columns: 'th:not(.not-export-column)'
				},
				customize: function( xlsx ) {
					var sheet = xlsx.xl.worksheets['sheet1.xml'];
					$('row c', sheet).attr( 's', '25' );
					$('row:first c', sheet).attr( 's', '27' );
				}	
			}
		]
	});
	
});