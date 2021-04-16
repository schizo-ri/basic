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
		
		
		if ( ! $.fn.DataTable.isDataTable( '.all_absences #index_table' ) ) {
			init_absence_table ();
		}
		delete_request ();

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
						if(type_text == 'Prekovremeni sati' || type_text == 'Izlazak' ) {
							$('.table-responsive .table thead th.absence_end_date').css('display','none');
							$('.table-responsive .table tbody td.absence_end_date').css('display','none');
							$('.table-responsive .table thead th.absence_time').css('display','table-cell');
							$('.table-responsive .table tbody td.absence_time').css('display','table-cell');
						} else if (type_text == 'Svi tipovi') {
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
			console.log(url);
			$.ajax({
				url: url,
				type: "get",
				beforeSend: function(){
					$('body').prepend('<div id="loader"></div>');
				},
				success: function( response ) {
					$('.main_absence ').load(url + " .main_absence>section",function(){
						$('#loader').remove();
						
						if(type_text == 'Prekovremeni sati' || type_text == 'Izlazak' ) {
							$('.table-responsive .table thead th.absence_end_date').css('display','none');
							$('.table-responsive .table tbody td.absence_end_date').css('display','none');
							$('.table-responsive .table thead th.absence_time').css('display','table-cell');
							$('.table-responsive .table tbody td.absence_time').css('display','table-cell');
						} else if (type_text == 'Svi tipovi') {
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
			if( $('#filter_types').length > 0) {
				type = $('#filter_types').val();
				type_text = $('#filter_types').find('option:selected').text();
			} else {
				type = 'all';
				type_text = 'Svi tipovi';
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
			console.log(type);
			console.log(type_text);
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
						if(type_text == 'Prekovremeni sati' || type_text == 'Izlazak' ) {
							$('.table-responsive .table thead th.absence_end_date').css('display','none');
							$('.table-responsive .table tbody td.absence_end_date').css('display','none');
							$('.table-responsive .table thead th.absence_time').css('display','table-cell');
							$('.table-responsive .table tbody td.absence_time').css('display','table-cell');
						} else if (type_text == 'Svi tipovi') {
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
						if(type_text == 'Prekovremeni sati' || type_text == 'Izlazak' ) {
							$('.table-responsive .table thead th.absence_end_date').css('display','none');
							$('.table-responsive .table tbody td.absence_end_date').css('display','none');
							$('.table-responsive .table thead th.absence_time').css('display','table-cell');
							$('.table-responsive .table tbody td.absence_time').css('display','table-cell');
						} else if (type_text == 'Svi tipovi') {
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

			if (! confirm("Sigurno želiš obraditi "+broj_zahtjeva+" zahtjeva?")) {
				
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
$(function(){
    var employee_id;
	var type;
	var start_date;
	var end_date;
	var StartDate;
	var EndDate;
	var start_time;
	var end_time;
	var time_diff
	var tomorrow;
	var diff_days;
	var broj_dana;
	var date = new Date();
	var today = date.getFullYear() + '-' + ( '0' + (date.getMonth()+1) ).slice( -2 ) + '-' + ( '0' + (date.getDate()) ).slice( -2 );
	var user_role;	
	var parent;
	var url_edit;
	var data;

	$('.btn-submit').on('click',function(){
		$( this ).hide();
	});
	
	$('input').on('change',function(){
		$('.btn-submit').show();
	});

	$('select').on('change',function(){
		$('.btn-submit').show();
	});

	$('textarea').on('change',function(){
		$('.btn-submit').show();
	});
	$('.show_button').on('click',function () {
		$('.dt-buttons').show();
	});
	if($('form.absence').length > 0) {
		$( "#request_type" ).on('change',function() {
			$('input[name=start_date]').prop('disabled', false);
			$('input[name=start_date]').prop('hidden', false);
			$('input[name=end_date]').prop('disabled', false);
			$('input[name=end_date]').prop('hidden', false);
			type = $(this).val();
			employee_id = $('#select_employee').val();

			if(type == 'IZL' || type == 63) {
				start_date = $( "#start_date" ).val();
				end_date = $( "#end_date" );
				end_date.val(start_date);			
				
				date.setDate(date.getDate()-1);
				tomorrow = date.getFullYear() + '-' + ( '0' + (date.getMonth()+1) ).slice( -2 ) + '-' + ( '0' + (date.getDate()) ).slice( -2 );
				$('#start_date').attr('min', tomorrow);
				$('#start_date').val(today);
				$('.form-group.time_group').show();
				$('.form-group.date2').hide();

				checkTime();

			} else {
				$('.form-group.time_group').hide();
				$('.form-group.date2').show();
				$('#start_date').removeAttr('min');
				$('.tasks').remove();
			}
			if(type == 'GO' || type == 'holiday') {
				if( employee_id != '' && employee_id != undefined) {
					getDays( employee_id );
				}
			} else {
				$('.days_employee').text("");
				$('.days_employee').hide();
				$('.btn-submit').show();
			}
		
			if(type == 'BOL' || type == 2 || type == 'IZL' || type == 63) { 
				$('.datum.date2').hide();
				if(type == 'BOL' || type == 2) {
				
					$('#end_date').val(null);
					$('#end_date').prop('required', false);
				} else {
					$('#end_date').prop('required', true);
				}
			} else {
				$('.datum.date2').show();
			}

			if(type == 'SLD' || type == 66) {
				employee_id = $('#select_employee').val();

				url = location.origin + '/days_offUnused/'+employee_id;
				console.log(url);
				$.ajax({
					url: url,
					type: "get",
					success: function( days_response ) {
						broj_dana = days_response;
						if( broj_dana == 0 ) {
							$('.days_employee').text("Nemoguće poslati zahtjev. Svi slobodni dani su iskorišteni!");
							$('.btn-submit').hide();
						} else {
							$('.days_employee').text("Neiskorišteno "+broj_dana+" slobodnih dana");
							$('.btn-submit').show();
						}
						$('.days_employee').show();
					},
					error: function(jqXhr, json, errorThrown) {
						console.log(jqXhr);
						console.log(json);
						console.log(errorThrown);
					}
				});
			}
		});
		
		$( "#select_employee" ).on('change',function() {
			employee_id = $(this).val();
			type = $( "#request_type" ).val();
			
			if( employee_id != '' &&  employee_id != undefined && (type == 'GO' || type == 'holiday') ) {
				getDays( employee_id );
			}
		});	
		
		$( "#start_date" ).on('change',function() {
			start_date = $( this ).val();
			end_date = $( "#end_date" );
			

			if(type == 'BOL' || type == 2) {
				end_date.val(null);
			} else {
				end_date.val(start_date);
			}
			
			StartDate = new Date(start_date);
			EndDate = new Date(end_date);
			if(EndDate != 'Invalid Date' &&  EndDate < StartDate) {
				$('.days_request').text('Nemoguće poslati zahtjev. Završni datum ne može biti prije početnog');
				$('.days_request').show();
			} 
		
			employee_id = $('#select_employee').val();
			
		});
		
		$( "#end_date" ).on('change',function() {
			start_date = $( "#start_date" ).val();
			end_date = $( this ).val();
			
			StartDate = new Date(start_date);
			EndDate = new Date(end_date);
		
			if(EndDate != 'Invalid Date' && EndDate < StartDate) {
				$('.days_request').text('Nemoguće poslati zahtjev. Završni datum ne može biti prije početnog');
				$('.days_request').show();
				$('.btn-submit').hide();
			} else {
				$('.days_request').text("");
				$('.days_request>span').text("");
				$('.days_request').hide();
				$('.btn-submit').show();
				if(start_date == '') {
					$( "#start_date" ).val(end_date);
				}
	
				type = $( "#request_type" ).val();
				employee_id = $('#select_employee').val();
				if( employee_id != '' && (type == 'GO' || type == 'holiday') ) {
					url = location.origin + '/daniGO';
					$.ajax({
						url: url,
						type: "get",
						data:  { start_date: start_date, end_date: end_date},
						success: function( days_response ) {
							user_role = $('#user_role').text();	
							if( (broj_dana - days_response) < 0 && user_role != 'admin' ) {
								$('.days_request').text('Nemoguće poslati zahtjev. Broj dana zahtjeva je veći od broja neiskorištenih dana za');
								$('.days_request>span').text( broj_dana - days_response);
								$('.days_request').show();
								$('.btn-submit').hide();
							} else {
								$('.days_request>span').text("");
								$('.days_request').hide();
								$('.btn-submit').show();
							} 
						},
						error: function(jqXhr, json, errorThrown) {
							console.log(jqXhr);
							console.log(json);
							console.log(errorThrown);
						}
					});
				}
			}
		});

		$('.edit_absence').on('submit', function(e) {
			e.preventDefault();
			url_edit = $( this ).attr('action');
			
		/* 	data_array = $( this ).serializeArray();
			id = data_array[0].value */
			
			console.log(url_edit);

			approve = $('#filter_approve').val();
			type = $('#filter_types').val();
			month = $('#filter_years').val();
			employee_id =  $('#filter_employees').val();

			url_load = location.href + '?month='+month+'&type='+type+'&employee_id='+employee_id+'&approve='+approve;
			data =  $( this ).serialize();
			console.log(data);
			$.ajax({
				url: url_edit,
				type: 'POST',
				data: data,
				beforeSend: function(){
					$('body').prepend('<div id="loader"></div>');
				},
				success: function(result) {
					$('tbody ').load(url_load + " tbody>tr",function(){
						$('#loader').remove();
					
						$.modal.close();
						$("#filter_types").find('option[value="'+type+'"]').attr('selected',true);
						$('#filter_employees').find('option[value="'+employee_id+'"]').attr('selected',true);
						$('#filter_years').find('option[value="'+month+'"]').attr('selected',true);
						$('#filter_approve').find('option[value="'+approve+'"]').attr('selected',true);
					});
				}
			});
		});
	}

	if($('form.form_afterhour').length > 0 || $('form.form_work_diary').length > 0) {
		select_employee ();
		select_project ();
		dateChange ();
		checkTime();
	}

	function dateChange () {
		$('#date').on('change', function(){
			employee_id = $( '#select_employee' ).val();
			start_date =  $( '#date' ).val();
			/* console.log('dateChange: ' + 'employee_id '+employee_id + ' start_date '+start_date); */
			if( employee_id != '' && employee_id != undefined && start_date != '' && start_date != undefined ) {
				getProjectEmpl ( employee_id, start_date );
			}
		});
	}

	function select_employee () {
		$('#select_employee').on('change', function(){
			employee_id = $( '#select_employee' ).val();
			start_date =  $( '#date' ).val();
			/* console.log('select_employee: ' + 'employee_id '+employee_id + ' start_date '+start_date); */
			if( employee_id != '' && employee_id != undefined && start_date != '' && start_date != undefined ) {
				getProjectEmpl ( employee_id, start_date );
			}
		});
	}

	function select_project () {
		$('select[id^="select_project"]').on('change', function(){
			parent = $(this).parent().parent();
			employee_id = $( '#select_employee' ).val();
			start_date =  $( '#date' ).val();
			project = $( this ).val();
			/* console.log('select_project: ' + 'employee_id '+employee_id + ' start_date '+start_date+ ' project '+project); */
			getTasksEmpl ( employee_id, start_date, project, parent );
		});
	}

	function getProjectEmpl ( employee_id, start_date ) {
		url = location.origin + '/getProject';
	
		$.ajax({
			url: url,
			type: "get",
			data:  { employee_id: employee_id, start_date: start_date },
			success: function( projects ) {
				console.log(projects);
				if($.map(projects, function(n, i) { return i; }).length > 0 ) {
					projectElement(projects);
				}
				console.log("projects.length "+projects.length);
			
			/* 	getTasksEmpl ( employee_id, start_date, Object.keys(projects)[0] ); */
			},
			error: function(jqXhr, json, errorThrown) {
				console.log(jqXhr);
				console.log(json);
				console.log(errorThrown);
			}
		}); 
	}
	
	function getTasksEmpl ( employee_id, start_date, project, parent )	{
		url = location.origin + '/getTasks';
		console.log('getTasksEmpl: ' + 'employee_id '+employee_id + ' start_date '+start_date+ ' project '+project);
		$.ajax({
			url: url,
			type: "get",
			data:  { employee_id: employee_id, start_date: start_date, project:project },
			success: function( tasks ) {
				console.log(tasks);
				if($.map(tasks, function(n, i) { return i; }).length > 0 ) {
					tastElement(tasks, parent);
				} else {
					$(parent).find('.tasks select option').not('.disabled').remove();
				}
			},
			error: function(jqXhr, json, errorThrown) {
				console.log(jqXhr);
				console.log(json);
				console.log(errorThrown);
			}
		}); 
	}

	function projectElement(projects) {
		var select_projects = '<option value="" disabled selected>Izaberi projekt</option>';
		$.each(projects, function( id, project ) {
			select_projects += '<option class="project_list" name="erp_task_id" value="'+id+'">'+project+'</option>';
		});
		
		$('select[id^="select_project"] option').remove();
		if ($('select[id^="select_project"]').length > 0 ) {
			$('select[id^="select_project"]').prepend(select_projects);
		}
	}
	
	function tastElement(tasks, parent) {
		var select_tasks = '<option value="" disabled selected>Izaberi zadatak</option>';

		$.each(tasks, function( id, task ) {
			select_tasks += '<option class="project_list" name="erp_task_id" value="'+id+'">'+task+'</option>';
		});
		select_tasks += '</select></div>';
	
		$(parent).children('div.form-group.tasks').find('select[id^="select_task"]').children('option').remove();
		$(parent).children('div.form-group.tasks').find('select[id^="select_task"]').prepend(select_tasks);
		if( select_tasks == '') {
			$(parent).children('div.form-group.tasks').find('select[id^="select_task"]').prop('required', false);
		}
	}
	
	function getDays( employee_id )	{
		user_role = $('#user_role').text();	
		url = location.origin + '/getDays/'+employee_id;
		$.ajax({
			url: url,
			type: "get",
			success: function( days_response ) {
				broj_dana = days_response;
				
				if( broj_dana == 0 && user_role != 'admin') {
					$('.days_employee').text("Nemoguće poslati zahtjev. Svi su dani iskorišteni!");
					$('input[name=start_date]').prop('disabled', true);
					$('input[name=end_date]').prop('disabled', true);
					$('.btn-submit').hide();
				} else {
					$('.days_employee').text("Neiskorišteno "+broj_dana+" dana razmjernog godišnjeg odmora");
					$('input[name=start_date]').prop('disabled', false);
					$('input[name=end_date]').prop('disabled', false);
					$('.btn-submit').show();
				}
				$('.days_employee').show();
			},
			error: function(jqXhr, json, errorThrown) {
				console.log(jqXhr);
				console.log(json);
				console.log(errorThrown);
			}
		}); 
	}

	function checkTime() {
		$('input[name=end_time]').on('blur',function(){
			start_time = $('input[name=start_time]').val();
			end_time = $('input[name=end_time]').val();

			time_diff =  new Date("1970-1-1 " + end_time) - new Date("1970-1-1 " + start_time);
			time_diff = time_diff/1000/60/60;
			console.log(time_diff);
		
			if( time_diff <= 0 ) {
				$('.time_request').show();
				$('.btn-submit').hide();
				$('.btn-submit').prop('disabled',true);
				$('.btn-submit').css('border','2px solid red');
			} else {
				$('.time_request').hide();
				$('.btn-submit').show();
				$('.btn-submit').prop('disabled',false);
				$('.btn-submit').css('border','1px solid rgb(21, 148, 240)');
			} 
		});
	}
});	
if($('.main_ads').length >0) {
    $('.select_filter.sort').on('change',function () {
        $('main.main_ads').load($(this).val() + ' main.main_ads article');
    });
    
    var body_width = $('body').width();
    if(body_width > 450) {
        var all_height = [];
        $('.noticeboard_notice_body.panel .ad_content').each(function(){
            all_height.push($(this).height());
        });
        all_height.sort(function(a, b) {
            return b-a;
        });
        var max_height = all_height[0];
        $('.noticeboard_notice_body.panel .ad_content').height(max_height);
        
    }
}

$('.benefit_body').first().show();

$('.benefit_title').on('click',function(){
	$('.benefit_title').removeClass('active');
	var id = $(this).attr('id');
	console.log(id);
	$('.benefit_body').hide();
	$('.benefit_body#_'+id).show();
	$(this).addClass('active');
});
var main_benefits_height = $('.main_benefits').height();
var main_benefits_head_height = $('.main_benefits_head').height()+40;
var benefits_scroll = $('.benefits_scroll').height();
var body_width = $('body').width();
var div_width = $( '.main_benefits_head').width();
var all_width = 0;

$('.benefit_title').first().addClass('active');

if(body_width > 450) {
	
	$('.main_benefits_body').height(main_benefits_height-main_benefits_head_height-benefits_scroll);
	$( ".main_benefits_head > .benefit_title" ).each( (index, element) => {
		all_width += 203;
	});
	if((all_width - 30) > (div_width +1 )) {
		$('.main_benefits .scroll_right').show();
	}
} else {
	$( ".main_benefits_head > .benefit_title" ).each( (index, element) => {
		all_width += 110;
	});
	if((all_width - 10) > (div_width +1 )) {
		$('.main_benefits .scroll_right').show();
	}
}
	
$('#right-button').on('click',function(event) {
	event.preventDefault();
	$('.main_benefits_head').animate({
		scrollLeft: "+=203px"
	}, "slow");
	$('.main_benefits .scroll_left').show();
	
});

$('#left-button').on('click',function(event) {
	event.preventDefault();
	$('.main_benefits_head').animate({
		scrollLeft: "-=203px"
	}, "slow");
	if($('.main_benefits_head').scrollLeft() < 203 ) {
		$('.main_benefits .scroll_left').hide();
	} else {
		$('.main_benefits .scroll_left').show();
	}
});

$( window ).on('resize',function() {
	$( ".main_benefits_head > .benefit_title" ).each( (index, element) => {
		all_width += 203;
	});
	
	if((all_width - 30) > (div_width +1 )) {
		$('.main_benefits .scroll_right').show();
	} else {
		$('.main_benefits .scroll_right').hide();
	}
});
// kalendar dashboard
$( function () {
    if( $("#calendar").length > 0) {
        var locale = $('.locale').text();
        $('.dates li').first().addClass('active_date');
        var div_width = $( '.dates').width();
        var all_width = 69;
        var dates = $('.box-content').find('.dates');

        if(locale == 'en' || locale == 'uk' ) {
            var day_of_week = new Array("SUN","MON","TUE","WED","THU","FRI","SAT");
            var monthNames = new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
        } else if(locale == 'hr')  {
            var day_of_week = new Array("ned","pon","uto","sri","čet","pet","sub");
            var monthNames = new Array("sij","velj","ožu","tra","svi","lip","srp","kol","ruj","lis","stu","pro");
        }
       
        var today = new Date();
        var date_today = today.getFullYear() + '-' +  ('0' + (today.getMonth() +1) ).slice(-2) + '-' + ('0' + today.getDate()).slice(-2);
        var broj_dana = div_width / all_width;
    
        dates.append('<li id="li-' + date_today + '" class="active_date"><span class="month">' + monthNames[today.getMonth()] +  '</span><span class="day">' + today.getDate() +  '</span><span class="week_day">' + day_of_week[today.getDay()]  +  '</span><span class="display_none YYYY_mm">' + today.getFullYear()  + '-' + + ('0' + (today.getMonth()+1)).slice(-2)+ '</span></li>');
    
        for(i=0; i<broj_dana-1; i++) {
            var date_plus1 = new Date(today.setDate(today.getDate() +1));
            var date_new = date_plus1.getFullYear() + '-' +  ('0' + (date_plus1.getMonth() +1) ).slice(-2) + '-' + ('0' + date_plus1.getDate()).slice(-2);
            dates.append('<li id="li-' + date_new + '" class=""><span class="month">' + monthNames[date_plus1.getMonth()] +  '</span><span class="day">' + date_plus1.getDate() +  '</span><span class="week_day">' + day_of_week[date_plus1.getDay()]  +  '</span><span class="display_none YYYY_mm">' + today.getFullYear()  + '-' + + ('0' + (today.getMonth()+1)).slice(-2)+ '</span></li>');
        }
    
        $( window ).on('resize',function() {
            var div_width = $( '.dates').width();
            var broj_dana = div_width / all_width;
            
            for(i=0; i<broj_dana; i++) {
                var date_plus1 = new Date(today.setDate(today.getDate() +1));
        
                var date_new = date_plus1.getFullYear() + '-' +  ('0' + (date_plus1.getMonth() +1) ).slice(-2) + '-' + ('0' + date_plus1.getDate()).slice(-2);
        
                dates.append('<li id="li-' + date_new + '" class=""><span class="month">' + monthNames[date_plus1.getMonth()] +  '</span><span class="day">' + date_plus1.getDate() +  '</span><span class="week_day">' + day_of_week[date_plus1.getDay()]  +  '</span><span class="display_none YYYY_mm">' + today.getFullYear()  + '-' + + ('0' + (today.getMonth()+1)).slice(-2)+ '</span></li>');
            }
    
        });
       
        //prikaz evenata za selektirani dan
        $('.dates li').on('click',function(){
            var active_li =  $(this).attr('id');
            var active_date = active_li.replace('li-','');
            var this_li = $(this).attr('id');
            var url = location.origin + '/dashboard?active_date='+active_date;
           
            $('.dates>li').removeClass('active_date');
            $( this ).addClass('active_date');
    
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });   
            $.ajax({
                url: url,
                type: "GET",
                success: function( response ) {
                    $('.comming_agenda').load(url + ' .comming_agenda>section',function(){
                        if( $( '.comming_agenda .all_agenda .agenda').length == 0 ) {
                           $('.comming_agenda .placeholder').show();
                        };
                       
                        if(this_li) {
                            var this_id = this_li.replace("li-",""); // selektirani datum
                            $( ".comming_agenda > .agenda" ).each( (index, element) => {
                                $(element).addClass('display_none');
                                $(element).removeClass('show_agenda');
                                if($(element).attr('id') == this_id ) {
                                    $(element).removeClass('display_none');
                                    $(element).addClass('show_agenda');
                                }
                            });
                        }
                        if(! $('.comming_agenda .agenda.show_agenda').length) {
                            $('.comming_agenda .placeholder').show();
                            var placeholder_height =  $('.placeholder img').height();
                       //      $('.calendar .comming_agenda').height(placeholder_height + 60);
                          $('.placeholder_cal >p').css('line-height',placeholder_height + 'px' );
                        } else {
                            $('.comming_agenda .placeholder').hide();
                        }
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
    
        setHeight();
        function setHeight() {
            if($('body').width() > 1000) {
                var div_height = $('section.calendar>div').height();
                var calendar_height = $('section.calendar #calendar').height();
               
               // $('.comming_agenda').height(div_height - calendar_height -30);
            }
        }
        
        if(! $('.comming_agenda .all_agenda.show_agenda').length) {
            var calendar_height = $('section.calendar>div').height() - $('section.calendar #calendar').height() -40;
            $('.comming_agenda .placeholder').show();
            var placeholder_height =  $('.placeholder img').height();
            /* /* $('.calendar .comming_agenda').height(calendar_height ); */
            //   $('.placeholder_cal >p').css('line-height',placeholder_height + 'px' ); */
        } else {
            $('.comming_agenda .placeholder').hide();
        }
        
        $('#left-button').on('click',function() {
            var active_li = $('.dates').find('li.active_date');
    
            var first_li = $(dates).find('li').first();
            var day = first_li.find('.day').text();
            var month = first_li.find('span.YYYY_mm').text().slice(5,7);
            var year = first_li.find('span.YYYY_mm').text().slice(0,4);
            var currentDate = new Date(year + '-' + month + '-' + day);
            var date_prev = new Date(currentDate.setDate(currentDate.getDate() -1));
    
            var date = date_prev.getFullYear() + '-' +  ('0' + (date_prev.getMonth() +1) ).slice(-2) + '-' + ('0' + date_prev.getDate()).slice(-2);
    
            if($('.dates').scrollLeft() == 0) {
                dates.prepend('<li id="li-' + date + '" class=""><span class="month">' + monthNames[date_prev.getMonth()] +  '</span><span class="day">' + date_prev.getDate() +  '</span><span class="week_day">' + day_of_week[date_prev.getDay()]  +  '</span><span class="display_none YYYY_mm">' + currentDate.getFullYear()  + '-' + + ('0' + (currentDate.getMonth()+1)).slice(-2)+ '</span></li>');
            }
            $('.dates').animate({
                scrollLeft: "-=69"
            }, "slow");
            var previous_li = active_li.prev();
            previous_li.addClass('active_date');
            active_li.removeClass('active_date');
            
            previous_li.trigger('click');
            setHeight();
            $.getScript( '/../js/event_click.js');
        });
    
        $('#right-button').on('click',function() {
            
            var active_li = $('.dates').find('li.active_date');
    
            var last_li = $(dates).find('li').last();
            var day_last = last_li.find('.day').text();
            var month = last_li.find('span.YYYY_mm').text().slice(5,7);
            var year = last_li.find('span.YYYY_mm').text().slice(0,4);
    
            var lastDate = new Date(year + '-' + month + '-' + day_last);
            var date_next = new Date(lastDate.setDate(lastDate.getDate() +1));
    
            var next_date = date_next.getFullYear() + '-' + ('0' + (date_next.getMonth() +1) ).slice(-2) + '-' + ('0' + date_next.getDate()).slice(-2);
      
            $('.dates').animate({
                scrollLeft: "+=69"
            }, "slow");
            var count_li = 0;
            $( ".dates > li" ).each(function (index, element) {
                all_width += 69;
                count_li++;
            });
            if(((count_li * 69) - (div_width + 69)) < $('.dates').scrollLeft() ){
                dates.append('<li id="li-' + next_date + '" class=""><span class="month">' + monthNames[date_next.getMonth()] +  '</span><span class="day">' + date_next.getDate() +  '</span><span class="week_day">' + day_of_week[date_next.getDay()] +  '</span><span class="display_none YYYY_mm">' + date_next.getFullYear()  + '-' + + ('0' + (date_next.getMonth()+1)).slice(-2)+ '</span></li>');
            }
            var next_li = active_li.next();
    
            next_li.addClass('active_date');
            active_li.removeClass('active_date');
            next_li.trigger('click');        
            setHeight();
            $.getScript( '/../js/event_click.js');
        });
    }
});
if($('.campaign_show').length > 0) {
    $('.campaign_show').on('click',function(e){
        window.history.replaceState({}, document.title, $(this).attr('href') ); 
     /*   location = $(this).attr('href'); */
        e.preventDefault();
        var page = $(this).attr('href');
        $('.index_main').load( page + ' .index_main > section', function() {
            $.getScript('/../js/jquery-ui.js');
            $.getScript( '/../js/sequence_dragDrop.js');
            $.getScript( '/../restfulizer.js');
           /*  $('.collapsible').click(function(event){
                $(this).siblings().toggle();
            }); */
           /*  $('.link_back').click(function(e){
                e.preventDefault();
                $('.campaigns_button').click();
               
            });  */
               /* Radi!!! Load back sa sekvence na kampanje*/
               $('.main_noticeboard .header_document .link_back').on('click', function(e){
                e.preventDefault();
                var url = location['origin'] +'/campaigns';
                
                $('.container').load( url + ' .container > div', function() {
                   
                    $.getScript( '/../js/datatables.js');
                    $.getScript( '/../js/filter_table.js');                    
                    $.getScript( '/../restfulizer.js');
                    $.getScript( '/../js/event.js');
                    $.getScript( '/../js/campaign.js');
                    /* $('.collapsible').click(function(event){        
                        $(this).siblings().toggle();
                    }); */
                    
                });
                window.history.pushState( location.href, 'Title',  url);
    
             }); 
            $('.campaign_mail').on('click', function(){
                $.modal.defaults = {
                    closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
                    escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
                    clickClose: true,       // Allows the user to close the modal by clicking the overlay
                    closeText: 'Close',     // Text content for the close <a> tag.
                    closeClass: '',         // Add additional class(es) to the close <a> tag.
                    showClose: true,        // Shows a (X) icon/link in the top-right corner
                    modalClass: "modal campaign_mail",    // CSS class added to the element being displayed in the modal.
                    // HTML appended to the default spinner during AJAX requests.
                    spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',
                
                    showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
                    fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
                    fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
                };
            });
        });
    });
}

$('.period').on('change',function(){
    if($(this).val() == 'customized') {
        $('#period .period').hide();
        $('#period .period').removeAttr('required');
        $('#interval').show();
        $('input.input_interval').prop( "required" );
    }
});

$('.label_custom_interal').click(function(){
    $('#period .period').hide();
    $('#period .period').removeAttr('required');
    $('#interval').show();
    $('input.input_interval').prop( "required" );
});

$('.label_period').on('click',function(){
    $('#period .period').show();
    $('#period .period').prop('required');
    $('#interval').hide();
    $('input.input_interval').removeAttr( "required" );
});

var form_sequence_height = $('.form_sequence').height();
var header_campaign_height = $('.header_campaign').height();
if($('body').width() > 760) {
    $('.main_campaign').height(form_sequence_height-header_campaign_height);
}

var url = $('form.form_sequence').attr('action');
var html; 
var design; 

try {
    unlayer.init({
        appearance: {
            theme: 'light',
            panels: {
                tools: {
                    dock: 'left'
                }
            }
        },
        id: 'editor-container',
        projectId: 4441,
        displayMode: 'email'
    })
    
    unlayer.addEventListener('design:updated', function(updates) {
        unlayer.exportHtml(function(data) {
            /* json = data.design; */ // design json
            html = data.html; // design html
            design = data.design;
         /*    $('#text_html').text(html);
            $('#text_json').text(JSON.stringify(design)); */
          /*   console.log(html);
            console.log(JSON.stringify(design)); */
        })
    })	
} catch (error) {
    
}		

$('.form_sequence.create .btn-submit').on('click',function(e) {
    var validate = [];
    e.preventDefault();
    var form = $('.form_sequence')[0];

    var data = new FormData(form);              // Create an FormData object 
    data.append('text_json',JSON.stringify(design) );
    data.append('text_html', html );

    $(".btn-submit").prop("disabled", true);   // disabled the submit button
    
    var form_data_array = $('.form_sequence').serializeArray();

    jQuery.each( form_data_array, function( i, field ) {
        if(field.value == "" ) {
            validate.push("block");
        } else {
            validate.push(true);
        }
    });
    if( html == undefined  || JSON.stringify(design) == undefined ) {
        validate.push("block");
    } else {
        validate.push(true);
    }
 
    if(validate.includes("block") ) {
        e.preventDefault();
        alert("Nisu uneseni svi parametri, nemoguće spremiti sekvencu");
     } else {    
        $(".btn-submit").prop("disabled", false);
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function (data) {
                alert("Dizajn je spremljen!");
                $(".btn-submit").prop("disabled", false);
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
     }  
});

$(function() {
    $('.nav-link').css('background-color','#F8FAFF !important');
    $('.active.nav-link').css('background-color','#1594F0 !important'); 
});

var dataArrTemplates;
var htmlTemplates;
var designTemplates;
var temp;

if($('.dataArrTemplates').text()) {
    dataArrTemplates = JSON.parse( $('.dataArrTemplates').text()); // template JSON */
    /* console.log(dataArrTemplates); */
    
    $.each(dataArrTemplates, function(i, item) {

        var title = item.title;
        $('#template-container').append('<span class="template_button blockbuilder-content-tool" id="' + i +'"><div>'+title+'</div></span>');
    });
}
$('.template_button').on('click',function(){
    temp = $( this ).attr('id');

    designTemplates = JSON.parse( dataArrTemplates[temp].text_json); // template JSON */
    htmlTemplates = dataArrTemplates[temp].text; 
    unlayer.loadDesign(designTemplates);
    $('.show_temp#temp' + temp).remove();
});
$( ".template_button" ).on('mouseover', function(){
        temp = $( this ).attr('id');
        htmlTemplates = dataArrTemplates[temp].text; 
        $('body').append('<span class="show_temp" id="temp' + temp +'">'+htmlTemplates+'</span>');
        $('.show_temp#temp'+temp).show();
        
});
$( ".template_button" ).on('mouseout', function(){
    $('.show_temp#temp' + temp).remove();
});
$('.period').on('change',function(){
    if($(this).val() == 'customized') {
        $('#period .period').hide();
        $('#period .period').removeAttr('required');
        $('#interval').show();
        $('input.input_interval').prop( "required" );
    }
});

$('.label_custom_interal').click(function(){
    $('#period .period').hide();
    $('#period .period').removeAttr('required');
    $('#interval').show();
    $('input.input_interval').prop( "required" );
});
$('.label_period').click(function(){
    $('#period .period').show();
    $('#period .period').prop('required');
    $('#interval').hide();
    $('input.input_interval').removeAttr( "required" );
});
var form_sequence_height = $('.form_sequence').height();
var header_campaign_height = $('.header_campaign').height();

$('.main_campaign').height(form_sequence_height-header_campaign_height);

try {
    var design = JSON.parse( $('.dataArr').text()); // template JSON */
    var html = $('.dataArrHtml').text();
    var form_data = $('.form_sequence').serialize();
    var url = $('form.form_sequence').attr('action');
    var data_new = {};
    var json = '';
    var html = '';
    var id = $('#id').val();

    unlayer.init({
        appearance: {
            theme: 'light',
            panels: {
                tools: {
                    dock: 'left'
                }
            }
        },
        id: 'editor-container',
        projectId: 4441,
        displayMode: 'email'
    })
    unlayer.loadDesign(design);
    
    
    unlayer.addEventListener('design:updated', function(updates) {
        unlayer.exportHtml(function(data) {
            json = data.design; // design json
            html = data.html; // design html
    
        /* 	$('#text_html').text( html.replace(/\n\s+|\n/g, ""));
            $('#text_json').text(JSON.stringify(json)); */
        })
    
    })
} catch (error) {
    
}

$('.form_sequence.edit .btn-submit').on('click',function(e) {
    var validate = [];
	e.preventDefault();
	form_data = $('.form_sequence').serialize();
    form_data_array = $('.form_sequence').serializeArray();
    data_new = form_data;
    var form = $('.form_sequence')[0];
    var data = new FormData(form);              // Create an FormData object 
    data.append('text_json', JSON.stringify(design) );
    data.append('text_html', html );
    jQuery.each( form_data_array, function( i, field ) {
        if(field.value == "" ) {
            validate.push("block");
        } else {
            validate.push(true);
        }
      });

    console.log(form_data_array);
    console.log(validate); 

    if(validate.includes("block") ) {
        e.preventDefault();
      
        alert("Nisu uneseni svi parametri, nemoguće spremiti sekvencu");
        
     } else {    
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });     
    
        $.ajax({
            type: "post",
            enctype: 'multipart/form-data',
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function (data) {
                alert("Dizajn je spremljen!");
                console.log("SUCCESS : ", form_data_array);
                $(".btn-submit").prop("disabled", false);
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
     }  
});

$('.btn-back').on('click',function(e){
  /*   e.preventDefault();

    url = location.origin + '/dashboard';
    console.log("url "+ url);
    console.log("referrer "+ document.referrer);
    window.location = url;
    console.log(" window.location = url");
 */
  /*   window.location = location.origin + "/campaign_sequences/" + campaign_id; */
});

var dataArrTemplates;
var htmlTemplates;
var designTemplates;

if($('.dataArrTemplates').text()) {
    dataArrTemplates = JSON.parse( $('.dataArrTemplates').text()); // template JSON */
    
    $.each(dataArrTemplates, function(i, item) {
       /*  html = dataArrTemplates[i].text;  */
        var title = dataArrTemplates[i].title; 
        $('#template-container').append('<span class="template_button blockbuilder-content-tool" id="' + i +'"><div>'+title+'</div></span>');
    });
}
$('.template_button').on('click',function(){
    temp = $( this ).attr('id');

    designTemplates = JSON.parse( dataArrTemplates[temp].text_json); // template JSON */
    htmlTemplates = dataArrTemplates[temp].text; 
    unlayer.loadDesign(designTemplates);
    $('.show_temp#temp' + temp).remove();
});
$( ".template_button" ).on('mouseover', function(){
        temp = $( this ).attr('id');
        htmlTemplates = dataArrTemplates[temp].text; 
        $('body').append('<span class="show_temp" id="temp' + temp +'">'+htmlTemplates+'</span>');
        $('.show_temp#temp'+temp).show();
        
});
$( ".template_button" ).on('mouseout', function(){
    $('.show_temp#temp' + temp).remove();
});


var ctx = $('#myChart');
var dataArr = $('.dataArr').text();   

try {
    if(ctx.length >0) {
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pročitano', 'Nepročitano'],
                datasets: [{
                    label: '# of Votes',
                    data: dataArr,
                    backgroundColor: [
                        'rgba(21, 148, 240, 1)',
                        'rgba(241, 241, 241, 1)',
                    ],
                    borderColor: [
                        'rgba(21, 148, 240, 1)',
                        'rgba(241, 241, 241, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                legend: {
                    display: false,
                    labels: {
                        fontColor: 'rgb(255, 99, 132)'
                    }
                },
                tooltips: {
                    enabled: false,
                }
            }
        });
    }
} catch (error) {
    
}


var ctx1 = $('#myChart1');
try {
    if(ctx1.length > 0) {
        var myChart = new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: ['Pročitano', 'Nepročitano'],
                datasets: [{
                    label: '# of Votes',
                    data: [25, 75],
                    backgroundColor: [
                        'rgba(7, 30, 87, 1)',
                        'rgba(241, 241, 241, 1)',
                    ],
                    borderColor: [
                        'rgba(7, 30, 87, 1)',
                        'rgba(241, 241, 241, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                legend: {
                    display: false,
                    labels: {
                        fontColor: 'rgb(255, 99, 132)'
                    }
                },
                tooltips: {
                    enabled: false,
                }
            }
        });
    }
    
} catch (error) {
    
}


var ctx2 = $('#myChart2');
try {
    if(ctx2.length>0) {
        var myChart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Pročitano', 'Nepročitano'],
                datasets: [{
                    label: '# of Votes',
                    data: [25, 75],
                    backgroundColor: [
                        'rgba(234, 148, 19, 1)',
                        'rgba(241, 241, 241, 1)',
                    ],
                    borderColor: [
                        'rgba(234, 148, 19, 1)',
                        'rgba(241, 241, 241, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                legend: {
                    display: false,
                    labels: {
                        fontColor: 'rgb(255, 99, 132)'
                    }
                },
                tooltips: {
                    enabled: false,
                }
            }
        });
    }
    
} catch (error) {
    
}

var ctx3 = $('#myChart3');
try {
    if(ctx3.length >0) {
        var myChart = new Chart(ctx3, {
            type: 'doughnut',
            data: {
                labels: ['Pročitano', 'Nepročitano'],
                datasets: [{
                    label: '# of Votes',
                    data: [25, 75],
                    backgroundColor: [
                        'rgba(19, 234, 144, 1)',
                        'rgba(241, 241, 241, 1)',
                    ],
                    borderColor: [
                        'rgba(19, 234, 144, 1)',
                        'rgba(241, 241, 241, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                legend: {
                    display: false,
                    labels: {
                        fontColor: 'rgb(255, 99, 132)'
                    }
                },
                tooltips: {
                    enabled: false,
                }
            }
        });
    }
    
} catch (error) {
    
}


$(function() { 
    $('.collapsible').on('click',function(event){ 
        console.log("collapsible");
       if($(this).siblings().is(":visible")){ 
            $(this).siblings().css('display','none');
        } else {
            $(this).siblings().css('display','block');
        }
     
    });
    $('.index_page table.dataTable .content').on('mouseleave',function(){
        $(this).hide();
    });

    $('.modal.modal_questionnaire .question .content').on('mouseleave',function(){
        $(this).hide();
    });
    $('.modal.modal_questionnaire .category .content').on('mouseleave',function(){
        $(this).hide();
    });

   
});
if( $('.competence_table').length > 0 ) {
    var mouse_is_inside = false;
    var form = $('form.form_evaluation');
    var url;
    var ev_id;
    var rating_id;
    var comment;
    var value;
    var container;
    var rating = 0;
    var rating2 = 0;
    var all_rating = 0;
    var all_rating2 = 0;
    var total_rating = 0;
    var total_group = 0;
    var element_id;
    var coefficient;
    var q_rating;

    $('.show_button_upload').on('click', function(){
        element_id = $(this).attr('id');
        console.log("show_button_upload");
        $('.upload_file.'+element_id).modal();
        $('.upload_file.'+element_id).show();
    });


    $('.rating_radio.evaluate_manager>input').on('click',function(){
        mouse_is_inside = true;
        ev_id = $(this).attr('title');
        container = null;
        rating_id = $(this).val();
        comment = null;
        submit_form (ev_id,rating_id, comment);
    });

    $('.rating_radio.evaluate_user>input').on('click',function() {
        coefficient = parseFloat( $('#coefficient').text());
       
        total_rating = 0;
        total_group = 0;
        $( ".rating_radio.evaluate_user input:checked" ).each(function( index, element ) {
            q_rating = parseFloat($( element ).siblings('span.span_question_rating').text());
            console.log($(element).next('label.label_rating').text());
            rating = parseFloat($( element ).next('label.label_rating').text()) * coefficient * q_rating;
            console.log(rating);
            if($.isNumeric( rating ) ) {
                total_rating+=rating;
            }

            if( $(element).is(':visible')) {
                total_group +=  rating; 
            }
        });

        $('.rating_all span').text(total_rating.toFixed(2));
        $('.mySlides:visible .rating_group span').text(total_group.toFixed(2))
    });
    
    $(".evaluation_comment").on('change',function(){
        container = $( this );
        mouse_is_inside = true;
        ev_id = $(this).attr('title');
        comment = $(this).val();
        rating_id = null;
        $(document).click(function(e) {
            if(container && !container.is(e.target) && mouse_is_inside == true ) {
                submit_form (ev_id,rating_id, comment);
            }
        });
    });
    
    function submit_form (ev_id, rating_id, comment) {
        /* form_data = form.serialize();  */
        if(rating_id) {
            url = $( form ).attr('action') + '?rating_id='+rating_id+'&id='+ev_id;
        }
        if( comment ) {
            url = $( form ).attr('action') + '?comment='+comment+'&id='+ev_id;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });  
        $.ajax({
            url: url,
            type: "post",
            success: function( response ) {
                console.log(response);
                mouse_is_inside = false;
            }, 
            error: function(jqXhr, json, errorThrown) {
                $(".btn-submit").prop("disabled", false);
                var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
                                    'message':  jqXhr.responseJSON.message,
                                    'file':  jqXhr.responseJSON.file,
                                    'line':  jqXhr.responseJSON.line };

                console.log(data_to_send); 
        
                $('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + "Podaci nisu spremljeni, došlo je do greške: " + data_to_send.message + '</div></div></div>').appendTo('body').modal();
            }
        });
    }

    $('.filter_evaluation').on('change',function(){
        $('.total_rating').remove();
        var element = $(this);
        value = $(this).val().toLowerCase();
        console.log(value);
        if( value == 'all') {
            $(".tr_evaluation").show();
        } else {
            $(".tr_evaluation").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
            all_rating = 0;
            all_rating2 = 0;
            $( ".rating_empl:visible" ).each(function( index ) {
                rating =  parseFloat($( this ).text());
                all_rating+=rating;
            });
            $( ".edit_evaluation_id:visible input:checked" ).each(function( index ) {
                rating2 = parseFloat($( this ).siblings('label').text());
                if($.isNumeric( rating2 ) ) {
                    all_rating2+=rating2;
                }
            });
            $( element ).parent().after('<p class="total_rating">Ukupna bodovi: '+all_rating.toFixed(2)+'</p>');
         /*    $( element ).parent().after('<p class="total_rating">Ukupna ocjena nadređenog: '+all_rating2+'</p>'); */
            
        }
    });

    $('.prev').on('click',function(){
        $('.slideshow-container form').animate({ scrollTop: 0 }, "slow");
        return false;
    });
    $('.next').on('click',function(){
        $('.slideshow-container form').animate({ scrollTop: 0 }, "slow");
        return false;
    });
}
$( function () {
	if( $('table.display').not('.evidention_employee table.display').not('.all_absences #index_table').length >0 ) {
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
		if ($('#index_table').hasClass('sort_0_desc')) {
			kolona = 0;
			sort = 'desc';
		}
		if ($('#index_table').hasClass('sort_1_asc')) {
			kolona = 1;
			sort = 'asc';
		}
		if ($('#index_table').hasClass('sort_2_desc')) {
			kolona = 2;
			sort = 'desc';
		}
		if ($('#index_table').hasClass('sort_3_desc')) {
			kolona = 3;
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
		
		if($('table.display').length > 0) {
			var table = $('table.display').not('.evidention_employee table.display', '.all_absences #index_table').DataTable( {
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
				"fixedHeader": false,
				"colReorder": true,
				"columnDefs": [ {
					"targets"  :target,
					"type": 'date-eu'
				}],
				stateSave: true,
				dom: 'Bfrtip',
				buttons: [
					'copyHtml5',
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
					},
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
						/* 	var width = (100/count_col) + '%';
							for (let index = 0; index < th_length.length; index++) {
								widths.push(width);
							}
							doc.content[1].table.widths = widths; */
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
			if($(".index_table_filter .show_button").length == 0) {
				$('.index_table_filter').not('.index_table_filter.structure_company').append('<span class="show_button"><i class="fas fa-download"></i></span>');
			}
			
			$('.show_button').on('click',function () {
				$('.dt-buttons').show();
			});
			$('a.toggle-vis').on( 'click', function (e) {
				e.preventDefault();
				// Get the column API object
				var column = table.column( $(this).attr('data-column') );
				
				// Toggle the visibility
				column.visible( ! column.visible() );
			});
			table.columns( '.col_hidden' ).visible( false );
		}
	
		$('table.display').show();
	}
});

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
						footer: true,
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
									if( i == 3 || i == 8 || i == 15 || i == 17) {
										doc.content[1].table.body[i][j].fillColor = 'lightgrey';
									}
									if( i == 24 ) {
										doc.content[1].table.body[i][j].bold = true;
										doc.content[1].table.body[i][j].fontSize = 10;
										doc.content[1].table.body[i][j].fillColor = 'lightgrey';
									}
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
						footer: true,
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

$( function () {
    if( $('.index_page.index_documents').length > 0) {
        var body_width = $('body').width();
        var div_width = $( '.preview_doc').width();
        var all_width = 115;
       
        $( ".preview_doc > .thumbnail" ).each( (index, element) => {
            all_width += 115;
        });
    
        if(all_width > div_width ) {
            $('.preview_doc .scroll_right').show();
        }
        /* $('.collapsible').click(function(event){ 
            $(this).siblings().toggle();
        }); */
        $('#right-button').on('click',function(event) {
            event.preventDefault();
            $('.preview_doc').animate({
                scrollLeft: "+=115px"
            }, "slow");
            $('.preview_doc .scroll_left').show();
            
        });
    
        $('.thumbnail').each(function(){
            var src = $(this).attr('title');
        //	$( this ).find('.ajax-content').load(src);
        });
    
        $('#left-button').on('click',function(event) {
            event.preventDefault();
            $('.preview_doc').animate({
                scrollLeft: "-=115px"
            }, "slow");
            if($('.preview_doc').scrollLeft() < 115 ) {
                $('.preview_doc .scroll_left').hide();
            } else {
                $('.preview_doc .scroll_left').show();
            }
        });
        
        var documents_height = $('.all_documents').height();
        var filter_height = $('.dataTables_filter').height();
        var table_height = documents_height - filter_height;
        var body_height;
    //    $('.display.table.dataTable').height(table_height);
    
        var index_height = $('.index_main.main_documents').height();
        var header_height = $('.page-header.header_document').height();
        
        if(body_width<768) {
         //   $('.all_documents').css('height','auto');
        } 
    
        $('.show').on('click',function(){
            $('.show').toggle();
            $('.hide').toggle();
            $('.preview_doc').show();
            
            index_height = $('.index_main.main_documents').height();
            header_height = $('.page-header.header_document').height();
            body_height = index_height - header_height - 60;
            $('.all_documents').height(body_height);
        });
        
        $('.hide').on('click',function(){
            $('.show').toggle();
            $('.hide').toggle();
            $('.preview_doc').hide();
            index_height = $('.index_main.main_documents').height();
            header_height = $('.page-header.header_document').height();
            body_height = index_height - header_height - 60;
            $('.all_documents').height(body_height);
        });
        
        $('.button_nav').css({
           /*  'background': '#051847',
            'color': '#ffffff' */
        });
        
        $( '.doc_button' ).css({
           /*  'background': '#0A2A79',
            'color': '#ccc' */
        });
        
        $(function() {
             $('#index_table').css('height','fit-content');
        });
    }
});
	$('.efc_show').on('click',function(){
       // $('.efc').css('visibility','initial');
       $('.salery_hidden').hide();
       $('.salery_show').toggle();
       $('.salery_show').css('display','block');
        $('.efc_show').hide();
        $('.efc_hide').show();
    });
    $('.efc_hide').on('click',function(){
       // $('.efc').css('visibility','hidden');
       $('.salery_hidden').toggle();
       $('.salery_hidden').css('display','block');
       $('.salery_show').toggle();
        $('.efc_show').show();
        $('.efc_hide').hide();
    });
if( $('.energy_consumptions').length >0) {
    var location_id;
    var energy_id;
    var counter = null;
    var counter1 = null;
    var counter2 = null;

    $( "select[name=location_id]" ).on('change',function() {
        location_id = $(this).val();
        energy_id = $("select[name=energy_id]").val();
        
        if( location_id != null && energy_id != null ) {
            console.log(location_id);
            console.log(energy_id);
            countSource(location_id, energy_id);
        }
    });

    $( "select[name=energy_id]" ).on('change',function() {
        energy_id = $(this).val();
        location_id = $("select[name=location_id]").val();

        if( location_id != null && energy_id != null ) {
            countSource(location_id, energy_id);
        }
    });

    function countSource (location_id, energy_id) {
        url = location.origin + '/lastCounter/'+location_id+'/'+energy_id;
        console.log(url);
        $.ajax({
            url: url,
            type: "get",
            success: function( data ) {
                console.log(data);
                counter1 = data.counter[1];
                counter2 = data.counter[2];
                var no_counter = data.no_counter;
              
                console.log(counter1);
                console.log(counter2);
                console.log(no_counter);
               
                if( counter1 != null) {
                    $('.last_counter span').text( counter1 );
                } 
                if( no_counter > 1 ) {
                    $('.hidden_counter').show();
                    $('.last_counter2 span').text( counter2 );
                    $('input[name=counter2]').attr('disabled',false);
                } else {
                    $('.hidden_counter').hide();
                    $('input[name=counter2]').attr('disabled',true);
                    $('.last_counter2 span').text('');
                }
            },
            error: function(jqXhr, json, errorThrown) {
                console.log(jqXhr);
                console.log(json);
                console.log(errorThrown);
            }
        });
    }

    function countSource_last (location_id, energy_id, date) {
        url = location.origin + '/lastCounter_Skip/'+location_id+'/'+energy_id+'/'+date;
        $.ajax({
            url: url,
            type: "get",
            success: function( data ) {
                console.log(data);
                counter1 = data[1];
                counter2 = data[2];
                if( counter1 != null) {
                    $('.last_counter span').text( counter1 );
                    $('#result').text( $('input[name=counter').val() - counter1 );
                } 
                if( counter2 != null) {
                    $('.last_counter span').text( counter1 );
                    $('#result2').text( $('input[name=counter').val() - counter2 );
                } 
            },
            error: function(jqXhr, json, errorThrown) {
                console.log(jqXhr);
                console.log(json);
                console.log(errorThrown);
            }
        });
    }

    $( "input[name=counter]" ).on('keyup',function() {
        counter = $(this).val();
        last_counter = $('.last_counter span').text();
        console.log(counter);
        console.log(last_counter);

        if( counter && last_counter ) {
            $( '#result' ).text( counter - last_counter);
            if( (counter - last_counter) <0 ) {
                $( '#result' ).css('color','red');
            } else {
                $( '#result' ).css('color','inherit');
            }
        }
    });
    $( "input[name=counter2]" ).on('keyup',function() {
        counter2 = $(this).val();
        last_counter2 = $('.last_counter2 span').text();
        console.log(counter2);
        console.log(last_counter2);

        if( counter2 && last_counter2 ) {
            $( '#result2' ).text( counter2 - last_counter2);
            if( (counter2 - last_counter2) <0 ) {
                $( '#result2' ).css('color','red');
            } else {
                $( '#result2' ).css('color','inherit');
            }
        }
    });

    if( $('.energy_consumptions.edit').length >0) {
        location_id = $("select[name=location_id]").val();
        energy_id = $("select[name=energy_id]").val();
        date = $("input[type=date]").val();

        countSource_last(location_id, energy_id, date);
    }
}
$(function() {
    var url_basic = location.origin + '/events';
    var calendar_main_height;
    var calendar_aside_height;
    var body_width = $('body').width();
    var view;
    var data1;
    if( $('.dataArr').text()) {
        var data1 = JSON.parse( $('.dataArr').text());
    }
   if( $('.calender_view').length >0) {
        $('.calender_view').pignoseCalendar({
        multiple: false,
        week: 1,
        weeks: [
            'Ned',
            'Pon',
            'Uto',
            'Sri',
            'Čet',
            'Pet',
            'Sub',
        ],
        monthsLong: [
            'Siječanj',
            'Veljača',
            'Ožujak',
            'Travanj',
            'Svibanj',
            'Lipanj',
            'Srpanj',
            'Kolovoz',
            'Rujan',
            'Listopad',
            'Studeni',
            'Prosinac'
        ],
        months: [
            'Sij',
            'Velj',
            'Ožu',
            'Tra',
            'Svi',
            'Lip',
            'Srp',
            'Kol',
            'Ruj',
            'Lis',
            'Stu',
            'Pro'
        ],
        controls: {
                ok: 'ok',
                cancel: 'poništi'
        },
        init: function(context) {
            calendar_aside_height = $('.calendar_aside').height();
            calendar_main_height = $('.calendar_main').height();
            if($('body').width() > 450 && $('body').height() < 768) {
                $('.index_aside .day_events').height('fit-content');   
            } else if($('body').width() > 450) {
                $('.index_aside .day_events').height(calendar_aside_height -calendar_main_height - 110 );   
            } else {
              //  $('.index_aside .day_events').height(calendar_aside_height -calendar_main_height - 60 );
            }
                   
        },
        scheduleOptions: {
            colors: {
                event: '#1390EA',
                task: '#eb0e0e',
                birthday: '#EA9413',
                GO: '#13EA90',
                IZL: '#13EA90',
                BOL: '#13EA90',
                locco: '#009933',
            }
        },
        schedules: data1,
        select: function(date, schedules, context) {
            /**
             * @params this Element
             * @params event MouseEvent
             * @params context PignoseCalendarContext
             * @returns void
             */
            var $this = $(this); // This is clicked button Element.
            if(date[0] != null && date[0] != 'undefined') {
                if(date[0]['_i'] != 'undefined' && date[0]['_i'] != null) {
                    var day = date[0]['_i'].split('-')[2];
                    var month = date[0]['_i'].split('-')[1]; // (from 0 to 11)
                    var year = date[0]['_i'].split('-')[0];
                    var datum = year + '-' + month + '-' + day;
                    view = $('.change_view_calendar').val();
                    
                    var d = new Date(datum);
                    if( d != 'Invalid Date') {
                        var url = url_basic + '?dan=' + datum;
                        get_url(url, datum);
                    } 
                    /*    if(body_width < 768) {
                        $('.index_main.index_event').modal();
                    }   */
                }
            }
            
        },
        prev: function(info, context) {
            // This is clicked arrow button element.
            var $this = $(this);

            // `info` parameter gives useful information of current date.
            var type = info.type; // it will be `prev`.
            var year = info.year; // current year (number type), ex: 2020
            var month = info.month; // current month (number type), ex: 2
            var day = info.day; // current day (number type), ex: 22
            
            // You can get target element in `context` variable.
            var element = context.element;

            // You can also get calendar element, It is calendar view DOM.
            var calendar = context.calendar;
            
            var prevDate = new Date(year + '-' + month + '-' + day);
            var month_before = prevDate.getMonth()+1; 
            var searchDate = year + '-' + ('0' + (month_before) ).slice(-2) + '-' + ('0' + (day)).slice(-2);
            var d = new Date(searchDate);
            /*  $('.pignose-calendar-unit-date').find('[data-date="' + searchDate + '"] > a' ).click(); */
            if( d != 'Invalid Date') {
                var url = url_basic + '?dan=' + searchDate;
            
                get_url(url, searchDate);
            }
        },
        next: function(info, context) {
            /**
             * @params context PignoseCalendarPageInfo
             * @params context PignoseCalendarContext
             * @returns void
             */

            // This is clicked arrow button element.
            var $this = $(this);

            // `info` parameter gives useful information of current date.
            var type = info.type; // it will be `next`.
            var year = info.year; // current year (number type), ex: 2017
            var month = info.month; // current month (number type), ex: 6
            var day = info.day; // current day (number type), ex: 22
            
            // You can get target element in `context` variable.
            var element = context.element;

            // You can also get calendar element, It is calendar view DOM.
            var calendar = context.calendar;

            var currentDate = new Date(year + '-' + month + '-' + day);
            var month_after = currentDate.getMonth() +1; 
            var searchDate = year + '-' + ('0' + (month_after) ).slice(-2) + '-' + ('0' + (day)).slice(-2);                
            var d = new Date(searchDate);
            if( d != 'Invalid Date') {
                var url = url_basic + '?dan=' + searchDate;

                get_url(url, searchDate);
            }
        }   
        });
   }
    
   $('.index_aside .day_events').show();

    function get_url(url, datum ) {
        $.get(url, { dan: datum }, function(data, status){
            var content =  $('.day_events>div',data ).get(0).outerHTML;
            $( ".day_events" ).html( content );
            $('.index_aside .day_events').show();
            var content_2 = $('.index_event>section',data ).get(0).outerHTML;
            $( ".index_event" ).html( content_2 );
            /* var content_3 = $('.calender_view>.pignose-calendar ',data ).get(0).outerHTML;
            $( ".calender_view" ).html( content_3 );  */
            $('.main_calendar_month tbody td').on('click',function(){
                var date = $(this).attr('data-date');
                $('.pignose-calendar-body').find('[data-date="' + date + '"] > a' ).trigger("click");
            });
        
            $( ".change_employee" ).on('change',function() {
                var value = $(this).val().toLowerCase();
                $(".show_event").filter(function() {
                    //$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    $(this).toggle($(this).hasClass(value));
                });
                $(".month_event").filter(function() {
                    //$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    $(this).toggle($(this).hasClass(value));
                });
                if(value == '') {
                    $(".show_event").show();
                    $(".month_event").show();
                }
            });

            $( ".change_car" ).on('change',function() {
                var value = $(this).val().toLowerCase();
                $(".show_locco").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    
                });
                if(value == '') {
                    $(".show_locco").show();
                }
            });
            
            $( ".change_view_calendar" ).on('change',function() {
                view = $( this ).val();
                if(view == 'day') {
                    $('.main_calendar_day').show();
                    $('.main_calendar_week').hide();
                    $('.main_calendar_month').hide();
                    $('.main_calendar_list').hide();
                    $('button.show_locco').show();
                    var scroll = $('.hour_val.position_8').position().top;
                    $('.main_calendar_day').scrollTop(scroll);
                } 
                if(view == 'week') {
                    $('.main_calendar_day').hide();
                    $('.main_calendar_week').show();
                    $('.main_calendar_month').hide();
                    $('.main_calendar_list').hide();
                    $('button.show_locco').show();
                    var scroll = $('.main_calendar_week tr.position_8').position().top;
                    $('.main_calendar_week').scrollTop(scroll);
                } 
                if(view == 'list') {
                        $('.main_calendar_list').show();
                        $('.main_calendar_day').hide();
                        $('.main_calendar_week').hide();
                        $('.main_calendar_month').hide();
                        $('.change_car').hide();
                        $('button.show_locco').hide();
                    } 
                if(view == 'month') {
                        $('.main_calendar_day').hide();
                        $('.main_calendar_week').hide();
                        $('.main_calendar_month').show();
                        $('.main_calendar_list').hide();
                        $('button.show_locco').show();

                }
            });
            
            $('button.show_loccos').on('click',function(e){
                e.preventDefault();
                $('.main_calendar td>a').toggle();
                $('.main_calendar .show_event').toggle();
                $('.main_calendar .show_locco ').toggle();
                $('.change_employee').toggle();
                $('.change_car').toggle();
            });
            
            var position_selected_day = $('.selected_day').position().top -30;
            $('.main_calendar_month').scrollTop(position_selected_day);
        
            select_view();
        });
    }
    function select_view() {
        if(view == 'day') {
            $('.change_view_calendar').val('day') ;
            $('.main_calendar').hide();
            $('.main_calendar_day').show();
            
        } else if(view == 'month') {
            $('.change_view_calendar').val('month') ;                                
            $('.main_calendar').hide();
            $('.main_calendar_month').show();
           
        } else if(view == 'week') {
            $('.change_view_calendar').val('week') ;
            $('.main_calendar').hide();
            $('.main_calendar_week').show();
        } else if(view == 'list') {
            $('.change_view_calendar').val('list') ;
            $('.main_calendar').hide();
            $('.main_calendar_week').show();
        }
    }
});
$('.shortcuts_container .shortcut').on('click',function(){
    $('.icon_delete').toggle();
});

$('.shortcuts_container .new_open').on('click',function(){
    $('<div><div class="modal-header">Novi prečac</div><div class="modal-body" style="padding-top: 20px"><p>Da biste dodali prečac otvorite stranicu koju želite i u gornjem desnom kutu pronađite link za spremanje "Prečaca"</p><p>Ukoliko Prečac već postoji na stranici imate mogućnost promijeniti naslov prečaca</p></div></div>').modal();
});
$('.shortcuts_container .open_new_shortcut').on('click',function(){
    $('<div><div class="modal-header">Novi prečac</div><div class="modal-body" style="padding-top: 20px"><p>Da biste dodali prečac otvorite stranicu koju želite i u gornjem desnom kutu pronađite link za spremanje "Prečaca"</p><p>Ukoliko Prečac već postoji na stranici imate mogućnost promijeniti naslov prečaca</p></div></div>').modal();
});

 var shortcuts_container_width = $('.shortcuts_container').first().width();
var shortcut_box_width = shortcuts_container_width / 6;
/* $('.shortcut_box').width(shortcut_box_width-15); */

$('#right-button-scroll').on('click',function(event) {
    event.preventDefault();
    $('.shortcuts_container .profile_images').animate({
        scrollLeft: "+=127px"
    }, "slow");
    $('.profile_images .scroll_left').show();
});
$('#left-button-scroll').on('click',function(event) {
    event.preventDefault();
    $('.shortcuts_container .profile_images').animate({
        scrollLeft: "-=127px"
    }, "slow");
});
$('.event_show .type_event').on('click',function(){
    $('.event_hidden').show();
    $('#event_type').val('event');
    $('.event_show').hide();
});
$('.event_show .type_task').on('click',function(){
    $('.event_hidden').show();
    $('#event_type').val('task');
    $('.event_show').hide();
});
$('.event_show .type_other').on('click',function(){
    $('.event_hidden').show();
    $('#event_type').val('other');
    $('.event_show').hide();
});
$(function() {
	$("#mySearch").on( 'keyup', function() { //ima funkcija u filter_table
		console.log("mySearch1");
		var value = $(this).val().toLowerCase();
		$(".panel").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
	$("#mySearch1").on( 'keyup',function() {
		var value = $(this).val().toLowerCase();
		$(".panel1").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
	$("#mySearch_noticeboard").on( 'keyup',function() {
		var value = $(this).val().toLowerCase();
		$(".panel").parent().filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
});
$(function() { // filter knowledge base
	var value = null;
	var date = null;
	var year = null;
	var employee_id = null;
	var task = null;
	var url;
	var project;
/* 
	$('.change_month_afterhour').on('change',function(){
		date =  $(this).val().toLowerCase();
		employee_id =  $('.change_employee_afterhour').val();
		if(employee_id != '' && employee_id != null) {
			employee_id = employee_id.replace("empl_","");
		}
		url =  location.href + '?date='+date+'&employee_id='+employee_id;
		$.ajax({
			url: url,
			type: "get",
			beforeSend: function(){
				// Show image container
				$('body').prepend('<div id="loader"></div>');
			},
			success: function( response ) {
				$( '#admin_page >main' ).load(url + ' #admin_page >main .table-responsive',function(){
				
					$('#loader').remove();
					$.getScript('/../js/datatables.js');
					$('.show_button').on('click',function () {
                        $('.dt-buttons').toggle();		
                    })
					$.getScript('/../restfulizer.js');
					if( $('tbody tr').length == 0)  {
						$('.btn-store').hide();
					} else {
						$('.btn-store').show();
					}
				});
			},
			error: function(jqXhr, json, errorThrown) {
				console.log(jqXhr.responseJSON.message);
			}
		});
	});
	
	$('.change_employee_afterhour').on('change',function(){
		employee_id =  $(this).val().toLowerCase();
		if(employee_id != '' && employee_id != null) {
			employee_id = employee_id.replace("empl_","");
		}
		date =  $('.change_month_afterhour').val();
		
		$.ajax({
			url: location.href + '?date='+date+'&employee_id='+employee_id ,
			type: "get",
			beforeSend: function(){
				// Show image container
				$('body').prepend('<div id="loader"></div>');
			},
			success: function( response ) {
				$( '#admin_page >main' ).load(location.href + '?date='+date+'&employee_id='+employee_id + ' #admin_page >main .table-responsive',function(){
					$('#loader').remove();
					$.getScript('/../js/datatables.js');
					$('.show_button').on('click',function () {
                        $('.dt-buttons').toggle();		
                    })
					$.getScript('/../restfulizer.js');
					if( $('tbody tr').length == 0)  {
						$('.btn-store').hide();
					} else {
						$('.btn-store').show();
					}
				});
			},
			error: function(jqXhr, json, errorThrown) {
				console.log(jqXhr.responseJSON.message);
			}
		});
	}); */

	$('.filter_employees').not('.main_absence .filter_employees').on('change',function(){
		employee_id = $( this ).val();
		if( employee_id == 'all') {
			employee_id = null;
		}
		url = location.href + '?employee_id='+employee_id;

		if( $('#filter_tasks').length > 0) { 
			task = $('#filter_tasks').val();
			if( task == 'all') {
				task = null;
			}
			url = url + '&task='+task;
		}
		if( $('#filter_month').length > 0) { 
			date = $('#filter_month').val();
			if( date == 'all') {
				date = null;
			}
			url = url + '&date='+date;
		}
		if( $('#filter_project').length > 0) { 
			project = $('#filter_project').val();
			if( project == 'all') {
				project = null;
			}
			url = url + '&project='+project;
		}
		console.log(url);
		$.ajax({
			url:url,
			type: "get",
			beforeSend: function(){
				$('body').prepend('<div id="loader"></div>');
			},
			success: function( response ) {
				if( url.includes('work_diaries/1')) {
					$( '.page-main' ).load(url + ' .page-main .diary_project',function(){
						$('#loader').remove();
						$.getScript('/../js/datatables.js');
						$('.show_button').on('click',function () {
							$('.dt-buttons').toggle();		
						})
						$.getScript('/../restfulizer.js');
					});
				} else {
					$( '#admin_page >main' ).load(url + ' #admin_page >main .table-responsive',function(){
						$('#loader').remove();
						$.getScript('/../js/datatables.js');
						$('.show_button').on('click',function () {
							$('.dt-buttons').toggle();		
						})
						$.getScript('/../restfulizer.js');
					});
				}
				
			},
			error: function(jqXhr, json, errorThrown) {
				console.log(jqXhr.responseJSON.message);
			}
		});	
	});

	$('.filter_tasks').on('change',function(){
		task =  $(this).val();
		date =  $('#filter_month').val();
		employee_id = $( '#filter_employees' ).val();

		url = location.href;

		
		if( task == 'all') {
			task = null;
		}
		url = url + '?task='+task;
		
		if( $('#filter_month').length > 0) { 
			date = $('#filter_month').val();
			if( date == 'all') {
				date = null;
			}
			url = url + '&date='+date;
		}
		if( $('#filter_employees').length > 0) { 
			employee_id = $('#filter_employees').val();
			if( employee_id == 'all') {
				employee_id = null;
			}
			url = url + '&employee_id='+employee_id;
		}
		if( $('#filter_project').length > 0) { 
			project = $('#filter_project').val();
			if( project == 'all') {
				project = null;
			}
			url = url + '&project='+project;
		}
		/* url =  url + '?date='+date+'&task='+task+'&employee_id='+employee_id; */
		console.log(url);
		$.ajax({
			url:url,
			type: "get",
			beforeSend: function(){
				// Show image container
				$('body').prepend('<div id="loader"></div>');
			},
			success: function( response ) {
				if( url.includes('work_diaries/1')) {
					$( '.page-main' ).load(url + ' .page-main table',function(){
						$('#loader').remove();
						$.getScript('/../js/datatables.js');
						$('.show_button').on('click',function () {
							$('.dt-buttons').toggle();		
						})
						$.getScript('/../restfulizer.js');
					});
				} else {
					$( '#admin_page >main' ).load(url + ' #admin_page >main .table-responsive',function(){
						$('#loader').remove();
						$.getScript('/../js/datatables.js');
						$('.show_button').on('click',function () {
							$('.dt-buttons').toggle();		
						})
						$.getScript('/../restfulizer.js');
					});
				}
			},
			error: function(jqXhr, json, errorThrown) {
				console.log(jqXhr.responseJSON.message);
			}
		});
	});

	$('.filter_project').on('change',function(){
		project = $( this ).val();
		if( project == 'all') {
			project = null;
		}
		url = location.href + '?project='+project;

		if( $('#filter_tasks').length > 0) { 
			task = $('#filter_tasks').val();
			if( task == 'all') {
				task = null;
			}
			url = url + '&task='+task;
		}
		if( $('#filter_employees').length > 0) { 
			employee_id = $('#filter_employees').val();
			if( employee_id == 'all') {
				employee_id = null;
			}
			url = url + '&employee_id='+employee_id;
		}
		if( $('#filter_month').length > 0) { 
			date =  $('#filter_month').val();
			if( date == 'all') {
				date = null;
			}
			url = url + '&date='+date;
		}
		
		console.log(url);
		$.ajax({
			url:url,
			type: "get",
			beforeSend: function(){
				// Show image container
				$('body').prepend('<div id="loader"></div>');
			},
			success: function( response ) {
				if( url.includes('work_diaries/1')) {
					$( '.page-main' ).load(url + ' .page-main table',function(){
						$('#loader').remove();
						$.getScript('/../js/datatables.js');
						$('.show_button').on('click',function () {
							$('.dt-buttons').toggle();		
						})
						$.getScript('/../restfulizer.js');
					});
				} else {
					$( '#admin_page >main' ).load(url + ' #admin_page >main .table-responsive',function(){
						$('#loader').remove();
						$.getScript('/../js/datatables.js');
						$('.show_button').on('click',function () {
							$('.dt-buttons').toggle();		
						})
						$.getScript('/../restfulizer.js');
					});
				}
			},
			error: function(jqXhr, json, errorThrown) {
				console.log(jqXhr.responseJSON.message);
			}
		});
	});

	$('#filter').on('change',function() {
		var trazi = $(this).val().toLowerCase();
		console.log("filter");
		console.log(trazi);
		if(trazi == "all"){
			$('.panel').show();
		} else {
			$('.panel').filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(trazi) > -1);
			});
		}
	});	
	$('#filter1').on('change',function() {
		console.log('filter1');
		var trazi = $(this).val().toLowerCase();
		console.log(trazi);

		$('.div_keyResultTasks').show();
		$( ".div_keyResults" ).show();
		
		if(trazi == "svi"){
			$('.panel').show();
		} else {
			$('.panel').filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(trazi) > -1);
			});
		}
	});	
	
	$('#filter_car').on('change',function() {
		var date =  $('#filter_month').val().toLowerCase();
		var car =  $('#filter_car').val();
	
		if( date == 'all') {
			date = null;
		}
		url = location.href + '?date='+date;
		if(car == 'all') {
			car = null;
		}
		url = url + '&car='+car;
		console.log(url);

		$.ajax({
			url: url,
			type: "get",
			data: { 'date': date},
			beforeSend: function(){
				$('body').prepend('<div id="loader"></div>');
			},
			success: function( response ) {
				$( '#admin_page >main' ).load(url + ' #admin_page >main .table-responsive',function(){
					$('#loader').remove();
					$.getScript('/../js/datatables.js');
					/* $('.show_button').on('click',function () {
						console.log("show_button click 2");
                        $('.dt-buttons').toggle();		
                    }) */
					$.getScript('/../restfulizer.js');
				});
			},
			error: function(jqXhr, json, errorThrown) {
				console.log(jqXhr.responseJSON.message);
			}
		});

	});	

	$('.filter_notices').on('change',function() {
		date =  $('#filter_month').val().toLowerCase();
		url =location.href + '?date='+date;
		console.log(url);
		$.ajax({
			url: url,
			type: "get",
			data: { 'date': date},
			beforeSend: function(){
				// Show image container
				$('body').prepend('<div id="loader"></div>');
			},
			success: function( response ) {
				/* console.log(response); */
				$( '.notices' ).load(location.href + '?date='+date + ' .notices>article',function(){
					$('#loader').remove();
					$.getScript('/../restfulizer.js');
				});
			},
			error: function(jqXhr, json, errorThrown) {
				console.log(jqXhr.responseJSON.message);
			}
		});
	});	

	$('#filter_month').on('change',function() {
		date =  $('#filter_month').val().toLowerCase();
		if( date == 'all') {
			date = null;
		}

		url = location.href + '?date='+date;
		
		if( $('#filter_tasks').length > 0) { 
			task = $('#filter_tasks').val();
			if( task == 'all') {
				task = null;
			}
			url = url + '&task='+task;
		}
		if( $('#change_employee_afterhour').length > 0) { 
			employee_id = $('#change_employee_afterhour').val();
			if( employee_id == 'all') {
				employee_id = null;
			}
			url = url + '&employee_id='+employee_id;
		}
		 
		if( $('#filter_employees').length > 0) { 
			employee_id = $('#filter_employees').val();
			if( employee_id == 'all') {
				employee_id = null;
			}
			url = url + '&employee_id='+employee_id;
		}
		if( $('#filter_project').length > 0) { 
			project = $('#filter_project').val();
			if( project == 'all') {
				project = null;
			}
			url = url + '&project='+project;
		}
		if( $('#filter_car').length > 0) { 
			car = $('#filter_car').val();
			if( car == 'all') {
				car = null;
			}
			url = url + '&car='+car;
		}

		console.log(url);
		$.ajax({
			url: url,
			type: "get",
			data: { 'date': date},
			beforeSend: function(){
				// Show image container
				$('body').prepend('<div id="loader"></div>');
			},
			success: function( response ) {
				if( url.includes('work_diaries/1')) {
					$( '.page-main' ).load(url + ' .page-main>.diary_project',function(){
						console.log("work_diaries");
						console.log(url);
						console.log(location.href );
						$('#loader').remove();
						$.getScript('/../js/datatables.js');
						$.getScript('/../js/collaps.js');
						
						$('.show_button').on('click',function () {
							$('.dt-buttons').toggle();		
						})
						
					$.getScript('/../restfulizer.js');
					});
				} else {
					$( '#admin_page >main' ).load(url + ' #admin_page >main .table-responsive',function(){
						$('#loader').remove();
						$.getScript('/../js/datatables.js');
						$('.show_button').on('click',function () {
							console.log("show_button click 3");
							$('.dt-buttons').toggle();
						})
						$.getScript('/../restfulizer.js');
					});
				}
			},
			error: function(jqXhr, json, errorThrown) {
				console.log(jqXhr.responseJSON.message);
			}
		});
	});	
	
	$('.filter_travel').on('change',function() {
		var employee_id = $('#filter_employee').val().toLowerCase();
		var date = $('#filter_date').val().toLowerCase();
		url = location.origin + '/travel_orders';

		if(employee_id == 'all' ) {
			employee_id = '';
		} 
		if(date == 'all') {
			date = '';
		}

		if(employee_id == '' && date == ''){
			$('.panel').show();
		} else {
			$('.panel').filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(date) > -1 && $(this).text().toLowerCase().indexOf(employee_id) > -1);
			});
		}
		/* $.ajax({
			url: url,
			type: "get",
			data: { 'employee_id': employee_id,'date': date},
			success: function( response ) {
				console.log(response);
				$( '#admin_page' ).load( url + '?employee_id='+employee_id+'&date='+date, function( response, status, xhr ) {
					 
					if ( status == "error" ) {
						  var msg = "Sorry but there was an error: ";
						  $( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
					  }
					
					  $('#filter_employee option.id_'+ employee_id).attr('selected','selected');
					  $('#filter_date option.date_'+ date).attr('selected','selected');
					  $.getScript( '/../restfulizer.js');
				  });
				
			},
			error: function(jqXhr, json, errorThrown) {
				console.log(jqXhr.responseJSON.message);
			}
		}); */
	/* 	$('#filter_employee option.id_'+ employee_id).attr('selected','selected');
		$('#filter_date option.date_'+ date).attr('selected','selected'); */
	});	

	if( $('.section_notice .notices').length > 0) {
		$('.select_filter.sort').on('change',function () {
			$('.section_notice .notices').load($(this).val() + ' .section_notice .notices .noticeboard_notice_body');
		});
	}

	$('.filter_checkout').on('change',function(e) {
		var check = $(this).val();
		url = location.href + '?status='+check;

		$.ajax({
			url: url,
			type: "get",
			beforeSend: function(){
				$('body').prepend('<div id="loader"></div>');
			},
			success: function( response ) {
				$( '#admin_page >main' ).load(url + ' #admin_page >main .table-responsive',function(){
					$('#loader').remove();
					$.getScript('/../js/open_modal.js');
					$.getScript('/../js/datatables.js');
					$('.show_button').on('click',function () {
                        $('.dt-buttons').toggle();		
                    })
					$.getScript('/../restfulizer.js');
				});
			},
			error: function(jqXhr, json, errorThrown) {
				console.log(jqXhr.responseJSON.message);
			}
		});
	});

	if( $('.table-responsive.roles').length > 0) {
		
		$('.more').on('click',function(){
			$( this ).siblings('.role').toggle();
			$( this ).hide();
			$( this ).siblings('.hide').show();
		});
		$('.hide').on('click',function(){
			$( this ).siblings('.role').hide();
			$( this ).siblings('.role._0').show();
			$( this ).siblings('.role._1').show();

			$( this ).siblings('.more').show();
			$( this ).hide();
		});
	}
});
function mySearchTable() {
  $("#mySearchTbl").on('keyup',function() {
    var value = $(this).val().toLowerCase();
    var search_Array = value.split(" ");

    $(".display.table tbody tr").filter(function() {
      if( search_Array.length == 1 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1);
      } else if( search_Array.length == 2 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1);
      } else if( search_Array.length == 3 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1);
      } else if( search_Array.length == 4 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[3]) > -1);
      } else if( search_Array.length == 5 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[4]) > -1);
      }
    });
    if($("#index_table1 tbody tr").length > 0 ) {
      $("#index_table1 tbody tr").filter(function() {
        if( search_Array.length == 1 ) {
          $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1);
        } else if( search_Array.length == 2 ) {
          $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1);
        } else if( search_Array.length == 3 ) {
          $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1);
        } else if( search_Array.length == 4 ) {
          $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[3]) > -1);
        } else if( search_Array.length == 5 ) {
          $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[4]) > -1);
        }
      });
    }
  });
}

