
<div class="modal-header">
		<h3 class="panel-title">@lang('basic.add_employee_terminations')</h3>
	</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('employee_terminations.store') }}" >
			<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
				<label>@lang('basic.employee')</label>
				<select class="form-control" name="employee_id" value="{{ old('employee_id') }}" autofocus required >
					<option value="" disabled selected></option>
						@foreach ($employees as $employee)
							<option name="employee_id" value="{{ $employee->id }}">{{ $employee->user['last_name']  . ' ' . $employee->user['first_name'] }}</option>
						@endforeach
				</select>
				{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('termination_id')) ? 'has-error' : '' }}">
				<label>@lang('basic.termination_type')</label>
				<select class="form-control" name="termination_id" value="{{ old('termination_id') }}" required >
					<option value="" disabled selected></option>
					@foreach ($terminations as $termination)
						<option name="termination_id" value="{{ $termination->id }}">{{ $termination->name }}</option>
					@endforeach	
				</select>
				{!! ($errors->has('termination_id') ? $errors->first('termination_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group datum date1 float_l  {{ ($errors->has('check_out_date')) ? 'has-error' : '' }}" >
				<label>@lang('basic.date')</label>
				<input name="check_out_date" id="date" class="form-control" type="date" value="{!!  old('check_out_date') ? old('check_out_date') : Carbon\Carbon::now()->format('Y-m-d') !!}" required>
				{!! ($errors->has('check_out_date') ? $errors->first('check_out_date', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group clear_l  {{ ($errors->has('notice_period')) ? 'has-error' : '' }}" >
				<label>@lang('basic.notice_period')</label>
				<input name="notice_period" id="date" class="form-control" type="text" value="{{ old('notice_period') }}" maxlength="100" required>
				{!! ($errors->has('notice_period') ? $errors->first('notice_period', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group clear_l {{ ($errors->has('comment')) ? 'has-error' : '' }}">
				<label>@lang('basic.comment')</label>
				<textarea rows="4" name="comment" type="text" class="form-control" value="" maxlength="16535" required>{{ old('comment') }}</textarea>
				{!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
			</div>
		{{ csrf_field() }}
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
		<a href="#" rel="modal:close" class="btn-close">@lang('basic.cancel')</a>
	</form>
</div>
