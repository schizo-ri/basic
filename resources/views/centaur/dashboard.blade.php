@extends('Centaur::layout')

@section('title',config('app.name'))
@php
	use App\Http\Controllers\PostController;
	
	$thisYear = date('Y');
 	$today = new DateTime();
	$today->modify('-1 years');
	$today->modify('-14 days');
	$key = 0;
@endphp
@section('content')
	@if (Sentinel::check())
		<section class="col-xs-12 col-sm-12 col-md-12 col-lg-4 float_left">
			@include('Centaur::side_noticeboard')
		</section>
		<div class="user_header col-xs-12 col-sm-12 col-md-12 col-lg-8" >
			<div class="info ">
				<div class="col-md-3 float_left user_header_info">
					@if(isset($profile_image) && ! empty($profile_image))
						<span class="image_prof">
							<img class="" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($profile_image)) }}" alt="Profile image" />
						</span>
					@else
						<span class="image_prof">
							<img class="radius50 " src="{{ URL::asset('img/profile.svg') }}" alt="Profile image"  />
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
				@if(isset($employee) || isset($temporaryEmployee))
					<div class="col-md-9 padd_0 float_left salary ">
						{{-- <span class="efc_hide">@lang('basic.hide_salery')<img class="radius50" src="{{ URL::asset('icons/arrow_up.png') }}" alt="arrow" /></span>
						<span class="efc_show">@lang('basic.show_salery')<img class="radius50" src="{{ URL::asset('icons/arrow_down.png') }}" alt="arrow" /></span> --}}
						{{-- <div class="efc col-md-12">
							<p class="col-4"><span class="salery_show" >{{ number_format($employee->brutto, 2, ',', '.') }} kn</span><span class="salery_hidden">- Kn</span>@lang('basic.yearly_salary')</p>
							<p class="col-4"><span class="salery_show">{{ number_format($employee->brutto /12, 2, ',', '.') }}  kn</span><span class="salery_hidden">- Kn</span>@lang('basic.monthly_cost')</p>
							<p class="col-4"><span class="salery_show">{{ number_format($employee->effective_cost, 2, ',', '.')}}  kn</span><span class="salery_hidden">- Kn</span>@lang('basic.hourly_rate')</p>
						</div> --}}
						<div class="shortcuts_container">
							<div class="" >
								<span class="shortcut">
									<span class="shortcut-text">@lang('basic.edit_shortcut') </span>
									<span class="btn-new"><i class="fas fa-pen"></i></span>
								</span> 
							</div>
							<div class="scroll_button">
								<button id="left-button-scroll" class=""><i class="fas fa-chevron-left"></i></button>
								<button id="right-button-scroll" class=""><i class="fas fa-chevron-right"></i></button>
							</div>
						</div>
						<div class="shortcuts_container">
							<div>
								<div class="profile_images">
									@if (isset($employee) && count($shortcuts) > 0 )
										@foreach ($shortcuts as $key => $shortcut)
											<span class="shortcut_box hasShortcut" >
												<a href="{{ route('shortcuts.destroy', $shortcut->id) }}" class="action_confirm btn-delete danger icon_delete" title="{{ __('basic.delete')}}" data-method="delete" data-token="{{ csrf_token() }}">
													<i class="fas fa-minus-square "></i>
												</a>
												<a class="" href="{{ $shortcut->url }}"  title="{{ __('basic.new_post')}}">
													{{ $shortcut->title }}
												</a>
											</span>
										@endforeach
									@endif
									@for ($i =  $key; $i < 10; $i++)
										<span class="profile_img new_open">
											<span>
												<span><i class="fas fa-plus"></i></span>
												<span class="shortcut">@lang('basic.add_shortcut')</span> 
											</span>
										</span>
									@endfor
								</div>
							</div>
						</div>
						<div class="col-md-12 padd_0 float_left layout_button ">
							@if(isset($employee) )
								<button class=""><a href="{{ route('absences.create') }}" rel="modal:open">
									<span>
										<span class="img beach"></span>
										<p>@lang('absence.request_vacation')</p>
									</span></a>
								</button>
								@if(in_array('Prekovremeni', $moduli))  
									<button class=""><a href="{{ route('afterhours.create') }}" rel="modal:open">
										<span>
											<span class="img clock"></span>
											<p>@lang('basic.add_afterhour')</p>
										</span></a>
									</button>
								@endif
								@if( Sentinel::inRole('administrator') || count(Sentinel::getUser()->employee->hasEmployeeTask) > 0 )
									<button class="" ><a href="{{ route('task_list') }}" rel="modal:open">
										<span>
											<span class="img task"></span>
											<p>@lang('calendar.tasks')</p>
										</span></a>
									</button>
								@endif
								@if(in_array('Locco vožnja', $moduli))  
									<button class="{!! $locco_active->first() ? 'background_red' : '' !!}">
										<a href="{!! $locco_active->first() ? route('loccos.edit', $locco_active->first()->id ) : route('loccos.create') !!}" rel="modal:open">
										<span>
											<span class="img car "></span>
												<p>{!! $locco_active->first()  ? __('basic.finish_locco') : __('basic.add_locco') !!}</p>
										</span></a>
									</button>
								@endif
								@if(in_array('Putni nalozi', $moduli))  
									<button class="" ><a href="{{ route('travel_orders.show', $employee->id) }}" class="travel_show" rel="modal:open">
										<span>
											<span class="img travel"></span>
												<p>{{  __('basic.travel_orders') }}</p>
										</span></a>
									</button>
									@endif
								@if(in_array('Locco vožnja', $moduli))  
									{{-- <button class="" ><a href="{{ route('fuels.create')}}" rel="modal:open">
										<span>
											<span class="img fuel"></span>
												<p>{{  __('basic.fuel') }}</p>
											</span>
												
										</span></a>
									</button> --}}
								@endif
								<button class="button_absence" >
									<a href="{{ route('absences.index') }}" >
										<span>
											<span class="img all_req"></span>
												<p>{{  __('absence.view_all_request') }}</p>
											</span>
										{{-- 	<span class="img all_req">
												<p>@lang('absence.view_all_request')</p>
												@if($count_requests >0)
													<span class="count_request">{{ $count_requests }}</span>
												@endif
											</span> --}}
										</span>
									</a>
								</button>
							@elseif(in_array('Privremeni', $moduli) && isset($temporaryEmployee))
								<button class=""><a href="{{ route('temporary_employee_requests.create') }}" rel="modal:open">
									<span>
										<span class="img beach"></span>
										<p>@lang('absence.request_vacation')</p>
									</span></a>
								</button>
							@endif
							<button class="">
								<a href="{{ route('radne_upute') }}" >
									<span>
										<span class="img books"></span>
										<p>@lang('basic.instructions')</p>
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
								@if( isset($employee) && in_array('Kalendar', $moduli) )
									<a class="view_all" href="{{ route('events.index') }}" >@lang("basic.view_all")</a>
								@endif
								<button id="right-button" class="scroll_right_cal"><img src="{{ URL::asset('icons/arrow_right.png') }}" alt=""></button>
								<button id="left-button" class="scroll_left_cal"><img src="{{ URL::asset('icons/arrow_left.png') }}" alt=""></button>
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
					<section>
						@if(in_array('Kalendar', $moduli))
							@if(isset($employee))
								<a class="btn btn-primary btn-lg btn-new" href="{{ route('events.create') }}" rel="modal:open">
									<i style="font-size:11px" class="fa">&#xf067;</i>
								</a>
							@endif
							<h2 class="agenda_title">@lang('calendar.your_agenda') </h2>
							<div class="all_agenda">
								@if( isset($employee) && (isset($events) && count($events)>0) || ( isset($tasks) && count($tasks) > 0) )
									@foreach($events->take(5) as $event)
										<p class="agenda" id="{{ $event->date }}">
											<span class="agenda_mark"><span class="green"></span></span>
											<span class="agenda_time">{{ date('H:i',strtotime($event->time1)) }}<br><span>{{ date('H:i',strtotime($event->time2)) }}</span></span>
											<span class="agenda_comment">{{ $event->description }}</span>
										</p>
									@endforeach
									@foreach($tasks->take(5) as $task)
										<p class="agenda" id="{{ $task->created_at }}">
											<span class="agenda_mark"><span class="green"></span></span>
										<!-- 	<span class="agenda_time">{{ date('H:i',strtotime($task->time1)) }}<br><span>{{ date('H:i',strtotime($task->time2)) }}</span></span> -->
											<span class="agenda_comment">{{ $task->task->task . ' - ' }} {{ $task->task->description }}{!! $task->car_id ? ', ' . $task->car['registration']  : '' !!}</span>
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
					</section>
					
				</div>
			</div>
		</section>
		<section class="col-xs-12 col-sm-12 col-md-12 col-lg-3 float_left posts">
			<div class="all_post">
				<div>
					<h2>
						@lang('basic.posts')
						@if( isset($countComment_all) && $countComment_all > 0)
							<span class="count_coments">{{ $countComment_all }}</span>
						@endif   
						@if(Sentinel::getUser()->hasAccess(['posts.create']) || in_array('posts.create', $permission_dep))
							<a class="btn btn-primary btn-lg btn-new" href="{{ route('posts.create') }}" rel="modal:open" title="{{ __('basic.new_post')}}">
								<i style="font-size:12px" class="fa">&#xf067;</i>
							</a>
						@endif
						@if(Sentinel::getUser()->hasAccess(['posts.view']) || in_array('posts.view', $permission_dep) )
							<a class="view_all" href="{{ route('posts.index') }}" >@lang('basic.view_all')</a>
						@endif
					</h2>
					@if( in_array('Poruke',$moduli) && isset($posts) && count( $posts ) > 0)
						@foreach($posts as $post)
							<article class="main_post">
								<a href="{{ route('posts.index',['id' =>  $post->id ]) }}">
									<span class="post_empl">
										@if($post->to_employee_id != null)
											<span class="profile_image">
												@if( is_array($post->image_employee) && ! empty($post->image_employee) )
													<img class="radius50" src="{{ URL::asset('storage/' . $post->user_name_post . '/profile_img/' . end($image_employee)) }}" alt="Profile image"  />
												@else
													<img class="radius50" src="{{ URL::asset('img/profile.svg') }}" alt="Profile image"  />
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
										@php
										//dd($post);
									@endphp
										@if( $post->countComment > 0)<span class="count_coments">{{ $post->countComment }}</span>@endif
									<span class="post_time">{{ date('d.m. H:i',strtotime( $post->updated_at )) }}</span>
									</span>
									<span class="post_text">
										{!! $post->post_comment->to_employee_id == Sentinel::getUser()->employee->id || $post->to_department_id == Sentinel::getUser()->employee->work->department->id ? '<i class="fas fa-long-arrow-alt-left red"></i>' : '<i class="fas fa-long-arrow-alt-right green"></i>' !!} 
										{{$post->post_comment->content }}	
										{!! $post->to_employee_id && $post->post_comment->status == 1 ? '<i class="fas fa-check green"></i>' : '' !!}
									</span>
									@if ($post->to_department_id != null && $post->employee_id == Sentinel::getUser()->employee->id  )
										<span class="read_post">
											@foreach ($post->comments->where('to_employee_id','<>',null) as $comment)
												@if ($comment->toEmployee->checkout == null)
													<span class="read_comment {!! $comment->status == 0? 'post_unread' : 'post_read'!!}">{!! $comment->toEmployee  ? mb_substr($comment->toEmployee->user['first_name'],0,1) . mb_substr($comment->toEmployee->user['last_name'],0,1) : $comment->id !!}</span>
												@endif
											@endforeach
										</span>
									@endif
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
@stop