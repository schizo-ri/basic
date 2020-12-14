@extends('Centaur::layout')

@section('title', __('basic.tasks'))

@section('content')
<div class="index_page index_documents">
	<main class="col-md-12 index_main main_documents float_right">
		<section>
			<div class="page-header header_document">
				<a class="link_back" href="{{ url()->previous() }}" ><span class="curve_arrow_left"></span></a>
				@if(Sentinel::getUser()->hasAccess(['tasks.create'])  )
					@lang('basic.task') {!! count($employeeTasks) > 0 ? ' - ' . $employeeTasks->first()->task->task : '' !!} 
				@else
					@lang('basic.tasks')
				@endif
			</div>
			<main class="all_documents">
				<div class="table-responsive">
					<header class="page-header">
						<div class="index_table_filter">
							<label>
								<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
							</label>
							{{-- @if(Sentinel::getUser()->hasAccess(["tasks.create"]) || in_array("tasks.create", $permission_dep) )
								<a class="add_new" href="{{ route('tasks.create') }}" class="" rel="modal:open"><i style="font-size:11px" class="fa">&#xf067;</i></a>
							@endif --}}
						</div>
					</header>
					@if(count($employeeTasks) > 0)
						<table id="index_table" class="display table table-hover sort_3_desc">
							<thead>
								<tr>
									<th>@lang('basic.task') | @lang('basic.description')</th>
									<th>@lang('basic.employees_in_charge')</th>
									<th class="sort_date">@lang('basic.date')</th>
									<th>@lang('basic.comment')</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($employeeTasks->sortBy('created_at') as $employeeTask)
									<tr >
										<td>{{ $employeeTask->task->task }}<br> <small>{{  $employeeTask->task->description }}</small></td>
										<td>{{ $employeeTask->employee->user->first_name . ' ' . $employeeTask->employee->user->last_name}}</td>
										<td>{{ date('d.m.Y', strtotime($employeeTask->created_at )) }}</td>
										<td>{{ $employeeTask->comment }}</td>
										<td>{!! $employeeTask->status == 1 ? '<span class="green padd_l_15">Izvršen</span>' : '' !!}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					@else
						<p class="no_data">@lang('basic.no_data')</p>
					@endif
				</div>
			</main>
		</section>
	</main>
	<div id="login-modal" class="modal">
		
	</div>
</div>
@stop