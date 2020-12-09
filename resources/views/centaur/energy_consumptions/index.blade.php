@extends('Centaur::layout')

@section('title', 'Potrošnja energenata')

@section('content')
@php
	use App\Models\EnergyConsumption;
@endphp
<div class="index_page index_documents">
	<main class="col-md-12 index_main main_documents float_right">
		<section>
			<div class="header_document">
				<a class="link_back" href="{{ url()->previous() }}" ><span class="curve_arrow_left"></span></a>
				Potrošnja energenata
				@if (Sentinel::inRole('administrator')	)
					<a href="{{ route('energy_locations.index') }}" class="view_all" title="Lokacije" >Lokacije</a>
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
							@if(Sentinel::getUser()->hasAccess(["energy_consumptions.create"]) || in_array("energy_consumptions.create", $permission_dep) )
								<a class="add_new" href="{{ route('energy_consumptions.create') }}" class="" rel="modal:open"><i style="font-size:11px" class="fa">&#xf067;</i></a>
							@endif
						</div>
					</header>
					@if(count($energyConsumptions) > 0)
						<table id="index_table" class="display table table-hover">
							<thead>
								<tr>
									<th>Lokacija</th>
									<th>Energent</th>
									<th>Datum očitanja</th>
									<th>Stanje brojila</th>
									<th>Potrošnja </th>
									<th>Komentar</th>
									<th>@lang('basic.options')</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($energyConsumptions as $energyConsumption)
									@php
										$prevConsumption = new EnergyConsumption();
										$prevConsumption = $prevConsumption->prevConsumption($energyConsumption->id);
									
									@endphp
									<tr>
										<td>{{ $energyConsumption->location->name }}</td>
										<td>{{ $energyConsumption->source->name }}</td>
										<td>{{ date('d.m.Y', strtotime( $energyConsumption->date) ) }}</td>
										<td>{{ $energyConsumption->counter }}</td>
										<td>{!! $prevConsumption ? $energyConsumption->counter - $prevConsumption->counter : '' !!}</td>
										<td>{{ $energyConsumption->comment }}</td>
										<td class="center">
											@if(Sentinel::getUser()->hasAccess(['energy_consumptions.update']) || in_array('energy_consumptions.update', $permission_dep))
												<a href="{{ route('energy_consumptions.edit', $energyConsumption->id) }}" class="btn-edit"  title="{{ __('basic.edit')}}" rel="modal:open">
														<i class="far fa-edit"></i>
												</a>
											@endif
											@if(Sentinel::getUser()->hasAccess(['energy_consumptions.delete']) || in_array('energy_consumptions.delete', $permission_dep))
												<a href="{{ route('energy_consumptions.destroy', $energyConsumption->id) }}" class="action_confirm btn-delete danger" data-method="delete" title="{{ __('basic.delete')}}" data-token="{{ csrf_token() }}">
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