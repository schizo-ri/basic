<div class="modal-header">
	<h3 class="panel-title">@lang('calendar.edit_task')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('tasks.update', $task->id) }}">
		<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
			<label for="">@lang('basic.employee')</label>
			<select  class="form-control" name="employee_id" value="{{ old('employee_id') }}" required>
				<option selected disabled></option>
				@foreach ($employees as $employee)
					<option value="{{ $employee->id }}" {!! $task->employee_id == $employee->id  ? 'selected' : '' !!}>{{ $employee->user['first_name'] . ' ' .  $employee->user['last_name'] }}</option>
				@endforeach
			</select>
			{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
			<label>@lang('basic.title')</label>
			<input name="title" type="text" value="{{ $task->title }}" class="form-control" required>
			{!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group datum {{ ($errors->has('date')) ? 'has-error' : '' }}">
			<label>@lang('basic.date')</label>
			<input name="date" type="date" class="form-control" value="{{ $task->date }}" required>
			{!! ($errors->has('date') ? $errors->first('date', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<label class="time_label">@lang('basic.time')</label>
		<div class="form-group time {{ ($errors->has('time1')) ? 'has-error' : '' }}">
			<input name="time1" class="form-control" type="time" value="{{ $task->time1 }}" required />
			{!! ($errors->has('time1') ? $errors->first('time1', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group span">
			<span>@lang('calendar.to')</span>
		</div>
		<div class="form-group time {{ ($errors->has('time2')) ? 'has-error' : '' }}">
		<input name="time2" class="form-control" type="time" value="{{ $task->time2 }}" required />
			{!! ($errors->has('time2') ? $errors->first('time2', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group description clear_l {{ ($errors->has('description')) ? 'has-error' : '' }}">
			<label>@lang('basic.description')</label>
			<textarea name="description" class="form-control" type="text" >{{ $task->description }}</textarea>
			{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('car_id')) ? 'has-error' : '' }}">
			<label for="">@lang('basic.car')</label>
			<select  class="form-control" name="car_id" value="{{ old('car_id') }}" >
				<option selected disabled></option>
				@foreach ($cars as $car)
					<option value="{{ $car->id }}" {!! $task->car_id == $car->id  ? 'selected' : '' !!}>{{ $car->model . ' ' . $car->registration }}</option>
				@endforeach
			</select>
			{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>

		<input name="type" type="hidden" value="event" id="event_type" />
		{{ csrf_field() }}
		{{ method_field('PUT') }}
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
		<a href="" class="modal_close float_r" rel="modal:close">@lang('basic.cancel')</a>
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
$.getScript( '/../js/validate.js');
</script>