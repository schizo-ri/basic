$(function(){
    var employee_id;
	var type;
	var start_date;
	var end_date;
	var start_time;
	var end_time;
	var time_diff
	var tomorrow;
	var diff_days;
	var broj_dana;
	var date = new Date();
	var today = date.getFullYear() + '-' + ( '0' + (date.getMonth()+1) ).slice( -2 ) + '-' + ( '0' + (date.getDate()) ).slice( -2 );
	var user_role = $('#user_role').text();	

	if($('form.absence').length > 0) {
		$( "#request_type" ).on('change',function() {
			$('input[name=start_date]').prop('disabled', false);
			$('input[name=start_date]').prop('hidden', false);
			$('input[name=end_date]').prop('disabled', false);
			$('input[name=end_date]').prop('hidden', false);
			type = $(this).val();
			employee_id = $('#select_employee').val();

			if(type == 'IZL' || type == 61) {
				start_date = $( "#start_date" ).val();
				end_date = $( "#end_date" );
				end_date.val(start_date);			
				
				date.setDate(date.getDate()-1);
				tomorrow = date.getFullYear() + '-' + ( '0' + (date.getMonth()+1) ).slice( -2 ) + '-' + ( '0' + (date.getDate()) ).slice( -2 );
				$('#start_date').attr('min', tomorrow);
				$('#start_date').val(today);
				$('.form-group.time_group').show();
				$('.form-group.date2').hide();

				/* 	getTask(employee_id, start_date); */

				$('input[name=end_time]').on('blur',function(){
					start_time = $('input[name=start_time]').val();
					end_time = $('input[name=end_time]').val();

					time_diff =  new Date("1970-1-1 " + end_time) - new Date("1970-1-1 " + start_time);
					time_diff = time_diff/1000/60/60;

					if( time_diff < 0 ) {
						$('.time_request').show();
						$('.btn-submit').hide();
					} else {
						$('.time_request').hide();
						$('.btn-submit').show();
					} 
				});
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
			console.log("type "+type);
			if(type == 'BOL' || type == 2 || type == 'IZL' || type == 61) { 
				$('.datum.date2').hide();
				if(type == 'BOL' || type == 2) {
					console.log('bolovanje');
					$('#end_date').val(null);
					$('#end_date').prop('required', false);
				} else {
					$('#end_date').prop('required', true);
				}
			} else {
				$('.datum.date2').show();
			}

			if(type == 'SLD') {
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
			console.log("select_employee");
			employee_id = $(this).val();
			type = $( "#request_type" ).val();
			
			if( employee_id != '' &&  employee_id != undefined && (type == 'GO' || type == 'holiday') ) {
				getDays( employee_id );
			}
		});	
		
		$( "#start_date" ).on('change',function() {
			console.log("start_date");
			start_date = $( this ).val();
			end_date = $( "#end_date" );
			if(type == 'BOL' || type == 2) {
				end_date.val(null);
			} else {
				end_date.val(start_date);
			}
			
		
			employee_id = $('#select_employee').val();
			// na promjenu tipa zahtjeva sa erpa dohvatiti taskove ako je IZLAZAK
			console.log("type "+type)
			if(type == 'IZL' || type == 61) {
				/* $('.tasks').remove();
				getTask(employee_id, start_date); */
			}
		});
		
		$( "#end_date" ).on('change',function() {
			console.log("end_date");
			start_date = $( "#start_date" ).val();
			end_date = $( this ).val();
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
						console.log( days_response );
						if( (broj_dana - days_response) < 0 && user_role != 'admin' ) {
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
		});
	}
	if($('form.form_afterhour').length > 0) {
		$( "#date" ).on('change',function() {
			start_date = $( this ).val();
			employee_id = $('#select_employee').val();
			console.log("employee_id "+employee_id);
			console.log("start_date "+start_date);
			
			/* getTask(employee_id, start_date); */
		});
		$( "#select_employee" ).on('change',function() {
			employee_id = $(this).val();
			start_date = $( "#date" ).val();
			console.log("employee_id "+employee_id);
			console.log("start_date "+start_date);

			if( employee_id != '' &&  employee_id != undefined ) {
			/* 	getTask(employee_id, start_date); */
			}
		});	

	}
});

function getTask(employee_id, start_date)
{
	url = location.origin + '/getTasks';
	console.log("getTask");
	$.ajax({
		url: url,
		type: "get",
		data:  { employee_id: employee_id, start_date: start_date},
		success: function( tasks ) {
			console.log(tasks);
			if($.map(tasks, function(n, i) { return i; }).length > 0 ) {
				var select_tasks = '<div class="form-group tasks"><label>Zadatak</label><select id="select-state" name="erp_task_id" placeholder="Izaberi zadatak..." required><option value="" disabled selected></option>';
				$.each(tasks, function( id, task ) {
					select_tasks += '<option class="project_list" name="erp_task_id" value="'+id+'">'+task+'</option>';

				});
				select_tasks += '</select></div>';
				$('.tasks').remove();
				$('.time_group').before(select_tasks);
			}
		},
		error: function(jqXhr, json, errorThrown) {
			console.log(jqXhr);
			console.log(json);
			console.log(errorThrown);
		}
	}); 
}

function getDays( employee_id )
{
	console.log("getDays");
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
