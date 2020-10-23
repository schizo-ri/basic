$(function() { // filter knowledge base
	var value = null;
	var date = null;
	var employee_id = null;

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
			success: function( response ) {
				$( '#admin_page >main' ).load(url + ' #admin_page >main .table-responsive',function(){
					$.getScript('/../restfulizer.js');
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
			success: function( response ) {
				$( '#admin_page >main' ).load(location.href + '?date='+date+'&employee_id='+employee_id + ' #admin_page >main .table-responsive',function(){
					$.getScript('/../restfulizer.js');
				});
			},
			error: function(jqXhr, json, errorThrown) {
				console.log(jqXhr.responseJSON.message);
			}
		});
	});

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
		date =  $('#filter_month').val().toLowerCase();
		$.ajax({
			url: location.href + '?date='+date,
			type: "get",
			data: { 'date': date},
			success: function( response ) {
				$( '#admin_page >main' ).load(location.href + '?date='+date + ' #admin_page >main .table-responsive',function(){
					$.getScript('/../restfulizer.js');
				});
			},
			error: function(jqXhr, json, errorThrown) {
				console.log(jqXhr.responseJSON.message);
			}
		});


		/* if(date == 'all' ) {
			date = '';
		}  */
		
	/* 	if(date == ''){
			$('tbody tr').show();
		} else {
			$('tbody tr').filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(date) > -1);
			});
		} */
		/* var title = $(document).prop('title'); 
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