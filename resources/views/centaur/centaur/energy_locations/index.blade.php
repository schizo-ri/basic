@extends('Centaur::layout')

@section('title', 'Lokacije')

@section('content')
<div class="index_page index_documents">
	<main class="col-md-12 index_main main_documents float_right">
		<section>
			<div class="header_document">
				<a class="link_back" href="{{ url()->previous() }}" ><span class="curve_arrow_left"></span></a>
				Lokacije
				@if (Sentinel::inRole('administrator')	)
					<a href="{{ route('energy_consumptions.index') }}" class="view_all" title="Potrošnja" >Potrošnja</a>
					<a href="{{ route('energy_sources.index') }}" class="view_all" title="Energenti" >Energenti</a>
				@endif
			</div>
			<main class="all_documents">
				<div class="table-responsive">
					<header class="page-header">
						<div class="index_table_filter">
							<label>
								<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
							</label>
							@if(Sentinel::getUser()->hasAccess(["energy_locations.create"]) || in_array("energy_locations.create", $permission_dep) )
								<a class="add_new" href="{{ route('energy_locations.create') }}" class="" rel="modal:open"><i style="font-size:11px" class="fa">&#xf067;</i></a>
							@endif
						</div>
					</header>
					@if(count($energyLocations) > 0)
						<table id="index_table" class="display table table-hover">
							<thead>
								<tr>
									<th>Naziv</th>
									<th>Adresa</th>
									<th>Grad</th>
									<th>Telefon</th>
									<th>Komentar</th>
									<th>@lang('basic.options')</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($energyLocations as $energyLocation)
									<tr>
										<td>{{ $energyLocation->name }}</td>
										<td>{{ $energyLocation->address }}</td>
										<td>{{ $energyLocation->city }}</td>
										<td>{{ $energyLocation->phone }}</td>
										<td>{{ $energyLocation->comment }}</td>
										<td class="center">
											@if(Sentinel::getUser()->hasAccess(['energy_locations.update']) || in_array('energy_locations.update', $permission_dep))
												<a href="{{ route('energy_locations.edit', $energyLocation->id) }}" class="btn-edit"  title="{{ __('basic.edit')}}" rel="modal:open">
														<i class="far fa-edit"></i>
												</a>
											@endif
											@if(Sentinel::getUser()->hasAccess(['energy_locations.delete']) || in_array('energy_locations.delete', $permission_dep))
												<a href="{{ route('energy_locations.destroy', $energyLocation->id) }}" class="action_confirm btn-delete danger" data-method="delete" title="{{ __('basic.delete')}}" data-token="{{ csrf_token() }}">
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
</div>
@stop