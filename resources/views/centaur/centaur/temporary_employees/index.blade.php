@extends('Centaur::admin')

@section('title', __('basic.temporary_employees'))

@section('content')
	@php
		use App\Models\Absence;
	@endphp
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['temporary_employees.create']) || in_array('temporary_employees.create', $permission_dep))
				<a class="btn-new" href="{{ route('temporary_employees.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($temporary_employees) > 0)
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.fl_name')</th>
							<th class="sort_date">@lang('basic.reg_date')</th>
							<th>@lang('basic.checkout')</th>
							<th>@lang('basic.work')</th>
							<th>@lang('basic.superior')</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($temporary_employees as $temporary_employee)
							<tr class="tr_open_link " data-href="/temporary_employee/{{ $temporary_employee->id }}" data-modal >
								<td>{{ $temporary_employee->user['last_name'] . ' ' . $temporary_employee->user['first_name'] }}</td>
								<td>{{ date('d.m.Y',strtotime($temporary_employee->reg_date)) }}</td>
								<td>{!! $temporary_employee->checkout ? 'odjavljen' : '' !!}</td>
								<td>{!! $temporary_employee->work ? $temporary_employee->work->name : '' !!}</td>
								<td>{{  $temporary_employee->employee->user['first_name'] . ' ' . $temporary_employee->employee->user['last_name']}}</td>
								
								
								<td class="center">
									<!-- <button class="collapsible option_dots float_r"></button> -->
									@if(Sentinel::getUser()->hasAccess(['temporary_employees.update']) || in_array('temporary_employees.update', $permission_dep))
										<a href="{{ route('temporary_employees.edit', $temporary_employee->id) }}" class="btn-edit" rel="modal:open">
											<i class="far fa-edit"></i>
										</a>
									@endif
									@if( Sentinel::getUser()->hasAccess(['temporary_employees.delete']) || in_array('absence_types.delete', $permission_dep) )
										<a href="{{ route('temporary_employees.destroy', $temporary_employee->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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