@extends('Centaur::admin')

@section('title', __('basic.task') . ' ' . $task->task )

@section('content')
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			<!-- @if(Sentinel::getUser()->hasAccess(['tasks.create']) || in_array('tasks.create', $permission_dep))
				<a class="btn-new" href="{{ route('tasks.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif -->
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($employeeTasks) > 0)
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.task') | @lang('basic.description')</th>
							<th>@lang('basic.employees_in_charge')</th>
							<th>@lang('basic.date')</th>
							<th>@lang('basic.comment')</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($employeeTasks as $employeeTask)
							<tr >
								<td>{{ $employeeTask->task->task }} <br> <small>{{ $task->description }}</small></td>
								<td>{{ $employeeTask->employee->user->first_name . ' ' . $employeeTask->employee->user->last_name}}</td>
								<td>{{ date('d.m.Y', strtotime($employeeTask->created_at )) }}</td>
								<td>{{ $employeeTask->comment }}</td>
								<td>{{ $employeeTask->status }}</td>
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
		$(function(){
			$.getScript( '/../js/filter_table.js');

		}); 
	</script>
@stop