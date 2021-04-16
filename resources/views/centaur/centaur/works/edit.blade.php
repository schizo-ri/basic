
<div class="modal-header">
	<h3 class="panel-title">@lang('basic.edit_work')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('works.update', $work->id) }}">
		<fieldset>
			<div class="form-group {{ ($errors->has('department_id')) ? 'has-error' : '' }}" >
				<label>@lang('basic.department')</label>
				<select class="form-control" name="department_id" required value="{{ $work->department_id }}">
					<option value="" disabled selected ></option>
					@foreach($departments as $department)
						<option value="{{ $department->id}}" {!! $work->department_id ==  $department->id ? 'selected' : '' !!} >{{ $department->name }}</option>
					@endforeach
				</select>
				{!! ($errors->has('department_id') ? $errors->first('department_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
				<label>@lang('basic.name')</label>
				<input class="form-control" placeholder="{{ __('basic.name')}}" name="name" maxlength="255" type="text" value="{{ $work->name }}" required />
				{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('job_description')) ? 'has-error' : '' }}">
				<label>@lang('basic.job_description')</label>
				<textarea name="job_description" type="text" class="form-control" rows="5" maxlength="255" >{{ $work->job_description }}</textarea>
				{!! ($errors->has('job_description') ? $errors->first('job_description', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
				<label>@lang('basic.director')</label>
				<select class="form-control" name="employee_id">
					<option value="" disabled selected ></option>
					@foreach($employees as $employee)
						<option value="{{ $employee->id}}" {!! $work->employee_id == $employee->id ? 'selected' : '' !!} >{{ $employee->first_name . ' ' .  $employee->last_name }}</option>
					@endforeach
				</select>
				{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('first_superior')) ? 'has-error' : '' }}">
				<label>@lang('basic.department_first')</label>
				<select class="form-control" name="first_superior">
					<option value="" disabled selected ></option>
					@foreach($employees as $employee)
						<option value="{{ $employee->id}}" {!! $work->first_superior == $employee->id ? 'selected' : '' !!}>{{ $employee->first_name . ' ' .  $employee->last_name }}</option>
					@endforeach
				</select>
				{!! ($errors->has('first_superior') ? $errors->first('first_superior', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<input class="btn-submit" type="submit" value="{{ __('basic.edit')}}">
		</fieldset>
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
	$.getScript( '/../js/validate.js');
</script>