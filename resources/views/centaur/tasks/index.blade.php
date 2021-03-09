@extends('Centaur::layout')

@section('title', __('basic.tasks'))

@section('content')
<div class="index_page index_documents">
	<main class="col-md-12 index_main main_documents float_right">
		<section>
			<div class="page-header header_document">
				<a class="link_back" href="{{ url()->previous() }}" ><span class="curve_arrow_left"></span></a>
				@lang('basic.tasks')
			</div>
			<main class="all_documents">
				<div class="table-responsive">
					<header class="page-header">
						<div class="index_table_filter">
							<label>
								<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
							</label>
							@if(Sentinel::getUser()->hasAccess(["tasks.create"]) || in_array("tasks.create", $permission_dep) )
								<a class="add_new" href="{{ route('tasks.create') }}" class="" rel="modal:open"><i style="font-size:11px" class="fa">&#xf067;</i></a>
							@endif
						</div>
					</header>
					@if(count($tasks) > 0)
						<table id="index_table" class="display table table-hover sort_3_desc">
							<thead>
								<tr>
									<th>@lang('basic.task') | @lang('basic.description')</th>
									<th>@lang('basic.to_employee')</th>
									<th class="sort_date">@lang('absence.start_date')</th>
									<th class="sort_date">@lang('absence.end_date')</th>
									<th>@lang('basic.interval')</th>
									<th>Status</th>
									<th class="not-export-column">@lang('basic.options')</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($tasks as $task)
									<tr class="tr_open_link_new_page"  data-href="/employee_tasks/{{ $task->id }}" >
										<td>{{ $task->task }} <br> <small>{{ $task->description }}</small></td>
										<td>
											@php
												$to_employees = explode(',', $task->to_employee_id);
											@endphp
											@foreach($to_employees as $employee_id)
												{!! $employees->where('id', $employee_id)->first() ? $employees->where('id', $employee_id)->first()->first_name . ' ' . $employees->where('id', $employee_id)->first()->last_name : '' !!} <br>
											@endforeach
										</td>
										<td>{{ date('d.m.Y', strtotime($task->start_date )) }}</td>
										<td>{!! $task->end_date ? date('d.m.Y', strtotime($task->end_date )) : '' !!}</td>
										<td>
											@switch($task->interval_period)
												@case('no_repeat')
													Bez ponavljanja
													@if( count($task->employeeTasks) > 0 && $task->employeeTasks->first()->status == 1 ) <span class="green padd_l_15"> Izvršen</span> @endif
													@break
												@case('every_day')
													Dnevno
													@break
												@case('once_week')
													Tjedno
													@break
												@case('once_month')
													Mjesečno
													@break
												@case('once_year')
													Godišnje
													@break
												@default
											@endswitch
										</td>
										<td>{!! $task->active == 1 ? 'aktivan':  'neaktivan' !!}</td>
										<td class="center not_link">
											<!-- <button class="collapsible option_dots float_r"></button> -->
											@if(Sentinel::getUser()->hasAccess(['tasks.update']))
												<a href="{{ route('tasks.edit', $task->id) }}" class="btn-edit" title="{{ __('basic.edit')}}" rel="modal:open">
													<i class="far fa-edit"></i>
												</a>
											@endif								
											@if(Sentinel::getUser()->hasAccess(['tasks.delete']))
												<a href="{{ route('tasks.destroy', $task->id) }}" class="action_confirm btn-delete danger" title="{{ __('basic.delete')}}" data-method="delete" data-token="{{ csrf_token() }}">
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
		</section>
	</main>
	<div id="login-modal" class="modal">
		
	</div>
</div>
@stop