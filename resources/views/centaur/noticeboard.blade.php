@extends('Centaur::layout')

@section('title', __('basic.ads'))
    <link rel="stylesheet" href="{{ URL::asset('css/dashboard.css') }}"/>
	<link rel="stylesheet" href="{{ URL::asset('css/index.css') }}"/>
	<script src="{{ URL::asset('node_modules/jquery/dist/jquery.js') }}"></script>
	<script src="{{ URL::asset('node_modules/moment/moment.js') }}"></script>
@php
	$user_department = array();
    $permission_dep = array();
    if($user) {
        array_push($user_department, $user->work->department->id);
        array_push($user_department, $departments->where('level1',0)->first()->id);
        $permission_dep = explode(',', count($user->work->department->departmentRole) > 0 ? $user->work->department->departmentRole->toArray()[0]['permissions'] : '');
    }
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
							<input type="text" id="mySearch" placeholder="{{ __('basic.search')}}" title="Type ... " class="input_search" autofocus>
						</div>
						<div class="float_right col-6 height100  position_rel padd_tb_17">
							<div class='add_notice float_right '>
								@if(Sentinel::getUser()->employee && Sentinel::getUser()->hasAccess(['notices.create']) || in_array('notices.create', $permission_dep) )
									<a class="add_new" href="{{ route('notices.create') }}" rel="modal:open" >
										<i style="font-size:11px" class="fa">&#xf067;</i>@lang('basic.add_notice')
									</a>
								@endif
							</div>
						</div>
					</div>
				</header>
				<section class="section_notice overflow_auto bg_white">
					<div class="notice_filter">
						<img class="img_filter" src="{{ URL::asset('icons/filter.png') }}" alt="Filter"/></a>
						<select id="filter" class="select_filter" >
							<option value="ASC">Oldest first</option>
							<option  value="DESC">Latest first</option>
						</select>
					</div>
					<div class="notices">
						@if(count($notices))
							@foreach ($notices as $notice)
								@php
									$notice_dep = explode(',', $notice->to_department);
									$docs = '';
									$user_name = explode('.',strstr($notice->employee['email'],'@',true));
									if(count($user_name) == 2) {
										$user_name = $user_name[1] . '_' . $user_name[0];
									} else {
										$user_name = $user_name[0];
									}

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
								@if(array_intersect($user_department, $notice_dep) )
									<a href="{{ route('notices.show', $notice->id) }}" class="col-3 notice_link panel" rel="modal:open">    
										<article class="noticeboard_notice_body">
											@if($notice_img)
												<img class="" src="{{ URL::asset('storage/notice/' . $notice->id . '/' . end($notice_img)) }}" alt="Profile image" title="Notice image"  />
											@endif
											<div class="">
												<p class="notice_title">
													{{ $notice->title }}
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
										</article>
									</a>
								@endif
							@endforeach
						@endif
					</div>
				</section>
			</main>
		</section>
    </main>
</div>
<script src="{{ URL::asset('js/filter.js') }}" ></script>
<script src="{{URL::asset('js/filter_dropdown.js') }}" ></script>
<script src="{{URL::asset('js/set_height_notice.js') }}" ></script>
@stop