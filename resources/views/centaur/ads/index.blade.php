@extends('Centaur::layout')

@section('title', __('basic.ads'))

@section('content')
     <a href="{{ route('ad_categories.index') }}">@lang('basic.ad_categories')</a>
	 <div class="page-header">
		<div class='btn-toolbar pull-right'>
			@if(isset($category))
				<a class="btn btn-primary btn-lg" href="{{ route('ads.create', ['category_id' => $category->id]) }}">
			@else
				<a class="btn btn-primary btn-lg" href="{{ route('ads.create') }}">
			@endif
				<i class="fas fa-plus"></i>
				@lang('basic.add_ad')
			</a>
        </div>
        <h1>@lang('basic.ads')@if(isset($category)) - {{ $category->name}}@endif</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
				@if(count($ads))
					<table id="index_table" class="display table table-hover">
						<thead>
							<tr>
								<th>@lang('basic.ad_category')</th>
								<th>@lang('basic.employee')</th>
								<th>@lang('basic.subject')</th>
								<th>@lang('basic.description')</th>
								<th>@lang('basic.price')</th>
								<th>@lang('basic.options')</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($ads as $ad)
								<tr>
									<td>{{ $ad->category['name'] }}</td>
									<td>{{ $ad->employee->user['first_name'] . ' ' .  $ad->employee->user['last_name'] }}</td>
									<td><a href="{{ route('ads.show', $ad->id) }}" >{{ $ad->subject }}</a></td>
									<td>{!! str_limit(strip_tags($ad->description),100) !!}</td>
									<td>{{ $ad->price }}</td>
									<td class="center">
										@if(Sentinel::getUser()->hasAccess(['ads.update']) || in_array('ads.update', $permission_dep) )
											<a href="{{ route('ads.edit', $ad->id) }}" class="btn-edit">
												 <i class="far fa-edit"></i>
											</a>
										@endif
										@if(Sentinel::getUser()->hasAccess(['ads.delete']) || in_array('ads.delete', $permission_dep))
											<a href="{{ route('ads.destroy', $ad->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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