function mySearch() {
  $("#mySearch").on('keyup',function() {
    var value = $(this).val().toLowerCase();
    var search_Array = value.split(" ");

    $(".panel").filter(function() {
      /* $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1) */
      if( search_Array.length == 1 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1);
      } else if( search_Array.length == 2 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1);
      } else if( search_Array.length == 3 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1);
      } else if( search_Array.length == 4 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[3]) > -1);
      } else if( search_Array.length == 5 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[4]) > -1);
      }
    });
  
  });
}


function mySearchTableAbsence() {
  $("#mySearchTbl").on('keyup',function() {
    var value = $(this).val().toLowerCase();
    var search_Array = value.split(" ");
    $("#index_table tbody tr").filter(function() {
      if( search_Array.length == 1 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1);
      } else if( search_Array.length == 2 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1);
      } else if( search_Array.length == 3 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1);
      } else if( search_Array.length == 4 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[3]) > -1);
      } else if( search_Array.length == 5 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[4]) > -1);
      }
    });
  });
}

function mySearchDoc() {
  $("#mySearch").on('keyup',function() {
    var value = $(this).val().toLowerCase();
    $("#index_table tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
    $(".panel").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
  });
}

/* function mySearch_col1() {
  // Declare variables
  var input, filter, ul, li, a, i;
  input = document.getElementById("mySearch");
  filter = input.value.toUpperCase();
  ul = document.getElementById("myTable");
  li = ul.getElementsByTagName("tr");

  // Loop through all list items, and hide those who don't match the search query
  for (i = 0; i < li.length; i++) {
    a = li[i].getElementsByTagName("td")[0];
	if (a != undefined){
		if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
		  li[i].style.display = "";
		} else {
		  li[i].style.display = "none";
		}
	}
  }
} */

function mySearchElement() {
  $("#mySearchElement").on('keyup', function() {
		var value = $(this).val().toLowerCase();
		$(".user_card").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
}
function GO_value(){
	if(document.getElementById("prikaz").value == "GO" ){
		document.getElementById("napomena").value = "GO" ;
		document.getElementById("zahtjev").innerHTML = "Zahtjev";
	}else {
		document.getElementById("napomena").value = "" ;
	}
	if(document.getElementById("prikaz").value == "Bolovanje" ){
		document.getElementById("zahtjev").innerHTML = "Obavijest";
	}
	if(document.getElementById("prikaz").value == "Izlazak"){
		document.getElementById("zahtjev").innerHTML = "Zahtjev";
	}
	if(document.getElementById("prikaz").value == "NPL" ||document.getElementById("prikaz").value == "PL"){
		document.getElementById("zahtjev").innerHTML = "Zahtjev";
	}
	if(document.getElementById("prikaz").value == "SLD"){
		document.getElementById("zahtjev").innerHTML = "Zahtjev";
	}
	if(document.getElementById("prikaz").value == "Vik"){
		document.getElementById("zahtjev").innerHTML = "Zahtjev";
	}
	
}

$(function() {
    if( $('.index_main.index_event.load_content').length >0 ) {
        var day = $('.event_day .day').text();
        var month =   $('.event_day .month').text();
        var year =  $('.event_day .year').text();
    
        var view;
        function daysInMonth (month, year) { 
            return new Date(year, month, 0).getDate(); 
        } 
        function daysInPrevMonth (month, year) { 
            var d=new Date(year, month, 0);
            d.setDate(1); 
            d.setHours(-1);
            return d.getDate();
        } 
        var currentDate_day = new Date(year + '-' + month + '-' + day);
        var currentDate_week = new Date(year + '-' + month + '-' + day);
        var currentDate_list = new Date(year + '-' + month + '-' + day);
        var currentDate_month = new Date(year + '-' + month + '-' + day);
        var days_in_month = daysInMonth(month, year);
        var days_in_prev_month = daysInPrevMonth(month,year);

        day_after();
        day_before();

        var position_selected_day = $('.selected_day').position().top -30;
        $('.main_calendar_month').scrollTop(position_selected_day);
    
        function day_after() {
            $(document).on('click', '.arrow .day_after', function(e) {
                e.preventDefault(); 
                day = $('.event_day .day').text();
                month =   $('.event_day .month').text()
                year =  $('.event_day .year').text();
                currentDate_day = new Date(year + '-' + month + '-' + day);
                currentDate_week = new Date(year + '-' + month + '-' + day);
                currentDate_list = new Date(year + '-' + month + '-' + day);
                currentDate_month = new Date(year + '-' + month + '-' + day);
                days_in_month = daysInMonth(month, year);
                days_in_prev_month = daysInPrevMonth(month,year);

                var date_after;
                var searchDate;
                view = $( ".change_view_calendar" ).val();
                if(view == 'day') {
                    date_after = new Date(currentDate_day.setDate(currentDate_day.getDate() +1));
                    
                    searchDate = date_after.getFullYear() + '-' + ('0' + (date_after.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_after.getDate()) ).slice(-2);
                    if(month < date_after.getMonth() +1 ) {
                        $('.pignose-calendar-top-nav.pignose-calendar-top-next').click();
                    }
                } else if(view == 'week') {
                    date_after =  new Date(currentDate_week.setDate(currentDate_week.getDate() +7));
                
                    searchDate = date_after.getFullYear() + '-' + ('0' + (date_after.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_after.getDate ()) ).slice(-2);
                    if(month < date_after.getMonth() +1 ) {
                        $('.pignose-calendar-top-nav.pignose-calendar-top-next').click();
                    }
                } else if(view == 'list') {
                    date_after = new Date(currentDate_list.setDate(currentDate_list.getDate() + days_in_month));
                    days_in_month = daysInMonth(date_after.getMonth() +1, date_after.getFullYear());
                
                    searchDate = date_after.getFullYear() + '-' + ('0' + (date_after.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_after.getDate()) ).slice(-2);
                } else if(view == 'month') {
                    date_after = new Date(currentDate_month.setDate(currentDate_month.getDate() + days_in_month));
                    days_in_month = daysInMonth(date_after.getMonth() +1, date_after.getFullYear());
                    searchDate = date_after.getFullYear() + '-' + ('0' + (date_after.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_after.getDate()) ).slice(-2);
                    $('.pignose-calendar-top-nav.pignose-calendar-top-next').click();
                }
                
                /* $('.event_day .day').text(('0' + ( date_after.getDate()) ).slice(-2));
                $('.event_day .week_day').text(day_of_week[date_after.getDay()]);
                $('.month_year').text(monthNames[date_after.getMonth()] + ' ' + date_after.getFullYear()); */
                
                $('.pignose-calendar-body').find('[data-date="' + searchDate + '"] > a' ).click();
            });
        }

        function day_before() {
            $(document).on('click', '.arrow .day_before', function(e) {
                e.preventDefault(); 
                day = $('.event_day .day').text();
                month =   $('.event_day .month').text()
                year =  $('.event_day .year').text();
                currentDate_day = new Date(year + '-' + month + '-' + day);
                currentDate_week = new Date(year + '-' + month + '-' + day);
                currentDate_list = new Date(year + '-' + month + '-' + day);
                currentDate_month = new Date(year + '-' + month + '-' + day);
                days_in_month = daysInMonth(month, year);
                days_in_prev_month = daysInPrevMonth(month,year);
                var date_before;
                var searchDate_bef;
                view = $( ".change_view_calendar" ).val();
                if(view == 'day') {
                    date_before = new Date(currentDate_day.setDate(currentDate_day.getDate() -1));
                    searchDate_bef = date_before.getFullYear() + '-' + ('0' + (date_before.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_before.getDate()) ).slice(-2);
                    if(month > date_before.getMonth() +1 ) {
                        $('.pignose-calendar-top-nav.pignose-calendar-top-prev').click();
                    }
                } else if(view == 'week') {
                    date_before =  new Date(currentDate_week.setDate(currentDate_week.getDate() -7));
                    searchDate_bef = date_before.getFullYear() + '-' + ('0' + (date_before.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_before.getDate ()) ).slice(-2);
                    if(month > date_before.getMonth() +1 ) {
                        $('.pignose-calendar-top-nav.pignose-calendar-top-prev').click();
                    }
                } else if(view == 'list') {
                    date_before = new Date(currentDate_month.setDate(currentDate_month.getDate() - days_in_prev_month));
                    days_in_prev_month = daysInPrevMonth(date_before.getMonth() +1, date_before.getFullYear());
                    
                    searchDate_bef = date_before.getFullYear() + '-' + ('0' + (date_before.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_before.getDate()) ).slice(-2);
                } else if(view == 'month') {
                    date_before = new Date(currentDate_month.setDate(currentDate_month.getDate() - days_in_prev_month));
                    days_in_prev_month = daysInPrevMonth(date_before.getMonth() +1, date_before.getFullYear());
                    searchDate_bef = date_before.getFullYear() + '-' + ('0' + (date_before.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_before.getDate()) ).slice(-2);
                    $('.pignose-calendar-top-nav.pignose-calendar-top-prev').click();
                }
            
            /*  $('.event_day .day').text(('0' + ( date_before.getDate()) ).slice(-2)); */
            /*  $('.event_day .week_day').text(day_of_week[date_before.getDay()]); */
            /*  $('.month_year').text(monthNames[date_before.getMonth()] + ' ' +  date_before.getFullYear()); */
                
                $('.pignose-calendar-body').find('[data-date="' + searchDate_bef + '"] > a' ).click();
            });
        }
    
        $( ".change_employee" ).on('change',function() {
            var value = $(this).val().toLowerCase();
            $(".show_event").filter(function() {
                //$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                $(this).toggle($(this).hasClass(value));
            });
            $(".month_event").filter(function() {
                //$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                $(this).toggle($(this).hasClass(value));
            });
            if(value == '') {
                $(".show_event").show();
                $(".month_event").show();
            }
        });
        
        $( ".change_car" ).on('change',function() {
            var value = $(this).val().toLowerCase();
            $(".show_locco").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                
            });
            if(value == '') {
                $(".show_locco").show();
            }
        });
        
        var scroll_day;
        var scroll_week;
        $( ".change_view_calendar" ).on('change',function() {
            view = $( this ).val();
            
            if(view == 'day') {
                $('.main_calendar_day').show();
                $('.main_calendar_week').hide();
                $('.main_calendar_month').hide();
                $('.main_calendar_list').hide();
                $('button.show_locco').show();
                
                scroll_day = $('.hour_val.position_8').position().top;
                if(scroll_day != 0) {
                    $('.main_calendar_day').scrollTop(scroll_day);
                }
            } 
            if(view == 'week') {
                $('.main_calendar_day').hide();
                $('.main_calendar_week').show();
                $('.main_calendar_month').hide();
                $('.main_calendar_list').hide();
                $('button.show_locco').show();
                scroll_week = $('.main_calendar_week tr.position_8').position().top;
                if(scroll_week != 0) {
                    $('.main_calendar_week').scrollTop(scroll_week);
                }
            } 
            if(view == 'list') {
                $('.main_calendar_list').show();
                $('.main_calendar_day').hide();
                $('.main_calendar_week').hide();
                $('.main_calendar_month').hide();
                $('.change_car').hide();
                $('button.show_locco').hide();
            } 
        if(view == 'month') {
                $('.main_calendar_day').hide();
                $('.main_calendar_week').hide();
                $('.main_calendar_month').show();
                $('.main_calendar_list').hide();
                $('button.show_locco').show();
        }
        });
        
        $('.main_calendar_month tbody td').on('click',function(){
            var date = $(this).attr('data-date');
            $('.pignose-calendar-body').find('[data-date="' + date + '"] > a' ).click();
        });
        
        $('button.show_loccos').on('click',function(e){
            e.preventDefault();
            $('.main_calendar td>a').toggle();
            $('.main_calendar .show_event').toggle();
            $('.main_calendar .show_locco ').toggle();
            $('.change_employee').toggle();
            $('.change_car').toggle();

        });
        
        $('.selected_day a[rel="modal:open"]').on('click',function(){
            $.getScript( '/../restfulizer.js');
        });
    }
});
$('.side_navbar a.link1').click(function(e){ 
	e.preventDefault(); // cancel click
	var page = $(this).attr('href');   
	$('.container').load(page + ' .container .row');
	
	$('.side_navbar a').removeAttr("style");
	$('.nav li').removeAttr("style");
	$('.nav .link_ads').removeAttr("style");
	$('.link_admin').removeAttr("style");
	$(this).css('color','orange');
});
$('nav.navbar a').on("click",function(e){ 
	e.preventDefault(); // cancel click
	var page = $(this).attr('href');  
	$('.container').load(page + ' .container .row');
	
	$('nav a').removeAttr("style");
	$('.side_navbar a').removeAttr("style");
	$(this).css('color','orange');
});

$('.side_navbar a.link3').on("click",function(e){ 

//	e.preventDefault(); // cancel click
	var page = $(this).attr('href'); 

//	$('.container').load(page + ' .container .row .calender_view', function()
//	{ $.getScript("node_modules/moment/moment.js");
//	$.getScript("node_modules/pg-calendar/dist/js/pignose.calendar.min.js");
//	
//	}
//	);
	
	$('nav li').removeAttr("style");
	$('.side_navbar a').removeAttr("style");
	$(this).css('color','orange');
});
var prev_url = location.href;
var url_modul;
var date;



/* $(function() {
    var body_width = $('body').width();
    var body_height = $('body').height();
    var header_height = $('header.header_nav').height();
    var user_header_height = $('.user_header').height();
    var container_height = $('.container').height();
    var section_top_nav_height = $('.section_top_nav').height();
    var user_header_info_height = $('.user_header_info').height();
    var posts_height;
   
   if(body_width > 990) {
      
        $('.container > .calendar').height(container_height - user_header_height -20);  
        $('.container > .posts').height(container_height - user_header_height -20);  
    }
});

$( window ).on('resize',function() {
    var body_width = $('body').width();
    var body_height = $('body').height();
    var header_height = $('header.header_nav').height();
    var user_header_height = $('.user_header').height();
    var container_height = $('.container').height();
    var section_top_nav_height = $('.section_top_nav').height();
    var user_header_info_height = $('.user_header_info').height();
    var posts_height;
    if(body_width>990) {
        $('.container > .calendar').height(container_height - user_header_height -20);  
        $('.container > .posts').height(container_height - user_header_height -20);  
    }
});  */

var section_top_width =  $('.section_top_nav').width();

function myTopNav() {
    var x = $(".section_top_nav");
    if (x.hasClass("responsive")) {
        x.removeClass("responsive");
    } else {
        x.addClass("responsive");
    } 
}

$('span.logo_icon').on('click',function(){
    $('.section_top_nav').css('width','250px');
    $('#myTopnav:not(".responsive")').css('display','block');
    $('#myTopnav:not(".responsive")').css('width','250px');
    $('.header_nav .section_top_nav .close_topnav svg').show();
});

$('.close_topnav').on('click',function(){
    $('.header_nav .section_top_nav .close_topnav svg').hide();
    $('#myTopnav:not(".responsive")').css('display','none');
    $('#myTopnav:not(".responsive")').css('width',0);
    $('.section_top_nav').css('width', 0);
});
var body_width = $('body').width();

if(body_width > 768) {
    $("body").on('click',function(){
        $('.close_topnav').trigger('click');
    });
    
    $(".logo_icon").on('click',function(event) {
        event.stopPropagation();
    });
    $(".section_top_nav").on('click',function(event) {
        event.stopPropagation();
    });
    var layout_button_width = $('div.layout_button').width();
    var count_layout_button =  $('.layout_button button').length;
   
    if( count_layout_button > 0) {
        var button_width = (layout_button_width / count_layout_button);
        $('.layout_button button').width(button_width -15);
        $('.layout_button button').css('min-width',button_width -15);
        $('.layout_button button').css('max-width',button_width -15);
        $('.layout_button button:last-child').width(button_width);
        $('.layout_button button:last-child').css('min-width',button_width);
        $('.layout_button button:last-child').css('max-width',button_width);
    }


}

$("a[rel='modal:open']").addClass('disable');

$(function() {
    $("a[rel='modal:open']").removeClass('disable');
    
    url_modul = window.location.pathname;
    url_modul = url_modul.replace("/","");
    if(url_modul.indexOf("/") > 0) {
        url_modul = url_modul.slice(0, url_modul.indexOf("/"));
    }
 
    if(url_modul.includes('campaign_sequences') ) {
        $('.button_nav').removeClass('active');
        $('.button_nav.'+ 'campaigns_button').addClass('active');
    } else if(url_modul == 'admin_panel/') { //povratna putanja sa admin_panel/templates 
        //
    } else if( $('.button_nav.'+url_modul+'_button').length > 0) {   // na reload stavlja klasu activ na button prema url pathname
        $('.button_nav').removeClass('active');
        $('.button_nav.'+url_modul+'_button').addClass('active');
    }
});

/* $('.evidention_check>form button').on('click',function(e){
    $(this).attr('disabled','disabled');
}); */


$('.form_evidention').on('submit',function(e){
    e.preventDefault();
   // $(this).hide();
    var url = location.origin + '/work_records';
    var form = $(this);
    form.find('button').attr('disabled','disabled');
    var data = form.serialize();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });     
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function( response ) {
            $('<div><div class="modal-header"><span class="img-success"></span></div><div class="modal-body"><div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>success:</strong>' + response + '</div></div></div>').appendTo('body').modal();
            $('.header_nav').load(location.href + ' .header_nav .topnav',function(){
                $.getScript('/../js/nav_active.js');
            });
        }, 
        error: function(jqXhr, json, errorThrown) {
            console.log(jqXhr.responseJSON);
            $(this).show();
            var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
                                'message':  jqXhr.responseJSON.message,
                                'file':  jqXhr.responseJSON.file,
                                'line':  jqXhr.responseJSON.line };
            $.modal.close();
            $.ajax({
                url: 'errorMessage',
                type: "get",
                data: data_to_send,
                success: function( response ) {
                   $('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + "Prijava nije uspjela, osvježi stranicuu i pokušaj ponovno" + '</div></div></div>').appendTo('body').modal();
                   
                }, 
                error: function(jqXhr, json, errorThrown) {
                    console.log(jqXhr.responseJSON); 
                }
            });
        }
    });
});

