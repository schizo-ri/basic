@extends('Centaur::layout')

@section('title', __('basic.benefits'))

@section('content')
<div class="index_page ads_index">
	<aside class="col-lg-12 col-xl-12 float_left">
		@include('Centaur::side_noticeboard')
	</aside>
	<main class="col-lg-12 col-xl-8 index_main float_right">
		<section>
			<header class="header_ads">
				<div class="filter">
					<div class="float_left col-6 height100 position_rel padd_0">
						<img class="img_search" src="{{ URL::asset('icons/search.png')  }}" alt="Search"/>
						<input type="text" id="mySearch" placeholder="{{ __('basic.search')}}" title="{{ __('basic.search')}}" class="input_search" >
					</div>
					<div class="float_right col-6 height100  position_rel padd_tb_5">
						<div class='add_ads float_right '>
							@if(Sentinel::getUser()->employee)
								<a class="btn btn-primary btn-new" href="{{ route('benefits.create') }}"  title="{{ __('basic.add_benefit')}}" rel="modal:open">
									<i class="fas fa-plus"></i>
								</a>
							@endif
						</div>
					</div>
				</div>
			</header>
			<main class="main_ads">
				@if(isset($benefits) && count($benefits) >0)
					@foreach($benefits as $benefit)
						<article class="ad panel col-sm-12 col-md-12 col-lg-6 col-xl-6 float_left">
							<div>
								<header class="ad_head">
									<h4>{{ $benefit->name }}</h4>
								</header>
								<main class="ad_main">
									<p>{!! $benefit->description !!}</p>
									<p> 
										{!! $benefit->comment !!}
									</p>
								</main>
								<footer>
									@if ($benefit->url)
										<p><a href="{{ $benefit->url }}" target="_blank" class="btn">Više... </a></p>
									@endif
									@if ( $benefit->url2)
									<p><a href="{{ $benefit->url2 }}" target="_blank" class="btn">Više...</a></p>
									@endif								
								</footer>
							</div>
						</article>
					@endforeach
				@else 
					<div class="placeholder">
						<img class="" src="{{ URL::asset('icons/placeholder_ad.png') }}" alt="Placeholder image" />
						<p>@lang('basic.no_ad1')
							<label type="text" class="add_new" rel="modal:open" >
								<i style="font-size:11px" class="fa">&#xf067;</i>
							</label>
							@lang('basic.no_ad2')
						</p>
					</div>
				@endif
			</main>
		</section>
	</main>
</div>
<script>
	$.getScript( '/../js/filter.js');
</script>
@stop
	