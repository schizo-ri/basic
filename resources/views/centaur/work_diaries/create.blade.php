<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_work_diary')</h3>
</div>
<div class="modal-body">
	<form class="form_work_diary" accept-charset="UTF-8" role="form" method="post" action="{{ route('work_diaries.store') }}">
		@if( Sentinel::inRole('administrator'))
			<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}" >
				<label>@lang('basic.employee')</label>
				<select class="form-control" name="employee_id" value="{{ old('employee_id') }}" id="select_employee" required >
					<option value="" disabled selected ></option>
					@foreach($employees as $employee)
						<option value="{{ $employee->id}}" {!! $employee->id == Sentinel::getUser()->employee->id ? 'selected' : '' !!} >{{ $employee->user->last_name . ' ' . $employee->user->first_name }}</option>
					@endforeach
				</select>
				{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
		@else
			<input type="hidden" name="employee_id"  id="select_employee" value="{{ Sentinel::getUser()->employee->id }}" >
		@endif
		<div class="form-group datum date1 float_l  {{ ($errors->has('date')) ? 'has-error' : '' }}" >
			<label>@lang('basic.date')</label>
			<input name="date" id="date" class="form-control" type="date" value="{!! old('date') ? old('date') : Carbon\Carbon::now()->format('Y-m-d') !!}" min="{!! !Sentinel::inRole('administrator') ? date_format(date_modify( New DateTime('now'),'-1 day'), 'Y-m-d') : '' !!}" required >
			{!! ($errors->has('date') ? $errors->first('date', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		@for ($i = 1; $i <= 5; $i++)
			<section id="{{ $i }}" class="project {!! $i!= 1 ? 'hidden' : '' !!}"> 
			@if ($i != 1)
				<p class="remove_project cursor">Obriši projekt {{ $i }}</p>
			@endif
				<h6 class="align_c overflow_hidd clear_l font_15 crimson">@lang('basic.project') {{ $i }}</h6>
				<section>
					@if(isset($projects) || isset($projects_erp) )
						<div class="form-group select_project {{ ($errors->has('project_id')) ? 'has-error' : '' }}">
							<label>@lang('basic.project')</label>
							<select id="select_project{{ $i }}" name="project_id[{{ $i }}]" placeholder="Izaberi projekt..."  value="{{ old('project_id') }}" {!! $i == 1 ? 'required' : '' !!}>
								<option value="" disabled selected>Izaberi projekt</option>
								@if(isset($projects_erp) && $projects_erp && count( $projects_erp ) > 0)
									@foreach ($projects_erp as $id => $project)
										<option class="project_list" value="{{ $id }}">{{ $project  }}</option>
									@endforeach	
								@else 
									@if(isset($projects) && $projects && count( $projects ) > 0 )
										@foreach ($projects as $project)
											<option class="project_list" value="{{ intval($project->id) }}" >{{ $project->erp_id  . ' ' . $project->name }}</option>
										@endforeach	
									@endif
								@endif
							</select>
						</div>
					@endif
					<div class="form-group tasks {{ ($errors->has('erp_task_id')) ? 'has-error' : '' }}">
						<label>@lang('basic.task')</label>
						<select id="select_task{{ $i }}" name="erp_task_id[{{ $i }}]" placeholder="Izaberi zadatak..."  value="{{ old('erp_task_id') }}" {!! $i == 1 ? 'required' : '' !!}>
							<option value="" disabled selected >Izaberi zadatak</option>
							@if(isset( $tasks ) && $tasks )
								@foreach ($tasks as $id => $task)
									<option class="project_list" value="{{ $id }}">{{ $task  }}</option>
								@endforeach	
							@endif
						</select>
					</div>
					<div class="form-group work_task_group {{ ($errors->has('task_id')) ? 'has-error' : '' }}" >
						<label>@lang('basic.work_tasks')</label>
						@foreach($workTasks as $workTask)
							<div class="form-group {{ ($errors->has('task_id')) ? 'has-error' : '' }}" >
								<span class="task_name show_hidden">{{ $workTask->name }}<i class="fas fa-caret-down"></i></span>
								<span class="hide_task"><i class="fas fa-eye-slash"></i></span>
								<span class="show_task"><i class="fas fa-eye"></i></span>
								<p class="task_description hidden">{!! trim($workTask->description) !!}</p>
								<article>
									<input class="task_id" type="hidden" name="task_id[{{ $i }}][]" value="{{  $workTask->id }}" >
									<input class="task_time" type="time" name="time[{{ $i }}][]" value="{!! old('time') ? old('time') : '' !!}" min="00:00" max="12:00"  > 
									<span class="padd_l_20" id="restHours_{{ $workTask->id }}"></span>
									<textarea class="form-control task_description" name="description[{{ $i }}][]" type="text"  rows="3" placeholder="Opis rada">{{ old('description') }}</textarea>
								</article>
							</div>
						@endforeach
					</div>
				</section>
			</section>
		@endfor
		<div class="col-md-12 clear_l overflow_hidd padd_0 form-group time_group" >
			<div class="time {{ ($errors->has('start_time')) ? 'has-error' : '' }}" >
				<label>@lang('absence.start_time')</label>
				<input name="start_time" class="form-control" type="time" value="{!! old('start_time') ? old('start_time') : '00:00' !!}" required disabled >
				{!! ($errors->has('start_time') ? $errors->first('start_time', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="time {{ ($errors->has('end_time')) ? 'has-error' : '' }}"  >
				<label>@lang('absence.end_time')</label>
				<input name="end_time" class="form-control" type="time" value="{!! old('end_time') ? old('end_time') : '00:00' !!}" required disabled readonly>
				{!! ($errors->has('end_time') ? $errors->first('end_time', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group select_project {{ ($errors->has('project_overtime')) ? 'has-error' : '' }}">
				<label>Prekovremeni sati na projekt</label>
				<select id="select_project_overtime" name="project_overtime" placeholder="Izaberi projekt..."  value="{{ old('project_overtime') }}" >
					@if(isset($projects_erp) && $projects_erp && count( $projects_erp ) > 0)
						@foreach ($projects_erp as $id => $project)
							<option class="project_list" value="{{ $id }}">{{ $project  }}</option>
						@endforeach	
					@endif
				</select>
			</div>		
		</div>
		<span class="add_project cursor">Dodaj projekt</span>
		{{ csrf_field() }}
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
	</form>
</div>

<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
	$.getScript('/../js/absence_create_new.js');

	/* showHideEelement();
	textareaChange();
	timeChange(); */
/* 	selectSearchModal (); */

/* 	var time;
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
	var project;
	var task;
	
	$('.add_project').on('click',function(){
		$('section.project:hidden').first().show();
		$('section.project:visible').last().children('section').children('.select_project').find('select').prop('required',true);
		$('section.project:visible').last().children('section').children('.tasks').find('select').prop('required',true);
	});

	$('.remove_project').on('click',function(){
		var id_parent = $( this ).parent().attr('id');
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
				console.log((h.toString().length == 1 ? '0'+h.toString() : h) + ':'+ m);
				$('input[name=end_time]').val((h.toString().length == 1 ? '0'+h.toString() : h) + ':'+ m);
			} else {
				$('.time_group').hide();
				$('.time_group').find('input').attr('disabled',true);
				$('.time_group p.alert').remove();
			}
		});
	}
 */
	
</script>