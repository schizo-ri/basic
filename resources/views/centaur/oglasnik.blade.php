@extends('Centaur::layout')

@section('title', __('basic.ads'))

@section('content')
<div class="row">
	<a href="{{ route('ad_categories.index') }}">@lang('basic.ad_categories')</a>
	<a href="{{ route('ads.index') }}">@lang('basic.ads')</a>
	<div class="page-header">
		<div class="filter">
			<input type="text" id="mySearch" placeholder="{{ __('basic.search')}}" title="Type ... " class="input_search" autofocus>
			<select id="filter" >
				<option>all</option>
				@foreach($ads->unique('category_id') as $ad)
					<option value="{{  $ad->category['name'] }}">{{ $ad->category['name'] }}</option>
				@endforeach
			</select>
			
		</div>
	</div>
	@if(isset($ads))
		@foreach($ads as $ad)
			<div class="col-md-4 col-md-offset-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><span>{{ $ad->category['name'] }} </span>{{  ' | ' . $ad->subject }}</h3>
					</div>
					<div class="panel-body">
					{!! $ad->description !!}
					</div>
					 <div class="panel-footer ad">
						<small>{{ $ad->employee->user['first_name'] .' | ' . \Carbon\Carbon::createFromTimeStamp(strtotime($ad->created_at))->diffForHumans()  }}</small>
					 </div>
				</div>
			</div>
		@endforeach
	@endif
</div>
<script>
$(function() {
	$('.link_ads').css('color','orange');
});
</script>
@stop
