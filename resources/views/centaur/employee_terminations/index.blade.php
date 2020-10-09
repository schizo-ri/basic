@extends('Centaur::admin')

@section('title', __('basic.employee_terminations'))

@section('content')
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['employee_terminations.create']) || in_array('employee_terminations.create', $permission_dep))
				<a class="btn-new" href="{{ route('employee_terminations.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($employee_terminations)>0)
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.fl_name')</th>
							<th>@lang('basic.termination_type')</th>
							<th>@lang('basic.checkout_date')</th>
							<th>@lang('basic.notice_period')</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($employee_terminations as $employee_termination)
							<tr>
								<td>{!!  $employee_termination->employee->user ? $employee_termination->employee->user->first_name . ' ' .  $employee_termination->employee->user->last_name :  $employee_termination->employee->email!!}</td>
								<td>{{ $employee_termination->termination->name }}</td>
								<td>{{ date('d.m.Y',strtotime($employee_termination->check_out_date ) )}}</td>
								<td>{{ $employee_termination->notice_period }}</td>
								<td class="center">
									<!-- 	<button class="collapsible option_dots float_r"></button> -->
									@if(Sentinel::getUser()->hasAccess(['employee_terminations.update']) || in_array('employee_terminations.update', $permission_dep))
										<a href="{{ route('employee_terminations.edit', $employee_termination->id) }}" title="{{ __('basic.edit')}}" class="btn-edit" rel="modal:open">
												<i class="far fa-edit"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['employee_terminations.delete']) || in_array('employee_terminations.delete', $permission_dep) )
										<a href="{{ route('employee_terminations.destroy', $employee_termination->id) }}" title="{{ __('basic.delete')}}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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
	
@stop