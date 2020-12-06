@extends('Centaur::admin')

@section('title', __('basic.tasks'))

@section('content')
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['tasks.create']) || in_array('tasks.create', $permission_dep))
				<a class="btn-new" href="{{ route('tasks.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($tasks))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.task') | @lang('basic.description')</th>
							<th>@lang('basic.to_employee')</th>
							<th>@lang('absence.start_date')</th>
							<th>@lang('absence.end_date')</th>
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
										$to_employees = explode(',',$task->to_employee_id);
									@endphp
									@foreach($to_employees as $employee_id)
										{{ $employees->where('id', $employee_id)->first()->first_name . ' ' . $employees->where('id', $employee_id)->first()->last_name }} <br>
									@endforeach
								</td>
								<td>{{ date('d.m.Y', strtotime($task->start_date )) }}</td>
								<td>{{ date('d.m.Y', strtotime($task->end_date )) }}</td>
								<td>
									@switch($task->interval_period)
										@case('no_repeat')
											Bez ponavljanja
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
								<td class="center">
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
	<div id="login-modal" class="modal">
		
	</div>
	<script>
		$(function(){
			$.getScript( '/../js/filter_table.js');

		/* $('.collapsible').click(function(event){        
				$(this).siblings().toggle();
			});*/
		}); 
	</script>
@stop