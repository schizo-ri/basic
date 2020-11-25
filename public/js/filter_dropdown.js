$(function() { // filter knowledge base
	var value = null;
	var date = null;
	var employee_id = null;
	var url;
	var data_to_send;

	$('#filter').on('change',function() {
		value = $('#filter').val().toLowerCase();
		if(value == "all"){
			$('.panel').show();
		} else {
			$('.panel').filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
			});
		}
	});	
	
	$( ".first_view_header .change_employee_work" ).on('change',function() {
		employee_id =  $(this).val().toLowerCase();
		if(employee_id != '' && employee_id != null) {
			employee_id = employee_id.replace("empl_","");
		}
		date =  $('.first_view_header .change_month').val();
		
		url =  location.href + '?date='+date+'&employee_id='+employee_id;

		console.log(date);
		console.log(employee_id);
		console.log(url);
		$.ajax({
			url: url,
			type: "get",
			beforeSend: function(){
                $('body').prepend('<div id="loader"></div>');
            },
			success: function( response ) {
				$( '#admin_page >main' ).load(url + ' #admin_page >main .table-responsive',function(){
					$('#loader').remove();
					$.getScript('/../restfulizer.js');
					$.getScript('/../js/datatables.js');
					$('.show_button').on('click',function () {
						$('.index_page .dt-buttons').toggle();		
					})
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
	
	$( ".second_view_header .change_employee_work" ).on('change',function() {
		employee_id =  $(this).val().toLowerCase();
		if(employee_id != '' && employee_id != null) {
			employee_id = employee_id.replace("empl_","");
		}
		date =  $('.second_view_header .change_month').val();
		
		url =  location.href + '?date='+date+'&employee_id='+employee_id;

		console.log(date);
		console.log(employee_id);
		console.log(url);
		$.ajax({
			url: url,
			type: "get",
			beforeSend: function(){
                $('body').prepend('<div id="loader"></div>');
            },
			success: function( response ) {
				$('#loader').remove();
				$('.main_work_records').load(url + " .main_work_records .second_view",function(){
					$( ".td_izostanak:contains('GO')" ).each(function( index ) {
						$( this ).addClass('abs_GO');
					});
					$( ".td_izostanak:contains('BOL')" ).each(function( index ) {
						$( this ).addClass('abs_BOL');
					});
					$('table').show();

					$.getScript('/../restfulizer.js');
					$.getScript('/../js/datatables.js');
					$('.show_button').on('click',function () {
						$('.index_page .dt-buttons').toggle();		
					})
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
	
	$( ".first_view_header .change_month" ).on('change',function() {
		date =  $(this).val().toLowerCase();
		employee_id =  $('.first_view_header .change_employee_work').val();
		if(employee_id != '' && employee_id != null) {
			employee_id = employee_id.replace("empl_","");
		}

		url =  location.href + '?date='+date+'&employee_id='+employee_id;

		console.log(date);
		console.log(employee_id);
		console.log(url);
		$.ajax({
			url: url,
			type: "get",
			beforeSend: function(){
                $('body').prepend('<div id="loader"></div>');
            },
			success: function( response ) {
				$( '.export_file').load(url +  ' .export_file>a');
				$( '#admin_page >main' ).load(url + ' #admin_page >main .table-responsive',function(){
					$('#loader').remove();
					$.getScript('/../restfulizer.js');
					$.getScript('/../js/datatables.js');
					$.getScript('/../js/work_records.js');
					$('.show_button').on('click',function () {
						$('.index_page .dt-buttons').toggle();		
					})
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
		/* if($(this).val() != undefined) {
			date = $(this).val().toLowerCase();
			url = location.origin + '/work_records'+ '?date='+date;
			$.ajax({
				type: "GET",
				date: { 'date': date },
				url: url, 
				success: function(response) {
					$('tbody').load(url + " tbody tr");
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
		} */
	});

	$( ".second_view_header .change_month" ).on('change',function() {
		date =  $(this).val().toLowerCase();
		employee_id =  $('.second_view_header .change_employee_work').val();
		if(employee_id != '' && employee_id != null) {
			employee_id = employee_id.replace("empl_","");
		}

		url =  location.href + '?date='+date+'&employee_id='+employee_id;

		$.ajax({
			url: url,
			type: "get",
			beforeSend: function(){
                $('body').prepend('<div id="loader"></div>');
            },
			success: function( response ) {
				$('.main_work_records').load(url + " .main_work_records .second_view",function(){
					$('#loader').remove();
					$( ".td_izostanak:contains('GO')" ).each(function( index ) {
						$( this ).addClass('abs_GO');
					});
					$( ".td_izostanak:contains('BOL')" ).each(function( index ) {
						$( this ).addClass('abs_BOL');
					});
					$('table').show();

					$.getScript('/../restfulizer.js');
					/* $.getScript('/../js/datatables.js'); */
					$('.show_button').on('click',function () {
						$('.index_page .dt-buttons').toggle();		
					})
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

	$('.filter_fuels').on('change',function() {
		var date =  $('#filter_month').val().toLowerCase();
		var car =  $('#filter_car').val().toLowerCase();
		if(date == 'all' ) {
			date = '';
		} 
		if(car == 'all') {
			car = '';
		}
	
		if(date == '' && car == ''){
			$('tbody tr').show();
		} else {
			$('tbody tr').filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(date) > -1 && $(this).text().toLowerCase().indexOf(car) > -1);
			});
		}
	});	
	
	$('.filter_loccos').on('change',function() {
		var date =  $('#filter_month').val().toLowerCase();
		console.log(date);

		$.ajax({
			url: location.href + '?date='+date,
			type: "get",
			data: { 'date': date},
			beforeSend: function(){
                $('body').prepend('<div id="loader"></div>');
            },
			success: function( response ) {
				$( '#admin_page >main' ).load(location.href + '?date='+date + ' #admin_page >main .table-responsive',function(){
					$('#loader').remove();
					var title = $(document).prop('title'); 
					title = title.substring(0, title.indexOf(','));
					console.log(title);
					title += ', ' +date;

					$(document).prop('title', title);  
					$.getScript('/../restfulizer.js');
					$.getScript('/../js/datatables.js');
					$('.show_button').on('click',function () {
						$('.index_page .dt-buttons').toggle();		
					})
				});
			},
			error: function(jqXhr, json, errorThrown) {
				console.log(jqXhr.responseJSON.message);
			}
		});
		
	/* 	var date =  $('#filter_month').val().toLowerCase();
		console.log(date);
		if(date == 'all' ) {
			date = '';
		} 
		
		if(date == ''){
			$('tbody tr').show();
		} else {
			$('tbody tr').filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(date) > -1);
			});
		}
		var title = $(document).prop('title'); 
		title += ' - ' +date;
		$(document).prop('title', title); */
	});	

	$('.filter_travel').on('change',function() {
		var employee_id = $('#filter_employee').val().toLowerCase();
		var date = $('#filter_date').val().toLowerCase();
		var url = location.origin + '/travel_orders';

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
});