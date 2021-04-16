
<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_employee_training')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('employee_trainings.store') }}">
		<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}" >
			<label>@lang('basic.employee')</label>
			<select class="form-control" name="employee_id" required value="{{ old('employee_id') }}" required >
				<option value="" disabled selected ></option>
				@foreach($employees as $employee)
					@if ($employee->user)
						<option value="{{ $employee->id}}" >{{ $employee->user->first_name . ' ' . $employee->user->last_name }}</option>
					@endif
				@endforeach
			</select>
			{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('training_id')) ? 'has-error' : '' }}" >
			<label>@lang('basic.training')</label>
			<select class="form-control" name="training_id" required value="{{ old('training_id') }}" required >
				<option value="" disabled selected ></option>
				@foreach($trainings as $training)
					<option value="{{ $training->id}}" >{{ $training->name }}</option>
				@endforeach
			</select>
			{!! ($errors->has('training_id') ? $errors->first('training_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('date')) ? 'has-error' : '' }}">
			<label>@lang('basic.date')</label>
			<input class="form-control" name="date" type="date" value="{{ old('date') }}" required />
			{!! ($errors->has('date') ? $errors->first('date', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('expiry_date')) ? 'has-error' : '' }}">
			<label>@lang('basic.expiry_date')</label>
			<input class="form-control" name="expiry_date" type="date" value="{{ old('expiry_date') }}" required />
			{!! ($errors->has('expiry_date') ? $errors->first('expiry_date', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
			<label for="">@lang('basic.description')</label>
			<textarea class="form-control" placeholder="{{ __('basic.description')}}" name="description" type="text" rows="3" maxlength="191" required >{{ old('description') }}</textarea>
			{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		{{ csrf_field() }}
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
$.getScript( '/../js/validate.js');
$.getScript( '/../js/filter.js');
</script>