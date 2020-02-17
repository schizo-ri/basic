@extends('Centaur::layout')

@section('title', __('basic.benefits'))

@section('content')
<div class="index_page campaign_index">
	<aside class="col-lg-12 col-xl-12 float_left">
		@include('Centaur::side_noticeboard')
	</aside>
	<main class="col-lg-12 col-xl-8 index_main float_right">
		<section>
			<header class="header_campaign">
				<div class="filter">
					<div class="float_left col-6 height100 position_rel padd_0">
						<img class="img_search" src="{{ URL::asset('icons/search.png')  }}" alt="Search"/>
						<input type="text" id="mySearch" placeholder="{{ __('basic.search')}}" title="{{ __('basic.search')}}" class="input_search" >
					</div>
					<div class="float_right col-6 height100  position_rel padd_tb_5">
						<div class='add_campaign float_right '>
							@if(Sentinel::getUser()->employee)
								<a class="btn btn-primary btn-new" href="{{ route('campaigns.create') }}"  title="{{ __('basic.add_campaign')}}" rel="modal:open">
									<i class="fas fa-plus"></i>
								</a>
							@endif
						</div>
					</div>
				</div>
			</header>
			<main class="main_campaign">
				@if(isset($campaigns) && count($campaigns) >0)
					@foreach($campaigns as $campaign)
						@if ( $campaignSequences->where('campaign_id', $campaign->id)->first())
							@php
								$sequences = $campaignSequences->where('campaign_id', $campaign->id);
							@endphp
							<article class="campaign panel col-sm-12 col-md-12 col-lg-6 col-xl-6 float_left">
								<div>
									<header class="campaign_head">
										<h4>{{ $campaign->name }}</h4>
										<h4><em>{{ $campaign->description }}</em></h4>
									</header>
									<main class="campaign_main">
										@foreach ($sequences as $sequence)
											<p>{!! $sequence->text !!}</p>
										@endforeach
									</main>
									<footer>														
									</footer>
								</div>
							</article>
						@endif
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