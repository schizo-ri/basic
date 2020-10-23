@extends('Centaur::admin')

@section('title', __('basic.employee_departments'))

@section('content')
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			{{-- @if(Sentinel::getUser()->hasAccess(['employee_departments.create']) || in_array('employee_departments.create', $permission_dep))
				<a class="btn-new" href="{{ route('employee_departments.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif--}}
		</div> 
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($departments))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.department')</th>
							<th>@lang('basic.employees')</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($departments->groupBy('name') as $department)
							<tr>
								<td>{{ $department->first()->name }}</td>
								<td>
									@foreach ($department as $employee_department)
										<span class="col-3 float_l">{{ $employee_department->last_name . ' ' . $employee_department->first_name }}</span>
									@endforeach
								</td>
								<td>
									@if(Sentinel::getUser()->hasAccess(['employee_departments.create']) || in_array('employee_departments.create', $permission_dep))
										<a  href="{{ route('employee_departments.create',['department_id' => $department->first()->department_id]) }}" rel="modal:open">
											<i class="fas fa-user-plus"></i>
										</a>
									@endif
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@else
				<p class="no_data">@lang('basic.no_data')</p>
			@endif
		</div>
	</main>
	
@stop