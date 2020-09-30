$( function () {
	var url = location.href;
	var wrap_col;
	if( url.includes('loccos/')) {
		var wrap_col = "H";
		
	}
	
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
	var th_length = $('table.display thead th').not('.not-export-column');
	var target = [];
	var widths = [];
	$(th_length).each(function(index){
		if($(this).hasClass("sort_date") ) {
			target.push(index);
		}
	});
	try {
		jQuery.extend( jQuery.fn.dataTableExt.oSort, {
			"date-eu-pre": function ( date ) {
				date = date.replace(" ", "");
				
				if ( ! date ) {
					return 0;
				}
		 
				var year;
				var eu_date = date.split(/[\.\-\/]/);
				/*year (optional)*/
				if ( eu_date[2] ) {
					year = eu_date[2];
				}
				else {
					year = 0;
				}
		 
				/*month*/
				var month = eu_date[1];
					if (month != undefined &&  month.length == 1 ) {
						month = 0+month;
					}
			
					/*day*/
					var day = eu_date[0];
					if ( day.length == 1 ) {
						day = 0+day;
					}
			
					return (year + month + day) * 1;
			
			},
		 
			"date-eu-asc": function ( a, b ) {
				return ((a < b) ? -1 : ((a > b) ? 1 : 0));
			},
		 
			"date-eu-desc": function ( a, b ) {
				return ((a < b) ? 1 : ((a > b) ? -1 : 0));
			}
		} );
	} catch (error) {
	/* 	target = null; */
	}
	
	if($('table.display').length >0) {
		var table = $('table.display').not('.evidention_employee table.display').DataTable( {
			"language": {
				"search": "",
				"searchPlaceholder": "Search"
			},
			"lengthMenu": [ 10, 25, 50, 75, 100 ],
			"pageLength": 50,
			"paging": false,
			"searching": true,
			"ordering": true,
			"order": [ kolona, sort ],
			"info":     true,
			"bDestroy": true,
			"lengthChange": true,
			"fixedHeader": true,
			"colReorder": true,
			"columnDefs": [ {
				"targets"  :target,
				"type": 'date-eu'
			}],
			stateSave: true,
			dom: 'Bfrtip',
			buttons: [
			/* 	'copyHtml5',
				{
					extend: 'print',
					exportOptions: {
						columns: 'th:not(.not-export-column)',
						rows: ':visible'
					},
					customize: function ( win ) {
						$(win.document.body).find('h1').addClass('title_print');
						$(win.document.body).find('table').addClass('table_print');
						$(win.document.body).find('table tr td').addClass('row_print');
						$(win.document.body).addClass('body_print');
						$(win.document.body).find('table tr th').addClass('hrow_print');
						$(win.document.body).find('table tr th:last-child').addClass('not_print');
						$(win.document.body).find('table tr td:last-child').addClass('not_print');
					
						var last = null;
						var current = null;
						var bod = [];
		
						var css = '@page { size: landscape; }',
							head = win.document.head || win.document.getElementsByTagName('head')[0],
							style = win.document.createElement('style');
		
						style.type = 'text/css';
						style.media = 'print';
		
						if (style.styleSheet)
						{
						style.styleSheet.cssText = css;
						}
						else
						{
						style.appendChild(win.document.createTextNode(css));
						}
		
						head.appendChild(style);
					
					}
				}, */
				{
					extend: 'pdfHtml5',
					orientation: 'landscape',
					pageSize: 'A4',
					download: 'open',
					exportOptions: {
						columns: 'th:not(.not-export-column)',
						rows: ':visible'
					},
					customize: function( doc ) {
						doc.defaultStyle.fontSize = 8;
						var count_col = table.columns(':not(.not-export-column)').count();
						/* 	console.log(doc); */
						var width = (100/count_col) + '%';
						for (let index = 0; index < th_length.length; index++) {
							widths.push(width);
						}
						doc.content[1].table.widths = widths;
						doc.styles.tableHeader = {
							color: 'black',
							background: 'grey',
							alignment: 'center',
						}
						doc['footer']=(function(page, pages) {
							return {
							columns: [
								'Broj strana',
								{
									alignment: 'right',
									text: [
										{ text: page.toString(), italics: true },
										' of ',
										{ text: pages.toString(), italics: true }
									]
								}
							],
							margin: [30, 10]
							}
						});
						doc.styles = {
							table: {
								fontSize: 8,
							},
							subheader: {
								fontSize: 8,
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
						
						var objLayout = {};
						objLayout['hLineWidth'] = function(i) { return .8; };
						objLayout['vLineWidth'] = function(i) { return .5; };
						objLayout['hLineColor'] = function(i) { return '#aaa'; };
						objLayout['vLineColor'] = function(i) { return '#aaa'; };
						/* objLayout['paddingLeft'] = function(i) { return 8; };
						objLayout['paddingRight'] = function(i) { return 8; }; */
						doc.content[1].layout = objLayout;
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
						$('row:first c', sheet).attr( 's', '2' );
					/* 	console.log(xlsx); */
						var pageSet = sheet.createElement("pageSetup");
						sheet.childNodes["0"].appendChild(pageSet);
						var pageSetup = sheet.getElementsByTagName("pageSetup")[0];
						pageSetup.setAttribute("paperSize", "8");
						pageSetup.setAttribute("orientation", "landscape");
						pageSetup.setAttribute("r:id", "rId1"); 
						
						var sheet1 = xlsx.xl['styles.xml'];
						var tagName = sheet1.getElementsByTagName('sz');
						for (i = 0; i < tagName.length; i++) {
						tagName[i].setAttribute("val", "9")
						}
						$('row c', sheet).each(function() {
							$(this).attr('s', '25');
						});
						var col = $('col', sheet);
						//set the column width otherwise it will be the length of the line without the newlines
						//$(col[1]).attr('width', 50);
						$('row c[r^="'+wrap_col+'"]', sheet).each(function() {
							if ($('is t', this).text()) {
								//wrap text
								$(this).attr('s', ['55']);
							}
						});
					}	
				}
			]
		});
	}
	if($(".index_table_filter .show_button").length == 0) {
		$('.index_table_filter').append('<span class="show_button"><i class="fas fa-download"></i></span>');
	}

	$('.show_button').on('click',function () {
		$('.index_page .dt-buttons').toggle();		
	})
	$('table.display').show();
});