@extends('Centaur::admin')

@section('title', __('basic.fuel'))

@section('content')
	<header class="page-header fuel_header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>		
			@if(Sentinel::getUser()->hasAccess(['fuels.create']) || in_array('fuels.create', $permission_dep))
				<a class="btn-new" href="{{ route('fuels.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
			<select id="filter_car" class="select_filter filter_fuels" >
				<option value="all">@lang('basic.all_cars')</option>
				@foreach ($cars as $car)
					<option value="{{ $car->registration }}">{{ $car->registration }}</option>
				@endforeach
			</select>
			<select id="filter_month" class="select_filter filter_fuels" >
				<option value="all">@lang('basic.all_month')</option>
				@foreach ($dates as $date)
					<option value="{{ $date }}">{{ $date }}</option>
				@endforeach
			</select>
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if (count($fuels) > 0)
				<table id="index_table" class="display table table-hover sort_1_desc">
					<thead>
						<tr>
							<th class="sort_date" >@lang('basic.date')</th>
							<th>@lang('basic.car')</th>
							<th>@lang('basic.liters')</th>
							<th>@lang('basic.current_km')</th>
							<th>@lang('basic.average_consumption') [l/100km]</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody class="">
						@foreach ($fuels as $fuel)
							@php
								$fuel_prev = $fuels->where('car_id',$fuel->car_id)->where('date','<', $fuel->date)->first();
							@endphp
							<tr class="panel">
								<td class="sort_date">{{ date('d.m.Y.', strtotime($fuel->date)) }} </td>
								<td>{{ $fuel->car['registration'] }}</td>
								<td>{{ $fuel->liters }}</td>
								<td>{{ $fuel->km }}</td>
								<td>{!! $fuel_prev ? round($fuel->liters / ($fuel->km - $fuel_prev->km)  * 100, 2) : 0 !!}</td>
								<td>
									@if(Sentinel::getUser()->hasAccess(['fuels.view']) || in_array('fuels.view', $permission_dep))
										<a href="{{ route('fuels.edit',$fuel->id ) }}" class="edit_service btn-edit" title="{{ __('basic.fuel')}}" rel="modal:open">
											<i class="far fa-edit"></i>
										</a>
									@endif
									@if( Sentinel::getUser()->hasAccess(['fuels.delete']) || in_array('fuels.delete', $permission_dep))
										<a href="{{ route('fuels.destroy', $fuel->id) }}" class="action_confirm btn-delete danger edit_service " data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
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
		$(function(){
			/* $.getScript( '/../js/filter_table.js');
			$.getScript( '/../js/filter_dropdown.js');
			$.getScript( '/../js/open_modal.js'); 
			$.getScript( '/../restfulizer.js'); */
		});
	</script>
@stop