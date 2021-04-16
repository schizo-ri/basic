@extends('Centaur::admin')

@section('title', __('basic.employee_trainings'))

@section('content')
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['employee_trainings.create']) || in_array('employee_trainings.view', $permission_dep))
				<a class="btn-new" href="{{ route('employee_trainings.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($employee_trainings))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.employee')</th>
							<th>@lang('basic.training')</th>
							<th>@lang('basic.date')</th>
							<th>@lang('basic.expiry_date')</th>
							<th>@lang('basic.description')</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($employee_trainings as $employee_training)
							<tr>
								<td>{{ $employee_training->employee->user->last_name . ' ' . $employee_training->employee->user->first_name}}</td>
								<td>{{ $employee_training->training->name }}</td>
								<td>{{ date('d.m.Y', strtotime($employee_training->date)) }}</td>
								<td>{{ date('d.m.Y', strtotime($employee_training->expiry_date))  }}</td>
								<td>{{ $employee_training->description }}</td>
								<td class="center">
									<!-- <button class="collapsible option_dots float_r"></button> -->
									@if(Sentinel::getUser()->hasAccess(['employee_trainings.update']) || in_array('employee_trainings.update', $permission_dep))
										<a href="{{ route('employee_trainings.edit', $employee_training->id) }}" class="btn-edit" rel="modal:open">
											<i class="far fa-edit"></i>
										</a>
									@endif
									@if( Sentinel::getUser()->hasAccess(['employee_trainings.delete']) || in_array('employee_trainings.delete', $permission_dep))
										<a href="{{ route('employee_trainings.destroy', $employee_training->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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
	<script>
		/* $(function(){
			$.getScript( '/../js/filter_table.js');
		$('.collapsible').click(function(event){        
				$(this).siblings().toggle();
			});
		});
		$.getScript( '/../restfulizer.js'); */
	</script>
@stop