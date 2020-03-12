@extends('Centaur::layout')

@section('title', __('basic.ads'))
@php
	use App\Http\Controllers\DashboardController;
@endphp
@section('content')
<div class="index_page noticeboard_index">
	<aside class="col-4 index_aside">
		@include('Centaur::side_calendar')
	</aside>
	<main class="col-lg-12 col-xl-8 index_main main_noticeboard float_right">
		<section>
			<header class="header_noticeboard">
				<a class="link_back" href="{{ url()->previous() }}"><span class="curve_arrow_left"></span></a>@lang('basic.notice_board')
			</header>
			<main class="all_notices">
				<header class="header_ads header_notice">
					<div class="filter">
						<div class="float_left col-6 height100 position_rel padd_0">
							<img class="img_search" src="{{ URL::asset('icons/search.png')  }}" alt="Search"/>
							<input type="text" id="mySearch_noticeboard" placeholder="{{ __('basic.search')}}" title="Type ... " class="input_search" autofocus>
						</div>
						<div class="float_right col-6 height100  position_rel padd_tb_17">
							<div class='add_notice float_right'>
								@if(Sentinel::getUser()->employee && Sentinel::getUser()->hasAccess(['notices.create']) || in_array('notices.create', $permission_dep) )
									<a class="add_new create_notice" href="{{ route('notices.create') }}" rel="modal:open" >
										<i style="font-size:11px" class="fa">&#xf067;</i>@lang('basic.add_notice')
									</a>
								@endif
							</div>
							
						</div>
					</div>
				</header>
				<section class="section_notice bg_white">
					<img class="img_search" src="{{ URL::asset('icons/filter.png')  }}" alt="Filter"/>
					<div class="notice_filter">						
						{{-- <span class="arrow_left1"></span> --}}
						<select id="filter1" class="select_filter sort" >
							<option class="sort_desc" value="{{ route('noticeboard', ['sort' => 'DESC'])}}">
								@lang('basic.new_first')
							</option>
							<option class="sort_asc" value="{{ route('noticeboard', ['sort' => 'ASC']) }} ">
								@lang('basic.old_first')
							</option>
						</select>
					</div>
					<div class="notices">
						@if(count($notices)>0)
							@foreach ($notices as $notice)
								@php
									$notice_dep = explode(',', $notice->to_department);
									$docs = '';
									$user_name = DashboardController::user_name($notice->employee_id);
								
									$path = 'storage/' . $user_name . "/profile_img/";
									if(file_exists($path)){
										$docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
									} else {
										$docs = '';
									}
									$notice_img = '';
									$path_notice = 'storage/notice/' . $notice->id . '/';
									if(file_exists($path_notice)){
										$notice_img = array_diff(scandir($path_notice), array('..', '.', '.gitignore'));
									} else {
										$notice_img = '';
									}
								@endphp
								@if(Sentinel::inRole('administrator'))
									<article class="noticeboard_notice_body">
										@if(Sentinel::getUser()->hasAccess(['notices.delete']) || in_array('notices.delete', $permission_dep))
											<a href="{{ route('notices.destroy', $notice->id) }}" class="delete action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
												<i class="far fa-trash-alt"></i>
											</a>
										@endif
										<a href="{{ route('notices.show', $notice->id) }}" class="notice_link panel notice_show" rel="modal:open">    
											<header class="ad_header">
												@if($notice_img)
													<img class="" src="{{ URL::asset('storage/notice/' . $notice->id . '/' . end($notice_img)) }}" alt="Profile image" title="Notice image" />
												@else 
													<img class="placeholder_image" src="{{ URL::asset('icons/placeholderAd.png') }}" alt="Ad image"/>
												@endif
											</header>
											<div class="ad_main">
												<p class="notice_title">
													{{ $notice->title }} 
													@if ($notice->schedule_date > $today . ' ' . $time)	<span class="scheduled">@lang('basic.scheduled')</span>@endif
													@if ($notice_dep)
													
														@foreach ($notice_dep as $department)
															<span class="user_department">{{ $departments->where('id',$department)->first()->name }}</span>
														@endforeach
													@endif
												</p>
												<span class="noticeboard_notice_empl">
													@if($docs)
														<img class="notice_img radius50" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($docs)) }}" alt="Profile image" title="{{ $notice->employee->user['first_name'] . ' ' . $notice->employee->user['last_name'] }}"  />
													@else
														<img class="notice_img radius50" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
													@endif
													<span>{{ $notice->employee->user['first_name'] . ' ' . $notice->employee->user['last_name'] }}</span>
												</span>
												<span class="noticeboard_notice_time">{{ date('l, d.F.Y',strtotime($notice->created_at))}}</span>												
											</div>
										</a>
									</article>
								@elseif(array_intersect($user_department, $notice_dep) )
									<article class="noticeboard_notice_body">
										@if(Sentinel::getUser()->hasAccess(['notices.delete']) || in_array('notices.delete', $permission_dep))
											<a href="{{ route('notices.destroy', $notice->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
												<i class="far fa-trash-alt"></i>
											</a>
										@endif
										<a href="{{ route('notices.show', $notice->id) }}" class="notice_link panel notice_show" rel="modal:open"> 
											<header class="ad_header">
												@if($notice_img)
													<img class="" src="{{ URL::asset('storage/notice/' . $notice->id . '/' . end($notice_img)) }}" alt="Profile image" title="Notice image"  />
												@else 
													<img class="placeholder_image" src="{{ URL::asset('icons/placeholderAd.png') }}" alt="Ad image"/>
												@endif
											</header>
											<div class="ad_main">
												<p class="notice_title">
													{{ $notice->title }} 
												</p>
												<span class="noticeboard_notice_empl">
													@if($docs)
														<img class="notice_img radius50" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($docs)) }}" alt="Profile image" title="{{ $notice->employee->user['first_name'] . ' ' . $notice->employee->user['last_name'] }}" />
													@else
														<img class="notice_img radius50" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
													@endif
													<span>{{ $notice->employee->user['first_name'] . ' ' . $notice->employee->user['last_name'] }}</span>
												</span>
												<span class="noticeboard_notice_time" >{!! $notice->schedule_date ? date('l, d.F.Y',strtotime($notice->schedule_date)) :  date('l, d.F.Y',strtotime($notice->created_at)) !!}</span>
											</div>
										</a>
									</article>
								@endif
							@endforeach
						@else 
							<div class="placeholder">
								<img class="" src="{{ URL::asset('icons/placeholder_notice.png') }}" alt="Placeholder image" />
								<p>@lang('basic.no_notice1')
									<label type="text" class="add_new" rel="modal:open" >
										<i style="font-size:11px" class="fa">&#xf067;</i>
									</label>
									@lang('basic.no_notice2')
								</p>
							</div>
						@endif
					</div>
				</section>
			</main>
		</section>
	</main>
	<div hidden class="dataArr">{!! json_encode($dataArr) !!}</div>
</div>
<script>
	$( function () {
		$.getScript( '/../js/filter.js');
		$.getScript( '/../js/filter_dropdown.js');
		$.getScript( '/../js/set_height_notice.js');
		$('.select_filter.sort').change(function () {
			console.log($(this).val());
			$('.section_notice .notices').load($(this).val() + ' .section_notice .notices .noticeboard_notice_body');
		});
		
		$('.placeholder').show();
		
	});
</script>
@stop
		
	