@extends('Centaur::admin')

@section('title', __('basic.cars'))

@section('content')
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['cars.create']) || in_array('cars.create', $permission_dep))
				<a class="btn-new" href="{{ route('cars.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($cars))
				<table id="index_table" class="display table table-hover ">
					<thead>
						<tr>
							<th>@lang('basic.manufacturer')</th>
							<th>Model</th>
							<th>@lang('basic.license_plate')</th>
							<th>@lang('basic.chassis')</th>
							<th class="sort_date">@lang('basic.first_registration')</th>
							<th class="sort_date">@lang('basic.last_registration')</th>
							<th>@lang('basic.current_km')</th>
							<th>@lang('basic.department')</th> 
							<th>@lang('basic.employee')</th> 
							<th>@lang('basic.private_car')</th> 
							<th class="not-export-column">@lang('basic.options')</th>
					</thead>
					<tbody>
						@foreach ($cars as $car)
							<tr class="tr_open_link"  data-href="/cars/{{ $car->id }}" data-modal >
								<td>{{ $car->manufacturer }}</td>
								<td>{{ $car->model }} {!! $car->car_index ? ' ('. $car->car_index . ')' : '' !!}</td>
								<td>{{ $car->registration }}</td>
								<td>{{ $car->chassis }}</td>
								<td>{{ date('d.m.Y.', strtotime($car->first_registration )) }}</td>
								<td>{{  date('d.m.Y.', strtotime($car->last_registration ))  }}</td>
								<td>{{ $car->current_km }}</td>
								<td>{{ $car->department['name'] }}</td>
								<td>{!! $car->employee ? $car->employee->user['first_name'] . ' ' . $car->employee->user['last_name'] : '' !!}</td>					
								<td>{!! $car->private_car == 1 ? 'privatno' : '' !!}</td>					
								<td class="center">
									@if( ! count($car->locco) > 0 && (Sentinel::getUser()->hasAccess(['cars.delete']) || in_array('cars.delete', $permission_dep)))
										<a href="{{ route('cars.destroy', $car->id) }}"  class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
											<i class="far fa-trash-alt"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['cars.update']) || in_array('cars.update', $permission_dep))
										<a href="{{ route('cars.edit', $car->id) }}" class="btn-edit" title="{{ __('basic.edit')}}" rel="modal:open">
											<i class="far fa-edit"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['fuels.create']) || in_array('fuels.create', $permission_dep))
										<a href="{{ route('fuels.create', [ 'car_id' => $car->id]) }}" class="btn-edit" title="{{ __('basic.add_fuel')}}" rel="modal:open">
											<i class="fas fa-gas-pump"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['vehical_services.create']) || in_array('vehical_services.create', $permission_dep))
										<a href="{{ route('vehical_services.create', [ 'car_id' => $car->id]) }}" class="btn-edit" title="{{ __('basic.add_service')}}" rel="modal:open">
											<i class="fas fa-tools"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['fuels.view']) || in_array('fuels.view', $permission_dep))
										<a href="{{ route('fuels.show', [ 'car_id' => $car->id]) }}" class="open_car_modal btn-edit" title="{{ __('basic.fuel')}}" rel="modal:open">
											<i class="fas fa-list"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['vehical_services.view']) || in_array('vehical_services.view', $permission_dep))
										<a href="{{ route('vehical_services.show', [ 'car_id' => $car->id]) }}" class="open_car_modal btn-edit" title="{{ __('basic.vehical_services')}}" rel="modal:open">
											<i class="fas fa-list"></i>
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
		<div id="login-modal" class="modal">
		
		</div>
	</main>
@stop