@extends('Centaur::layout')

@section('title', 'Kategorizacija elektromontera')

@section('content')
<div class="index_page diary_table">
	<main class="col-md-12 index_main main_documents float_right">
		<section>
			<div class="page-header header_document">
				<a class="link_back" href="{{ url()->previous() }}" ><span class="curve_arrow_left"></span></a>
				Kategorizacija elektromontera
				@if (Sentinel::inRole('administrator')	)
					<a href="{{ route('competences.index') }}" class="view_all" title="Potrošnja" >Kompentencije</a>
					<a href="{{ route('competence_groups.index') }}" class="view_all" title="Potrošnja" >Grupe kompentencija</a>
					<a href="{{ route('construction_sites.index') }}" class="view_all" title="Energenti" >Tipovi gradilišta</a>
				@endif
			</div>
			<main class="all_documents">
				<div class="table-responsive">
					<header class="page-header diary_header">
						<div class="index_table_filter">
							<label>
								<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearch()" id="mySearch">
							</label>
							
						</div>
					</header>
					<section class="page-main">
						@if(count($categorizations))
							<table id="index_table" class="display table table-hover sort_1_asc">
								<thead>
									<tr>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<th class="not-export-column">@lang('basic.options')</th>
								</thead>
								<tbody>
									@foreach ($categorizations as $categorization)
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td class="center">
												@if(Sentinel::getUser()->hasAccess(['categorizations.update']) || in_array('categorizations.update', $permission_dep))
													<a href="{{ route('categorizations.edit', $categorization->id) }}" class="btn-edit" title="Ispravi" rel="modal:open">
															<i class="far fa-edit"></i>
													</a>
												@endif
												@if(Sentinel::getUser()->hasAccess(['categorizations.delete']) || in_array('categorizations.delete', $permission_dep))
												<a href="{{ route('categorizations.destroy', $categorization->id) }}" class="action_confirm btn-delete danger" style="display:none" data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
													<i class="far fa-trash-alt"></i>
												</a>
												@endif
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						@else
							<div class="placeholder">
								<img class="" src="{{ URL::asset('icons/placeholder_document.png') }}" alt="Placeholder image" />
								<p> @lang('basic.no_file1')
									@if(Sentinel::getUser()->hasAccess(["documents.create"]) || in_array("documents.create", $permission_dep) )
									@lang('basic.no_file2')
										<label type="text" class="add_new" rel="modal:open" >
											<i style="font-size:11px" class="fa">&#xf067;</i>
										</label>
										@lang('basic.no_file3')
									@endif
								</p>
							</div>
						@endif
					</section>
				</div>
			</main>
		</section>
	</main>
</div>
@stop