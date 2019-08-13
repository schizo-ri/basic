@extends('Centaur::layout')

@section('title', __('welcome.dashboard'))
<link rel="stylesheet" href="{{ URL::asset('css/dashboard.css') }}"/>
@php
	use App\Models\Employee;
	use App\Http\Controllers\PostController;
	use App\Http\Controllers\CompanyController;

	if (Sentinel::check()) {
		//dohvaća dopuštenja odjela za korisnika
		try{
			$permission_dep = explode(',', Sentinel::getUser()->employee->work->department->departmentRole->toArray()[0]['permissions']);
		} catch (Exception $e) {
			$permission_dep = array();
		} 

		//dohvaća module firme
		$moduli = CompanyController::getModules();

		$employee = Sentinel::getUser()->employee;

		$docs = '';
		if($employee) {
			$user_name = explode('.',strstr($employee->email,'@',true));
			if(count($user_name) == 2) {
				$user_name = $user_name[1] . '_' . $user_name[0];
			} else {
				$user_name = $user_name[0];
			}

			$path = 'storage/' . $user_name . "/profile_img/";
			if(file_exists($path)){
				$docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
			}else {
				$docs = '';
			}
		}
	}
@endphp
@section('content')
	@if (Sentinel::check())
		<div class="user_header col-sm-12 col-md-12 col-lg-12 col-xl-8  float_left " >
			<div class="info">
				<div class="col-md-3 float_left ">
					@if($docs)
						<img class="radius50" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($docs)) }}" alt="Profile image"  />
					@else
						<img class="radius50" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
					@endif
					<h2>{{ Sentinel::getUser()->first_name . ' ' . Sentinel::getUser()->last_name }}</h2>
					<p>@if($employee){{ $employee->work['name'] }}@endif</p>
					<div class="header_user_go">
						<p>
							<span>6</span>
							<span>Vacation<br>days left</span>
						</p>
						<p>
							<span>3</span>
							<span>Vacation<br>days used</span>
						</p>
					</div>
				</div>
				@if($employee)
				<div class="col-md-9 padd_0 float_left salary ">
					<span class="efc_hide">Hide your salary <img class="radius50" src="{{ URL::asset('icons/arrow_up.png') }}" alt="arrow" /></span>
					<span class="efc_show">Show your salary <img class="radius50" src="{{ URL::asset('icons/arrow_down.png') }}" alt="arrow" /></span>
					<div class="efc col-md-12">
						<p class="col-4"><span>{{ number_format($employee->brutto, 2, ',', '.') }} kn</span>Yearly salary</p>
						<p class="col-4"><span>{{ number_format($employee->brutto /12, 2, ',', '.') }}  kn</span>Company's monthly cost</p>
						<p class="col-4"><span>{{ number_format($employee->effective_cost, 2, ',', '.')}}  kn</span>Hourly rate</p>
					</div>
					<div class="col-md-12 padd_0 float_left layout_button ">
						<button class=""><a href="{{ route('absences.create', ['type' => 'GO']) }}" rel="modal:open">
							<span>
								<span class="img beach"></span>
								<p>Request for Vacation days</p></a>
							</span>
						</button>
						<button class="" ><a href="{{ route('absences.create') }}" rel="modal:open">
							<span>
								<span class="img overtime"></span>
								<p>Request for overtimes</p></a>
							</span>
						</button>
						<button class="" ><a href="{{ route('absences.create', ['type' => 'BOL']) }}" rel="modal:open">
							<span>
								<span class="img sick"></span>
								<p>Sick leave</p></a>
							</span>
						</button>
						<button class="" ><a href="{{ route('absences.index') }}" >
							<span>
								<span class="img all_req"><p>View all requests</p></span></a>
							</span>
						</button>
					</div>
				</div>
				@endif
			</div>
		</div>
		@include('Centaur::side_noticeboard')
		<section class="col-md-12 col-lg-5 float_left calendar">
			<div>
				<h2>@lang('calendar.calendar') <a class="view_all" href="{{ route('events.index') }}">@lang('basic.view_all')</a></h2>
				<div class="cal_days">
					<p class="date active">
						<span class="month">July</span>
						<span class="day">01</span>
						<span class="week_day">thu</span>
					</p>
					<p class="date">
						<span class="month">July</span>
						<span class="day">02</span>
						<span class="week_day">thu</span>
					</p>
					<p class="date">
						<span class="month">July</span>
						<span class="day">03</span>
						<span class="week_day">thu</span>
					</p>
					<p class="date">
						<span class="month">July</span>
						<span class="day">04</span>
						<span class="week_day">thu</span>
					</p>
					<p class="date">
						<span class="month">July</span>
						<span class="day">05</span>
						<span class="week_day">thu</span>
					</p>
					<p class="date">
						<span class="month">July</span>
						<span class="day">06</span>
						<span class="week_day">thu</span>
					</p>
					<p class="date">
						<span class="month">July</span>
						<span class="day">07</span>
						<span class="week_day">thu</span>
					</p>
					<p class="date">
						<span class="month">July</span>
						<span class="day">08</span>
						<span class="week_day">thu</span>
					</p>
					<p class="date">
						<span class="month">July</span>
						<span class="day">09</span>
						<span class="week_day">thu</span>
					</p>
					<p class="date">
						<span class="month">July</span>
						<span class="day">10</span>
						<span class="week_day">thu</span>
					</p>
					<p class="date">
						<span class="month">July</span>
						<span class="day">11</span>
						<span class="week_day">thu</span>
					</p>
				</div>
				<div class="comming_agenda ">
					@if($employee)<a class="btn btn-primary btn-lg btn-new" href="{{ route('events.create') }}"" rel="modal:open">
							<i style="font-size:11px" class="fa">&#xf067;</i>
					</a>@endif
					<h3 class="agenda_title">@lang('calendar.your_agenda') </h3>
					@if(isset($events) && count($events) > 0)
						@foreach($events as $event)
							<p class="agenda">
								<span class="agenda_mark"><span class="green"></span></span>
								<span class="agenda_time">{{ date('H:i',strtotime($event->time1)) }}<br><span>{{ date('H:i',strtotime($event->time2)) }}</span></span>
								<span class="agenda_comment">{{ $event->description }}</span>
							</p>
						@endforeach
					@endif
				</div>
			</div>
		</section>
		<section class="col-md-12 col-lg-3 float_left posts">
			<div class="all_post">
				<h2>Latest messages <a class="view_all" href="{{ route('posts.index') }}" >@lang('basic.view_all')</a></h2>
				@if(isset($posts) && count( $posts))
					@foreach($posts as $post)
						<?php
							$docs = '';
							$empl = Employee::where('id',$post->employee_id)->first();
							$user_name = explode('.',strstr($empl->email,'@',true));
							if(count($user_name) == 2) {
								$user_name = $user_name[1] . '_' . $user_name[0];
							} else {
								$user_name = $user_name[0];
							}

							$path = 'storage/' . $user_name . "/profile_img/";
							if(file_exists($path)){
								$docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
							}
							$post_comment = $comments->where('post_id',$post->id)->first(); //zadnji komentar na poruku
						?>
						<article class="main_post">
							<a href="{{ route('posts.show', $post->id ) }}">
								<span class="post_empl">
									@if($docs)
										<img class="radius50" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($docs)) }}" alt="Profile image"  />
									@else
										<img class="radius50" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
									@endif
									<span class="post_send">
										@if(Sentinel::getUser()->employee->id == $post->to_employee_id )
											{{ $post->employee->user['first_name'] . ' ' . $post->employee->user['last_name'] }}
										@else
											{{ $post->to_employee->user['first_name'] . ' ' . $post->to_employee->user['last_name'] }}
										@endif
									</span>
									@if(PostController::countComment($post->id) > 0)<span class="count_coments">{{ PostController::countComment($post->id) }}</span>@endif
									<span class="post_time">13:25</span>
								</span>
								<span class="post_text">
									{{	$post_comment->content }}
								</span>
							</a>
						</article>
					@endforeach
				@endif
			</div>
		</section>

	
		<!--@if(count( $questionnaires))
			@foreach($questionnaires as $questionnaire)
				<a href="{{ route('questionnaires.show', $questionnaire->id ) }}">{{ $questionnaire->name }}</a>
			@endforeach
		@endif-->
    @else
		<div class="row">
			<div class="jumbotron">
				<h1>@lang('welcome.welcome')</h1>
				<p>@lang('welcome.must_login')</p>
				<p><a class="btn btn-primary btn-lg" href="{{ route('auth.login.form') }}" role="button">Log In</a></p>
			</div>
		</div>
	@endif
	
@stop