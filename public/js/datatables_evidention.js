
$( function () {
	var table = $('table.display').DataTable( {
		language: {
			"search": "",
			"searchPlaceholder": "Search"
        },
		pageLength: 50,
		paging: false,
		searching: false,
		ordering: false,
		info:     true,
		bDestroy: false,
		lengthChange: true,
		fixedHeader: true,
		colReorder: true,
		responsive: true,
		columnDefs: [ {
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
				orientation: 'landscape',
				pageSize: 'A3',
				exportOptions: {
					columns: 'th:not(.not-export-column)',
					rows: ':visible',
					orientation: 'landscape',
					pageSize: 'A3',
				},
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
				pageSize: 'A3',
				exportOptions: {
					columns: 'th:not(.not-export-column)',
					rows: ':visible'
				},
            },
			{
				extend: 'excelHtml5',
				autoFilter: true,
				createEmptyCells: true, 
				orientation: 'landscape',
				pageSize: 'A2',
				exportOptions: {
					columns: 'th:not(.not-export-column)',
					rows: ':visible'
				},
			
				customize: function( xlsx ) {
					var sheet = xlsx.xl.worksheets['sheet1.xml'];
					$('row:first c', sheet).attr( 's', '2' );
				
					var pageSet = sheet.createElement("pageSetup");
					sheet.childNodes["0"].appendChild(pageSet);
					var seiteneinstellung = sheet.getElementsByTagName("pageSetup")[0];
					seiteneinstellung.setAttribute("paperSize", "8");
					seiteneinstellung.setAttribute("orientation", "landscape");
					seiteneinstellung.setAttribute("r:id", "rId1"); 
					$('row c', sheet).each(function() {
						$(this).attr('s', '25');
					});
					var col = $('col', sheet);
					//set the column width otherwise it will be the length of the line without the newlines
					
					$(col[1]).attr('width', 50);
					$('row c[r^="B"]', sheet).each(function() {
						if ($('is t', this).text()) {
							//wrap text
							$(this).attr('s', '55');
						}
					});
				
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