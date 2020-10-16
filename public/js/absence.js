$(function(){
	if($('.all_absences #index_table').length > 0) {
		var d = new Date();
		var ova_godina = d.getFullYear();
		var prosla_godina = ova_godina - 1;
		var year = '';
		
		if($(".all_absences").length == 0) {
			init_absence_table ();
		}
		function init_absence_table () {
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
					try {
						
						if ( month.length == 1 ) {
							month = 0+month;
						}
				
						/*day*/
						var day = eu_date[0];
						if ( day.length == 1 ) {
							day = 0+day;
						}
				
						return (year + month + day) * 1;
					} catch (error) {
						target = null;
					}
				},
				
				"date-eu-asc": function ( a, b ) {
					return ((a < b) ? -1 : ((a > b) ? 1 : 0));
				},
				
				"date-eu-desc": function ( a, b ) {
					return ((a < b) ? 1 : ((a > b) ? -1 : 0));
				}
			} );
			var admin = $('#user_admin').text();
			if (admin == 'true') {
				order = [ 1, "desc" ];
				targets = [1,3,4];
			} else {
				order = [ 2, "desc" ];
				targets = [0,2,3];
			}
			$('.all_absences #index_table').DataTable( {
				"order": [order],
				"fixedHeader": true,
				"columnDefs": [ {
					"targets"  :targets,
					"type": 'date-eu'
				}],
				dom: 'Bfrtip',
				"paging": false,
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
			} );
		}
		
		$('#year_vacation').on('change',function(){
			year = $(this).val();
			
			$('.info_abs>p>.go').hide();
			$('.info_abs>p>.go.go_'+year).show();
			$('#mySearchTbl').val("");
	
			var url = location.href + '?year='+year;
			console.log(url);
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});     
			$.ajax({
				url: url,
				type: "GET",
				success: function( response ) {
				
					$('table').load(url + ' table',function(){
						
						$.getScript( '/../restfulizer.js');
					});
				}, 
				error: function(jqXhr, json, errorThrown) {
					var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
										'message':  jqXhr.responseJSON.message,
										'file':  jqXhr.responseJSON.file,
										'line':  jqXhr.responseJSON.line };
	
					$.ajax({
						url: 'errorMessage',
						type: "get",
						data: data_to_send,
						success: function( response ) {
							$('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
						}, 
						error: function(jqXhr, json, errorThrown) {
							console.log(jqXhr.responseJSON); 
							
						}
					});
				}
			});
		});
		$('#year_sick').on('change',function(){
			year = $(this).val();
			$('.info_abs>p>.bol').hide();
			$('.info_abs>p>.bol.bol_'+year).show();
	
			var url = location.href + '?year='+year+'&type=BOL';
			console.log(url);
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});     
			$.ajax({
				url: url,
				type: "GET",
				success: function( response ) {
					console.log(response);
					$('table').load(url + ' table',function(){
						
						$.getScript( '/../restfulizer.js');
					});
				}, 
				error: function(jqXhr, json, errorThrown) {
					var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
										'message':  jqXhr.responseJSON.message,
										'file':  jqXhr.responseJSON.file,
										'line':  jqXhr.responseJSON.line };
	
					$.ajax({
						url: 'errorMessage',
						type: "get",
						data: data_to_send,
						success: function( response ) {
							$('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
						}, 
						error: function(jqXhr, json, errorThrown) {
							console.log(jqXhr.responseJSON); 
						}
					});
				}
			});
		});
		$( "#request_type" ).on('change',function() {
			if($(this).val() == 'IZL') {
				$('.form-group.time').show();
				$('.form-group.date2').hide();
				var start_date = $( "#start_date" ).val();
				var end_date = $( "#end_date" );
				end_date.val(start_date);
			} else {
				$('.form-group.time').hide();
				$('.form-group.date2').show();
			}
		});
		$( "#start_date" ).on('change',function() {
			var start_date = $( this ).val();
			var end_date = $( "#end_date" );
			end_date.val(start_date);
		});
	}
	$('.all_absences #index_table_filter').prepend('<a class="add_new" href="'+ location.origin + '/absences/create ' +'" class="" rel="modal:open"><i style="font-size:11px" class="fa">&#xf067;</i>'+ 'Novi zahtjev'+'</a>');
	if($(".all_absences #index_table_filter .show_button").length == 0) {
		$('.all_absences #index_table_filter').append('<span class="show_button"><i class="fas fa-download"></i></span>');
	}
	$('.all_absences #index_table_filter').show(); 
});