if(body_width < 450) {
    document.addEventListener("visibilitychange", function() {
        if (document.hidden){
        } else {
            location.reload();
        }
    });
}

$('body').on($.modal.OPEN, function(event, modal) {
    if( $('input[type=datetime-local]').length > 0 ) {
        $('input[type=datetime-local]').on('change',function(){
            date = new Date( $(this).val());

            if( date == 'Invalid Date') {
                $( '<div class="error_date danger">Neispravan unos datuma. Molim provjeri!</div>' ).modal();
                $('.btn-submit').attr('disabled', 'disabled');
            } else {
                $('.btn-submit').attr('disabled', false);
                $('.error_date').remove();
            }
        });
    }
    if( $('input[type=date]').length > 0 ) {
        $('input[type=date]').on('change',function(){
            date = new Date( $(this).val());

            if( date == 'Invalid Date') {
                $( '<div class="error_date danger">Neispravan unos datuma. Molim provjeriti</div>' ).modal();
                $('.btn-submit').attr('disabled', 'disabled');
            } else {
                $('.btn-submit').attr('disabled', false);
                $('.error_date').remove();
            }
        });
    }
});
$('.button_nav').css({
  /*   'background': '#051847',
    'color': '#ffffff' */
});
$( '.button_nav.active' ).css({
/*     'background': '#0A2A79',
    'color': '#ccc' */
});
var prev_url = location.href;
$(".admin_pages a.admin_link").addClass('disable');
var body_width = $('body').width();
var url_location = location.href;
var active_link;
var url_modul = location.pathname;
var title;
url_modul = url_modul.replace("/","");
url_modul = url_modul.split('/')[0];

