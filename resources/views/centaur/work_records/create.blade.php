<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_work_records')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('work_records.store') }}" >
		<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
			<label>@lang('basic.employee')</label>
			<select class="form-control" name="employee_id" value="{{ old('employee_id') }}" autofocus required >
				<option value="" selected disabled></option>
				@foreach ($employees as $employee)
					<option name="employee_id" value="{{ $employee->id }}">{{ $employee->user['last_name']  . ' ' . $employee->user['first_name'] }}</option>
				@endforeach	
			</select>
			{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group datum date1 float_l  {{ ($errors->has('start')) ? 'has-error' : '' }}" >
			<label>@lang('absence.start_date')</label>
			<input name="start" id="start" class="form-control" type="datetime-local" value="{!!  old('start') ? old('start') : Carbon\Carbon::now()->format('Y-m-d\TH:i') !!}" required>
			{!! ($errors->has('start') ? $errors->first('start', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group datum  date2 float_r  {{ ($errors->has('end')) ? 'has-error' : '' }}" >
			<label>@lang('absence.end_date')</label>
			<input name="end" id="end" class="form-control" type="datetime-local" value="{!!  old('end') ? old('end') : '' !!}" >
			{!! ($errors->has('end') ? $errors->first('end', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<input type="hidden" name="entry" id="entry" value="entry">
		{{ csrf_field() }}
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
$.getScript( '/../js/validate.js');
</script>