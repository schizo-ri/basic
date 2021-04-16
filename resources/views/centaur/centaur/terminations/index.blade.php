@extends('Centaur::admin')

@section('title', __('basic.termination_types'))

@section('content')
	@php
		use App\Models\Absence;
	@endphp
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['terminations.create']) || in_array('terminations.create', $permission_dep))
				<a class="btn-new" href="{{ route('terminations.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($terminations) > 0)
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.name')</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($terminations as $termination)
							<tr>
								<td>{{ $termination->name }}</td>
								<td class="center">
									<!-- <button class="collapsible option_dots float_r"></button> -->
									@if(Sentinel::getUser()->hasAccess(['terminations.update']) || in_array('terminations.update', $permission_dep))
										<a href="{{ route('terminations.edit', $termination->id) }}" class="btn-edit" rel="modal:open">
											<i class="far fa-edit"></i>
										</a>
									@endif
									@if( ! Absence::where('type', $termination->id)->first() && Sentinel::getUser()->hasAccess(['terminations.delete']) || in_array('absence_types.delete', $permission_dep) )
										<a href="{{ route('terminations.destroy', $termination->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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