function selectSearch () {
    $(function(){
        if( $('.select_filter').length > 0 ) {
            $('.select_filter').select2({
                dropdownParent: $('.index_page>main'),
                matcher: matchCustom,
                width: 'resolve',
                placeholder: {
                    id: '-1', // the value of the option
                },
                theme: "classic",
                
            });
        }
    });
}
function matchCustom(params, data) {
    /*   console.log(params);
    console.log(params.term);
    
    console.log(data);
    console.log(data.text); */
    // If there are no search terms, return all of the data
    if ($.trim(params.term) === '') {
      return data;
    }

    // Do not display the item if there is no 'text' property
    if (typeof data.text === 'undefined') {
      return null;
    }

    // `params.term` should be the term that is used for searching
    // `data.text` is the text that is displayed for the data object
    var value = params.term;
    var search_Array = value.split(" ");
    /* console.log(value);
    console.log(search_Array); */
    if( search_Array.length == 1 ) {
        if (data.text.toLowerCase().indexOf(search_Array[0]) > -1) {
            var modifiedData = $.extend({}, data, true);
            return modifiedData;
        }
    } else if( search_Array.length == 2 ) {
        if (data.text.toLowerCase().indexOf(search_Array[0]) > -1 && data.text.toLowerCase().indexOf(search_Array[1]) > -1) {
            var modifiedData = $.extend({}, data, true);
            return modifiedData;
        }
    } else if( search_Array.length == 3 ) {
        if (data.text.toLowerCase().indexOf(search_Array[0]) > -1 && data.text.toLowerCase().indexOf(search_Array[1]) > -1 && data.text.toLowerCase().indexOf(search_Array[2]) > -1) {
            var modifiedData = $.extend({}, data, true);
            return modifiedData;
        }
    } else if( search_Array.length == 4 ) {
        if (data.text.toLowerCase().indexOf(search_Array[0]) > -1 && data.text.toLowerCase().indexOf(search_Array[1]) > -1 && data.text.toLowerCase().indexOf(search_Array[2]) > -1  && data.text.toLowerCase().indexOf(search_Array[3]) > -1) {
            var modifiedData = $.extend({}, data, true);
            return modifiedData;
        }
    }  else if( search_Array.length == 5 ) {
        if (data.text.toLowerCase().indexOf(search_Array[0]) > -1 && data.text.toLowerCase().indexOf(search_Array[1]) > -1 && data.text.toLowerCase().indexOf(search_Array[2]) > -1  && data.text.toLowerCase().indexOf(search_Array[3]) > -1 && data.text.toLowerCase().indexOf(search_Array[4]) > -1) {
            var modifiedData = $.extend({}, data, true);
            return modifiedData;
        }
    } 
    // Return `null` if the term should not be displayed
    return null;
}

