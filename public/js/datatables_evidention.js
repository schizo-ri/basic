
$( function () {
	if( $('.evidention_employee').length > 0) {
		if ( ! $.fn.DataTable.isDataTable( '.evidention_employee table.display' ) ) {
			var table = $('.evidention_employee table.display').DataTable( {
				language: {
					"search": "",
					"searchPlaceholder": "Search"
				},
				pageLength: 50,
				paging: false,
				searching: false,
				ordering: false,
				order: [],
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
					/* 'copyHtml5',
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
					}, */
					{
						extend: 'pdfHtml5',
						orientation: 'landscape',
						pageSize: 'A3',
						defaultStyle: {
							fontSize: 6,
							color: 'black'
						},
						exportOptions: {
							columns: 'th:not(.not-export-column)',
							rows: ':visible'
						},
						customize: function( doc ) {
							console.log(doc);
							/*	var count_col = table.columns(':not(.not-export-column)').count();
								console.log(doc);
								var width = (100/count_col) + '%';
								doc.content[1].table.widths = [width,width,width,width,width]
							} */
							doc.styles.tableHeader = {
								color: 'black',
								background: 'grey',
								alignment: 'center'
							}
							doc.defaultStyle = {
								fontSize: 8
							}
							doc.styles = {
								subheader: {
									fontSize: 10,
									bold: true,
									color: 'black'
								},
								tableHeader: {
									bold: true,
									fontSize: 8,
									color: 'black'
								},
								lastLine: {
									bold: true,
									fontSize: 8,
									color: 'blue'
								},
								defaultStyle: {
									fontSize: 8,
									color: 'black'
								}
							}
							var rowCount = doc.content[1].table.body.length;
							for (i = 1; i < rowCount; i++) {
								var columnsCount = doc.content[1].table.body[i].length;
								var align;
								for (j = 1; j < columnsCount; j++) {
									if(j == 0 || j == columnsCount-1) {
										align = 'right';
									} else if (j == 1 ) {
										align = 'left';
									} else {
										align = 'center';
									}
									doc.content[1].table.body[i][j].alignment = align;
								}
							}
							var objLayout = {};
							objLayout['hLineWidth'] = function(i) { return .8; };
							objLayout['vLineWidth'] = function(i) { return .5; };
							objLayout['hLineColor'] = function(i) { return '#aaa'; };
							objLayout['vLineColor'] = function(i) { return '#aaa'; };
							/* objLayout['paddingLeft'] = function(i) { return 8; };
							objLayout['paddingRight'] = function(i) { return 8; }; */
							doc.content[1].layout = objLayout;
						},
					},
					{
						extend: 'excelHtml5',
						autoFilter: false,
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
							var sheet = xlsx.xl['styles.xml'];
							var tagName = sheet.getElementsByTagName('sz');
							for (i = 0; i < tagName.length; i++) {
							tagName[i].setAttribute("val", "8")
							}
						}	
					}
				]
			});
		}
	
		if($(".index_table_filter .show_button").length == 0) {
			$('.index_table_filter').append('<span class="show_button"><i class="fas fa-download"></i></span>');
		}
	
		$('.evidention_employee .show_button').on('click',function () {
			$('.evidention_employee .dt-buttons').toggle();		
		})
	
		$('table.display').show();
	}
});