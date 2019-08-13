@extends('Centaur::layout')

@section('title', __('basic.ads'))
<?php
use App\Http\Controllers\AdController;
?>
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
						<input type="text" id="mySearch" placeholder="{{ __('basic.search')}}" title="Type ... " class="input_search" autofocus>
					</div>
					<div class="float_right col-6 height100  position_rel padd_tb_5">
						<div class='add_ads float_right '>
							@if(Sentinel::getUser()->employee)
								<a class="btn btn-primary btn-new" href="{{ route('ads.create') }}">
									<i class="fas fa-plus"></i> Add
								</a>
							@endif
						</div>
						<select id="filter" class="select_filter" >
							<option>all</option>
							@foreach($ads->unique('category_id') as $ad)
								<option value="{{  $ad->category['name'] }}">{{ $ad->category['name'] }}</option>
							@endforeach
						</select>
						<img class="img_filter" src="{{ URL::asset('icons/filter.png') }}" alt="Filter"/></a>
					</div>
				</div>
				
			</header>
			<main class="main_ads">
				<!--<a href="{{ route('ad_categories.index') }}">@lang('basic.ad_categories')</a> |-->
			<!--	<a href="{{ route('ads.index') }}">@lang('basic.ads')</a>-->
				@if(isset($ads))
					@foreach($ads as $ad)
						<?php
							
							$path = 'storage/ads/' . $ad->id . '/';
							if(file_exists($path)) {
								$docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
							}
							$profile_img = '';
							
							$user_name = explode('.',strstr($ad->employee->email,'@',true));
							if(count($user_name) == 2) {
								$user_name = $user_name[1] . '_' . $user_name[0];
							} else {
								$user_name = $user_name[0];
							}

							$path_profile = 'storage/' . $user_name . "/profile_img/";
							if(file_exists($path_profile)){
								$profile_img = array_diff(scandir($path_profile), array('..', '.', '.gitignore'));
							}else {
								$profile_img = '';
							}

						?>
						<article class="ad panel col-sm-12 col-md-6 col-lg-4 col-xl-3 float_left">
							<div>
								<header class="ad_header">
									@if(isset($docs))
										@foreach($docs as $doc)
											@if(file_exists($path . $doc))
												<img src="{{ asset($path . $doc) }}" alt="Ad image"/>
											@endif
										@endforeach
									@endif
								</header>
								<main class="ad_main">
									<span class="ad_category">{{ $ad->category['name'] }}</span>
									<span class="ad_content">{{ $ad->subject }} | {!!  $ad->description !!}</span>
								</main>
								<footer class="ad_footer">
									<div class="ad_empl">
										@if($profile_img)
											<img class="radius50 profile_img" src="{{ URL::asset($path_profile . end($profile_img)) }}" alt="Profile image"  />
										@else
											<img class="radius50 profile_img" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
										@endif
										<p class="employee">{{ $ad->employee->user['first_name'] . ' ' . $ad->employee->user['last_name'] }} <span>{{  $ad->employee->work['name'] }}</span></p>  <!--  .' | ' . \Carbon\Carbon::createFromTimeStamp(strtotime($ad->created_at))->diffForHumans()-->
										<span class="contact">
											<a class="btn-send" href="{{ route('posts.create') }}">
												<img class="img_send" src="{{ URL::asset('icons/chat.png') }}" alt="messages"/></a>
											</a>
										</span>
										<span class="contact">
											<a class="btn-send" href="{{ route('posts.create') }}">
												<img class="img_send" src="{{ URL::asset('icons/envelope.png') }}" alt="messages"/></a>
											</a>
										</span>
									</div>
									<div class="price">
										<p>100 kn</p>
									</div>
								</footer>
							</div>
						</article>
					@endforeach
				@endif
			</main>
		</section>
	</main>
</div>

<script>
$(function() {
	$('.link_ads').css('color','orange');
});
</script>
<script src="{{ URL::asset('js/filter.js') }}" ></script>
<script src="{{URL::asset('js/filter_dropdown.js') }}" ></script>
@stop
