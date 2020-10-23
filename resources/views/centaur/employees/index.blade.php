@extends('Centaur::admin')

@section('title', __('basic.employees'))
	@section('content')
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['employees.create']) || in_array('employees.create', $permission_dep))
				<a class="btn-new" href="{{ route('employees.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
		{{-- 	<span class="change_view"></span> --}}
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($employees))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.fl_name')</th>
							<th class="sort_date">@lang('basic.b_day')</th>
							<th>@lang('basic.work')</th>
							<th>@lang('basic.department')</th>
							<th class="sort_date">@lang('basic.reg_date')</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($employees as $employee)
							<tr class="tr_open_link "  data-href="/employees/{{ $employee->id }}" data-modal >
								<td>{{ $employee->user['first_name'] . ' ' . $employee->user['last_name'] }}
									<span class="employee_color" {!! $employee->color ? 'style="background-color:' . $employee->color . '"' : '' !!}>
									</span>
								</td>
								<td>{!! $employee->b_day ? date("d.m.Y",strtotime($employee->b_day)) : '' !!}</td>
								<td>{{ $employee->work['name'] }}</td>
								<td>
									@if($employee->hasEmployeeDepartmen && count($employee->hasEmployeeDepartmen)>0)
										@foreach ( $employee->hasEmployeeDepartmen as $empl_department )
											{{ $empl_department->department->name }} <br>
										@endforeach
									@endif
								</td>
								<td>{!! $employee->reg_date ? date("d.m.Y",strtotime($employee->reg_date)) : '' !!}</td>
								<td class="center">
									<!-- <button class="collapsible option_dots float_r"></button> -->
									@if(Sentinel::getUser()->hasAccess(['employees.update']) || in_array('employees.update', $permission_dep))
										<a href="{{ route('employees.edit', $employee->id) }}" title="{{ __('basic.edit_employee') }}"  rel="modal:open">
											<i class="fas fa-user-cog"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['users.update']) &&  $employee->user_id)
										<a href="{{ route('users.edit', $employee->user_id) }}" class="" title="{{ __('basic.edit_user') }}" rel="modal:open">
											<i class="far fa-edit"></i>
										</a>
									@endif
									@if(Sentinel::inRole('superadmin'))
										<a href="{{ route('employees.destroy', $employee->id ) }}" style="display:none" class="action_confirm danger" data-method="delete" data-token="{{ csrf_token() }}">
											<i class="far fa-trash-alt"></i>
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
	<div id="login-modal" class="modal modal_user modal_employee">
		
	</div>
@stop