$(function(){
    if($('.index_admin').length > 0 ) {
        var class_open;

        if(body_width > 992) {
            class_open = $('.admin_link.active_admin').parent().attr('class');

            if(class_open != undefined && class_open != '') {
                class_open = "."+class_open.replace(" ",".");
                $(class_open).show();
            }
        }
    
        $('.open_menu').on('click', function(e){
            e.preventDefault();
            class_open = $( this).attr('id');
            $('.'+class_open).toggle();
        });
        $(".admin_pages a.admin_link").removeClass('disable');
        
        // ako ima shortcut - href edit
        try {
            url_location = location.href;
            $.get( location.origin+"/shortcut_exist", {'url': url_location }, function( id ) {
                if(id != null && id != '') {
                    $('.shortcut').attr('href', location.origin +'/shortcuts/'+id+'/edit/');
                    $('.shortcut_text').text('Ispravi prečac'); 
                } else {
                    title = $('.admin_link.active_admin').attr('id');
                    $('.shortcut').attr('href', location.origin +'/shortcuts/create/?url='+url_location+'&title='+title );
                    $('.shortcut_text').text('Dodaj prečac'); 
                }
            });
        } catch (error) {
            //
        }
    }
    if( $('.select_filter').not('.sort').length > 0 ) {
        selectSearch ();
    }
});

if($(".index_table_filter .show_button").length == 0) {
    $('.index_table_filter').not('.index_table_filter.structure_company').append('<span class="show_button"><i class="fas fa-download"></i></span>');
} 

var click_element;
var title;
var url;

