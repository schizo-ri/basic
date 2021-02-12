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
						console.log("broj slobodnih dana " + broj_dana);
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
	}

	if( $('form.form_work_diary').length > 0 ) {
		showHideEelement();
		textareaChange();
		timeChange();
		selectSearchModal ();

		var time;
		var time_array = [];
		var total_minute;
		var h;
		var m;
		var afterhour_min;
		var start_time;
		var start_time_Arr;
		var end_time_minute;
		var end_time;
		var this_time;
		var this_description;
		var count_project;
		var parent;

		$('.add_project').on('click',function(){
			console.log('************add_project***********');
			count_project = $('section.project').length;
			console.log(count_project);
			$('.add_project').before('<section id="'+(count_project +1)+'" class="project"><h6 class="align_c overflow_hidd clear_l font_15 crimson">Project'+(count_project +1)+'</h6></section');

			$( 'section#'+count_project+'>section' ).clone().appendTo( '#'+(count_project +1));
			

			$( 'section#'+(count_project+1)+'>section' ).find('select#select_project').attr('name', 'project_id['+ (count_project +1) +']');
			$( 'section#'+(count_project+1)+'>section' ).find('select#select_task').attr('name', 'erp_task_id['+ (count_project +1) +']');
			$( 'section#'+(count_project+1)+'>section' ).find('select#select_task').prop('required',false);
			$( 'section#'+(count_project+1)+'>section' ).find('input.task_id').attr('name', 'task_id['+ (count_project +1) +'][]');
			$( 'section#'+(count_project+1)+'>section' ).find('input.task_id').prop('required', false);
			$( 'section#'+(count_project+1)+'>section' ).find('input.task_time').attr('name', 'task_id['+ (count_project +1) +'][]');
			$( 'section#'+(count_project+1)+'>section' ).find('input.task_time').val("");
			$( 'section#'+(count_project+1)+'>section' ).find('input.task_time').prop('required', false);
			$( 'section#'+(count_project+1)+'>section' ).find('textarea.task_description').attr('name', 'task_id['+ (count_project +1) +'][]');
			$( 'section#'+(count_project+1)+'>section' ).find('textarea.task_description').val("");
			$( 'section#'+(count_project+1)+'>section' ).find('textarea.task_description').prop('required', false);
			parent = $( 'section#'+(count_project+1)+'>section' );
			showHideEelement();
			textareaChange();
			timeChange();
			selectSearchModal ();
			
			
			$('#select_project').on('change', function(){
				if(parent == null || parent == '') {
					parent = $( this ).parent().parent();
				}
				console.log('parent:');
				console.log(parent);
				employee_id = $( '#select_employee' ).val();
				start_date =  $( '#date' ).val();
				project = $( this ).val();
				console.log("employee_id " + employee_id);
				console.log("start_date "+start_date);
				console.log("project "+project);
				getTasksEmpl ( employee_id, start_date, project, parent );
			});
			
		});

		function showHideEelement() {
			$('.show_hidden').on('click', function(){
				$( this ).parent().find('.task_description').toggle();
			});
			$('.hide_task').on('click',function(){
				$(this).hide();
				$( this ).siblings('.show_task').show();
				$( this ).parent().find('article').toggle();
			});
			$('.show_task').on('click',function(){
				$(this).hide();
				$( this ).siblings('.show_task').show();
				$( this ).parent().find('article').toggle();
			});
		}

		function textareaChange() {
			$('textarea').on('change', function(){
				this_description = $( this ).val();
				console.log(this_description);
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
						time_array = time.split(':');
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

					$('input[name=end_time]').val((h.toString().length == 1 ? '0'+h.toString() : h) + ':'+ m);
				} else {
					$('.time_group').hide();
					$('.time_group').find('input').attr('disabled',true);
					$('.time_group p.alert').remove();
				}
			});
		}

		function selectSearchModal () {
			$(function(){
				if( $('select.form-control').length > 0 ) {
					$('select.form-control').select2({
						dropdownParent: $('body'),
						width: 'resolve',
						placeholder: {
							id: '-1', // the value of the option
						},
					});
				}
			});
		}
	}
	if($('form.form_afterhour').length > 0 || $('form.form_work_diary').length > 0) {
		select_employee (null);
		select_project (null);
		dateChange (null);
	}

	function dateChange (parent) {
		$('#date').on('change', function(){
			employee_id = $( '#select_employee' ).val();
			start_date =  $( '#date' ).val();
			if(parent == null) {
				parent = $( this ).parent().siblings('section.project');
			}
			if( employee_id != '' && employee_id != undefined ) {
				getProjectEmpl ( employee_id, start_date, parent );
			}
		});
	}

	function select_employee (parent) {
		console.log('select_employee');
		console.log('parent:');
		console.log(parent);
		$('#select_employee').on('change', function(){
			if(parent == null) {
				parent = $( this ).parent().siblings('section.project');
			}
			console.log('parent:');
			console.log(parent);
			employee_id = $( '#select_employee' ).val();
			start_date =  $( '#date' ).val();
			console.log("employee_id " + employee_id);
			console.log("start_date "+start_date);
			getProjectEmpl ( employee_id, start_date, parent );
		});
	}

	function select_project ( parent ) {
		$('#select_project').on('change', function(){
			if(parent == null || parent == '') {
				parent = $( this ).parent().parent();
			}
			employee_id = $( '#select_employee' ).val();
			start_date =  $( '#date' ).val();
			project = $( this ).val();
			console.log("employee_id " + employee_id);
			console.log("start_date "+start_date);
			console.log("project "+project);
			getTasksEmpl ( employee_id, start_date, project, parent );
		});
	}

	function getProjectEmpl ( employee_id, start_date, parent ) {
		url = location.origin + '/getProject';
		$.ajax({
			url: url,
			type: "get",
			data:  { employee_id: employee_id, start_date: start_date },
			success: function( projects ) {
				console.log("projects:");
				console.log(projects);
				if($.map(projects, function(n, i) { return i; }).length > 0 ) {
					projectElement(projects, parent);
				}
				getTasksEmpl ( employee_id, start_date, Object.keys(projects)[0], parent );

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
		$.ajax({
			url: url,
			type: "get",
			data:  { employee_id: employee_id, start_date: start_date, project:project },
			success: function( tasks ) {
				console.log("tasks:");
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

	function projectElement(projects, parent) {
		var select_projects = '<option value="" disabled selected>Izaberi projekt</option>';
		$.each(projects, function( id, project ) {
			select_projects += '<option class="project_list" name="erp_task_id" value="'+id+'">'+project+'</option>';
		});
		
		$('#select_project option').remove();
		if ($('#select_project').length > 0 ) {
			$(parent).find('#select_project').prepend(select_projects);
		}
	}
	
	function tastElement(tasks, parent) {
		var select_tasks = '<option value="" disabled selected>Izaberi zadatak</option>';
		$.each(tasks, function( id, task ) {
			select_tasks += '<option class="project_list" name="erp_task_id" value="'+id+'">'+task+'</option>';
		});
		select_tasks += '</select></div>';
		$('#select_task option').remove();
		if ($('#select_task').length > 0 ) {
			$(parent).find('#select_task').prepend(select_tasks);
			
		}
		
		if(select_tasks == '') {
			$('#select_task').prop('required', false);
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
});	