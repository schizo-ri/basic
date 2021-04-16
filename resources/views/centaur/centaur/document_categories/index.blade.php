@extends('Centaur::admin')

@section('title', __('basic.ad_categories'))

@section('content')
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['document_categories.create']) || in_array('document_categories.view', $permission_dep))
				<a class="btn-new" href="{{ route('document_categories.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($document_categories))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.name')</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($document_categories as $category)
							<tr>
								<td>{{ $category->name }}</td>
								<td class="center">
									@if(Sentinel::getUser()->hasAccess(['document_categories.update']) || in_array('document_categories.update', $permission_dep))
										<a href="{{ route('document_categories.edit', $category->id) }}" class="btn-edit" rel="modal:open">
												<i class="far fa-edit"></i>
										</a>
									@endif
									@if( Sentinel::getUser()->hasAccess(['document_categories.delete']) || in_array('document_categories.delete', $permission_dep))
										<a href="{{ route('document_categories.destroy', $category->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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