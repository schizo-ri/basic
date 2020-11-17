@extends('Centaur::layout')

@section('title', __('basic.educations'))

@section('content')
<div class="index_page index_documents">
	<main class="col-md-12 index_main main_documents float_right">
		<section>
			<div class="page-header header_document">
				<a class="link_back" href="{{ url()->previous() }}" ><span class="curve_arrow_left"></span></a>
				@lang('basic.educations')
			</div>
			<main class="all_documents">
				<div class="table-responsive">
					<header class="page-header">
						<div class="index_table_filter">
							<label>
								<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
							</label>
							@if(Sentinel::getUser()->hasAccess(["educations.create"]) || in_array("educations.create", $permission_dep) )
								<a class="add_new" href="{{ route('educations.create') }}" class="" rel="modal:open">
									<i style="font-size:11px" class="fa">&#xf067;</i>
								</a>
							@endif
						</div>
					</header>
					<section class="page-main">
						@if(count($educations)>0)
							<table id="index_table" class="display table table-hover">
								<thead>
									<tr>
										<th>@lang('basic.title')</th>
										<th>@lang('basic.to_department')</th>
										@if (Sentinel::inRole('administrator'))
											<th class="not-export-column no-sort"></th>
										@endif
									</tr>
								</thead>
								<tbody>
									@foreach ($educations as $education)
										<tr class="tr_open_link_new_page" data-href="/educations/{{ $education->id }}"  >
											<td>{{ $education->name }}</td>
											<td >{{ $education->department->name }}</td>
											@if (Sentinel::inRole('administrator'))
												<td class="options center not_link" ">
													@if(Sentinel::getUser()->hasAccess(['educations.update']) || in_array('educations.update', $permission_dep) )
													<a href="{{ route('educations.edit', $education->id ) }}" class="btn-edit" title="{{ __('basic.edit_education')}}" rel="modal:open">
														<i class="far fa-edit"></i>
														</a>
													@endif
													@if(Sentinel::getUser()->hasAccess(['educations.delete']) || in_array('educations.delete', $permission_dep))
														<a href="{{ route('educations.destroy', $education->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
															<i class="far fa-trash-alt"></i>
														</a>
													@endif
												</td>
											@endif
										</tr>
									@endforeach
								</tbody>
							</table>
						@else
							<div class="placeholder">
								<img class="" src="{{ URL::asset('icons/placeholder_document.png') }}" alt="Placeholder image" />
								<p> @lang('basic.no_file1')
									@if(Sentinel::getUser()->hasAccess(["educations.create"]) || in_array("educations.create", $permission_dep) )
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