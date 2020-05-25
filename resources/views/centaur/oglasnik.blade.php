@extends('Centaur::layout')

@section('title', __('basic.ads'))
<?php
use App\Http\Controllers\AdController;
use App\Models\Ads;
?>
@section('content')
<div class="index_page ads_index">
	
	<main class="col-xs-12 col-sm-12 col-md-12 index_main float_right">
		<section>
			<div class="page-header header_document">
				@lang('basic.ads')
			</div>
			<header class="header_ads">
				<div class="index_table_filter">
					<div class="">
						<img class="img_search" src="{{ URL::asset('icons/search.png')  }}" alt="Search"/>
						<input type="text" id="mySearch" placeholder="{{ __('basic.search')}}" title="{{ __('basic.search')}}" class="input_search" >
					</div>
					<div class="">
						<div class='add_ads float_right '>
							@if(Sentinel::getUser()->employee)
								<a class="btn btn-primary btn-new" href="{{ route('ads.create') }}"  title="{{ __('basic.add_ad')}}" rel="modal:open">
									<i class="fas fa-plus"></i>
								</a>
							@endif
						</div>
						{{-- <span class="arrow_left1"></span> --}}
						<select id="filter" class="select_filter" >
							<option value="all">@lang('basic.view_all')</option>
							@foreach($ads->unique('category_id') as $ad)
								<option value="{{  $ad->category['name'] }}">{{ $ad->category['name'] }}</option>
							@endforeach
						</select>
						@if (count($ads) >0)
							{{-- <span class="arrow_left1"></span> --}}
							<select id="filter1" class="select_filter sort" >
								<option class="sort_desc" value="{{ route('oglasnik', ['sort' => 'DESC'])}}">
									@lang('basic.new_first')
								</option>
								<option class="sort_asc" value="{{ route('oglasnik', ['sort' => 'ASC']) }} ">
									@lang('basic.old_first')
								</option>
							</select>
						@endif
					</div>
				</div>
			</header>
			<main class="main_ads">
				@if(isset($ads) && count($ads) >0)
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
						<article class="col-xs-12 col-sm-49 col-md-32 col-lg-24 col-xl-19 noticeboard_notice_body panel">
							<a href="{{ route('ads.show', $ad->id) }}" rel="modal:open">
								<div>
									<header class="ad_header">
										@if(isset($docs))
											@if(file_exists($path . end($docs)) && end($docs) != ""  )
												<img src="{{ asset($path .  end($docs)) }}" alt="Ad image"/>
											@else 
												<img class="placeholder_image" src="{{ URL::asset('icons/placeholderAd.png') }}" alt="Ad image"/>
											@endif										
										@else 
											<img class="placeholder_image" src="{{ URL::asset('icons/placeholderAd.png') }}" alt="Ad image"/>
										@endif
									</header>
									<main class="ad_main">
										<span class="ad_category">{{ $ad->category['name'] }}</span>
										<span class="ad_content"><b>{{ $ad->subject }}</b> <br> {!! str_limit(strip_tags($ad->description),45) !!} </span>
									</main>
									<footer class="ad_footer">
										<div class="ad_empl">
											<span class="profile_img">
												@if($profile_img)
													<img class="radius50 " src="{{ URL::asset($path_profile . end($profile_img)) }}" alt="Profile image"  />
												@else
													<img class="radius50 profile_img" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
												@endif
											</span>
											<p class="employee">
												{{ $ad->employee->user['first_name'] . ' ' . $ad->employee->user['last_name'] }} 
												<span>{{  $ad->employee->work['name'] }}</span>
											</p> 
										</div>
										<div class="price">
											<p>@lang('basic.price'): {!! $ad->price ?  $ad->price : ' - ' !!} {!! is_numeric($ad->price) ? ' Kn' : '' !!} </p>
										</div>
									</footer>
								</div>
							</a>
							<div class="notice_links">
								
								@if(Sentinel::getUser()->hasAccess(['ads.delete']) || in_array('ads.delete', $permission_dep))
									<a href="{{ route('ads.destroy', $ad->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
										<i class="far fa-trash-alt"></i>
									</a>
								@endif
								@if(Sentinel::getUser()->hasAccess(['ads.update']) || in_array('ads.update', $permission_dep) )
									<a href="{{ route('ads.edit', $ad->id) }}" class="btn-edit" rel="modal:open">
											<i class="far fa-edit"></i>
									</a>
								@endif
								<a class="btn-send" href="{{ route('posts.create', ['employee_publish' => $ad->employee ] ) }}" rel="modal:open">
									<i class="far fa-comment-dots"></i>
								</a>
								<!-- <a class="btn-send" href="{{ route('posts.create', ['employee_publish' => $ad->employee ] ) }}" rel="modal:open"><img class="img_send" src="{{ URL::asset('icons/envelope.png') }}" alt="messages"/></a> -->
								
								
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
	$.getScript( '/../js/open_modal.js');
	$(function(){
		var body_width = $('body').width();

		if(body_width > 450) {
			var all_height = [];
			$('.ad.panel .ad_content').each(function(){
				all_height.push($(this).height());
			});

			all_height.sort(function(a, b) {
				return b-a;
			});
			var max_height = all_height[0];

			$('.ad.panel .ad_content').height(max_height);
		}

		$.getScript( 'js/filter.js');
		$.getScript( 'js/filter_dropdown.js');
		$.getScript( 'js/ads.js');
	});
	
</script>
@stop