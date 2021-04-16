@extends('Centaur::admin')

@section('title', __('absence.vacations'))

@section('content')
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['vacations.create']) || in_array('vacations.view', $permission_dep))
				<a class="btn-new" href="{{ route('vacations.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($vacations))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.title')</th>
							<th class="sort_date">@lang('absence.start_date')</th>
							<th class="sort_date">@lang('absence.end_date')</th>
							<th class="sort_date">@lang('absence.closing')</th>
							<th>Vremenski interval)</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($vacations as $vacation)
							<tr class="tr_open_link_new_page " data-href="/vacations/{{ $vacation->id }}" >
								<td>{{ $vacation->title }}</td>
								<td>{{ date('d.m.Y', strtotime($vacation->start_period)) }}</td>
								<td>{{ date('d.m.Y', strtotime($vacation->end_period)) }}</td>
								<td>{{ date('d.m.Y', strtotime( $vacation->end_date )) }}</td>
								<td>{{ $vacation->interval }}</td>
								<td class="center not_link">
									@if(Sentinel::getUser()->hasAccess(['vacations.update']) || in_array('vacations.update', $permission_dep))
										<a href="{{ route('vacations.edit', $vacation->id) }}" class="btn-edit" rel="modal:open">
												<i class="far fa-edit"></i>
										</a>
									@endif
									@if( count($vacation->hasPlans) == 0 && Sentinel::getUser()->hasAccess(['vacations.delete']) || in_array('vacations.delete', $permission_dep))
										<a href="{{ route('vacations.destroy', $vacation->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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