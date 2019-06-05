@extends('Centaur::layout')

@section('title', __('basic.ad_categories'))

@section('content')
    <div class="page-header">
        <a href="{{ route('ads.index') }}">@lang('basic.ads')</a>
		<div class='btn-toolbar pull-right'>
			@if(Sentinel::getUser()->hasAccess(['ad_categories.create']) || in_array('ad_categories.view', $permission_dep))
				<a class="btn btn-primary btn-lg" href="{{ route('ad_categories.create') }}">
					<i class="fas fa-plus"></i>
					@lang('basic.add_category')
				</a>
			@endif
        </div>
        <h1>@lang('basic.ad_categories')</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
				@if(count($categories))
					<table id="index_table" class="display table table-hover">
						<thead>
							<tr>
								<th>@lang('basic.name')</th>
								<th>@lang('basic.options')</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($categories as $category)
								<tr>
									<td><a href="{{ route('ads.index', ['category_id' => $category->id] ) }}" >{{ $category->name }}</a></td>
									<td class="center">
										@if(Sentinel::getUser()->hasAccess(['ad_categories.update']) || in_array('ad_categories.update', $permission_dep))
											<a href="{{ route('ad_categories.edit', $category->id) }}" class="btn-edit">
												 <i class="far fa-edit"></i>
											</a>
										@endif
										@if(Sentinel::getUser()->hasAccess(['ad_categories.delete']) || in_array('ad_categories.delete', $permission_dep))
											<a href="{{ route('ad_categories.destroy', $category->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
												<i class="far fa-trash-alt"></i>
											</a>
										@endif
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				@else
					@lang('basic.no_data')
				@endif
            </div>
        </div>
    </div>
<!-- Datatables -->
<script type="text/javascript" src="{{ URL::asset('dataTables/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dataTables/JSZip-2.5.0/jszip.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dataTables/pdfmake-0.1.36/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dataTables/pdfmake-0.1.36/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dataTables/Buttons-1.5.6/js/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('js/datatables.js') }}"></script>
@stop