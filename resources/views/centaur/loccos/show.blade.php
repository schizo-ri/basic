@extends('Centaur::admin')

@section('title', __('basic.loccos') . ' ' . count($loccos)>0 ? $loccos->first()->car->registration : '' )

@section('content')
	<header class="page-header">
		<div class="index_table_filter fuel_header">
		{{-- 	<span class="back_to_prev"><span class="curve_arrow_left_grey"></span></span> --}}
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['loccos.create']) || in_array('loccos.create', $permission_dep))
				<a class="btn-new" href="{{ route('loccos.create', ['car_id' => $car->id]) }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif 
			<div class="div_select2">
				<select id="filter_month" class="select_filter filter_loccos" >
					{{-- <option value="all">@lang('basic.all_month')</option> --}}
					@foreach ($dates as $date)
						<option value="{{ $date }}">{{ $date }}</option>
					@endforeach
				</select>
			</div>
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			<table id="index_table" class="display table table-hover sort_1_desc">
				<thead>
					<tr>
						<th class="sort_date" >@lang('basic.date')</th>
						<th>@lang('basic.car')</th>
						<th>@lang('basic.employee')</th>
						<th>@lang('basic.starting_point')</th>
						<th>@lang('basic.destination')</th>
						<th>@lang('basic.start_km')</th>
						<th>@lang('basic.end_km')</th>
						<th>@lang('basic.distance')</th>
						<th>@lang('basic.comment')</th>
						<th class="not-export-column">@lang('basic.options')</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($loccos as $locco)
						<tr >
							<td >{{ date('d.m.Y.', strtotime($locco->date)) }}</td>
							<td>{{ $locco->car['registration'] }}</td>
							<td>{!! $locco->employee ? $locco->employee->user['first_name'] . ' ' . $locco->employee->user['last_name'] : '' !!}</td>
							<td>{{ $locco->starting_point }}</td>
							<td>{{ $locco->destination }}</td>
							<td>{{ $locco->start_km }}</td>
							<td>{{ $locco->end_km }}</td>
							<td>{{ $locco->distance }}</td>
							<td>{{ date('H:i', strtotime($locco->date)) . '-'. date('H:i', strtotime($locco->end_date))  }} {{ $locco->comment }} </td>
							<td >
								<!-- <button class="collapsible option_dots float_r"></button> -->
								@if( ! $locco->travel && (Sentinel::getUser()->hasAccess(['loccos.delete']) || in_array('loccos.delete', $permission_dep))  )
									<a href="{{ route('loccos.destroy', $locco->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
										<i class="far fa-trash-alt"></i>
									</a>
								@endif
								@if(Sentinel::getUser()->hasAccess(['loccos.update']) || in_array('loccos.update', $permission_dep))
									<a href="{{ route('loccos.edit', $locco->id) }}" class="btn-edit" title="{{ __('basic.edit')}}" rel="modal:open">
										<i class="far fa-edit"></i>
									</a>
								@endif
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</main>

@stop