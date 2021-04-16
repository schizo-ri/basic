@extends('Centaur::admin')

@section('title', __('basic.vehical_services'))

@section('content')
	<header class="page-header fuel_header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['vehical_services.create']) || in_array('vehical_services.create', $permission_dep))
				<a class="btn-new" href="{{ route('vehical_services.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
			<div class="div_select2">
				<select id="filter_car" class="select_filter filter_fuels" >
					<option value="all">@lang('basic.all_cars')</option>
					@foreach ($cars as $car)
						<option value="{{ $car->id }}">{{ $car->registration }}</option>
					@endforeach
				</select>
			</div>
			<div class="div_select2">
				<select id="filter_month" class="select_filter filter_fuels" >
					<option value="all">@lang('basic.all_years')</option>
					@foreach ($dates as $date)
						<option value="{{ $date }}"  {!! $date == date('Y') ? 'selected' : '' !!}>{{ $date }}</option>
					@endforeach
				</select>
			</div>
			<span class="show_button_upload" title="Upload"><i class="fas fa-upload"></i></span>
			<form class="upload_file" action="{{ action('VehicalServiceController@importService') }}" method="POST" enctype="multipart/form-data">
				<button class="btn-new">Upload</button>
				<input type="file" name="file" required />
				@csrf
			</form>
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if (count($vehicalServices) > 0)
				<table id="table_id" class="display table table-hover sort_0_desc">
					<thead>
						<tr>
							<th class="sort_date">@lang('basic.date')</th>
							<th>@lang('basic.car')</th>
							<th>@lang('basic.amount')</th>
							<th>@lang('basic.current_km')</th>
							<th>@lang('basic.employee')</th>
							<th>@lang('basic.comment')</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody class="">
						@foreach ($vehicalServices as $service)
							<tr>
								<td>{{ date('d.m.Y', strtotime($service->date)) }}</td>
								<td>{{ $service->car->registration }}</td>
								<td>{{ number_format($service->price, 2, ',', '.') }}</td>
								<td>{{ $service->km }}</td>
								<td>{{ $service->employee->user['last_name'] }}</td>
								<td>{{ $service->comment }}</td>
								<td>
									@if(Sentinel::getUser()->hasAccess(['vehical_services.view']) || in_array('vehical_services.view', $permission_dep))
										<a href="{{ route('vehical_services.edit',$service->id ) }}" class="edit_service btn-edit" title="{{ __('basic.vehical_services')}}" rel="modal:open">
											<i class="far fa-edit"></i>
										</a>
									@endif
									@if( Sentinel::getUser()->hasAccess(['vehical_services.delete']) || in_array('vehical_services.delete', $permission_dep))
										<a href="{{ route('vehical_services.destroy', $service->id) }}" class="action_confirm btn-delete danger edit_service " data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
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