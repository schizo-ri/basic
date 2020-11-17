@extends('Centaur::layout')

@section('title', __('basic.instructions'))

@section('content')
<div class="index_page ads_index">
	<main class="col-xs-12 col-sm-12 col-md-12 index_main float_right">
		<section>
			<div class="page-header header_document">
				<a class="link_back" href="{{ url()->previous() }}" ><span class="curve_arrow_left"></span></a>
				@lang('basic.instructions')
			</div>
			<header class="header_ads">
				<div class="index_table_filter">
					<div class="">
						<img class="img_search" src="{{ URL::asset('icons/search.png')  }}" alt="Search"/>
						<input type="text" id="mySearch" placeholder="{{ __('basic.search')}}" title="{{ __('basic.search')}}" class="input_search" >
					</div>
					<div class="">
						<div class='add_ads float_right '>
							@if(Sentinel::getUser()->hasAccess(['instructions.create']) || in_array('instructions.create', $permission_dep))
								<a class="btn btn-primary btn-new" href="{{ route('instructions.create') }}"  title="{{ __('basic.add_instruction')}}" rel="modal:open">
									<i class="fas fa-plus"></i>
								</a>
							@endif
						</div>
					</div>
				</div>
			</header>
			<main class="main_ads main_instructions">
				@if(isset($instructions) && count($instructions) >0)
					@foreach($instructions as $instruction)
						@if (in_array($instruction->department_id, $employee_departments))
						<article class="col-xs-12 col-sm-49 col-md-32 col-lg-24 col-xl-19 noticeboard_notice_body instructions_body panel">
								<a href="{{ route('instructions.show', $instruction->id) }}" rel="modal:open">
									<div>
										<main class="ad_main">
											<span class="ad_category">{{ $instruction->department['name'] }}</span>
											<span class="ad_content"><b>{{ $instruction->title }}</b> <br></span>
										</main>
										<footer class="ad_footer">
											<span><small>{{ str_limit($instruction->description, 200) }}</small></span>
										</footer>
									</div>
								</a>
								<div class="notice_links">
								
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


	});
	
</script>
@stop