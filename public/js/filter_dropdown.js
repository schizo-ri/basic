$(function() { // filter knowledge base
	$('#filter').on('change',function() {
		var trazi = $('#filter').val().toLowerCase();
		if(trazi == "all"){
			$('.panel').show();
		} else {
			$('.panel').filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(trazi) > -1);
			});
		}
	});	
	$('.filter_fuels').on('change',function() {
		var date =  $('#filter_month').val().toLowerCase();
		var car =  $('#filter_car').val().toLowerCase();
		console.log(date);
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
		$(document).prop('title', title);
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