@extends('Centaur::layout')

@section('title', __('welcome.dashboard'))
@php
	use App\Models\Employee;
	use App\Http\Controllers\PostController;
	use App\Http\Controllers\DashboardController;
	$thisYear = date('Y');
@endphp
@section('content')
	@if (Sentinel::check())
		<div class="user_header col-xs-8 col-sm-8 float_left " >
			<div class="info">
				<div class="col-md-3 float_left ">
					@php
						$profile_image = DashboardController::profile_image(Sentinel::getUser()->employee['id']);
						$user_name =  DashboardController::user_name(Sentinel::getUser()->employee['id']);
					
					@endphp
					@if($profile_image && ! empty($profile_image))
						<span class="image_prof">
							<img class="" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($profile_image)) }}" alt="Profile image"  />
						</span>
					@else
						<span class="image_prof">
							<img class="radius50 " src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
						</span>
					@endif
					<h2>{{ Sentinel::getUser()->first_name . ' ' . Sentinel::getUser()->last_name }}</h2>
					@if(isset($employee))
					<p>{{ $employee->work['name'] }}</p>
					<div class="header_user_go">
						<p>
							<span>{{ $data_absence['ukupnoPreostalo']}}</span>
							<span>@lang('absence.vacation')<br>@lang('absence.days_left')</span>
						</p>
						<p>
							<span>{!! isset($data_absence[$thisYear]) && count($data_absence[$thisYear]) > 0 ? $data_absence[$thisYear]['dani_zahtjeva'] : 0 !!}</span>
							<span>@lang('absence.vacation')<br>@lang('absence.days_used')</span>
						</p>
					</div>
					@endif
				</div>
				@if(isset($employee))
				<div class="col-md-9 padd_0 float_left salary ">
					<span class="efc_hide">@lang('basic.hide_salery')<img class="radius50" src="{{ URL::asset('icons/arrow_up.png') }}" alt="arrow" /></span>
					<span class="efc_show">@lang('basic.show_salery')<img class="radius50" src="{{ URL::asset('icons/arrow_down.png') }}" alt="arrow" /></span>
					<div class="efc col-md-12">
						<p class="col-4"><span class="salery_show">{{ number_format($employee->brutto, 2, ',', '.') }} kn</span><span class="salery_hidden">- Kn</span>@lang('basic.yearly_salary')</p>
						<p class="col-4"><span class="salery_show">{{ number_format($employee->brutto /12, 2, ',', '.') }}  kn</span><span class="salery_hidden">- Kn</span>@lang('basic.monthly_cost')</p>
						<p class="col-4"><span class="salery_show">{{ number_format($employee->effective_cost, 2, ',', '.')}}  kn</span><span class="salery_hidden">- Kn</span>@lang('basic.hourly_rate')</p>
					</div>
					<div class="col-md-12 padd_0 float_left layout_button ">
						<button class=""><a href="{{ route('absences.create', ['type' => 'GO']) }}" rel="modal:open">
							<span>
								<span class="img beach"></span>
								<p>@lang('absence.request_vacation')</p>
							</span></a>
						</button>
						<button class="" ><a href="{{ route('absences.create', ['type' => 'BOL']) }}" rel="modal:open">
							<span>
								<span class="img sick"></span>
								<p>@lang('absence.sick_leave')</p>
							</span></a>
						</button>
						<button class="" ><a href="{{ route('loccos.create') }}" rel="modal:open">
							<span>
								<span class="img car"></span>
								<p>@lang('basic.add_locco')</p>
							</span></a>
						</button>
						<button class="button_absence" ><a href="{{ route('absences.index') }}" >
							<span>
								<span class="img all_req"><p>@lang('absence.view_all_request')</p></span>
							</span></a>
						</button>
					</div>
				</div>
				@endif
			</div>
		</div>
		<section class="col-xs-12 col-sm-4 float_left">
			@include('Centaur::side_noticeboard')
		</section>		
		<section class="col-xs-12 col-sm-5 float_left calendar">
			<div>
				<div id="calendar">
					<div class="box">
						<div class="header">
							<h2>@lang("calendar.calendar")
								@if(isset($events) && count($events) > 0)
									<a class="view_all" href="{{ route('events.index') }}" >@lang("basic.view_all")</a>
								@endif
							
								<button id="right-button" class="scroll_right_cal"></button>
								<button id="left-button" class="scroll_left_cal"></button>
							</h2>
							<span class="title display_none">{{ date('Y M') }}</span>
						</div>	
						<div class="box-content">
							<ul class="dates ">
							</ul>	
						</div>	
					</div>
				</div>	
				<div class="comming_agenda">
					@if(isset($employee))<a class="btn btn-primary btn-lg btn-new" href="{{ route('events.create') }}" rel="modal:open">
							<i style="font-size:11px" class="fa">&#xf067;</i>
					</a>@endif
					<h3 class="agenda_title">@lang('calendar.your_agenda') </h3>

					@if(isset($events) && count($events) > 0)
						@foreach($events->take(5) as $event)
							<p class="agenda display_none" id="{{ $event->date }}">
								<span class="agenda_mark"><span class="green"></span></span>
								<span class="agenda_time">{{ date('H:i',strtotime($event->time1)) }}<br><span>{{ date('H:i',strtotime($event->time2)) }}</span></span>
								<span class="agenda_comment">{{ $event->description }}</span>
							</p>
						@endforeach
					@endif
					<div class="placeholder">
						<img class="" src="{{ URL::asset('icons/placeholder_calendar.png') }}" alt="Placeholder image" />
						<p><span>@lang('basic.no_schedule')</span></p>
					</div>
				</div>
			</div>
		</section>
		<section class="col-xs-12 col-sm-3 float_left posts">
			<div class="all_post">
				<div>
					<h2>@lang('basic.latest_messages') <a class="view_all" href="{{ route('posts.index') }}" >@lang('basic.view_all')</a></h2>
					@if( isset($posts) && count( $posts ) > 0)
						@foreach($posts as $post)
							@php							
								$post_comment = PostController::profile($post)['post_comment'];
								$employee_post = PostController::profile($post)['employee'];
								$user_name_post = PostController::profile($post)['user_name'];
								$image_employee = PostController::profile($post)['docs']; // profilna slika							
							@endphp
							<article class="main_post">
								<a href="{{ route('posts.index',['id' =>  $post->id ]) }}">
									<span class="post_empl">
										@if($post->to_employee_id != null)
											<span class="profile_image">
												@if( is_array($image_employee) && ! empty($image_employee) )
													<img class="radius50" src="{{ URL::asset('storage/' . $user_name_post . '/profile_img/' . end($image_employee)) }}" alt="Profile image"  />
												@else
													<img class="radius50" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
												@endif

											</span>
										@endif
										<span class="post_send">									
											@if($post->to_employee_id != null)
												@if( Sentinel::getUser()->employee->id == $post->to_employee_id )
													{{ $post->employee->user['first_name'] . ' ' . $post->employee->user['last_name'] }}
												@else
													@if($post->to_employee_id != null)
														{{ $post->to_employee->user['first_name'] . ' ' . $post->to_employee->user['last_name'] }}
													
													@endif
												@endif
											@endif
											@if( $post->to_department_id != null )
												{{ $post->to_department['name'] }}
											@endif
										</span>
										@if(PostController::countComment($post->id) > 0)<span class="count_coments">{{ PostController::countComment($post->id) }}</span>@endif
									<span class="post_time">{{ date('d.m. H:i',strtotime($post->updated_at)) }}</span>
									</span>
									<span class="post_text">
										{{	$post_comment['content'] }}								
									</span>
								</a>
							</article>
						@endforeach
					@else
						<div class="placeholder">
							<img class="" src="{{ URL::asset('icons/placeholder_noticeadd.png') }}" alt="Placeholder image" />
							<p>@lang('basic.no_post')</p>
						</div>
					@endif
				</div>				
			</div>
		</section>
    @else
		<div class="row">
			<div class="jumbotron">
				<h1>@lang('welcome.welcome')</h1>
				<p>@lang('welcome.must_login')</p>
				<p><a class="btn btn-primary btn-lg" href="{{ route('auth.login.form') }}" role="button">@lang('welcome.login')</a></p>
			</div>
		</div>
	@endif
<script>
	$( function () {
		if($('.comming_agenda'))
		$('.placeholder').show();
		
		var placeholder_height =  $('.placeholder img').height();
        $('.calendar .comming_agenda').height(placeholder_height + 60);
		
		$.getScript( '/../js/event_click.js');
		
	});
	$( window ).resize(function() {
		var placeholder_height = $('.placeholder_cal>img').height();
		$('.placeholder_cal>p').height(placeholder_height);
	});
</script>
@stop