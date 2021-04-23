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
	var time;
	var time_array = [];
	var total_minute;
	var h;
	var m;
	var afterhour_min;
	var start_time_Arr;
	var end_time_minute;
	var this_time;
	var this_description;
	var project;
	var task;
	var hours = 0;
	var minutes  = 0;
	var seconds = 0;
	var timeParts;
	var total_time = 0;
	var bool;
	var select_projects;
	var select_tasks;
	var id_parent;
	
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
						if( broj_dana <= 0 ) {
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
		$('.btn-submit').on('click',function(e) {
			start_date = $( "#date" ).val();
			end_time = $("input[name=end_time]").val() + ':00';
			console.log("end_time1 " +end_time);
			if( end_time == '00:00:00') {
				if( h == undefined ) {
					end_time = '15:00:00';
				} else {
					end_time = (h.toString().length == 1 ? '0'+h.toString() : h) + ':'+ m + ':00';
				}
			}
			
			console.log("end_time2 " +end_time);
			StartDate = new Date(start_date + ' ' + end_time);
			console.log( date );
			console.log(StartDate);
			bool = (date >= StartDate);
			console.log(bool);

			if( StartDate != 'Invalid Date' && ! bool ) {
				e.preventDefault();
				alert ("Nemoguće je poslati zahtjev unaprijed! ")
			}
		});

		select_employee ();
		select_project ();
		dateChange ();
		checkTime();

		if( $('form.form_work_diary').length > 0) {
			showHideEelement();
			textareaChange();
			timeChange();
		}
	}

	function dateChange () {
		$('#date').on('change', function(){
			employee_id = $( '#select_employee' ).val();
			start_date =  $( '#date' ).val();
			if( employee_id != '' && employee_id != undefined && start_date != '' && start_date != undefined ) {
				getProjectEmpl ( employee_id, start_date );
			}
		});
	}

	function select_employee () {
		$('#select_employee').on('change', function(){
			employee_id = $( '#select_employee' ).val();
			start_date =  $( '#date' ).val();
			$('span[id^="restHours"]').text("");
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
			project_no = $( this ).find('option:selected').text();
			project_no = project_no.substring(1, 7);

			getTasksEmpl ( employee_id, start_date, project, parent );
			$('span[id^="restHours"]').text("");

			url = location.origin + '/getProjectTasks';
			$.ajax({
				url: url,
				type: "get",
				data:  { employee_id: employee_id, project:project_no },
				success: function( data ) {
					ProjectTasks = data.projectWorkTasks;
					console.log(ProjectTasks);
					if( ! jQuery.isEmptyObject(ProjectTasks)) {
						workDiaryItem = data.workDiaryItem;
						project_id = data.project_id;
						console.log(workDiaryItem);
						console.log(workDiaryItem);
						$.each(ProjectTasks, function( id, ProjectTask ) {
							
							total_time = 0;

							$.each( workDiaryItem[ProjectTask.task_id], function( key, item ) {
								time = item.time;
								timeParts = time.split(":");
								hours = timeParts[0];
								minutes = timeParts[1]/60;
								hours = parseFloat(hours)+parseFloat(minutes);
								total_time += hours;
							});
							$('#restHours_'+ProjectTask.task_id).text("Preostalo sati na projektu: " + (parseFloat(ProjectTask.hours) - total_time).toFixed(2));
							if(  (parseFloat(ProjectTask.hours) - total_time).toFixed(2) < 0) {
								$('#restHours_'+ProjectTask.task_id).addClass('red');
							}
						});
						$('.work_task_group').first().prepend('<a class="float_r open_recap" href="'+location.origin+'/projects/'+ project_id +'" rel="modal:open">Vidi obračun sati projekta</a>');
						$('.open_recap').on('click', function(){
							$.modal.defaults = {
								closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
								escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
								clickClose: true,       // Allows the user to close the modal by clicking the overlay
								closeText: 'Close',     // Text content for the close <a> tag.
								closeClass: '',         // Add additional class(es) to the close <a> tag.
								showClose: true,        // Shows a (X) icon/link in the top-right corner
								modalClass: "modal modal_big",    // CSS class added to the element being displayed in the modal.
								// HTML appended to the default spinner during AJAX requests.
								spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',
						
								showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
								fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
								fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
							};
						});
					}
				},
				error: function(jqXhr, json, errorThrown) {
					console.log(jqXhr);
					console.log(json);
					console.log(errorThrown);
				}
			}); 
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
	/* 	console.log('getTasksEmpl: ' + 'employee_id '+employee_id + ' start_date '+start_date+ ' project '+project); */
		$.ajax({
			url: url,
			type: "get",
			data:  { employee_id: employee_id, start_date: start_date, project:project },
			success: function( tasks ) {
			/* 	console.log(tasks); */
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
		select_projects = '<option value="" disabled selected>Izaberi projekt</option>';
		$.each(projects, function( id, project ) {
			select_projects += '<option class="project_list" name="erp_task_id" value="'+id+'">'+project+'</option>';
		});
		
		$('select[id^="select_project"] option').remove();
		if ($('select[id^="select_project"]').length > 0 ) {
			$('select[id^="select_project"]').prepend(select_projects);
		}
	}
	
	function tastElement(tasks, parent) {
		select_tasks = '<option value="" disabled selected>Izaberi zadatak</option>';

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

	
	$('.add_project').on('click',function(){
		$('section.project:hidden').first().show();
		$('section.project:visible').last().children('section').children('.select_project').find('select').prop('required',true);
		$('section.project:visible').last().children('section').children('.tasks').find('select').prop('required',true);
	});

	$('.remove_project').on('click',function(){
		id_parent = $( this ).parent().attr('id');
		console.log(id_parent);
		$( this ).parent().hide();
		$('section.project#'+id_parent).children('section').children('.select_project').find('select').prop('required',false);
		$('section.project#'+id_parent).children('section').children('.select_project').find('select').val(null);
		$('section.project#'+id_parent).children('section').children('.tasks').find('select').prop('required',false);
		$('section.project#'+id_parent).children('section').children('.tasks').find('select').val(null);
		$('section.project#'+id_parent+' input').val('');
		$('section.project#'+id_parent+' textarea').val('');

	});

	function showHideEelement() {
		$('.show_hidden').on('click', function(){
			$( this ).parent().find('.task_description').toggle();
		});
		$('.hide_task').on('click',function(){
			$(this).toggle();
			$( this ).siblings('.show_task').toggle();
			$( this ).parent().find('article').toggle();
		});
		$('.show_task').on('click',function(){
			$(this).toggle();
			$( this ).siblings('.show_task').toggle();
			$( this ).parent().find('article').toggle();
		});
	}

	function textareaChange() {
		$('textarea').on('change', function(){
			this_description = $( this ).val();
			if( this_description != '') {
				$( this ).parent().find('input[type=time]').prop('required', true);
			} else {
				$( this ).parent().find('input[type=time]').prop('required', false);
			}
		});
	}

	function timeChange() {
		$('input[type=time]').on('change', function(){
			this_time = $( this ).val();
			if( this_time != '00:00' && this_time != '') {
				$( this ).parent().find('textarea').prop('required', true);
			} else {
				$( this ).parent().find('textarea').prop('required', false);
			}
			total_minute = 0;
			end_time_minute=0;
			start_time = $('input[name=start_time]').val();
			
			$( "input.task_time[type=time]" ).each(function( index, element ) {
				time = $( this ).val();
				
				if(time != '') {
					console.log(time);
					time_array = time.split(':');
					console.log(time_array);
					if(time_array.length > 0 ) {
						total_minute = total_minute + parseInt((time_array[0] * 60));
						total_minute  = total_minute + parseInt(time_array[1]);
					}
				}
			});
		
			if(total_minute > (8*60) ) {
				$('.time_group').show();
				$('.error-modal').remove();
				$('.time_group').append('<p class="alert error-modal">Upisano je vrijeme veće od 8 radnih sati. Obavezan upis vremena za prekovremeni rad!</p>');
				
				$('.time_group').find('input').attr('disabled',false);
				project = $('section.project:visible').last().find('section').find('.select_project').find('select').val();
			
				$('.time_group .select_project select').find('option[value='+project+']').prop('selected',true);

				if( start_time == '00:00') {
					$('input[name=start_time]').val('15:00');
					start_time = $('input[name=start_time]').val();
				}
				afterhour_min = total_minute - (8*60);

				start_time_Arr = start_time.split(':');
				end_time_minute = end_time_minute + parseInt((start_time_Arr[0] * 60));
				end_time_minute  = end_time_minute + parseInt(start_time_Arr[1]);
				end_time = afterhour_min + end_time_minute;
				h = Math.floor(end_time / 60);
				m = end_time % 60;
				m = m < 10 ? '0' + m : m;
				/* console.log((h.toString().length == 1 ? '0'+h.toString() : h) + ':'+ m); */
				$('input[name=end_time]').val((h.toString().length == 1 ? '0'+h.toString() : h) + ':'+ m);
			} else {
				$('.time_group').hide();
				$('.time_group').find('input').attr('disabled',true);
				$('.time_group p.alert').remove();
			}
		});
	}
});	