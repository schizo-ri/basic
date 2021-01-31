
<div class="modal-header">
		<h3 class="panel-title">@lang('basic.add_afterhour')</h3>
	</div>
<div class="modal-body">
	<form class="form_afterhour" accept-charset="UTF-8" role="form" method="post" action="{{ route('afterhours.store') }}" >
		<input type="text" name="ERP_leave_type" id="request_type" value="67" hidden/> 
		@if (Sentinel::inRole('administrator'))
			<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
				<label>@lang('basic.employee')</label>
				<select class="form-control" name="employee_id"  id="select_employee" value="{{ old('employee_id') }}" size="10" autofocus required >
					<option value="" disabled></option>
					@foreach ($employees as $employee)
						<option name="employee_id" value="{{ $employee->id }}" {!! $employee->id == $request_empl->id ? 'selected' : '' !!}>{{ $employee->user['last_name']  . ' ' . $employee->user['first_name'] }}</option>
					@endforeach	
				</select>
				{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
		@else
			<p class="padd_10">Ja, {{ Sentinel::getUser()->first_name  . ' ' . Sentinel::getUser()->last_name }} 
				<span class="">@lang('absence.please_approve') @lang('basic.afterhours')</span>
			</p>
			<input name="employee_id" type="hidden" id="select_employee" value="{{  Sentinel::getUser()->employee->id }}"  />
		@endif
		<div class="form-group datum date1 float_l  {{ ($errors->has('date')) ? 'has-error' : '' }}" >
			<label>@lang('basic.date')</label>
			<input name="date" id="date" class="form-control" type="date" id="date" min="{!! !Sentinel::inRole('administrator') ? date_format(date_modify( New DateTime('now'),'-2 day'), 'Y-m-d') : '' !!}" value="{!! old('date') ? old('date') : Carbon\Carbon::now()->format('Y-m-d') !!}" required>
			{!! ($errors->has('date') ? $errors->first('date', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		@if($projects)
			<div class="form-group {{ ($errors->has('project_id')) ? 'has-error' : '' }}">
				<select id="select_project" name="project_id" placeholder="Izaberi projekt..."  value="{{ old('project_id') }}" id="sel1" required>
					<option value="" disabled selected></option>
					@foreach ($projects as $project)
						<option class="project_list" name="project_id" value="{{ intval($project->id) }}" >{{ $project->erp_id  . ' ' . $project->name }}</option>
					@endforeach	
				</select>
			</div>
		@endif
		@if(isset( $tasks ) && $tasks )
			<div class="form-group tasks {{ ($errors->has('erp_task_id')) ? 'has-error' : '' }}">
				<label>@lang('basic.task')</label>
				<select id="select_task" name="erp_task_id" placeholder="Izaberi zadatak..."  value="{{ old('erp_task_id') }}" id="sel1" required>
					<option value="" disabled selected></option>
					@foreach ($tasks as $id => $task)
						<option class="project_list" name="erp_task_id" value="{{ $id }}">{{ $task }}</option>
					@endforeach	
				</select>
			</div>
		@endif
		<div class="col-md-12 clear_l overflow_hidd padd_0 form-group time_group" >
            <div class="time {{ ($errors->has('start_time')) ? 'has-error' : '' }}" >
                <label>@lang('absence.start_time')</label>
                <input name="start_time" class="form-control" type="time" value="{!!  old('start_time') ? old('start_time') : '15:00' !!}"required>
                {!! ($errors->has('start_time') ? $errors->first('start_time', '<p class="text-danger">:message</p>') : '') !!}
            </div>
            <div class="time {{ ($errors->has('end_time')) ? 'has-error' : '' }}"  >
                <label>@lang('absence.end_time')</label>
                <input name="end_time" class="form-control" type="time" value="{!!  old('end_time') ? old('end_time') : '16:00' !!}"required>
                {!! ($errors->has('end_time') ? $errors->first('end_time', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
		<div class="form-group clear_l {{ ($errors->has('comment')) ? 'has-error' : '' }}">
			<label>@lang('basic.comment')</label>
			<textarea rows="4" name="comment" type="text" class="form-control" value="" maxlength="16535" required >{{ old('comment') }}</textarea>
			{!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		{{ csrf_field() }}
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
		<a href="#" rel="modal:close" class="btn-close">@lang('basic.cancel')</a>
	</form>
</div>
<script>
	$.getScript('/../js/absence_create.js');
</script>