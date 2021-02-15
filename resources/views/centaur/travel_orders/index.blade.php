@extends('Centaur::admin')

@section('title', __('basic.travel_orders'))

@section('content')
	<header class="page-header travel_header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['travel_orders.create']) || in_array('travel_orders.create', $permission_dep))
				<a href="{{ route('travel_orders.create') }}" class="btn-new " title="{{ __('basic.add_travel')}}" rel="modal:open" >
					<i class="fas fa-plus"></i>
				</a>
			@endif
			<select id="filter_employee" class="select_filter filter_travel" >
				<option value="all">@lang('basic.all_employees')</option>
				@foreach ($employees as $employee)
					@if(count($employee->hasTravels) >0)
						<option value="{{ $employee->id }}" class="id_{{ $employee->id }}">{{ $employee->first_name . ' ' .  $employee->last_name }}</option>
					@endif
				@endforeach
			</select>
			<select id="filter_date" class="select_filter filter_travel" >
				<option value="all">@lang('basic.all_month')</option>
				@foreach ($dates as $key => $date)
					<option value="{{ $date }}" class="date_{{ $date }}" {!! $key == 0 ? 'selected' : '' !!}>{{ $date }}</option>
				@endforeach
			</select>
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($travel_orders))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th class="sort_date">@lang('basic.date')</th>
							<th>@lang('basic.employee')</th>
							
							<th class="sort_date">@lang('absence.start_date')</th>
							<th class="sort_date">@lang('absence.end_date')</th>
							<th>@lang('basic.destination')</th>
							<th>@lang('basic.car')</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($travel_orders as $travel)
							<tr class="panel" id="travel_{{ $travel->id }}">
								<td>{{ date('d.m.Y.',strtotime($travel->date)) }}</td>
								<td>{{ $travel->employee->user['first_name'] . ' ' .  $travel->employee->user['last_name'] }}</td>
								<td>{{ date('d.m.Y.',strtotime($travel->start_date)) }}</td>
								<td>{!! $travel->end_date ? date('d.m.Y.',strtotime($travel->end_date)) : '' !!}</td>
								<td>{{ $travel->destination }}</td>
								<td>{{ $travel->car['registration'] }}</td>
								<td class="center">
									@if(Sentinel::getUser()->hasAccess(['travel_orders.update']))
										@if ($travel->status == 0 || $travel->status == null)
											<a href="{{ route('travelShow', $travel->id) }}" class="btn-edit" title="{{ __('basic.show')}}" target="_blank" >
												<i class="far fa-eye"></i>
											</a>
											
											{{-- <a href="{{ route('travel_orders.edit', $travel->id) }}" class="btn-edit" title="{{ __('basic.edit')}}" rel="modal:open">
													<i class="far fa-edit"></i>
												</a> --}}
											<a href="{{ action('TravelOrderController@close_travel', ['id' => $travel->id]) }}" class="btn-finish close_travel" title="{{ __('basic.finish')}}" >
												<i class="fas fa-check"></i>
											</a>
										@else
											<a href="{{ action('TravelOrderController@close_travel',  ['id' => $travel->id]) }}" class="btn-edit close_travel" title="{{ __('basic.open_order')}}" >
												<i class="fas fa-times"></i>
											</a>
										@endif
									@endif								
									@if(Sentinel::getUser()->hasAccess(['travel_orders.delete']))
										@if ($travel->status == 0 || $travel->status == null)
											<a href="{{ route('travel_orders.destroy', $travel->id) }}" class="action_confirm btn-delete danger" title="{{ __('basic.delete')}}" data-method="delete" data-token="{{ csrf_token() }}">
												<i class="far fa-trash-alt"></i>
											</a>
										@endif
									@endif
									
									@if ($travel->status == 1)
										<a href="{{ asset('/travelOrder/Putni nalog_' . $travel->id . '.pdf') }}" target="_blank"><span class="pdf"></span></a>
									
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
		<span hidden class="locale" >{{ App::getLocale() }}</span>
	</main>
	<script>
		$(function(){
			/* $.getScript( '/../js/filter_dropdown.js');
			$.getScript( '/../js/filter_table.js');
			$.getScript( '/../js/travel.js'); */
			/* $.getScript( '/../restfulizer.js'); */
		});
	</script>
@stop