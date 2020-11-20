<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_kid')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('kids.store') }}" >
		<fieldset>
			<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
				<label>@lang('basic.parent')</label>
				<select class="form-control" name="employee_id" value="{{ old('employee_id') }}" required >
					<option value="" selected disabled></option>
					@foreach ($employees as $employee)
						@if ($employee->user)
							<option name="employee_id" value="{{ $employee->id }}">{{ $employee->user->first_name . ' ' . $employee->user->last_name }}</option>
						@endif
					@endforeach	
				</select>
				{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }}">
				<label for="">@lang('basic.f_name')</label>
				<input class="form-control" name="first_name" type="text" maxlength="20" value="{{ old('first_name') }}" required />
				{!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
				<label for="">@lang('basic.l_name')</label>
				<input class="form-control" name="last_name" type="text" maxlength="20" value="{{ old('last_name') }}" required />
				{!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('b_day')) ? 'has-error' : '' }}">
				<label>@lang('basic.b_day')</label>
				<input class="form-control" name="b_day" type="date" value="{{ old('b_day') }}"  />
				{!! ($errors->has('b_day') ? $errors->first('b_day', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			{{ csrf_field() }}
			<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
		</fieldset>
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>