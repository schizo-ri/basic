$(function(){
	if($('.all_absences #index_table').length > 0) {
		var d = new Date();
		var ova_godina = d.getFullYear();
		var prosla_godina = ova_godina - 1;
		var year;
		var month;
		var eu_date;
		var day;
		var admin;
		var url;
		var data_to_send ;
		var start_date;
		var end_date;
		var sheet;
		var approve;
		var form_data;
		var url_delete;
		var url_load;
		var type;
		var type_text;

		if( $(".all_absences").length > 0 ) {
			if ( ! $.fn.DataTable.isDataTable( '.all_absences #index_table' ) ) {
				init_absence_table ();
			}
			delete_request ();
		}

		function init_absence_table () {
			jQuery.extend( jQuery.fn.dataTableExt.oSort, {
				"date-eu-pre": function ( date ) {
					date = date.replace(" ", "");
						
					if ( ! date ) {
						return 0;
					}
				
					eu_date = date.split(/[\.\-\/]/);
					/*year (optional)*/
					if ( eu_date[2] ) {
						year = eu_date[2];
					}
					else {
						year = 0;
					}
				
					/*month*/
					month = eu_date[1];
					try {
						
						if ( month.length == 1 ) {
							month = 0+month;
						}
				
						/*day*/
						day = eu_date[0];
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
			admin = $('#user_admin').text();
			if( $('.all_absences.table_absences.table_requests').length > 0 ) {
				order = [ 2, "desc" ];
				targets = [2,3];
			} else if( $( '.all_absences.table_absences').length > 0) {
				order = [ 0, "asc" ];
				targets = [];
			} else {
				if (admin == 'true') {
					order = [ 2, "desc" ];
					targets = [2,3];
				} else {
					order = [ 2, "desc" ];
					targets = [1,2];
				}
			}
			try {
				$('.all_absences #index_table').DataTable( {
					"order": [order],
					fixedHeader: true,
					"searching": false,
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
								sheet = xlsx.xl.worksheets['sheet1.xml'];
							// 	$('row c', sheet).attr( 's', '25' );  borders 
								$('row:first c', sheet).attr( 's', '27' );
							}	
						}
					]
				} );
			
			} catch (error) {
				console.log(error.message);
			}
		}

		$('#year_vacation').on('change',function(){
			year = $(this).val();
			employee_id =  $('#filter_employees').val();
			
			$('.info_abs>p>.go').hide();
			$('.info_abs>p>.go.go_'+year).show();
			$('#mySearchTbl').val("");
			url = location.href + '?year='+year+'&employee_id='+employee_id;
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
						delete_request ();
					/* 	$.getScript('/../js/absence.js'); */
					/* 	$.getScript( '/../restfulizer.js'); */
					});
				}, 
				error: function(jqXhr, json, errorThrown) {
					data_to_send = { 'exception':  jqXhr.responseJSON.exception,
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
	
			url = location.href + '?year='+year+'&type_bol=BOL';
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
						delete_request ();
						/* $.getScript('/../js/absence.js'); */
					/* 	$.getScript( '/../restfulizer.js'); */
					});
				}, 
				error: function(jqXhr, json, errorThrown) {
					data_to_send = { 'exception':  jqXhr.responseJSON.exception,
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
				start_date = $( "#start_date" ).val();
				end_date = $( "#end_date" );
				end_date.val(start_date);
			} else {
				$('.form-group.time').hide();
				$('.form-group.date2').show();
			}
		});
		
		$( "#start_date" ).on('change',function() {
			start_date = $( this ).val();
			end_date = $( "#end_date" );
			end_date.val(start_date);
		});
		
		$('#filter_employees').on('click', function(){
			$(this).val(""); 
			
		});
	
		$('#filter_employees').on('change',function() {
			if( $('datalist#list_employees').length > 0 ) {
				var value = $(this).val();  
				employee_id = $('#list_employees').find("[value='" + value + "']").attr('data-id'); 
			} else if ( $('select#filter_employees').length > 0) {
				employee_id =  $(this).val(); 
			}
			if ( employee_id == undefined) {
				employee_id == 'all';
			}
			/* $( this ).text(value); */
			if( $('#filter_types').length>0) {
				type = $('#filter_types').val();
				type_text = $('#filter_types').find('option:selected').text();
			} else {
				type = 'all';
			}
			if( $('#filter_years').length>0) {
				month =  $('#filter_years').val();
			} else {
				month = null;
			}
			if( $('#filter_approve').length>0) {
				approve =  $('#filter_approve').val();
			} else {
				approve = null;
			}

			url = location.href + '?month='+month+'&type='+type+'&employee_id='+employee_id+'&approve='+approve;
			console.log(url);
			
			$.ajax({
				url: url,
				type: "get",
				beforeSend: function(){
					// Show image container
					$('body').prepend('<div id="loader"></div>');
				},
				success: function( response ) {
					$('.main_absence ').load(url + " .main_absence>section",function(){
						$('#loader').remove();
						if(type_text == 'Prekovremeni sati' || type_text == 'Izlazak') {
							$('.table-responsive .table thead th.absence_end_date').css('display','none');
							$('.table-responsive .table tbody td.absence_end_date').css('display','none');
							$('.table-responsive .table thead th.absence_time').css('display','table-cell');
							$('.table-responsive .table tbody td.absence_time').css('display','table-cell');
						} else if (type_text == '') {
							$('.table-responsive .table thead th.absence_end_date').css('display','table-cell');
							$('.table-responsive .table tbody td.absence_end_date').css('display','table-cell');
							$('.table-responsive .table thead th.absence_time').css('display','table-cell');
							$('.table-responsive .table tbody td.absence_time').css('display','table-cell');
						} else {
							$('.table-responsive .table thead th.absence_end_date').css('display','table-cell');
							$('.table-responsive .table tbody td.absence_end_date').css('display','table-cell');
							$('.table-responsive .table thead th.absence_time').css('display','none');
							$('.table-responsive .table tbody td.absence_time').css('display','none');
						}
						$.getScript('/../js/absence.js');
						$.getScript('/../select2-develop/dist/js/select2.min.js');
						selectSearch ();
								
						$(this).find('option[value="'+employee_id+'"]').attr('selected',true);
						$('#filter_types').find('option[value="'+type+'"]').attr('selected',true);
						$('#filter_years').find('option[value="'+month+'"]').attr('selected',true);
						$('#filter_approve').find('option[value="'+approve+'"]').attr('selected',true);
						/* $.getScript( '/../restfulizer.js'); */
					});
				},
				error: function(jqXhr, json, errorThrown) {
					console.log(jqXhr.responseJSON.message);
				}
			});
		});	
		
		$('#filter_types').on('change',function() {
			type = $( this ).val();
		
			type_text = $( this ).find('option:selected').text();
			if( $('#filter_years').length>0) {
				month =  $('#filter_years').val();
			} else {
				month = null;
			}
			if( $('#filter_employees').length > 0) {
				employee_id = $('#filter_employees').val();
			} else {
				employee_id = null;
			}
			if( $('#filter_approve').length>0) {
				approve =  $('#filter_approve').val();
			} else {
				approve = null;
			}
		
			url = location.href + '?month='+month+'&type='+type+'&employee_id='+employee_id+'&approve='+approve;

			$.ajax({
				url: url,
				type: "get",
				beforeSend: function(){
					$('body').prepend('<div id="loader"></div>');
				},
				success: function( response ) {
					$('.main_absence ').load(url + " .main_absence>section",function(){
						$('#loader').remove();
						
						if(type_text == 'Prekovremeni sati' || type_text == 'Izlazak') {
							$('.table-responsive .table thead th.absence_end_date').css('display','none');
							$('.table-responsive .table tbody td.absence_end_date').css('display','none');
							$('.table-responsive .table thead th.absence_time').css('display','table-cell');
							$('.table-responsive .table tbody td.absence_time').css('display','table-cell');
						} else if (type_text == '') {
							$('.table-responsive .table thead th.absence_end_date').css('display','table-cell');
							$('.table-responsive .table tbody td.absence_end_date').css('display','table-cell');
							$('.table-responsive .table thead th.absence_time').css('display','table-cell');
							$('.table-responsive .table tbody td.absence_time').css('display','table-cell');
						} else {
							$('.table-responsive .table thead th.absence_end_date').css('display','table-cell');
							$('.table-responsive .table tbody td.absence_end_date').css('display','table-cell');
							$('.table-responsive .table thead th.absence_time').css('display','none');
							$('.table-responsive .table tbody td.absence_time').css('display','none');
						}
						
						$.getScript('/../js/absence.js');
						$.getScript('/../select2-develop/dist/js/select2.min.js');
						selectSearch ();
						$('.show_button').on('click',function () {
							$('.dt-buttons').show();
						});
						/* $.getScript( '/../restfulizer.js'); */
						$(this).find('option[value="'+type+'"]').attr('selected',true);						
						$('#filter_employees').find('option[value="'+employee_id+'"]').attr('selected',true);
						$('#filter_years').find('option[value="'+month+'"]').attr('selected',true);
						$('#filter_approve').find('option[value="'+approve+'"]').attr('selected',true);
					});
				},
				error: function(jqXhr, json, errorThrown) {
					console.log(jqXhr.responseJSON.message);
				}
			});
		});	
		
		$('#filter_years').on('change',function() {
			month = $( this ).val();
			if( $('#filter_types').length>0) {
				type = $('#filter_types').val();
				type_text = $('#filter_types').find('option:selected').text();
			} else {
				type = 'all';
			}
			if( $('#filter_employees').length>0) {
				employee_id =  $('#filter_employees').val();
			} else {
				employee_id = null;
			}
			if( $('#filter_approve').length>0) {
				approve =  $('#filter_approve').val();
			} else {
				approve = null;
			}
			url = location.href + '?month='+month+'&type='+type+'&employee_id='+employee_id+'&approve='+approve;
			$.ajax({
				url: url,
				type: "get",
				beforeSend: function(){
					// Show image container
					$('body').prepend('<div id="loader"></div>');
				},
				success: function( response ) {
					$('.main_absence ').load(url + " .main_absence>section",function(){
						$('#loader').remove();
						if(type_text == 'Prekovremeni sati' || type_text == 'Izlazak') {
							$('.table-responsive .table thead th.absence_end_date').css('display','none');
							$('.table-responsive .table tbody td.absence_end_date').css('display','none');
							$('.table-responsive .table thead th.absence_time').css('display','table-cell');
							$('.table-responsive .table tbody td.absence_time').css('display','table-cell');
						} else if (type_text == '') {
							$('.table-responsive .table thead th.absence_end_date').css('display','table-cell');
							$('.table-responsive .table tbody td.absence_end_date').css('display','table-cell');
							$('.table-responsive .table thead th.absence_time').css('display','table-cell');
							$('.table-responsive .table tbody td.absence_time').css('display','table-cell');
						} else {
							$('.table-responsive .table thead th.absence_end_date').css('display','table-cell');
							$('.table-responsive .table tbody td.absence_end_date').css('display','table-cell');
							$('.table-responsive .table thead th.absence_time').css('display','none');
							$('.table-responsive .table tbody td.absence_time').css('display','none');
						}
						$.getScript('/../js/absence.js');
						$.getScript('/../select2-develop/dist/js/select2.min.js');
						selectSearch ();

						$('#filter_employees').find('option[value="'+employee_id+'"]').attr('selected',true);
						$('#filter_types').find('option[value="'+type+'"]').attr('selected',true);
						$(this).find('option[value="'+month+'"]').attr('selected',true);
						$('#filter_approve').find('option[value="'+approve+'"]').attr('selected',true);
						/* $.getScript( '/../restfulizer.js'); */
					});
				},
				error: function(jqXhr, json, errorThrown) {
					console.log(jqXhr.responseJSON.message);
				}
			});
		});	
	
		$('#filter_approve').on('change',function() {
			approve = $( this ).val();
			type = $('#filter_types').val();
			type_text = $('#filter_types').find('option:selected').text();
			month = $('#filter_years').val();
			employee_id =  $('#filter_employees').val();
			url = location.href + '?month='+month+'&type='+type+'&employee_id='+employee_id+'&approve='+approve;
			/* console.log(url); */
			$.ajax({
				url: url,
				type: "get",
				beforeSend: function(){
					// Show image container
					$('body').prepend('<div id="loader"></div>');
				},
				success: function( response ) {
					$('.main_absence ').load(url + " .main_absence>section",function(){
						$('#loader').remove();
						if(type_text == 'Prekovremeni sati' || type_text == 'Izlazak') {
							$('.table-responsive .table thead th.absence_end_date').css('display','none');
							$('.table-responsive .table tbody td.absence_end_date').css('display','none');
							$('.table-responsive .table thead th.absence_time').css('display','table-cell');
							$('.table-responsive .table tbody td.absence_time').css('display','table-cell');
						} else if (type_text == '') {
							$('.table-responsive .table thead th.absence_end_date').css('display','table-cell');
							$('.table-responsive .table tbody td.absence_end_date').css('display','table-cell');
							$('.table-responsive .table thead th.absence_time').css('display','table-cell');
							$('.table-responsive .table tbody td.absence_time').css('display','table-cell');
						} else {
							$('.table-responsive .table thead th.absence_end_date').css('display','table-cell');
							$('.table-responsive .table tbody td.absence_end_date').css('display','table-cell');
							$('.table-responsive .table thead th.absence_time').css('display','none');
							$('.table-responsive .table tbody td.absence_time').css('display','none');
						}
						$.getScript('/../js/absence.js');
						$.getScript('/../select2-develop/dist/js/select2.min.js');
						selectSearch ();

						$('#filter_employees').find('option[value="'+employee_id+'"]').attr('selected',true);
						$('#filter_types').find('option[value="'+type+'"]').attr('selected',true);
						$('#filter_years').find('option[value="'+month+'"]').attr('selected',true);
						$(this).find('option[value="'+approve+'"]').attr('selected',true);
						/* $.getScript( '/../restfulizer.js'); */
					});
				},
				error: function(jqXhr, json, errorThrown) {
					console.log(jqXhr.responseJSON.message);
				}
			});
		});	
		$('.all_absences #index_table_filter').show(); 

		if($(".all_absences #index_table_filter .show_button").length == 0) {
			$('.all_absences #index_table_filter label').append('<span class="show_button"><i class="fas fa-download"></i></span>');
			$('.show_button').on('click',function () {
				$('.dt-buttons').toggle();		
			/* 	console.log("show_button"); */
			})
		}

		$('span#checkall').on('click',function(){
			$('.check.checkinput').prop('checked',true);
			$('.uncheck.checkinput').prop('checked',false);

		});

		$('span#uncheckall').on('click',function(){
			$('.check.checkinput').prop('checked',false);
			$('.uncheck.checkinput').prop('checked',true);
			
		});

		$('span#nocheckall').on('click',function(){
			$('.check.checkinput').prop('checked',false);
			$('.uncheck.checkinput').prop('checked',false);
			$('.nocheck.checkinput').prop('checked',false);
			
		});

		$('.after_form').on('submit',function(e){
			var broj_zahtjeva = $('.checkinput:checked').length;

			if (! confirm("Sigurno želiš odobriti "+broj_zahtjeva+" zahtjeva?")) {
				
				return false;
			} else {
				e.preventDefault();
				url = $(this).attr('action');
				form_data = $(this).serialize(); 
			
				approve = $( '#filter_approve' ).val();
				type = $('#filter_types').val();
				month = $('#filter_years').val();
				employee_id =  $('#filter_employees').val();
				url_load = location.href + '?month='+month+'&type='+type+'&employee_id='+employee_id+'&approve='+approve;
				
				console.log(url),
				console.log(form_data); 
				$.ajax({
					url: url,
					type: "post",
					data: form_data,
					beforeSend: function(){
						$('body').prepend('<div id="loader"></div>');
					},
					success: function( response ) {
						$('tbody').load(url_load + " tbody>tr",function(){
							$('#loader').remove();
							alert(response);
						});
					}, 
					error: function(xhr,textStatus,thrownError) {
						console.log("validate eror " + xhr + "\n" + textStatus + "\n" + thrownError);                            
					}
				});
			}
		}); 

		$('tr.tr_open_link td:not(.not_link)').on('click', function(e) {
			e.preventDefault();
			url = location.origin + $( this ).parent().attr('data-href');
			window.location = url;
		});
		
		function delete_request () {
			$('.action_confirm.btn-delete').on('click', function(e) {
				if (! confirm("Sigurno želiš obrisati zahtjev?")) {
					return false;
				} else {
					e.preventDefault();
					approve = $('#filter_approve').val();
					type = $('#filter_types').val();
					month = $('#filter_years').val();
					employee_id =  $('#filter_employees').val();
					url_delete = $( this ).attr('href');
					url_load = location.href + '?month='+month+'&type='+type+'&employee_id='+employee_id+'&approve='+approve;
					token = $( this ).attr('data-token');
					$.ajaxSetup({
						headers: {
							'_token': token
						}
					});
					$.ajax({
						url: url_delete,
						type: 'POST',
						data: {_method: 'delete', _token :token},
						beforeSend: function(){
							$('body').prepend('<div id="loader"></div>');
						},
						success: function(result) {
							$('tbody ').load(url_load + " tbody>tr",function(){
								$('#loader').remove();
								delete_request ();
							/* 	$.getScript('/../js/absence.js'); */
								$("#filter_types").find('option[value="'+type+'"]').attr('selected',true);
								$('#filter_employees').find('option[value="'+employee_id+'"]').attr('selected',true);
								$('#filter_years').find('option[value="'+month+'"]').attr('selected',true);
								$('#filter_approve').find('option[value="'+approve+'"]').attr('selected',true);
							});
						}
					});
				}
			});
		}

		selectSearch ();
	}
});