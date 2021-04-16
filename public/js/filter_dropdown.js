$(function() { // filter knowledge base
	var value = null;
	var date = null;
	var year = null;
	var employee_id = null;
	var task = null;
	var url;
	var project;

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
							$('.dt-buttons').toggle();
						})
						if (url.includes('afterhours'))  {
							if( $('tbody tr').length == 0) {
								$('.store_buttom').hide();
							} else {
								$('.store_buttom').show();
							}
						}
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