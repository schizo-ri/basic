@extends('Centaur::admin')

@section('title', __('basic.work_records'))

@section('content')
@php
		use App\Models\WorkRecord;
		if(isset($_GET['date'])) {
			$request_date = $_GET['date'];
		} else {
			$request_date = date('Y-m-d');
		}
	@endphp
	<header class="page-header work_record_header first_view_header">
		<div class="index_table_filter">
			<label>
				<input type="search" placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['work_records.create']) || in_array('work_records.create', $permission_dep))
				<a class="btn-new" href="{{ route('work_records.create') }}"  rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
			<a class="change_view" href="{{ route('work_records_table') }}"></a>
			<select class="change_month select_filter ">
				@foreach ($months as $month)
					<option value="{{ $month }}">{{ date('Y m',strtotime($month))}}</option>
				@endforeach
			</select>
			<select class="change_employee_work select_filter ">
				<option value="" selected>{{ __('basic.view_all')}} </option>
				@foreach ($employees as $employee)
					<option value="empl_{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->last_name }}</option>
				@endforeach
			</select>
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive first_view">
			@if(count($work_records))
				<table id="index_table" class="display table table-hover ">
					<thead>
						<tr>
							<th>@lang('basic.employee')</th>
							<th class="sort_date">@lang('absence.start_time')</th>
							<th class="sort_date">@lang('absence.end_time')</th>
							<th>@lang('basic.travel_orders')</th>
							<th>@lang('absence.time')</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($work_records as $record)
							@php
								$trav = $record->employee->hasTravels->whereBetween('start_date', [ date('Y-m-d',strtotime($record->start)) . ' 00:00:00', date('Y-m-d',strtotime($record->start))  . ' 23:59:59' ] );
								$locco_day = $record->employee->hasLocco->whereBetween('date', [ date('Y-m-d',strtotime($record->start))  . ' 00:00:00', date('Y-m-d',strtotime($record->start)) . ' 23:59:59' ] );
							@endphp
							<tr class="empl_{{ $record->employee_id }}">
								<td>
									<a href="{{ route('work_records.show', ['id'=>$record->employee->id, 'date' => $request_date ]) }}">
										{{ $record->employee->user['first_name'] . ' ' . $record->employee->user['last_name'] }}
									</a>
								</td>
								<td>{{ date('d.m.Y. H:i',strtotime($record->start)) }}</td>
								<td>{!! $record->end ? date('d.m.Y. H:i',strtotime($record->end)) : '' !!}</td>
								<td>
									@if( $trav )
										@foreach ($trav as $put)
										{{'PN - ' . $put->car->car_index }} <br>
										@endforeach
									@endif
									@if($locco_day)
										@foreach ($locco_day as $locco)
											{{'L - ' . $locco->car->car_index }}<br>
										@endforeach
									@endif
								</td>
								<td>{{ $record->interval  }}</td>
								<td class="center">
									@if(Sentinel::getUser()->hasAccess(['work_records.update']) || in_array('work_records.update', $permission_dep))
										<a href="{{ route('work_records.edit', $record->id) }}" class="btn-edit" rel="modal:open" >
												<i class="far fa-edit"></i>
										</a>
									@endif
									@if(  Sentinel::getUser()->hasAccess(['work_records.delete']) || in_array('work_records.delete', $permission_dep))
										<a href="{{ route('work_records.destroy', $record->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}" >
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