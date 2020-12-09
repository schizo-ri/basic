@extends('Centaur::layout')

@section('title', 'Energenti')

@section('content')
<div class="index_page index_documents">
	<main class="col-md-12 index_main main_documents float_right">
		<section>
			<div class="header_document">
				<a class="link_back" href="{{ url()->previous() }}" ><span class="curve_arrow_left"></span></a>
				Energenti
				@if (Sentinel::inRole('administrator')	)
					<a href="{{ route('energy_consumptions.index') }}" class="view_all" title="Potrošnja" >Potrošnja</a>
					<a href="{{ route('energy_locations.index') }}" class="view_all" title="Lokacije" >Lokacije</a>
				@endif
			</div>
			<main class="all_documents">
				<div class="table-responsive">
					<header class="page-header">
						<div class="index_table_filter">
							<label>
								<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
							</label>
							@if(Sentinel::getUser()->hasAccess(["energy_sources.create"]) || in_array("energy_sources.create", $permission_dep) )
								<a class="add_new" href="{{ route('energy_sources.create') }}" class="" rel="modal:open"><i style="font-size:11px" class="fa">&#xf067;</i></a>
							@endif
						</div>
					</header>
					@if(count($energySources) > 0)
						<table id="index_table" class="display table table-hover">
							<thead>
								<tr>
									<th>Naziv</th>
									<th>Komentar</th>
									<th>@lang('basic.options')</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($energySources as $energySource)
									<tr>
										<td>{{ $energySource->name }}</td>
										<td>{{ $energySource->comment }}</td>
										<td class="center">
											@if(Sentinel::getUser()->hasAccess(['energy_sources.update']) || in_array('energy_sources.update', $permission_dep))
												<a href="{{ route('energy_sources.edit', $energySource->id) }}" class="btn-edit"  title="{{ __('basic.edit')}}" rel="modal:open">
														<i class="far fa-edit"></i>
												</a>
											@endif
											@if(Sentinel::getUser()->hasAccess(['energy_sources.delete']) || in_array('energy_sources.delete', $permission_dep))
												<a href="{{ route('energy_sources.destroy', $energySource->id) }}" class="action_confirm btn-delete danger" data-method="delete" title="{{ __('basic.delete')}}" data-token="{{ csrf_token() }}">
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