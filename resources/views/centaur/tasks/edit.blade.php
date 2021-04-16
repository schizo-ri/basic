<div class="modal-header">
	<h3 class="panel-title">@lang('basic.edit_task')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('tasks.update', $task->id) }}">
		<div class="form-group {{ ($errors->has('task')) ? 'has-error' : '' }}">
			<label>@lang('basic.task')</label>
			<input name="task" type="text" class="form-control" value="{{ $task->task }}" maxlength="191" required >
			{!! ($errors->has('task') ? $errors->first('task', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group">
			<input type="checkbox" name="energy_consumptions" id="energy" value="1" {!! $task->energy_consumptions == 1 ? 'checked' : '' !!} ><label for="energy">Potrošnja energenata</label>
		</div>
		<div class="form-group clear_l {{ ($errors->has('description')) ? 'has-error' : '' }}">
			<label>@lang('basic.description')</label>
			<textarea name="description" class="form-control" type="text" maxlength="65535" >{{ $task->description }}</textarea>
			{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
			<label for="">@lang('basic.created_by')</label>
			<select class="form-control" name="employee_id" value="{{ $task->employee_id }}" size="10" required >
				@foreach ($employees as $employee)
					<option value="{{ $employee->id }}" {!! $task->employee_id == $employee->id ? 'selected' : '' !!}>{{ $employee->last_name . ' ' .  $employee->first_name }}</option>
				@endforeach
			</select>
			{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('to_employee_id')) ? 'has-error' : '' }}">
			<label for="">@lang('basic.employees_in_charge')</label>
			<select class="form-control" name="to_employee_id[]" value="{{ $task->to_employee_id }}" size="10" multiple required >
				@php
					$employee_ids = explode(',', $task->to_employee_id);
				@endphp
				@foreach ($employees as $employee)
					<option value="{{ $employee->id }}" {!! in_array($employee->id, $employee_ids )  ? 'selected' : '' !!}>{{ $employee->last_name . ' ' .  $employee->first_name }}</option>
				@endforeach
			</select>
			{!! ($errors->has('to_employee_id') ? $errors->first('to_employee_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group datum float_l {{ ($errors->has('start_date')) ? 'has-error' : '' }}">
			<label>@lang('absence.start_date')</label>
			<input name="start_date" type="date" id="start_date" class="form-control" value="{!! $task->start_date !!}" required>
			{!! ($errors->has('start_date') ? $errors->first('start_date', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group datum float_r {{ ($errors->has('end_date')) ? 'has-error' : '' }}">
			<label>@lang('absence.end_date')</label>
			<input name="end_date" type="date" id="end_date"  class="form-control" value="{!! $task->end_date !!}" required>
			{!! ($errors->has('end_date') ? $errors->first('end_date', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group datum float_l {{ ($errors->has('time1')) ? 'has-error' : '' }}">
			<label>@lang('absence.time')</label>
			<input name="time1" type="time" id="time1" class="form-control" value="{!! $task->time1 !!}" required>
			{!! ($errors->has('time1') ? $errors->first('time1', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('interval_period'))  ? 'has-error' : '' }} clear_l" id="period">
			<label class="label_period">Period ponavljanja</label>
			<select class="form-control period" name="interval_period" value="{{ $task->interval_period }}" required >
				<option class="no_repeat" value="no_repeat"  {!! $task->interval_period == 'no_repeat' ? 'selected' : '' !!}  >Bez ponavljanja</option>
				<option value="every_day" {!! $task->interval_period == 'every_day' ? 'selected' : '' !!} >Dnevno</option>
				<option value="once_week" {!! $task->interval_period == 'once_week' ? 'selected' : '' !!}>Tjedno</option>
				<option value="once_month" {!! $task->interval_period == 'once_month' ? 'selected' : '' !!}>Mjesečno</option>
				<option value="once_year" {!! $task->interval_period == 'once_year' ? 'selected' : '' !!}>Godišnje</option>
			</select>
		</div>
		<div class="form-group {{ ($errors->has('car_id')) ? 'has-error' : '' }}">
			<label for="">@lang('basic.car')</label>
			<select  class="form-control" name="car_id" value="{{ $task->car_id }}" >
				<option selected disabled></option>
				@foreach ($cars as $car)
					<option value="{{ $car->id }}" {!! $task->car_id ==  $car->id ? 'selected' : '' !!}  >{{ $car->model . ' ' . $car->registration }}</option>
				@endforeach
			</select>
			{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('active')) ? 'has-error' : '' }}">
			<label>Status</label>
			<label class="status" for="active_1">Aktivan <input name="active" type="radio" value="1" id="active_1" {!! $task->active == 1 ? 'checked' : '' !!} /></label>
			<label class="status" for="active_0">Neaktivan <input name="active" type="radio" value="0" id="active_0" {!! $task->active == 0 ? 'checked' : '' !!} /></label>
			{!! ($errors->has('start_date') ? $errors->first('start_date', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<input name="type" type="hidden" value="event" id="event_type" />
		<div class="form-group">
			<label >@lang('absence.email_send')</label>
			<span><input type="radio" name="send_email" value="DA"  /> @lang('basic.send_mail') </span>
			<span><input type="radio" name="send_email" value="NE" checked /> @lang('basic.dont_send_mail')</span>
		</div>
		{{ csrf_field() }}
		{{ method_field('PUT') }}
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
		<a href="" class="modal_close float_r" rel="modal:close">@lang('basic.cancel')</a>
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
	$( "#start_date" ).on('change',function() {
		start_date = $( this ).val();
		end_date = $( "#end_date" );
		end_date.val(start_date);
	});
</script>