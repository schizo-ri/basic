<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_task')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('tasks.store') }}">
		<div class="form-group {{ ($errors->has('task')) ? 'has-error' : '' }}">
			<label>@lang('basic.task')</label>
			<input name="task" type="text" class="form-control" value="{{ old('task') }}" maxlength="191" required >
			{!! ($errors->has('task') ? $errors->first('task', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group clear_l {{ ($errors->has('description')) ? 'has-error' : '' }}">
			<label>@lang('basic.description')</label>
			<textarea name="description" class="form-control" type="text" maxlength="65535" >{{ old('description') }}</textarea>
			{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('to_employee_id')) ? 'has-error' : '' }}">
			<label for="">@lang('basic.employees_in_charge')</label>
			<select class="form-control" name="to_employee_id[]" value="{{ old('to_employee_id') }}" size="10" multiple required >
				<option selected disabled></option>
				@foreach ($employees as $employee)
					<option value="{{ $employee->id }}">{{ $employee->last_name . ' ' .  $employee->first_name }}</option>
				@endforeach
			</select>
			{!! ($errors->has('to_employee_id') ? $errors->first('to_employee_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group datum float_l {{ ($errors->has('start_date')) ? 'has-error' : '' }}">
			<label>@lang('absence.start_date')</label>
			<input name="start_date" type="date" id="start_date" class="form-control" value="{!! isset($date) ? $date : Carbon\Carbon::now()->format('Y-m-d') !!}" required>
			{!! ($errors->has('start_date') ? $errors->first('start_date', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group datum float_r {{ ($errors->has('end_date')) ? 'has-error' : '' }}">
			<label>@lang('absence.end_date')</label>
			<input name="end_date" type="date" id="end_date"  class="form-control" value="{!! isset($date) ? $date : Carbon\Carbon::now()->format('Y-m-d') !!}" required>
			{!! ($errors->has('end_date') ? $errors->first('end_date', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('interval'))  ? 'has-error' : '' }} clear_l" id="period">
			<label class="label_period">Period ponavljanja</label>
			<select class="form-control period" name="interval_period" value="{{ old('interval_period') }}" required >
				<option class="no_repeat" value="no_repeat">Bez ponavljanja</option>
				<option value="every_day">Dnevno</option>
				<option value="once_week">Tjedno</option>
				<option value="once_month">Mjesečno</option>
				<option value="once_year">Godišnje</option>
			{{-- 	<option value="customized">Prilagođeno</option> --}}
			</select>
		</div>
		<div class="form-group {{ ($errors->has('car_id')) ? 'has-error' : '' }}">
			<label for="">@lang('basic.car')</label>
			<select  class="form-control" name="car_id" value="{{ old('car_id') }}" >
				<option selected disabled></option>
				@foreach ($cars as $car)
					<option value="{{ $car->id }}">{{ $car->model . ' ' . $car->registration }}</option>
				@endforeach
			</select>
			{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('active')) ? 'has-error' : '' }}">
			<label>Status</label>
			<label class="status" for="active_1">Aktivan <input name="active" type="radio" value="1" id="active_1" checked /></label>
			<label class="status" for="active_0">Neaktivan <input name="active" type="radio" value="0" id="active_0" /></label>
			{!! ($errors->has('start_date') ? $errors->first('start_date', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<input name="type" type="hidden" value="event" id="event_type" />
		{{ csrf_field() }}
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