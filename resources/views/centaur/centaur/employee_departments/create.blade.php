<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_employee_department') - {{ $department->name }}</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('employee_departments.store') }}" >
		<input type="hidden" name="department_id" value="{{ $department->id }}">
		<div class="form-group">
			<label>@lang('basic.employees')</label>
			@foreach($employees as $employee)
				<span class="col-sm-6 col-md-4 col-lg-4 float_l checkbox_span">
					<input type="checkbox" name="employee_id[]" value="{{ $employee->id }}" id="employee_id{{ $employee->id }}"  {!! $employee->hasEmployeeDepartmen->where('department_id', $department->id)->first() ? 'checked' : '' !!}>
					<label for="employee_id{{ $employee->id }}" >{{ $employee->user->first_name . ' ' . $employee->user->last_name }}</label>
				</span>
			@endforeach
		</div>
		{{ csrf_field() }}
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
	</form>
</div>