if($('.index_admin').length > 0 ) { 
    $('.admin_pages li>a').not('.open_menu').on('click',function(e) {

        $('#login-modal').remove();
        e.preventDefault();
        click_element = $(this);
        title = click_element.text();
        $("title").text( title ); 
        url = $(this).attr('href');
        // ako ima shortcut - href edit
        try {
            $.get( location.origin+"/shortcut_exist", {'url': url }, function( id ) {
                if(id != null && id != '' && id) {
                    $('.shortcut').attr('href', location.origin +'/shortcuts/'+id+'/edit/');
                    $('.shortcut_text').text('Ispravi prečac'); 
                } else {
                    title = $('.admin_link.active_admin').attr('id');

                    $('.shortcut').attr('href', location.origin +'/shortcuts/create/?url='+url+'&title='+title );
                    $('.shortcut_text').text('Dodaj prečac'); 
                }
            });
        } catch (error) {
            //
        }
       
        $('.admin_pages>li>a').removeClass('active_admin');
        $(this).addClass('active_admin');
        active_link = $('.admin_link.active_admin').attr('id');

        $( '.admin_main' ).load( url + ' .admin_main>section', function( response, status, xhr ) {
            window.history.replaceState({}, document.title, url);
            if ( status == "error" ) {
                var msg = "Sorry but there was an error: ";
                $( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
            }
            if ($('.show_button_upload').length > 0 )  {
                $('.show_button_upload').on('click', function(){
                    $('form.upload_file').modal();
                    $('form.upload_file').show();
                });
            }
            $.getScript( '/../restfulizer.js');
            $.getScript( '/../js/filter_dropdown.js');
            $.getScript( '/../js/open_modal.js');
            $.getScript( '/../js/datatables.js');
            $.getScript('/../select2-develop/dist/js/select2.min.js');
            if( $('.select_filter').not('.sort').length > 0 ) {
                selectSearch ();
            }
            if (url.includes('/work_records')) {
                $.getScript( '/../js/work_records.js');
            } else if(url.includes('/loccos')) {
                $('a.open_locco').on('click',function(event) {
                    event.preventDefault();
                    click_element = $(this);
                    title = click_element.text();
                    $("title").text( title ); 
                    url = $(this).attr('href');

                    $( '.admin_main' ).load( url + ' .admin_main>section', function( response, status, xhr ) {
                        window.history.replaceState({}, document.title, url);
                        if ( status == "error" ) {
                            var msg = "Sorry but there was an error: ";
                            $( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
                        }
                        if ($('.show_button_upload').length > 0 )  {
                            $('.show_button_upload').on('click', function(){
                                $('form.upload_file').modal();
                                $('form.upload_file').show();
                            });
                        }
                        $.getScript( '/../restfulizer.js');
                        $.getScript( '/../js/filter_dropdown.js');
                        $.getScript( '/../js/datatables.js');
                        $.getScript( '/../js/open_modal.js');
                        $.getScript('/../select2-develop/dist/js/select2.min.js');
                        if( $('.select_filter').not('.sort').length > 0 ) {
                            selectSearch ();
                        }
                    });
                    return false;
                });
            }
        
            if(body_width < 992 ) {
                $('aside.admin_aside').hide();
                $('main.admin_main').show();
            
                $('.link_back').on('click',function (e) {
                    e.preventDefault();
                    $('aside.admin_aside').show();
                    $('main.admin_main').hide();
                });
            }
        });
        return false;
    });
   
    if ($('.show_button_upload').length > 0 )  {
        $('.show_button_upload').on('click', function(){
            $('form.upload_file').modal();
            $('form.upload_file').show();
        });
    }
}

$("a[rel='modal:open']").addClass('disable');

$(function() {
  
    $("a[rel='modal:open']").removeClass('disable');
    $.modal.defaults = {
        closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
        escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
        clickClose: true,       // Allows the user to close the modal by clicking the overlay
        closeText: 'Close',     // Text content for the close <a> tag.
        closeClass: '',         // Add additional class(es) to the close <a> tag.
        showClose: true,        // Shows a (X) icon/link in the top-right corner
        modalClass: "modal",    // CSS class added to the element being displayed in the modal.
        // HTML appended to the default spinner during AJAX requests.
        spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

        showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
        fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
        fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
    };

    $('.btn-new:not(.create_notice), .add_new, a.create_user[rel="modal:open"], #add_event[rel="modal:open"], .oglasnik_button, .posts_button, .doc_button, .events_button').on('click',function(){
        $.modal.defaults = {
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: true,       // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',
    
            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
        };
    });
    $('a.create_notice[rel="modal:open"]').on('click',function(){
        $.modal.defaults = {
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: true,       // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal modal_notice",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',
    
            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
        };
    });
    $('a.notice_show[rel="modal:open"]').on('click',function(){
        $.modal.defaults = {
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: true,       // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal modal_notice notice_show",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',
    
            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
        };
    });

    $('.questionnaires_button, a.qname[rel="modal:open"], a.new_questionnaire[rel="modal:open"], .thumb_content a[rel="modal:open"]').on('click',function(){
        $.modal.defaults = {
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: true,       // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal modal_questionnaire",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
        };
    });
    $('a.open_statistic[rel="modal:open"]').on('click',function(){
        $.modal.defaults = {
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: true,       // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal modal_notice notice_show statistic_index",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
        };
    });
    $('a.user_show[rel="modal:open"]').on('click',function(){
        $.modal.defaults = {
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: true,       // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal modal_user",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
        };
    });
    $('.user_show.tr_open_link').on('click',function(){
        $.modal.defaults = {
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: true,       // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal modal_user",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
        };
    });
    $('a.campaign_show[rel="modal:open"]').on('click',function(){
        $.modal.defaults = { 
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: true,       // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal modal_notice modal_campaign",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)    
        }; 
    });
    $('.open_car_modal').on('click',function(){
		$.modal.defaults = {
			closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
			escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
			clickClose: true,       // Allows the user to close the modal by clicking the overlay
			closeText: 'Close',     // Text content for the close <a> tag.
			closeClass: '',         // Add additional class(es) to the close <a> tag.
			showClose: true,        // Shows a (X) icon/link in the top-right corner
			modalClass: "modal car_modal",    // CSS class added to the element being displayed in the modal.
			// HTML appended to the default spinner during AJAX requests.
			spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

			showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
			fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
			fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
		};
    });
    $('.evidention_check > button').on('click',function(){
		$.modal.defaults = {
			closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
			escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
			clickClose: true,       // Allows the user to close the modal by clicking the overlay
			closeText: 'Close',     // Text content for the close <a> tag.
			closeClass: '',         // Add additional class(es) to the close <a> tag.
			showClose: true,        // Shows a (X) icon/link in the top-right corner
			modalClass: "modal",    // CSS class added to the element being displayed in the modal.
			// HTML appended to the default spinner during AJAX requests.
			spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

			showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
			fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
			fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
		};
    });

    $('tr[data-modal] td:not(:last-child)').on("click", function(e) {
        e.preventDefault();
        var href = location.origin + $(this).parent().data('href');
       console.log(href);
        $.modal.defaults = {
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: true,      // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',
    
            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
        };
        $.get(href, function(html) { 
            $(html).appendTo('#login-modal');
        }); 
        $('#login-modal').modal();
        $('#login-modal').on($.modal.CLOSE, function(event, modal) {
            $( "#login-modal" ).empty();
        });

        $('a.close-modal').on('click',function(){
            $( "#login-modal" ).empty();
        });
    }); 

    $('tr.tr_open_link_new_page td:not(.not_link)').on('click', function(e) {
		e.preventDefault();
		url = location.origin + $( this ).parent().attr('data-href');
        window.location = url;
    });
    
    $('body').on($.modal.OPEN , function(event, modal) {
        $.getScript('/../select2-develop/dist/js/select2.min.js');
        selectSearchModal ();
    });

    function selectSearchModal () {
		$(function(){
			if( $('select.form-control').length > 0 ) {
				$('select.form-control').select2({
					dropdownParent: $('body'),
					width: 'resolve',
					placeholder: {
						id: '-1', // the value of the option
					},
                    language: 'hr',
                    matcher: matchCustom,
                 
				});
			}
		});
	}
    function matchCustom(params, data) {
       
        // If there are no search terms, return all of the data
        if ($.trim(params.term) === '') {
          return data;
        }
    
        // Do not display the item if there is no 'text' property
        if (typeof data.text === 'undefined') {
          return null;
        }
    
        // `params.term` should be the term that is used for searching
        // `data.text` is the text that is displayed for the data object
        var value = params.term;
        var search_Array = value.split(" ");
       
        if( search_Array.length == 1 ) {
            if (data.text.toLowerCase().indexOf(search_Array[0]) > -1) {
                var modifiedData = $.extend({}, data, true);
                return modifiedData;
            }
        } else if( search_Array.length == 2 ) {
            if (data.text.toLowerCase().indexOf(search_Array[0]) > -1 && data.text.toLowerCase().indexOf(search_Array[1]) > -1) {
                var modifiedData = $.extend({}, data, true);
                return modifiedData;
            }
        } else if( search_Array.length == 3 ) {
            if (data.text.toLowerCase().indexOf(search_Array[0]) > -1 && data.text.toLowerCase().indexOf(search_Array[1]) > -1 && data.text.toLowerCase().indexOf(search_Array[2]) > -1) {
                var modifiedData = $.extend({}, data, true);
                return modifiedData;
            }
        } else if( search_Array.length == 4 ) {
            if (data.text.toLowerCase().indexOf(search_Array[0]) > -1 && data.text.toLowerCase().indexOf(search_Array[1]) > -1 && data.text.toLowerCase().indexOf(search_Array[2]) > -1  && data.text.toLowerCase().indexOf(search_Array[3]) > -1) {
                var modifiedData = $.extend({}, data, true);
                return modifiedData;
            }
        }  else if( search_Array.length == 5 ) {
            if (data.text.toLowerCase().indexOf(search_Array[0]) > -1 && data.text.toLowerCase().indexOf(search_Array[1]) > -1 && data.text.toLowerCase().indexOf(search_Array[2]) > -1  && data.text.toLowerCase().indexOf(search_Array[3]) > -1 && data.text.toLowerCase().indexOf(search_Array[4]) > -1) {
                var modifiedData = $.extend({}, data, true);
                return modifiedData;
            }
        } 
   
        // Return `null` if the term should not be displayed
        return null;
        
    }
}); 
// on load
if( $('.posts_index').length > 0) {

    var form;
    var data;
    var url;
    var post_id;
    var tab_id;
    var id;
    var content;
    var refresh_height;
    var mess_comm_height;
    var comment_height;
    var body_width = $('body').width();
    var mouse_is_inside = false;
    var active_tabcontent;

    $(function() {
       
        broadcastingPusher();
        tablink_on_click();
        submit_form (); 
        $('.placeholder').show();
        $( '.type_message' ).attr('Placeholder','Type message...');
    
        $('.type_message').on('focus',function(){
            $( this ).attr('Placeholder','');
        });
        $('.type_message').on('blur',function(){
            $( this ).attr('Placeholder','Type message...');
        });
        
        $('.search_post').on('click',function(){
            $('.search_input').show();  
        });
        $(document).on('mouseup',function(e){
            var search_input = $(".search_input");
         
            // If the target of the click isn't the container
            if(!search_input.is(e.target) && search_input.has(e.target).length === 0){
                search_input.hide();
            }
        });

        url = location.search;
       
        if(body_width > 768 && location.href.includes('/posts') ) {
            if( url ) {
                id = url.replace("?id=", "");
                $('.tablink#' + id ).trigger('click');
                history.pushState({}, "", location.origin + '/posts');
            } else {
                $('.tablink').first().trigger('click');
            }
        }
    });
    
    // on submit ajax store
    function submit_form () {
        $('.form_post').on('submit',function(e){
            e.preventDefault();
            if( $(this).find('.post-content').val() == '' ) {
                return false;
            } else {
                form = $(this);
                data = form.serialize();
                
                url = '/commentStore';
                post_id = $(this).find('input[name=post_id]').val();
                content = $(this).find('input[name=content]').val();
                tab_id = '_' + post_id;
                
                $('.post-content').val('');
                $('.refresh.'+tab_id).append('<b><div class="message"><div class="right"><p class="comment_empl"><small>sada</small></p><div class="content"><p class="comment_content" >'+content+'</p></div></div></div><b>');
               
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type : 'post',
                    url : url,
                    data : data,
                    success:function(msg) {
                        $.get(location.origin + '/posts', function(data, status){
                            var content =  $('.posts>.all_post',data ).get(0).outerHTML;
                            $( '.posts').html( content );
                            var content2 =  $( '.posts_button .button_nav_img .line_btn',data ).get(0).outerHTML;
                            $( '.posts_button .button_nav_img').html( content2 );
                            var content3 =  $('.index_main>section',data ).get(0).outerHTML;
                            $( '.index_main' ).html( content3 );
                            $('.tabcontent#'+tab_id).show();
                          /*   broadcastingPusher(); */
                            submit_form (); 
                            refreshHeight(tab_id);
                            setPostAsRead(post_id);
                            
                        });
                   
                        $('.tablink#'+post_id).trigger('click');
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
                })
            }
          
            $('.post-content').css('line-height','70px');
        });
    }
    
    function tablink_on_click() {
        $( '.tablink' ).on( "click", function () {
            post_id = $( this ).attr('id');
            tab_id = '_' + post_id;
            if(body_width < 768) {
                $('.latest_messages').hide();
                $('.posts_index .index_main').show();
            }
            $('.tabcontent').hide();
            active_tabcontent = $('.tabcontent#'+tab_id);
            $(active_tabcontent).show();
        
            $.get(location.origin + '/posts', function(data, status){
                var content =  $('.posts>.all_post',data ).get(0).outerHTML;
                $( '.posts').html( content );
                var content2 =  $( '.posts_button .button_nav_img .line_btn',data ).get(0).outerHTML;
                $( '.posts_button .button_nav_img').html( content2 );
                var content3 =  $('.index_main>section',data ).get(0).outerHTML;
                $( '.index_main' ).html( content3 );
                $('.tabcontent#'+tab_id).show();
               
                $('#'+tab_id + ' .type_message').trigger('focus');
                /* broadcastingPusher(); */
                submit_form (); 
               
                if(post_id != undefined) {
                    refreshHeight(tab_id);
                    setPostAsRead(post_id);
                }
                $('.post_sent .link_back').on('click',function () {
                    $('.latest_messages').show();
                    $('.posts_index .index_main').hide();
                    console.log("link_back");
                });
            });
        });
    }
    
    function setPostAsRead(post_id) {
        var url_read = location.origin +"/setCommentAsRead/" + post_id;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "GET",
            url: url_read, 
            success: function(response) {
                $('.tablink#'+post_id).load( location.href + ' .tablink#'+post_id+'>span',function(){
                    tablink_on_click();
                } );
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
    }
    
    function refreshHeight(tab_id) {
         mess_comm_height = $("#" + tab_id ).find('.mess_comm').height();
        refresh_height = 90;
        $('.refresh.' + tab_id + ' .message').each( function() {
            refresh_height+= $(this).height();
        });
        comment_height = $("#" + tab_id ).find('.comments').height();
        if(refresh_height < mess_comm_height ) {
            $("#" + tab_id ).find('.refresh').css({"position": "absolute", "bottom": "0", "width": "100%"});
        }
        if(refresh_height > comment_height ) {
            $("#" + tab_id ).find('.refresh').css({"position": "static", "bottom": "0", "height": "100%"});
            $("#" + tab_id ).find('.refresh').scrollTop(refresh_height);
        } else {
            $("#" + tab_id ).find('.mess_comm').scrollTop(refresh_height);
        }
        $("#" + tab_id ).find('.mess_comm').scrollTop(refresh_height);
    }
    
    function broadcastingPusher () {
        // Enable pusher logging - don't include this in production
       /*  Pusher.logToConsole = true; */
        var employee_id = $('#employee_id').text();
    
         var pusher = new Pusher('d2b66edfe7f581348bcc', {
                                cluster: 'eu'
                                }); 
        var channel = pusher.subscribe('message_receive');
        channel.bind('my-event', function(data) {
            console.log(data.comment);
            if(employee_id == data.show_alert_to_employee) {
                $('.all_post ').load(  location.origin + '/posts .all_post .main_post');
                $( '.posts_button .button_nav_img').load( location.origin + '/posts .posts_button .button_nav_img .line_btn');
                $( '.refresh.' + tab_id ).load( location.origin + '/posts .refresh.' + tab_id + ' .message',function(){
                    tablink_on_click();
                });
            }
        }); 
    }
    
    function onKeyClick() {
      
        var key = window.event.keyCode;
        // If the user has pressed enter
        if (key === 13) {
            $('.post-content').css('line-height','unset');
        }
        else {
            return true;
        }
    }
}

$(function() { 
    if( $('.main_questionnaire').length > 0) {
        var body_width = $('body').width();
        if(body_width > 450) {
            var index_height = $('.index_main.main_documents').height();
            var header_height = $('.page-header.header_questionnaire').height();
            var body_height = index_height - header_height;
            $('.all_documents').height(body_height -65);
        }

        $('.index_page table.dataTable.no-footer').css('height','fit-content');

        var div_width = $( '.preview_doc').width();
        var all_width = 217;

        $( ".preview_doc .thumb_container" ).each( (index, element) => {
            all_width += 217;
        });

        if(all_width > div_width ) {
            $('.preview_doc .scroll_right').show();
        }

        $('tr.clickable-row[data-modal] td:not(:last-child)').on("click", function(e) {
            e.preventDefault();
            var href = location.origin + $(this).parent().data('href');
            console.log(href);

            if( $('.main_questionnaire').length > 0 ) {
                var class_modal = 'modal modal_questionnaire';
            } else {
                var class_modal = 'modal';
            }
            $.modal.defaults = {
                closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
                escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
                clickClose: true,      // Allows the user to close the modal by clicking the overlay
                closeText: 'Close',     // Text content for the close <a> tag.
                closeClass: '',         // Add additional class(es) to the close <a> tag.
                showClose: true,        // Shows a (X) icon/link in the top-right corner
                modalClass: class_modal,    // CSS class added to the element being displayed in the modal.
                // HTML appended to the default spinner during AJAX requests.
                spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',
        
                showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
                fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
                fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
            };
            $.get(href, function(html) { 
                $(html).appendTo('#login-modal');
            }); 
            $('#login-modal').modal();
            $('#login-modal').on($.modal.CLOSE, function(event, modal) {
                $( "#login-modal" ).empty();
            });
    
            $('a.close-modal').on('click',function(){
                $( "#login-modal" ).empty();
            });
        }); 

        $('.collapsible').on("click", function(event){ 
            $(this).siblings().toggle();
        });
        $('#right-button').on("click", function() {
            event.preventDefault();
            $('.preview_doc').animate({
                scrollLeft: "+=217px"
            }, "slow");
            $('.preview_doc .scroll_left').show();
            
        });

        $('.sendEmail').on("click", function(){
            if (!confirm("Stvarno želiš poslati obavijest mailom?")) {
                return false;
            }
        });
        
        $('#left-button').on("click", function() {
            event.preventDefault();
            $('.preview_doc').animate({
                scrollLeft: "-=217px"
            }, "slow");
            if($('.preview_doc').scrollLeft() < 217 ) {
                $('.preview_doc .scroll_left').hide();
            } else {
                $('.preview_doc .scroll_left').show();
            }
        });

        $('.show').on("click", function(){
            $('.show').toggle();
            $('.hide').toggle();
            $('.preview_doc').toggle();
            
            var body_width = $('body').width();
            if(body_width > 1200) {
                var index_height = $('.index_main.main_documents').height();
                var header_height = $('.page-header.header_questionnaire').height();
                var body_height = index_height - header_height;
                $('.all_documents').height(body_height -65 );
                var thumb_height = $('.preview_doc.preview_q .thumb_container').last().height();
                $('.thumb_container').first().height(thumb_height);
            }
        });

        $('.hide').on("click", function(){
            $('.show').toggle();
            $('.hide').toggle();
            $('.preview_doc').toggle();
            var index_height = $('.index_main.main_documents').height();
            var header_height = $('.page-header.header_questionnaire').height();
            var body_height = index_height - header_height;
            $('.all_documents').height(body_height - 65);
        });

        $( ".main_questionnaire .change_view" ).on('click', function() {
            $( ".change_view" ).toggle();
            $( ".change_view2" ).toggle();
            $('.table-responsive.first_view').toggle();
            $('.table-responsive.second_view').toggle();        
        });
        $( ".main_questionnaire .change_view2" ).on('click', function() {
            $( ".change_view" ).toggle();
            $( ".change_view2" ).toggle();
            $('.table-responsive.first_view').toggle();
            $('.table-responsive.second_view').toggle();
        });
    }
    
});

$( window ).resize(function() {
    var body_width = $('body').width();
    if(body_width > 1200) {
        var index_height = $('.index_main.main_documents').height();
        var header_height = $('.page-header.header_questionnaire').height();
        var body_height = index_height - header_height;
        $('.all_documents').height(body_height -65);
    }
});
	$(function() {

		var modal_height = $('.modal.modal_questionnaire').height();
		var header_height =  $('.modal-header').height();
		var body_width = $('body').width();
		var body_height =  modal_height - header_height - 80;
		if(body_width > 450) {
			$('.modal.modal_questionnaire .modal-body').height(body_height);
		}
		
		var countElement = 0;
		$('textarea').each(function(){
			countElement += 1;
		});
		$('input[type="radio"]').parent().parent().each(function(){
			countElement += 1;
		});
		$('input[type="checkbox"]').parent().parent().each(function(){
			countElement += 1;
		});
		$( "#questionnaire_form span.progress_val" ).text( 0 + '/' + countElement);
		var countChecked = function() {
			var n = $( "input[type=radio]:checked" ).length;
			
			$( "textarea" ).each(function(){ 
				if($(this).val() != '') {
					n += 1;
				}
			});
			$('input[type="checkbox"]:checked').parent().parent().each(function(){
				n += 1;
			});
				
			$( "#questionnaire_form span.progress_val" ).text( n + '/' + countElement);
			var valProgress = n/countElement*100;
			$('#questionnaire_form .progress_bar .progress').css('width', valProgress + '%');
		};
		countChecked();
		
		$( "input[type=radio]" ).on( "click", countChecked );
		$( "input[type=checkbox]" ).on( "click", countChecked );
		$( "textarea" ).on( "change", countChecked );
	});
	$( window ).on('resize',function() {
		var modal_height = $('.modal.modal_questionnaire').height();
		var header_height =  $('.modal-header').height();
		var body_height =  modal_height - header_height - 80;
		$('.modal.modal_questionnaire .modal-body').height(body_height);
		
	});
	$('.btn-statistic').on('click',function(){
		$('.statistic').toggle();
		var modal_height = $('.modal.modal_questionnaire').height();
		var header_height =  $('.modal-header').height();
		var body_height =  modal_height - header_height - 80;
		$('.modal.modal_questionnaire .modal-body').height(body_height);
	});

var sequence_id;
var order;
var i;

if( $('.emails_email_body').length > 0) {
    $(  "#sortable" ).sortable({
        stop: function( event, ui ) {
            var sequences = [];
            var sequences_id = [];
            sequences = event.target.children;
            
            order = 0;
            for (i = 0; i < sequences.length; i++) {
                sequence_id = sequences[ i ].id;
                if (sequence_id != '') {
                    order++;
                    console.log(sequences[i]);
                    console.log($(sequences[i]).find('.emails_order_no .order_no'));
                    $(sequences[i]).find('.emails_order_no .order_no').text(i+1);
                 //  $( this +'.emails_order_no .order_no').text(order);
                    sequences_id.push(sequence_id);
                } 
            }
            
            var url = location.origin + "/setOrder";
    
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "post",
                url: url, 
                data: {'sequences_id': sequences_id},
                success: function(response) {
                    $('.section_emails .emails').load(location.href + ' .section_emails .emails .emails_email_body')
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
        }
    });
    
    
}

 $(function() {
    $('a.notice_show[rel="modal:open"]').on('click',function(){
        $('.modal').addClass('modal_notice');
        $('.modal').addClass('notice_show');
        var height = 0;
        var modal_height = $('.modal.modal_notice').height();
        var header_height =  $('.modal-header').height();
        var body_height =  modal_height - header_height - 65;
        $('.modal_notice.notice_show .modal-body').height(body_height);
    });
});
$( window ).on('resize',function() {
  /*   $('.modal').addClass('modal_notice');
    $('.modal').addClass('notice_show'); */
    var height = 0;
    var modal_height = $('.modal.modal_notice').height();
    var header_height =  $('.modal-header').height();
    var body_height =  modal_height - header_height - 65;
    $('.modal_notice.notice_show .modal-body').height(body_height);
    
});
$('.btn-statistic').on('click',function(){
    $('.statistic').toggle();
    $('.modal').addClass('modal_notice');
    $('.modal').addClass('notice_show');
    var height = 0;
    var modal_height = $('.modal.modal_notice').height();
    var header_height =  $('.modal-header').height();
    var body_height =  modal_height - header_height - 65;
    $('.modal_notice.notice_show .modal-body').height(body_height);
});
$(function(){
    var slideIndex = 1;

    if($('.slide_index').length > 0) {
        slideIndex = $('.slide_index').text();
    }
    showSlides(slideIndex);

    function showSlides(n) {
        var slides = $(".mySlides");
        var slides_info = $(".mySlides_info");

        if (n > $(slides).length) {
            n = 1;
        } else if (n < 1){
            n = $(slides).length;
        } else {
            n = n;
        } 

        $(slides).each(function( index, element ) {
           
            if(index == n-1) {
                $(element).show();
                $(element).addClass('block');
                $(element).css('display','block');
               
            } else {
                $(element).hide();
                $(element).removeClass('block');
            }
            
        });
        $(slides_info).each(function( index, element ) {
            if(index == n-1) {
                $(element).show();
                $(element).addClass('block');
                $(element).css('display','block');
               
            } else {
                $(element).hide();
                $(element).removeClass('block');
            }
        });
       
    }

    function plusSlides(n) {
        var currentSlide;
        var slides = $(".mySlides");
        
        $(slides).each(function( index, element ) {
            if( $(element).hasClass('block')) {
                currentSlide = index+1;
            }
        });
        
        showSlides(currentSlide += n);
    }

    $('.prev').on('click',function(){
        plusSlides(-1);
    });
    $('.next').on('click',function(){
        plusSlides(+1);
    });
});
var form_data;
var url = $('form.form_template').attr('action');
var data_new = {};
var json;
var html; 
var design;
var temp;
try {
    unlayer.init({
        appearance: {
            theme: 'light',
            panels: {
                tools: {
                    dock: 'left'
                }
            }
        },
        mergeTags: {
            first_name: {
              name: "First Name",
              value: "Jelena"
            },
            last_name: {
              name: "Last Name",
              value: "Juras"
            }
        },
        id: 'editor-container',
        projectId: 4441,
        displayMode: 'email'
    })
    
    unlayer.addEventListener('design:updated', function(updates) {
        unlayer.exportHtml(function(data) {
            json = data.design; // design json
            html = data.html; // design html
            body = json.body;
            design = data.design;
        })
    })
} catch (error) {
    
}
	

$('.form_template.template_create .btn-submit').on('click',function(e) {
    var validate = [];
    e.preventDefault();
    var form = $('#form_template')[0];
    
    var data = new FormData(form);              // Create an FormData object 
    data.append('text_json',JSON.stringify(design) );
    data.append('text', html );
   
  //  $(".btn-submit").prop("disabled", true);   // disabled the submit button
 
 /*    form_data = $('.form_template').serialize(); */
    form_data_array = $('.form_template').serializeArray();

    
    jQuery.each( form_data_array, function( i, field ) {
        if(field.value == "") {  //$(field).attr('required') && 
            validate.push("block");
        } else {
            validate.push(true);
        }
    });
    if( html == undefined  || JSON.stringify(design) == undefined ) {
        validate.push("block");
    } else {
        validate.push(true);
    }
    console.log(validate);
    if(validate.includes("block") ) {
        e.preventDefault();
        alert("Nisu uneseni svi parametri, nemoguće spremiti predložak");
    } else { 
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function (data) {
                alert("Dizajn je spremljen!");
                console.log("responce : ", data);
                $(".btn-submit").prop("disabled", false);
                location.reload();
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
    }
});

$(function() {
    $('.nav-link').css('background-color','#F8FAFF !important');
    $('.active.nav-link').css('background-color','#1594F0 !important'); 
});

var dataArrTemplates;
var htmlTemplates;
var designTemplates;
if($('.dataArrTemplates').text()) {
    dataArrTemplates = JSON.parse( $('.dataArrTemplates').text()); // template JSON */

/*   console.log(dataArrTemplates); */
    $.each(dataArrTemplates, function(i, item) {
        htmlTemplates = dataArrTemplates[i].text; 
        var title = dataArrTemplates[i].title; 
        $('#template-container').append('<span class="template_button blockbuilder-content-tool" id="' + i +'"><div>'+title+'</div></span>');
    });
}
  
$('.template_button').on('click',function(){
    temp = $( this ).attr('id');

    designTemplates = JSON.parse( dataArrTemplates[temp].text_json); // template JSON */
    htmlTemplates = dataArrTemplates[temp].text; 
    unlayer.loadDesign(designTemplates);
    $('.show_temp#temp' + temp).remove();
});
$( ".template_button" ).on('mouseover', function(){
        temp = $( this ).attr('id');
        htmlTemplates = dataArrTemplates[temp].text; 
        $('body').append('<span class="show_temp" id="temp' + temp +'">'+htmlTemplates+'</span>');
        $('.show_temp#temp'+temp).show();
        
});
$( ".template_button" ).on('mouseout', function(){
    $('.show_temp#temp' + temp).remove();
});

// LINK BACK   
$('.main_noticeboard .header_document .link_back').on('click',function(e){
       
});


var form_data;
var url = $('form.form_template').attr('action');
var data_new = {};
var json;
var html; 
var design;
try {
    
    unlayer.init({
        appearance: {
            theme: 'light',
            panels: {
                tools: {
                    dock: 'left'
                }
            }
        },
        id: 'editor-container',
        projectId: 4441,
        displayMode: 'email'
    })
    unlayer.setMergeTags({
        first_name: {
        name: "First Name",
        value: "Jelena"
        },
        last_name: {
        name: "Last Name",
        value: "Juras"
        }
    });
    
    if($('.dataArr').text()) {
        var design = JSON.parse( $('.dataArr').text()); // template JSON */
        unlayer.loadDesign(design);
    }
    $('.template').on('change',function(){
        var template_id = $(this).val();
    
        unlayer.loadTemplate(12187); 
    });

    unlayer.addEventListener('design:updated', function(updates) {
        unlayer.exportHtml(function(data) {
            json = data.design; // design json
            html = data.html; // design html
            design = data.design;
            /* $('#text_html').text(html);
            $('#text_json').text(JSON.stringify(json)); */
        })
    })	
  
} catch (error) {
    
}

$('.form_template.template_edit .btn-submit').on('click', function(e) {
    var validate = [];
    e.preventDefault();
    var form = $('#form_template')[0];
    
    var data = new FormData(form);              // Create an FormData object 
    data.append('text_json',JSON.stringify(design) );
    data.append('text',html );
  //  $(".btn-submit").prop("disabled", true);   // disabled the submit button
 
 /*    form_data = $('.form_template').serialize(); */
    form_data_array = $('.form_template').serializeArray();

    jQuery.each( form_data_array, function( i, field ) {
        if(field.value == "" ) {  //$(field).attr('required') && 
            validate.push("block");
        } else {
            validate.push(true);
        }
    });
    console.log(validate);
    if(validate.includes("block") ) {
        e.preventDefault();
        alert("Nisu uneseni svi parametri, nemoguće spremiti obavijest");
    } else { 
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function (data) {
                alert("Dizajn je spremljen!");
                console.log("responce : ", data);
                $(".btn-submit").prop("disabled", false);
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
    }
});
/* 
$('.link_back').click(function(e){
    e.preventDefault();
    var url = location['origin'] +'/campaigns';
    
    $('.container').load( url + ' .container > div', function() {		
        $.getScript( '/../js/datatables.js');
        $.getScript( '/../js/filter_table.js');                    
        $.getScript( '/../restfulizer.js');
     
    });		
}); */

$(function() {
    $('.nav-link').css('background-color','#F8FAFF !important');
    $('.active.nav-link').css('background-color','#1594F0 !important'); 
});


var dataArrTemplates;
var htmlTemplates;
var designTemplates;
if($('.dataArrTemplates').text()) {
    dataArrTemplates = JSON.parse( $('.dataArrTemplates').text()); // template JSON */

/*   console.log(dataArrTemplates); */
    $.each(dataArrTemplates, function(i, item) {
        htmlTemplates = dataArrTemplates[i].text; 
        var title = dataArrTemplates[i].title; 
        $('#template-container').append('<span class="template_button blockbuilder-content-tool" id="' + i +'"><div>'+title+'</div></span>');
    });
}
  
$('.template_button').on('click',function(){
    temp = $( this ).attr('id');

    designTemplates = JSON.parse( dataArrTemplates[temp].text_json); // template JSON */
    htmlTemplates = dataArrTemplates[temp].text; 
    unlayer.loadDesign(designTemplates);
    $('.show_temp#temp' + temp).remove();
});
$( ".template_button" ).on('mouseover', function(){
        temp = $( this ).attr('id');
        htmlTemplates = dataArrTemplates[temp].text; 
        $('body').append('<span class="show_temp" id="temp' + temp +'">'+htmlTemplates+'</span>');
        $('.show_temp#temp'+temp).show();
        
});
$( ".template_button" ).on('mouseout', function(){
    $('.show_temp#temp' + temp).remove();
});
if( $('#tinymce_textarea').length >0 ) {
    tinymce.init({
        selector: '#tinymce_textarea',
        height : 300,	
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste imagetools wordcount'
        ],
        menubar: 'file edit insert view format table tools help',
        toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        /* enable title field in the Image dialog*/
        image_title: true,
        /* enable automatic uploads of images represented by blob or data URIs*/
        automatic_uploads: true,
        /*
            URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
            images_upload_url: 'postAcceptor.php',
            here we add custom filepicker only to Image dialog
        */
        file_picker_types: 'image',
        /* and here's our custom image picker*/
        file_picker_callback: function (cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');

            /*
            Note: In modern browsers input[type="file"] is functional without
            even adding it to the DOM, but that might not be the case in some older
            or quirky browsers like IE, so you might want to add it to the DOM
            just in case, and visually hide it. And do not forget do remove it
            once you do not need it anymore.
            */

            input.onchange = function () {
            var file = this.files[0];

            var reader = new FileReader();
            reader.onload = function () {
                /*
                Note: Now we need to register the blob in TinyMCEs image blob
                registry. In the next release this part hopefully won't be
                necessary, as we are looking to handle it internally.
                */
                var id = 'blobid' + (new Date()).getTime();
                var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                var base64 = reader.result.split(',')[1];
                var blobInfo = blobCache.create(id, file, base64);
                blobCache.add(blobInfo);

                /* call the callback and populate the Title field with the file name */
                cb(blobInfo.blobUri(), { title: file.name });
            };
            reader.readAsDataURL(file);
            };

            input.click();
        },
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });
    $('body').on($.modal.CLOSE, function(event, modal) {
        $.getScript('/../node_modules/tinymce/tinymce.min.js');
    });
}
var locale = $('.locale').text();
var saved;

if(locale == 'en') {
    saved = "Data saved successfully.";
} else {
    saved = "Podaci su spremljeni";
}
$('.close_travel').click(function(e){
    console.log("close_travel");
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var url = $(this).attr('href');
    console.log(url);
    $.ajax({
        url: url,
        type: "get",
        success: function( response ) {

            $('tbody').load(location.origin + '/travel_orders' + ' tbody>tr',function(){
                $.getScript( '/../restfulizer.js');
                $.getScript( '/../js/travel.js');
            });
            $('<div><div class="modal-header"><span class="img-success"></span></div><div class="modal-body"><div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>success:</strong>' + response + '</div></div></div>').appendTo('body').modal();
        },
        error: function(jqXhr, json, errorThrown) {
            console.log(jqXhr.responseJSON); 
        }

    });
}); 
//load wifhout refresh pages
$('.load_page').click(function(e){ 
	e.preventDefault(); // cancel click
	var page = $(this).attr('href');  
	$('.container').load(page + ' .container > .row > div', function()
	{$.getScript("js/upload_page.js");}
	);
});



$( function () {
    var div_width = $( '.profile_images').width();
    var all_width = 0;

    $( ".profile_images > .profile_img" ).each( (index, element) => {
        all_width += $(element).width();
    });
    if(all_width > div_width ) {
        $('.profile_images .scroll_right').show();
    }

    $('.profile_images #right-button').on('click',function(event) {
        event.preventDefault();
        $(this).parent().animate({
            scrollLeft: "+=200px"
        }, "slow");
        $('.profile_images .scroll_left').show();
        
    });

    $('.profile_images #left-button').on('click',function(event) {
        event.preventDefault();
        $(this).parent().animate({
            scrollLeft: "-=115px"
        }, "slow");
        if($('.profile_images').scrollLeft() < 115 ) {
            $('.profile_images .scroll_left').hide();
        } else {
            $('.profile_images .scroll_left').show();
        }
    });
    
});
$.getScript( '/../js/filter_table.js');
if($('.users_main').length > 0) {

    $('.more').on('click',function(){
        $( this ).siblings('.role').toggle();
        $( this ).hide();
        $( this ).siblings('.hide').show();
    });
    $('.hide').on('click',function(){
        $( this ).siblings('.role').hide();
        $( this ).siblings('.role._0').show();
        $( this ).siblings('.role._1').show();
    
        $( this ).siblings('.more').show();
        $( this ).hide();
    });
    
    $('.user_header .change_view').on('click', function(){
        $('.index_table_filter label #mySearchTbl').attr('id','mySearchElement');
        $('.index_table_filter label #mySearchElement').attr('onkeyup','mySearchElement()');
    
        $( ".change_view" ).toggle();
        $( ".change_view2" ).toggle();
       
        $('main.users_main .second_view').css('display','flex');
        $('.table-responsive').toggle();		
    });
    $( ".user_header .change_view2" ).on('click', function() {
        $('.index_table_filter label #mySearchElement').attr('id','mySearchTbl');
        $('.index_table_filter label #mySearchTbl').attr('onkeyup','mySearchTable()');
        $( ".change_view" ).toggle();
        $( ".change_view2" ).toggle();
        
        $('.second_view').css('display','none');
       
        $('.table-responsive').toggle();
    });
    $("a.show_user").on('click', function(event) {
        $.modal.defaults = {
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: true,       // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal modal_user",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',
    
            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
        };
    });
    $("a.edit_user").on('click', function(event) {
        $.modal.defaults = {
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: true,       // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',
    
            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
        };
    });
    $("a.create_user").on('click', function(event) {
        $.modal.defaults = {
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: true,       // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',
    
            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
        };
    });
}
$(function(){
    if( $('.index_page.vacation_index').length > 0 ) {
       /*  $('.add_plan').on('click',function(){
            if (! confirm("Sigurno želiš unijeti plan?")) {
                return false;
            } else {
                return true;
            }
        }); */
        $('.create_request').on('click',function(){
            if (! confirm("Sigurno želiš pokrenuti izradu zahtjeva?")) {
                return false;
            } else {
                return true;
            }
        });
        var slice = $('#no_week').text();
        $('tbody tr td').not('.employee_name').on('mouseover', function(){
            $( this ).css('background','#bbb');
            $( this ).nextAll().slice(0, slice - 1).css('background','#bbb');
            $( this ).nextAll().slice(0, slice - 1).find('a').css('visibility','hidden');
            
        });
        $('tbody tr td').not('.employee_name').on('mouseleave', function(){
            $( this ).css('background','inherit');
            $( this ).nextAll().slice(0, slice - 1).css('background','inherit');
            $( this ).nextAll().slice(0, slice - 1).find('a').css('visibility','visible');
        });

        delete_request ();
        store_request();

        function delete_request () {
            $('.btn-delete').on('click', function(e) {
                if (! confirm("Sigurno želiš obrisati zahtjev?")) {
                    return false;
                } else {
                    e.preventDefault();
                    id = $( this ).attr('id');
                    url_delete = $( this ).attr('href');
                    url_load = location.href;
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
                            if( $('.basic_view').length > 0 ) {
                                $("tr.basic_view").load(url_load + ' tr.basic_view>td',function(){
                                    $('#loader').remove();
                                    delete_request ();
                                    store_request();
                                });
                            } else {
                                $("tr#empl_"+id).load(url_load + " tr#empl_"+id+'>td',function(){
                                    $('#loader').remove();
                                    delete_request ();
                                    store_request();
                                });
                            }
                        }
                    });
                }
            });
        }

        function store_request () {
            $('.add_plan').on('click', function(e) {
                if (! confirm("Sigurno želiš dodati zahtjev?")) {
                    return false;
                } else {
                    e.preventDefault();
                    id = $( this ).attr('id');
                    url_store = $( this ).attr('href');
                    url_load = location.href;
                    token = $( this ).attr('data-token');

                    $.ajaxSetup({
                        headers: {
                            '_token': token
                        }
                    });
                    $.ajax({
                        url: url_store,
                        type: 'GET',
                        data: { _token :token },
                        beforeSend: function(){
                            $('body').prepend('<div id="loader"></div>');
                        },
                        success: function(result) {
                            if( $('.basic_view').length > 0 ) {
                                $("tr.basic_view").load(url_load + ' tr.basic_view>td',function(){
                                    $('#loader').remove();
                                    delete_request ();
                                    store_request();
                                });
                            } else {
                                $('#loader').remove();
                                
                                $("tr#empl_"+id).load(url_load + " tr#empl_"+id+'>td',function(){
                                    $('#loader').remove();
                                    delete_request ();
                                    store_request();
                            
                                });
                            }
                        }
                    });
                }
            });
        }
    }
});
var locale = $('.locale').text();
var validate_text;
var email_unique;
var error;
var request_send;
var status_requests;
var all_requests;
var done;
var saved;
var validate = [];

if(locale == 'en') {
    validate_text = "Required field";
    email_unique = "Email must be unique";
    error = "There was an error";
    saved = "Data saved successfully.";
    request_send = "Request sent";
    status_requests = "To see you request status and see all request visit All requests page";
    all_requests = "All requests";
    done = "Done";
    validate_name = "Required name entry";
    validate_lastname = "Required lastname entry ";
    validate_email = "Required e-mail entry";
    validate_password = "Required password entry";
    validate_passwordconf = "Password confirmation required";
    validate_password_lenght = "Minimum of 6 characters is required";
    validate_role = "Required role assignment";   
} else {
    validate_text = "Obavezno polje";
    email_unique = "E-mail mora biti jedinstven";
    error = "Došlo je do greške, poslana je poruka na podršku";
    saved = "Podaci su spremljeni";
    request_send = "Zahtjev je poslan";
    status_requests = "Da biste vidjeli status zahtjeva i pogledali sve zahtjeve posjetite Svi zahtjevi stranicu";
    all_requests = "Svi zahtjevi";
    done = "Gotovo";
    validate_name = "Obavezan unos imena";
    validate_lastname = "Obavezan unos prezimena";
    validate_email = "Obavezan unos emaila";
    validate_password = "Obavezan unos lozinke";
    validate_passwordconf = "Obavezna potvrda lozinke";
    validate_password_lenght = "Obavezan unos minimalno 6 znaka";
    validate_role = "Obavezna dodjela uloge";
}

$('.remove').on('click',function(){
    $(this).parent().remove();
});
var page = $('.admin_pages li').find('a.active_admin');
var modul_name = $('.admin_pages li').find('a.active_admin').attr('id');

function validate_user_form () {
    validate = [];
    $('.roles').on('change',function(event){
        if( roles.is(':checked')) {
            validate.push(true);
        } else {
            validate.push("block");
        }
        console.log('validate roles');
    });
    $( "textarea" ).each(function( index ) {
        if($(this).attr('required') == 'required' ) {
            if( $(this).val().length == 0 ) {
                if( !$( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate.push("block");
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate.push(true);
            }
        }
        console.log('validate textarea');
    });
    $( "input" ).not('.roles').each(function( index ) {
        if( $(this).attr('required') == 'required' ) {
            if( $(this).val().length == 0 || $(this).val() == '') {
                if( ! $( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate.push("block");
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate.push(true);
            }
            console.log('validate input');
        }    
   });
    $( "select" ).each(function( index ) {
        if($(this).attr('required') == 'required' ) {
            if( $(this).val() == null || $(this).val() == '' || $(this).val() == '') {
                if( ! $( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate.push("block");
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate.push(true);
            }
            console.log('validate select');  
        }
    });
     /*    if (tinyMCE.activeEditor) { */
    /*        if(tinyMCE.activeEditor.getContent().length == 0) { */
    /*            if( ! $('#mytextarea').parent().find('.modal_form_group_danger').length) { */
    /*                $('#mytextarea').parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>'); */
    /*            } */
    /*            validate.push("block"); */
    /*            $('#mytextarea').parent().find('.modal_form_group_danger').remove(); */
    /*            validate.push(true); */
    /*        } */
    /*    } */
    if( $("#password").length > 0 ) {
        password = $("#password");
        conf_password = $("#conf_password");    
       
        if ($(password).length > 0 && $(password).text() != '') {
            if( password.val().length < 6) {
                if( password.parent().find('.validate').length  == 0 ) {
                    password.parent().append(' <p class="validate">' + validate_password_lenght + '</p>');
                } else {
                    password.parent().find('.validate').text(validate_password_lenght);  
                }
                validate.push("block");
            } else {
                password.parent().find('.validate').text("");     
                if( ! $(conf_password).val() || ($(password).val() != conf_password.val()) ) {
                if( conf_password.parent().find('.validate').length  == 0 ) {                
                        conf_password.parent().append(' <p class="validate">' + validate_passwordconf + '</p>');
                    }
                    validate.push("block");
                } else {
                    conf_password.parent().find('.validate').text("");
                    validate.push(true);
                }
            }
        }
        console.log('validate password');  
    }
}
$('input[type="file"]').on('change',function(e){
    fileName = e.target.files[0].name;
    $('#file_name').text(fileName);
});

$('.btn-submit').on('click',function(event){
    /* event.preventDefault(); */
   
    var form = $(this).parents('form:first');
    let url = $(this).parents('form:first').attr('action');
    var form_data = form.serialize();
  
    var url_load = window.location.href;
    var pathname = window.location.pathname;
    validate_user_form ();
    console.log(url);
    console.log(form_data);
    console.log(validate);

    if(validate.includes("block") ) {
       event.preventDefault();
       validate = [];
    } else {
        $('.roles_form .checkbox').show();
      
       /*  $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });     
        $.ajax({
            url: url,
            type: "POST",
            data: form_data,
            success: function( response ) {
                $.modal.close();
                if(pathname == '/events' && url.includes("/events/")) {  //event edit
                    $('.modal-header').load(url + ' .modal-header h5');
                    $('.modal-body').load(url + ' .modal-body p');
                    $('.main_calendar_day').load(url_load + ' .main_calendar_day>div');
                    $('.main_calendar_month').load(url_load + ' .main_calendar_month table');
                    $('.main_calendar_week').load(url_load + ' .main_calendar_week table');
                    $('.main_calendar_list').load(url_load + ' .main_calendar_list>list');
                    $('.all_events').load(url_load + ' .all_events .hour_in_day');
                } else if(pathname == '/events' ) {
                    $('.all_events').load(url_load + ' .all_events .hour_in_day');
                } else if(url.includes("/vehical_services/")) {
                    url = window.location.origin + '/vehical_services';
                    $('.modal-body').load(url + " .modal-body table" );
                    $.getScript( '/../restfulizer.js');
                } else if(url_load.includes("/oglasnik")) {
                        url = window.location.origin + '/oglasnik';
                        $('.main_ads').load(url + " .main_ads article" );
                        $.getScript( '/../restfulizer.js');

                } else if(url.includes("/fuels/")) {                    
                    url = window.location.origin + '/fuels';
                    $('.modal-body').load(url + " .modal-body table" );
                    $.getScript( '/../restfulizer.js');
                } else if (pathname.includes("/edit_user")) {
                    location.reload();
                } else if (url.includes("/loccos") && pathname == '/dashboard' ) {
                    $('.layout_button').load(url_load + " .layout_button button" ); 

                } else if (url.includes("/events") && pathname == '/dashboard' ) {
                    $('.all_agenda').load(url + " .all_agenda .agenda");
                } else if ( pathname == '/dashboard' ) {
                    $('.salary').load(url_load + " .salary>div");
                } else if (url.includes("/posts") ) {
                    if(pathname == '/dashboard') {
                        $('.all_post').load(url_load + " .all_post>div");
                    } else if (pathname == '/posts') {
                        $('.container').load(url_load + ' .container .posts_index',function(){
                            $('.tablink').first().trigger('click');
                            $('.tabcontent').first().show();
                            broadcastingPusher();
                            refreshHeight(tab_id);
                            setPostAsRead(post_id);
                        });
                    }
                } else {
                    if($('.index_admin').length > 0 ) {
                        if(url.includes("/work_records")) {
                          
                            $('.first_view tbody').load($(page).attr('href') + " .first_view tbody>tr",function(){
                                $.getScript( '/../restfulizer.js');
                            });
                        } else if(url_load.includes('/work_records_table')) {
                            console.log("second");
                            $('tbody.second').load(location.origin+'/work_records_table'+ " tbody.second>tr",function(){
                                $.getScript( '/../restfulizer.js');
                            });
                        } else {
                            $('tbody').load(location.href + " tbody>tr:not(.second_view tbody>tr)",function(){
                                $.getScript( '/../restfulizer.js');
                            });
                        }
                    } else {
                        $('.index_main').load(url + " .index_main>section",function(){
                            if(url.includes("/absences")) {
                                $('#index_table_filter').show();
                                $('#index_table_filter').prepend('<a class="add_new" href="' + location.origin+'/absences/create' +'" class="" rel="modal:open"><i style="font-size:11px" class="fa">&#xf067;</i>Novi zahtjev</a>');
                                $('.all_absences #index_table_filter').append('<span class="show_button"><i class="fas fa-download"></i></span>');
                                $('.index_page table.display.table').show();
                                
                                $.getScript( '/../restfulizer.js');
                            } else if(url.includes("/employees")) {
                                $.getScript( '/../js/users.js');
                            }
                        });
                    }
                   
                }
                if(url.includes("/absences")) {
                    $('<div><div class="modal-header"><span class="img-success"></span></div><div class="modal-body"><div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>success:</strong>' + request_send + '<p>' + status_requests + '</p></div></div><div class="modal-footer"><span><button class="btn_all" ><a href="' + location.origin + '/absences' + '" >' + all_requests + '</a></button></span><button class="done"><a href="#close" rel="modal:close" >' + done + '</a></button></div></div>').appendTo('body').modal();
                } else if(! url.includes("/events/") && ! url.includes("/posts"))  {
                    $('<div><div class="modal-header"><span class="img-success"></span></div><div class="modal-body"><div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>success:</strong>' + saved + '</div></div></div>').appendTo('body').modal();
                }
            }, 
            error: function(jqXhr, json, errorThrown) {
                console.log(jqXhr.responseJSON);
                var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
                                    'message':  jqXhr.responseJSON.message,
                                    'file':  jqXhr.responseJSON.file,
                                    'line':  jqXhr.responseJSON.line };
                if(url.includes("users") && errorThrown == 'Unprocessable Entity' ) {
                    alert(email_unique);
                }  else {
                    $.modal.close();
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
            }
        });
         */
        if($(page).length > 0) {
            $(page).trigger('click');
        } else {
           $('.btn-submit').trigger('unbind');
        }
    }
});

$('.form_user .btn-next').on('click',function(event){
    f_name = $("#first_name");
    l_name = $("#last_name");
    email = $("#email");
    password = $("#password");
    conf_password = $("#conf_password");
    file = $("#file");        
    
    validate = [];

    validate_user_form ();
    console.log(validate);
    if(validate.includes("block") ) {
        //
    } else  {
        $('.first_tab').toggle();
        $('.second_tab').toggle();
        if($('.first_tab').is(':visible')) {
            $('.mark1').css('background','#1594F0');
            $('.mark2').css('background','rgba(43, 43, 43, 0.2)');
    
        } 
        if($('.second_tab').is(':visible')) {
            $('.mark1').css('background','rgba(21, 148, 240, 0.4)');
            $('.mark2').css('background','#1594F0');
        }
    }
});

$('.form_user .btn-back').on('click',function(){
    $('.first_tab').toggle();
    $('.second_tab').toggle();
    if($('.first_tab').is(':visible')) {
        $('.mark1').css('background','#1594F0');
        $('.mark2').css('background','rgba(43, 43, 43, 0.2)');

    } 
    if($('.second_tab').is(':visible')) {
        $('.mark1').css('background','rgba(21, 148, 240, 0.4)');
        $('.mark2').css('background','#1594F0');
    }
});
var locale = $('.locale').text();

if(locale == 'hr') {
    validate_text = "Obavezno polje";
} else if( locale = 'en') {
    validate_text = "Required field";            
} else {
    validate_text = "Obavezno polje";
}   

$('.form_doc .btn-submit').click(function(event){
  //  event.preventDefault();
    var form = $(this).parents('form:first');
    let url = $(this).parents('form:first').attr('action');
    var form_data = form.serialize();
   
    var validate = false;
    $( "select" ).each(function( index ) {
        if($(this).attr('required') == 'required' ) {
            if( $(this).val() == null || $(this).val() == '' || $(this).val() == '') {
                if( ! $( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate = false;
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate = true;
            }
        }
    });
    $( "input" ).each(function( index ) {
        if($(this).attr('required') == 'required' ) {      
        
            if( $(this).val().length == 0 || $(this).val() == '') {
                if( ! $( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate = false;
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate = true;
            }
        }
    });

    if(validate == false) {
        event.preventDefault();
    } 
});

/* var f_name;
var l_name;
var email;
var file;
var validate_name = '';
var validate_lastname = '';
var valiate_email = '';
var password;
var conf_password;
var validate_role = '';
var validate_password = '';
var validate_password_lenght = '';
var validate_passwordconf = '';
var roles;
var fileName;
var validate = false;
var validate2 = [];

var locale = $('.locale').text();

if(locale == 'hr') {
    validate_name = "Obavezan unos imena";
    validate_lastname = "Obavezan unos prezimena";
    valiate_email = "Obavezan unos emaila";
    validate_password = "Obavezan unos lozinke";
    validate_passwordconf = "Obavezna potvrda lozinke";
    validate_password_lenght = "Obavezan unos minimalno 6 znaka";
    validate_role = "Obavezna dodjela uloge";
   
} else if( locale = 'en') {
    validate_name = "Required name entry";
    validate_lastname = "Required lastname entry ";
    valiate_email = "Required e-mail entry";
    validate_password = "Required password entry";
    validate_passwordconf = "Password confirmation required";
    validate_password_lenght = "Minimum of 6 characters is required";
    validate_role = "Required role assignment";   
}

var first_tab_height = $('.first_tab').height();
$('.second_tab').height(first_tab_height);

roles = $('.roles');

$('input[type="file"]').on('change',function(e){
    fileName = e.target.files[0].name;
    $('#file_name').text(fileName);
});

$('.roles').on('change',function(event){
    if( roles.is(':checked')) {
        validate2.push(true);
    } else {
        validate2.push("block");
    }
});

$('.form_create_user .btn-next').on('click',function(event){
    f_name = $("#first_name");
    l_name = $("#last_name");
    email = $("#email");
    password = $("#password");
    conf_password = $("#conf_password");
    file = $("#file");        
    
    if(! f_name.val()) {
        if( f_name.parent().find('.validate').length  == 0) {
            f_name.parent().append(' <p class="validate">' + validate_name + '</p>');               
        }
        validate = false;
    } else {
        f_name.parent().find('.validate').text("");  
        validate = true;
    }
    if(! l_name.val()) {
        if( l_name.parent().find('.validate').length  == 0) {
            l_name.parent().append(' <p class="validate">' + validate_lastname + '</p>');
        }            
        validate = false;
    } else {
        l_name.parent().find('.validate').text("");
        validate = true;
    }
    if(! email.val()) {
        if( email.parent().find('.validate').length  == 0) {
            email.parent().append(' <p class="validate">' + valiate_email + '</p>');
        }
        validate = false;
    } else {
        email.parent().find('.validate').text("");  
        validate = true;     
    }
    if(! password.val()) {
        if( password.parent().find('.validate').length  == 0) {
            password.parent().append(' <p class="validate">' + validate_password + '</p>');
        }
        validate = false;
    } else {
        if(password.val().length < 6) {
            if( password.parent().find('.validate').length  == 0 ) {
                password.parent().append(' <p class="validate">' + validate_password_lenght + '</p>');
            } else {
                password.parent().find('.validate').text(validate_password_lenght);  
            }
            validate = false;
        } else {
            password.parent().find('.validate').text("");     
            if( ! conf_password.val() || (password.val() != conf_password.val()) ) {
            if( conf_password.parent().find('.validate').length  == 0 ) {                
                    conf_password.parent().append(' <p class="validate">' + validate_passwordconf + '</p>');
                }
                validate = false;
            } else {
                conf_password.parent().find('.validate').text("");     
                validate = true;  
            }
        }            
    }
    
    if(validate == true ) {
        $('.first_tab').toggle();
        $('.second_tab').toggle();
        if($('.first_tab').is(':visible')) {
            $('.mark1').css('background','#1594F0');
            $('.mark2').css('background','rgba(43, 43, 43, 0.2)');
    
        } 
        if($('.second_tab').is(':visible')) {
            $('.mark1').css('background','rgba(21, 148, 240, 0.4)');
            $('.mark2').css('background','#1594F0');
        }
    }

});

$('.form_create_user .btn-back').on('click',function(){
    $('.first_tab').toggle();
    $('.second_tab').toggle();
    if($('.first_tab').is(':visible')) {
        $('.mark1').css('background','#1594F0');
        $('.mark2').css('background','rgba(43, 43, 43, 0.2)');

    } 
    if($('.second_tab').is(':visible')) {
        $('.mark1').css('background','rgba(21, 148, 240, 0.4)');
        $('.mark2').css('background','#1594F0');

    }
});

/* 
$('.submit_user_create').on('click',function(event){
    console.log("submit_user_create");
    event.preventDefault();
    var form = $(this).parents('form:first');
    let url = $(this).parents('form:first').attr('action');
    var form_data = form.serialize();

    password = $("#password");
    conf_password = $("#conf_password"); 

    if(password.val().length > 0 ) {
        if( password.val().length < 6) {
            if( password.parent().find('.validate').length  == 0 ) {
                password.parent().append(' <p class="validate">' + validate_password_lenght + '</p>');
            } else {
                password.parent().find('.validate').text(validate_password_lenght);  
            }
            validate2.push("block");
        } else {
            password.parent().find('.validate').text("");     
            if( ! conf_password.val() || (password.val() != conf_password.val()) ) {
            if( conf_password.parent().find('.validate').length  == 0 ) {                
                    conf_password.parent().append(' <p class="validate">' + validate_passwordconf + '</p>');
                }
                validate2.push("block");
            } else {
                conf_password.parent().find('.validate').text("");     
                validate2.push(true);
            }
        }
    }

    if( validate2.includes("block") ) {
        event.preventDefault();

        if( roles.parent().parent().find('.validate').length  == 0 ) {                
            roles.parent().parent().append(' <p class="validate">' + validate_role + '</p>');
        }
    } else {
            roles.parent().find('.validate').text("");
    }

    console.log(validate2);
    console.log(password);
    console.log(conf_password);
    console.log(url);
    console.log(form_data);
});

 */

/* var f_name;
var l_name;
var email;
var file;
var validate_name = '';
var validate_lastname = '';
var valiate_email = '';
var password;
var conf_password;  
var validate_role = '';
var validate_password = '';
var validate_password_lenght = '';
var validate_passwordconf = '';
var roles;
var fileName;
var validate = [];

var locale = $('.locale').text();

if(locale == 'hr') {
    validate_name = "Obavezan unos imena";
    validate_lastname = "Obavezan unos prezimena";
    valiate_email = "Obavezan unos emaila";
    validate_password = "Obavezan unos lozinke";
    validate_passwordconf = "Obavezna potvrda lozinke";
    validate_password_lenght = "Obavezan unos minimalno 6 znaka";
    validate_role = "Obavezna dodjela uloge";
   
} else if( locale = 'en') {
    validate_name = "Required name entry";
    validate_lastname = "Required lastname entry ";
    valiate_email = "Required e-mail entry";
    validate_password = "Required password entry";
    validate_passwordconf = "Password confirmation required";
    validate_password_lenght = "Minimum of 6 characters is required";
    validate_role = "Required role assignment";   
}

var second_tab_height = $('.second_tab').height();
$('.first_tab').height(second_tab_height);

$('input[type="file"]').on('change',function(e){
    fileName = e.target.files[0].name;
    $('#file_name').text(fileName);
});

function validate_user_form () {
    validate = [];
    $('.roles').on('change',function(event){
        if( roles.is(':checked')) {
            validate.push(true);
        } else {
            validate.push("block");
        }
    });
    $( "textarea" ).each(function( index ) {
        if($(this).attr('required') == 'required' ) {
            if( $(this).val().length == 0 ) {
                if( !$( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate.push("block");
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate.push(true);
            }
        }
    });
    $( "input" ).each(function( index ) {
        if($(this).attr('required') == 'required' ) {
            if( $(this).val().length == 0 || $(this).val() == '') {
                if( ! $( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate.push("block");
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate.push(true);
            }
        }      
    });
    $( "select" ).each(function( index ) {
        if($(this).attr('required') == 'required' ) {
            if( $(this).val() == null || $(this).val() == '' || $(this).val() == '') {
                if( ! $( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate.push("block");
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate.push(true);
            }
        }
    });
    if($("#password").length >0) {
        password = $("#password");
        conf_password = $("#conf_password");    
        
        if(password.val().length > 0 ) {
            if( password.val().length < 6) {
                if( password.parent().find('.validate').length  == 0 ) {
                    password.parent().append(' <p class="validate">' + validate_password_lenght + '</p>');
                } else {
                    password.parent().find('.validate').text(validate_password_lenght);  
                }
                validate.push("block");
            } else {
                password.parent().find('.validate').text("");     
                if( ! conf_password.val() || (password.val() != conf_password.val()) ) {
                if( conf_password.parent().find('.validate').length  == 0 ) {                
                        conf_password.parent().append(' <p class="validate">' + validate_passwordconf + '</p>');
                    }
                    validate.push("block");
                } else {
                    conf_password.parent().find('.validate').text("");     
                    validate.push(true);
                }
            }
        }
    }
}

$('.form_edit_user').on('submit',function(event){   
    console.log("form_edit_user");
    event.preventDefault();
    var form = $(this).parents('form:first');
    let url = $(this).parents('form:first').attr('action');
    var form_data = form.serialize();
    
    validate_user_form ();

    if( validate.includes("block") ) {
        event.preventDefault();
        validate = [];
        if( roles.parent().parent().find('.validate').length  == 0 ) {                
            roles.parent().parent().append(' <p class="validate">' + validate_role + '</p>');
        }
    } else {
        roles.parent().find('.validate').text("");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });     
        $.ajax({
            url: url,
            type: "POST",
            data: form_data,
            success: function( response ) {
            }, 
            error: function(jqXhr, json, errorThrown) {
                console.log(jqXhr.responseJSON);
                var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
                                    'message':  jqXhr.responseJSON.message,
                                    'file':  jqXhr.responseJSON.file,
                                    'line':  jqXhr.responseJSON.line };
                if(url.includes("users") && errorThrown == 'Unprocessable Entity' ) {
                    alert(email_unique);
                }  else {
                    $.modal.close();
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
            }
        });
    }

    console.log(validate);
    console.log(password);
    console.log(conf_password);
    console.log(url);
    console.log(form_data);
});

$('.form_edit_user .btn-next').on('click',function(event){  
    f_name = $("#first_name");
    l_name = $("#last_name");
    email = $("#email");
    file = $("#file");        
    validate_user_form ();
  
    //console.log(validate);
    if( validate.includes("block") ) {
        validate = [];
    } else {
        $('.first_tab').toggle();
        $('.second_tab').toggle();
        if($('.first_tab').is(':visible')) {
            $('.mark1').css('background','#1594F0');
            $('.mark2').css('background','rgba(43, 43, 43, 0.2)');
    
        } 
        if($('.second_tab').is(':visible')) {
            $('.mark1').css('background','rgba(21, 148, 240, 0.4)');
            $('.mark2').css('background','#1594F0');
        }
    }
    console.log(validate);
});

$('.form_edit_user .btn-back').on('click',function(){
    $('.first_tab').toggle();
    $('.second_tab').toggle();
    if($('.first_tab').is(':visible')) {
        $('.mark1').css('background','#1594F0');
        $('.mark2').css('background','rgba(43, 43, 43, 0.2)');

    } 
    if($('.second_tab').is(':visible')) {
        $('.mark1').css('background','rgba(21, 148, 240, 0.4)');
        $('.mark2').css('background','#1594F0');
    }
}); */
var month;
var url;
var table = $('table.display');
$('.second_view_header .change_month').on('change',function() {
    if($(this).val() != undefined) {
        date = $(this).val();
        url = location.href + '?date='+date;
        console.log(url);
        $.ajax({
            url: url,
            type: "get",
            beforeSend: function(){
                $('body').prepend('<div id="loader"></div>');
            },
            success: function( response ) {
              /*   $('tbody').load(url + " tbody th"); */
               
                $('.main_work_records').load(url + " .main_work_records .second_view",function(){
                    $('#loader').remove();
                    $( ".td_izostanak:not(:empty)" ).each(function( index ) {
                        $( this ).addClass('abs_'+  $.trim($( this ).text()));
                       
                    });
                    $.getScript('/../js/datatables.js');
                    $('.show_button').on('click',function () {
                        $('.dt-buttons').toggle();		
                    })
                    $('.change_month').find('option[value="'+date+'"]').attr('selected',true);
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
    }
});	

$(function() {
    $( ".td_izostanak:not(:empty)" ).each(function( index ) {
        $( this ).addClass('abs_'+  $.trim($( this ).text()));
    });
});