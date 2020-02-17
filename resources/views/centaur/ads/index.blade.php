@extends('Centaur::layout')

@section('title', __('basic.ads'))

@section('content')
<div class="row">  
	<div class="page-header">
		<a href="{{ route('ad_categories.index') }}"  class="load_page" >@lang('basic.ad_categories')</a>
		<a href="{{ route('ads.index') }}"  class="load_page">@lang('basic.ads')</a>
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
							<th class="not-export-column">@lang('basic.options')</th>
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
				<p class="no_data">@lang('basic.no_data')</p>
			@endif
		</div>
	</div>
</div>

@stop