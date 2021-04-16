@extends('Centaur::layout')

@section('title', __('basic.education'))

@section('content')
<div class="index_page index_documents">
	<main class="col-md-12 index_main main_documents float_right">
		<section>
			<div class="page-header header_document">
				<a class="link_back" href="{{ route('educations.index') }}" ><span class="curve_arrow_left"></span></a>
				@lang('basic.education') {!! isset( $education )  ? $education->name : '' !!}
			</div>
			<main class="all_documents">
				<div class="table-responsive">
					<header class="page-header">
						<div class="index_table_filter">
							<label>
								<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
							</label>
							@if(Sentinel::getUser()->hasAccess(["education_themes.create"]) || in_array("education_themes.create", $permission_dep) )
								<a class="add_new" href="{{ route('education_themes.create',['education_id'=> $education ? $education->id : null ]) }}" rel="modal:open" title="{{ __('basic.add_educationTheme') }}">
									<i style="font-size:11px" class="fa">&#xf067;</i>
								</a>
							@endif
						</div>
					</header>
					<section class="page-main">
						@if( $education && count( $education->educationThemes )>0 )
							<table id="index_table" class="display table table-hover sort_1_asc">
								<thead>
									<tr>
										<th>@lang('basic.title')</th>
										@if (Sentinel::inRole('administrator'))
											<th class="not-export-column no-sort">@lang('basic.options')</th>
										@endif
									</tr>
								</thead>
								<tbody>
									@foreach ( $education->educationThemes->sortBy('name') as $educationTheme)
										@if(Sentinel::inRole('administrator') || count($educationTheme->educationArticles) >0)
											<tr class="tr_open_link_new_page"  data-href="/education_themes/{{ $educationTheme->id }}" >
												<td>{{ $educationTheme->name }}  [{{ count($educationTheme->educationArticles ) }}]</td>
												@if (Sentinel::inRole('administrator'))
													<td class="options center not_link">
														@if(Sentinel::getUser()->hasAccess(['education_themes.update']) || in_array('education_themes.update', $permission_dep) )
														<a href="{{ route('education_themes.edit', $educationTheme->id ) }}" class="btn-edit" title="{{ __('basic.edit_educationTheme')}}" rel="modal:open">
															<i class="far fa-edit"></i>
															</a>
														@endif
														@if(Sentinel::getUser()->hasAccess(['education_themes.delete']) || in_array('education_themes.delete', $permission_dep))
															<a href="{{ route('education_themes.destroy', $educationTheme->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
																<i class="far fa-trash-alt"></i>
															</a>
														@endif
													</td>
												@endif
											</tr>
										@endif
									@endforeach
								</tbody>
							</table>
						@else
							<div class="placeholder">
								<img class="" src="{{ URL::asset('icons/placeholder_document.png') }}" alt="Placeholder image" />
								<p> @lang('basic.no_education1')
									@if(Sentinel::getUser()->hasAccess(["education_articles.create"]) || in_array("education_articles.create", $permission_dep) )
									@lang('basic.no_education2')
									<label type="text" class="add_new" rel="modal:open" >
										<i style="font-size:11px" class="fa">&#xf067;</i>
									</label>
										
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