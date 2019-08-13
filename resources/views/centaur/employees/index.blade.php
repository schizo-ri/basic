@extends('Centaur::layout')

@section('title', __('basic.employees'))

@section('content')
<div class="row">
	<div class="page-header">
		<div class='btn-toolbar pull-right'>
			@if(Sentinel::getUser()->hasAccess(['employees.create']) || in_array('employees.create', $permission_dep))
				<a class="btn btn-primary btn-lg" href="{{ route('employees.create') }}">
					<i class="fas fa-plus"></i>
					@lang('basic.add_employee')
				</a>
			@endif
		</div>
		<h1>@lang('basic.employees')</h1>
	</div>

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($employees))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.fl_name')</th>
							<th>@lang('basic.b_day')</th>
							<th>@lang('basic.work')</th>
							<th>@lang('basic.reg_date')</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($employees as $employee)
							<tr>
								<td>{{ $employee->user['first_name'] . ' ' . $employee->user['last_name'] }}</td>
								<td>{{ date("d.m.Y",strtotime($employee->b_day)) }}</td>
								<td>{{ $employee->work['name'] }}</td>
								<td>{{ date("d.m.Y",strtotime($employee->reg_date)) }}</td>
								<td class="center">
									@if(Sentinel::getUser()->hasAccess(['employees.update']) || in_array('employees.update', $permission_dep))
										<a href="{{ route('employees.edit', $employee->id) }}" class="">
											<i class="far fa-edit"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['employees.delete']) || in_array('employees.delete', $permission_dep))
										<a href="{{ route('employees.destroy', $employee->id) }}" class="action_confirm danger" data-method="delete" data-token="{{ csrf_token() }}">
											<i class="far fa-trash-alt"></i>
										</a>
									@endif
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@else
				@lang('basic.no_data')
			@endif
		</div>
	</div>
</div>
@stop