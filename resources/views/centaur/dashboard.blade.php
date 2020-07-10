@extends('Centaur::layout')

@section('title',config('app.name'))
@php
	use App\Http\Controllers\PostController;
	use App\Http\Controllers\AbsenceController;
	
	$thisYear = date('Y');
	$count_requests =  AbsenceController::countRequest();
@endphp
@section('content')
	@if (Sentinel::check())
		<section class="col-xs-12 col-sm-12 col-md-12 col-lg-4 float_left">
			@include('Centaur::side_noticeboard')
		</section>
		<div class="user_header col-xs-12 col-sm-12 col-md-12 col-lg-8" >
			<div class="info ">
				<div class="col-md-3 float_left user_header_info">
				
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
							<span>@if(isset($data_absence)) {{ $data_absence['ukupnoPreostalo']  }}@endif</span>
							<span>@lang('absence.vacation')<br>@lang('absence.days_left')</span>
						</p>
						<p>
							<span>{!! isset($data_absence[$thisYear]) && count($data_absence[$thisYear]) > 0 ? $data_absence[$thisYear]['dani_zahtjeva'] : 0 !!}</span>
							<span>@lang('absence.vacation')<br>@lang('absence.days_used') <br> @lang('absence.this_year')</span>
						</p>
					</div>
					@endif
				</div>
				@if(isset($employee))
				<div class="col-md-9 padd_0 float_left salary ">
					<span class="efc_hide">@lang('basic.hide_salery')<img class="radius50" src="{{ URL::asset('icons/arrow_up.png') }}" alt="arrow" /></span>
					<span class="efc_show">@lang('basic.show_salery')<img class="radius50" src="{{ URL::asset('icons/arrow_down.png') }}" alt="arrow" /></span>
					<div class="efc col-md-12">
						<p class="col-4"><span class="salery_show" >{{ number_format($employee->brutto, 2, ',', '.') }} kn</span><span class="salery_hidden">- Kn</span>@lang('basic.yearly_salary')</p>
						<p class="col-4"><span class="salery_show">{{ number_format($employee->brutto /12, 2, ',', '.') }}  kn</span><span class="salery_hidden">- Kn</span>@lang('basic.monthly_cost')</p>
						<p class="col-4"><span class="salery_show">{{ number_format($employee->effective_cost, 2, ',', '.')}}  kn</span><span class="salery_hidden">- Kn</span>@lang('basic.hourly_rate')</p>
					</div>
					<div class="col-md-12 padd_0 float_left layout_button ">
						<button class=""><a href="{{ route('absences.create') }}" rel="modal:open">
							<span>
								<span class="img beach"></span>
								<p>@lang('absence.request_vacation')</p>
							</span></a>
						</button>
						<button class="" ><a href="{{ route('tasks.index') }}" rel="modal:open">
							<span>
								<span class="img task"></span>
								<p>@lang('calendar.tasks')</p>
							</span></a>
						</button>
						@if(in_array('Locco vožnja', $moduli))  
							<button class="" ><a href="{!! $locco_active->first() ? route('loccos.edit', $locco_active->first()->id ) : route('loccos.create') !!}" rel="modal:open">
								<span>
									<span class="img car"></span>
										<p>{!! $locco_active->first()  ? __('basic.edit_locco') : __('basic.add_locco') !!}</p>
								</span></a>
							</button>
						@endif
						@if(in_array('Putni nalozi', $moduli))  
							<button class="" ><a href="{{ route('travel_orders.show', $employee->id) }}" rel="modal:open">
								<span>
									<span class="img travel"></span>
										<p>{{  __('basic.travel_orders') }}</p>
								</span></a>
							</button>
						@endif
						@if(in_array('Locco vožnja', $moduli))  
							<button class="" ><a href="{{ route('fuels.create')}}" rel="modal:open">
								<span>
									<span class="img fuel"></span>
										<p>{{  __('basic.fuel') }}</p>
									</span>
										
								</span></a>
							</button>
						@endif
						<button class="button_absence" >
							<a href="{{ route('absences.index') }}" >
								<span>
									<span class="img all_req">
										<p>@lang('absence.view_all_request')</p>
										@if($count_requests >0)
											<span class="count_request">{{ $count_requests }}</span>
										@endif
									</span>
								</span>
							</a>
						</button>
					</div>
				</div>
				@endif
			</div>
		</div>
		<section class="col-xs-12 col-sm-12 col-md-12 col-lg-5 float_left calendar">
			<div>
				<div id="calendar">
					<div class="box">
						<div class="header">
							<h2>@lang("calendar.calendar")
								@if(in_array('Kalendar', $moduli) )
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
					@if(in_array('Kalendar', $moduli))
						@if(isset($employee))
							<a class="btn btn-primary btn-lg btn-new" href="{{ route('events.create') }}" rel="modal:open">
								<i style="font-size:11px" class="fa">&#xf067;</i>
							</a>
						@endif
						<h3 class="agenda_title">@lang('calendar.your_agenda') </h3>
						<div class="all_agenda">
							@if((isset($events) && count($events)>0) || ( isset($tasks) && count($tasks) > 0) )
								@foreach($events->take(5) as $event)
									<p class="agenda" id="{{ $event->date }}">
										<span class="agenda_mark"><span class="green"></span></span>
										<span class="agenda_time">{{ date('H:i',strtotime($event->time1)) }}<br><span>{{ date('H:i',strtotime($event->time2)) }}</span></span>
										<span class="agenda_comment">{{ $event->description }}</span>
									</p>
								@endforeach
								@foreach($tasks->take(5) as $task)
									<p class="agenda" id="{{ $task->date }}">
										<span class="agenda_mark"><span class="green"></span></span>
										<span class="agenda_time">{{ date('H:i',strtotime($task->time1)) }}<br><span>{{ date('H:i',strtotime($task->time2)) }}</span></span>
										<span class="agenda_comment">{{ $task->title . ' - ' }} {{ $task->description }}{!! $task->car_id ? ', ' . $task->car['registration']  : '' !!}</span>
									</p>
								@endforeach
							@else
								<div class="placeholder">
									<img class="" src="{{ URL::asset('icons/placeholder_calendar.png') }}" alt="Placeholder image" />
									<p><span>@lang('basic.no_schedule')</span></p>
								</div>
							@endif
						</div>
					@endif
					
				</div>
			</div>
		</section>
		<section class="col-xs-12 col-sm-12 col-md-12 col-lg-3 float_left posts">
			<div class="all_post">
				<div>
					@if( in_array('Poruke',$moduli) && isset($posts) && count( $posts ) > 0)
						<h2>
							@lang('basic.latest_messages')
							@if(PostController::countComment_all() > 0)
								<span class="count_coments">{{ PostController::countComment_all() }}</span>
							@endif   
							<a class="view_all" href="{{ route('posts.index') }}" >@lang('basic.view_all')</a></h2>
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
		$.getScript( '/../js/event_click.js');
	});
</script>
@stop