<div class="modal-header">
		<h3 class="panel-title">@lang('basic.add_day_off')</h3>
	</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('day_offs.store') }}" >
		<div class="form-group {{ ($errors->has('employee_id'))  ? 'has-error' : '' }}">
			<label>@lang('basic.employee')</label>
			<select  class="form-control" name="employee_id" value="{{ old('employee_id') }}" required>
				<option value="" disabled selected></option>
				@foreach ($employees as $employee)
					<option value="{{ $employee->id }}">{{ $employee->user->last_name  .' '. $employee->user->first_name }}</option>
				@endforeach
			</select>
			{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('days_no'))  ? 'has-error' : '' }}">
			<label>@lang('basic.no_days')</label>
			<input name="days_no" type="number" min="0" max="20" class="form-control" value="{{ old('days_no') }}" maxlength="150" required >
			{!! ($errors->has('days_no') ? $errors->first('days_no', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('comment'))  ? 'has-error' : '' }}">
			<label>@lang('basic.comment'):</label>
			<textarea name="comment" rows="5" maxlength="21845" required></textarea>
			{!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		{{ csrf_field() }}
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
		<a href="#" rel="modal:close" class="btn-close">@lang('basic.cancel')</a>
	</form>
</div>