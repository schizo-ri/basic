$( function () {
	var kolona = 0;
	var sort = 'asc';

	if ($('#index_table').hasClass('sort_1_asc')) {
		kolona = 1;
		sort = 'asc';
	}	
	if ($('#index_table').hasClass('sort_1_desc')) {
		kolona = 1;
		sort = 'desc';
	}	
	
	var table = $('table.display').DataTable( {
		"language": {
			"search": "",
			"searchPlaceholder": "Search"
        },
		"lengthMenu": [ 10, 25, 50, 75, 100 ],
		"pageLength": 50,
		"paging": false,
		"searching": true,
		"ordering": true,
		"order": [[ kolona, sort ]],
		"info":     true,
		"bDestroy": true,
		"lengthChange": true,
		"fixedHeader": true,
		"colReorder": true,
		"columnDefs": [ {
			"targets"  : 'sort',
			"orderable": true,
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
					columns: 'th:not(.not-export-column)',
					rows: ':visible'
				}
            },
			{
				extend: 'excelHtml5',
				autoFilter: true,
				exportOptions: {
					columns: 'th:not(.not-export-column)',
					rows: ':visible'
				},
				customize: function( xlsx ) {
					var sheet = xlsx.xl.worksheets['sheet1.xml'];
				/* 	$('row c', sheet).attr( 's', '25' );  borders */
					$('row:first c', sheet).attr( 's', '27' );
				}	
			}
		]
	});


	if($(".index_table_filter .show_button").length == 0) {
		$('.index_table_filter').append('<span class="show_button"><i class="fas fa-download"></i></span>');
	}

	$('.show_button').click(function () {
		$('.index_page .dt-buttons').toggle();		
	})
	$('table.display').show();
});