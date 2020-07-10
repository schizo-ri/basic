<div class="modal-header">
	<h3 class="panel-title">@lang('basic.edit_car')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('cars.update', $car->id) }}" >
		<fieldset>
			<div class="form-group {{ ($errors->has('manufacturer')) ? 'has-error' : '' }}">
				<label for="">@lang('basic.manufacturer')</label>
				<input class="form-control" placeholder="{{ __('basic.manufacturer')}}" name="manufacturer" type="text" maxlength="50" value="{{ $car->manufacturer }}" required />
				{!! ($errors->has('manufacturer') ? $errors->first('manufacturer', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('model')) ? 'has-error' : '' }}">
				<label for="">@lang('basic.model')</label>
				<input class="form-control" placeholder="Model" name="model" type="text" maxlength="50" value="{{ $car->model }}" required />
				{!! ($errors->has('model') ? $errors->first('model', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('registration')) ? 'has-error' : '' }}">
			<label for="">@lang('basic.license_plate')</label>
				<input class="form-control" placeholder="{{ __('basic.license_plate')}}" name="registration" type="text" maxlength="20" value="{{ $car->registration }}" required />
				{!! ($errors->has('registration') ? $errors->first('registration', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('chassis')) ? 'has-error' : '' }}">
				<label for="">@lang('basic.chassis')</label>
				<input class="form-control" placeholder="{{ __('basic.chassis')}}" name="chassis" type="text" maxlength="30" value="{{ $car->chassis }}" required />
				{!! ($errors->has('chassis') ? $errors->first('chassis', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('enc')) ? 'has-error' : '' }}">
				<label for="">ENC</label>
				<input class="form-control" placeholder="ENC" name="enc" type="text" maxlength="50" value="{{  $car->enc }}" />
				{!! ($errors->has('enc') ? $errors->first('enc', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('first_registration')) ? 'has-error' : '' }}">
				<label for="">@lang('basic.first_registration')</label>
				<input class="form-control" name="first_registration" type="date" value="{{ $car->first_registration }}" required />
				{!! ($errors->has('first_registration') ? $errors->first('first_registration', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('last_registration')) ? 'has-error' : '' }}">
				<label for="">@lang('basic.last_registration')</label>
				<input class="form-control" name="last_registration" type="date" value="{{ $car->last_registration }}" required />
				{!! ($errors->has('last_registration') ? $errors->first('last_registration', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('last_service')) ? 'has-error' : '' }}">
				<label for="">@lang('basic.last_service')</label>
				<input class="form-control" name="last_service" type="date" value="{{ $car->last_service }}" required />
				{!! ($errors->has('last_service') ? $errors->first('last_service', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('current_km')) ? 'has-error' : '' }}">
				<label for="">@lang('basic.current_km')</label>
				<input class="form-control" placeholder="{{ __('basic.current_km')}}" name="current_km" type="number" maxlength="20" value="{{ $car->current_km }}" maxlength="20" required />
				{!! ($errors->has('current_km') ? $errors->first('current_km', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('department_id')) ? 'has-error' : '' }}">
				<label for="">@lang('basic.department')</label>
				<select  class="form-control" name="department_id" >
					<option value="" selected disabled></option>
					@foreach ($departments as $department)
						<option value="{{ $department->id }}" {!! $car->department_id == $department->id ? 'selected' : '' !!} >{{ $department->name }}</option>
					@endforeach
				</select>
				{!! ($errors->has('department_id') ? $errors->first('department_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
				<label for="">@lang('basic.employee')</label>
				<select  class="form-control" name="employee_id" >
					<option value="" selected disabled></option>
					@foreach ($employees as $employee)
						<option value="{{ $employee->id }}" {!! $car->employee_id == $employee->id ? 'selected' : '' !!}>{{ $employee->user['first_name'] . ' ' .  $employee->user['last_name'] }}</option>
					@endforeach
				</select>
				{!! ($errors->has('department_id') ? $errors->first('department_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="servis form-group">
				<label for="travel">@lang('basic.private_car')</label>
				<input class="" type="checkbox" name="private" value="1" id="private"  {!! $car->private == 1 ? 'checked' : '' !!} />
			</div>
            {{ csrf_field() }}
            @method('PUT')
			<input class="btn-submit" type="submit" value="{{ __('basic.edit')}}">
		</fieldset>
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
	$.getScript( '/../js/validate.js');	
</script>