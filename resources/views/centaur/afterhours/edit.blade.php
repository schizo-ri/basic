
<div class="modal-header">
		<h3 class="panel-title">@lang('basic.edit_afterhour')</h3>
	</div>
<div class="modal-body">
	<form class="form_afterhour" accept-charset="UTF-8" role="form" method="post" action="{{ route('afterhours.update', $afterhour->id) }}" >
		<input type="text" name="ERP_leave_type" id="request_type" value="67" hidden/> 
		<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
			<label>@lang('basic.employee')</label>
			<select class="form-control" name="employee_id" id="select_employee" value="{{ old('employee_id') }}" size="10" autofocus required >
				<option value="" disabled></option>
				@foreach ($employees as $employee)
					<option name="employee_id" value="{{ $employee->id }}" {!! $afterhour->employee_id == $employee->id ? 'selected' : '' !!} >{{ $employee->user['first_name']  . ' ' . $employee->user['last_name'] }}</option>
				@endforeach	
			</select>
			{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group select_project {{ ($errors->has('project_id')) ? 'has-error' : '' }}">
			<label>@lang('basic.project')</label>
			<select id="select_project" name="project_id" placeholder="Izaberi projekt..."  value="{{ old('project_id') }}" required>
				<option value="" disabled selected></option>
				@if(isset($projects) &&  $projects)
					@foreach ($projects as $project)
						<option class="project_list" name="project_id" value="{{ intval($project->id) }}" {!! $afterhour->project_id == $project->id ? 'selected' : '' !!}>{{ $project->erp_id  . ' ' . $project->name }}</option>
					@endforeach	
				@endif
				@if(isset($projects_erp) && $projects_erp )
					@foreach ($projects_erp as $id => $project)
						<option class="project_list" name="project_id" value="{{ $id }}" {!! $afterhour->project_id == $id ? 'selected' : '' !!}>{{ $project  }}</option>
					@endforeach	
				@endif
			</select>
		</div>
		<div class="form-group tasks {{ ($errors->has('erp_task_id')) ? 'has-error' : '' }}">
			<label>@lang('basic.task')</label>
			<select id="select_task" name="erp_task_id" placeholder="Izaberi zadatak..."  value="{{ old('erp_task_id') }}" {{-- required --}}>
				<option value="" disabled selected></option>
				@if(isset( $tasks ) && count($tasks)>0 )
					@foreach ($tasks as $id => $task)
						<option class="project_list" name="erp_task_id" value="{{ $id }}" {!! $afterhour->erp_task_id == $id ? 'selected' : '' !!}>{{ $task }}</option>
					@endforeach	
				@endif 
			</select>
		</div>
		<div class="form-group datum date1 float_l  {{ ($errors->has('date')) ? 'has-error' : '' }}" >
			<label>@lang('basic.date')</label>
			<input name="date" id="date" class="form-control" type="date" value="{{ $afterhour->date }}" required>
			{!! ($errors->has('date') ? $errors->first('date', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group col-md-12 clear_l overflow_hidd padd_0 time_group" >
            <div class="time {{ ($errors->has('start_time')) ? 'has-error' : '' }}" >
                <label>@lang('absence.start_time')</label>
                <input name="start_time" class="form-control" type="time" value="{{ $afterhour->start_time }}"required>
                {!! ($errors->has('start_time') ? $errors->first('start_time', '<p class="text-danger">:message</p>') : '') !!}
            </div>
            <div class=" time {{ ($errors->has('end_time')) ? 'has-error' : '' }}"  >
                <label>@lang('absence.end_time')</label>
                <input name="end_time" class="form-control" type="time" value="{{ $afterhour->end_time }}" required>
                {!! ($errors->has('end_time') ? $errors->first('end_time', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
		<div class="form-group clear_l {{ ($errors->has('comment')) ? 'has-error' : '' }}">
			<label>@lang('basic.comment')</label>
			<textarea rows="4" name="comment" type="text" class="form-control" value="" maxlength="16535" required >{{ $afterhour->comment }}</textarea>
			{!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		{{ csrf_field() }}
        {{ method_field('PUT') }}
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
		<a href="#" rel="modal:close" class="btn-close">@lang('basic.cancel')</a>
	</form>
</div>
<script>
	$( ".date.form-control" ).change(function() {
		if( ! $('.role_admin').text()) {
			var date = $( this ).val();
			var now = new Date();
			var today = now.getFullYear() + '-' + ("0" + (now.getMonth()+1)).slice(-2) + '-' + ("0" + now.getDate()).slice(-2);
			
			var daybefore = new Date(now.setDate(now.getDate() - 1));
			var yesterday = daybefore.getFullYear() + '-' + ("0" + (daybefore.getMonth()+1)).slice(-2) + '-' + ("0" + daybefore.getDate()).slice(-2);

			if( date == today || date == yesterday) {
				$('.editOption5').removeAttr('disabled');
			} else {
				alert("Zahtjev je moguće poslati samo na danas i jučer");
				$('.editOption5').attr('disabled','true');
			}
		}

		$.getScript('/../js/absence_create_new.js');
	});
</script>