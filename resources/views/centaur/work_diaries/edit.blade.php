<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_work_diary')</h3>
</div>
<div class="modal-body">
	<form class="edit_form_work_diary" accept-charset="UTF-8" role="form" method="post" action="{{ route('work_diaries.update', $workDiary->id ) }}">
		@if( Sentinel::inRole('administrator'))
			<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}" >
				<label>@lang('basic.employee')</label>
				<select class="form-control" name="employee_id" required value="{{ old('employee_id') }}" required >
					<option value="" disabled selected ></option>
					@foreach($employees as $employee)
						<option value="{{ $employee->id }}" {!! $workDiary->employee_id ==  $employee->id ? 'selected' : '' !!}>{{ $employee->user->last_name . ' ' . $employee->user->first_name }}</option>
					@endforeach
				</select>
				{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
		@else
			<input type="hidden" name="employee_id" value="{{ Sentinel::getUser()->employee->id }}" >
		@endif
		<div class="form-group datum date1 float_l  {{ ($errors->has('date')) ? 'has-error' : '' }}" >
			<label>@lang('basic.date')</label>
			<input name="date" id="date" class="form-control" type="date" value="{{ $workDiary->date }}"  min="{!! !Sentinel::inRole('administrator') ? date_format(date_modify( New DateTime('now'),'-1 day'), 'Y-m-d') : '' !!}" required >
			{!! ($errors->has('date') ? $errors->first('date', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		@if($projects)
			<div class="form-group {{ ($errors->has('project_id')) ? 'has-error' : '' }}">
				<label>@lang('basic.project')</label>
				<select id="select-state" name="project_id" placeholder="Pick a state..."  value="{{ old('project_id') }}" id="sel1" required>
					<option value="" disabled selected></option>
					@foreach ($projects as $project)
						<option class="project_list" name="project_id" value="{{ intval($project->id) }}" {!! $workDiary->project_id ==  $project->id ? 'selected' : '' !!} >{{ $project->erp_id  . ' ' . $project->name }}</option>
					@endforeach	
				</select>
			</div>
		@endif
		@if(isset( $tasks ) && $tasks )
			<div class="form-group tasks {{ ($errors->has('erp_task_id')) ? 'has-error' : '' }}">
				<label>@lang('basic.task')</label>
				<select id="select-state" name="erp_task_id" placeholder="Pick a state..." value="{{ old('erp_task_id') }}" id="sel1" required>
					<option value="" disabled selected></option>
					@foreach ($tasks as $id => $task)
						<option class="project_list" name="erp_task_id" value="{{ $id }}" {!! $workDiary->erp_task_id ==  $id ? 'selected' : '' !!}>{{ $task  }}</option>
					@endforeach	
				</select>
			</div>
		@endif
		<div class="form-group {{ ($errors->has('task_id')) ? 'has-error' : '' }}" >
			<label>@lang('basic.work_tasks')</label>
			@foreach($workTasks as $task)
				@php
					$item = $workDiary->hasWorkDiaryItem->where('task_id', $task->id )->first();
				@endphp
				<div class="form-group {{ ($errors->has('task_id')) ? 'has-error' : '' }}" >
					<span class="task_name show_hidden">{{  $task->name }}<i class="fas fa-caret-down"></i></span>
					<p class="task_description hidden">{!! trim($task->description) !!}</p>
					<input type="hidden" name="task_id[]" value="{{  $task->id }}" >
					<input type="time" name="time[]" class="task_time" value="{!! $item && $item->time ?  $item->time : '00:00' !!}" min="00:00" max="12:00" required >
					<textarea name="description[]" type="text" class="form-control" rows="3" placeholder="Opis rada">{!! $item && $item->description ? $item->description : '00:00' !!}</textarea>
					
				</div>
			@endforeach
		</div>
		<div class="col-md-12 clear_l overflow_hidd padd_0 form-group time_group" >
            <div class="time {{ ($errors->has('start_time')) ? 'has-error' : '' }}" >
                <label>@lang('absence.start_time')</label>
                <input name="start_time" class="form-control" type="time" value="{!! $workDiary->start_time ? $workDiary->start_time : '15:00' !!}" required disabled >
                {!! ($errors->has('start_time') ? $errors->first('start_time', '<p class="text-danger">:message</p>') : '') !!}
            </div>
            <div class="time {{ ($errors->has('end_time')) ? 'has-error' : '' }}"  >
                <label>@lang('absence.end_time')</label>
                <input name="end_time" class="form-control" type="time" value="{!! $workDiary->end_time ? $workDiary->end_time : '16:00' !!}" required disabled readonly>
                {!! ($errors->has('end_time') ? $errors->first('end_time', '<p class="text-danger">:message</p>') : '') !!}
            </div>
		</div>
		{{ csrf_field() }}
		{{ method_field('PUT') }}
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
	$('.show_hidden').on('click', function(){
		$( this ).parent().find('.task_description').toggle();
	});

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

	allTime() ;

	$('input[type=time]').on('change', function(){
		allTime();

		afterhour_min = total_minute - (8*60);

		start_time = $('input[name=start_time]').val();
		start_time_Arr = start_time.split(':');
		console.log(start_time_Arr);
		end_time_minute = end_time_minute + parseInt((start_time_Arr[0] * 60));
		end_time_minute  = end_time_minute + parseInt(start_time_Arr[1]);
		end_time = afterhour_min + end_time_minute;

		h = Math.floor(end_time / 60);
		m = end_time % 60;
		m = m < 10 ? '0' + m : m;

		$('input[name=end_time]').val(h+':'+ m);
	});

	function allTime() 
	{
		total_minute = 0;
		end_time_minute=0;
		$( "input.task_time[type=time]" ).each(function( index, element ) {
			
			time = $( this ).val();
			time_array = time.split(':');

			total_minute = total_minute + parseInt((time_array[0] * 60));
			total_minute  = total_minute + parseInt(time_array[1]);
			console.log(total_minute);
			
		});
		if( total_minute > (8*60) ) {
			console.log(" total_minute > (8*60) ");
			$('p.alert').remove();
			$('.time_group').append('<p class="alert error-modal">Upisano je vrijeme veće od 8 radnih sati. Obavezan upis vremena za prekovremeni rad!</p>');
			$('.time_group').find('input').attr('disabled',false);
			console.log( $('.time_group').lenght );
			
			$('.time_group').show();
		} else {
			console.log(" total_minute < (8*60) ");
			$('.time_group').hide();
			$('.time_group').find('input').attr('disabled',true);
			$('p.alert').remove();
		}
	}
</script>