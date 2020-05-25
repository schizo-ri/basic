<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
		<meta charset="UTF-8">
		<meta name="description" content="Portal za zaposlenike">
		<meta name="author" content="Jelena Juras">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>@yield('title')</title>
        <!-- Bootstrap - Latest compiled and minified CSS -->
		<link rel="stylesheet" href="{{ URL::asset('/../node_modules/bootstrap/dist/css/bootstrap.min.css') }}"/>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
		
		<!--Awesome icons -->
		<link rel="stylesheet" href="{{ URL::asset('/../node_modules/@fortawesome/fontawesome-free/css/all.min.css') }}"/>
		
		<!-- Datatables -->
		<link rel="stylesheet" href="{{ URL::asset('/../dataTables/datatables.css') }}"/>
		<!-- JS modal -->
		<link rel="stylesheet" href="{{ URL::asset('/../node_modules/jquery-modal/jquery.modal.min.css') }}" type="text/css" />
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">

		<!-- CSS -->
		<link rel="stylesheet" href="{{ URL::asset('/../css/layout.css') }}"/>
		<link rel="stylesheet" href="{{ URL::asset('/../css/welcome.css') }}"/>
		<link rel="stylesheet" href="{{ URL::asset('/../css/basic.css') }}"/>
		<link rel="stylesheet" href="{{ URL::asset('/../css/modal.css') }}"/>
		<link rel="stylesheet" href="{{ URL::asset('/../css/dashboard.css') }}"/>
		<link rel="stylesheet" href="{{ URL::asset('/../css/index.css') }}"/>
		<link rel="stylesheet" href="{{ URL::asset('/../css/calendar.css') }}"/>
		<link rel="stylesheet" href="{{ URL::asset('/../css/admin.css') }}"/>
		
		<!-- ICON -->
		<link rel="shortcut icon" href="{{ asset('img/icon.ico') }}">

		<!--Jquery -->
		<script src="{{ URL::asset('/../node_modules/jquery/dist/jquery.min.js') }}"></script>

		<script src="{{ URL::asset('/../js/jquery-ui.js') }}"></script>
		<script src="{{ URL::asset('/../node_modules/chart.js/dist/Chart.js') }}"></script>
		
		<!-- Pusher -->
		<script src="https://js.pusher.com/6.0/pusher.min.js"></script>

		@stack('stylesheet')
    </head>
    <body>
		<?php 
			use App\Http\Controllers\PostController;
			use App\Http\Controllers\DashboardController;
			use App\Http\Controllers\CompanyController;
			$permission_dep = DashboardController::getDepartmentPermission();
			$moduli = CompanyController::getModules();
		?>
		@if (Sentinel::check())
			<section>
				<header class="header_nav">
					<nav class="nav_top col-md-12 topnav">
						<span class="logo_icon" >
							<i class="img_logo fas fa-bars"></i>
							<p class="button_nav_text">
								@if(file_exists('../public/storage/company_img/logo.png'))
									<img src="{{ URL::asset('storage/company_img/logo.png')}}" alt="company_logo"/>
								@else 
									<img src="{{ URL::asset('icons/myIntranet.png')}}" alt="company_logo"/>
								@endif
							</p>
						</span>
						<a class="" href="{{ route('dashboard') }}">
							@if(file_exists('../public/storage/company_img/logo.png'))
								<img src="{{ URL::asset('storage/company_img/logo.png')}}" alt="company_logo"/>
							@else 
								<img src="{{ URL::asset('icons/intranet_logo.png')}}" alt="company_logo"/>
							@endif
						</a>
						<ul class="nav_ul float_right">
							@if (Sentinel::check())
								@if(Sentinel::inRole('administrator'))
									<li><a id="open-admin" href="{{ route('admin_panel') }}" title="{{ __('basic.open_admin')}}"  >
										<img class="img_button" src="{{ URL::asset('icons/flash.png') }}" alt="messages" title="{{ __('basic.open_admin')}}" /></a>
									</li>
								@endif
								<li><a href="{{ action('UserController@edit_user', Sentinel::getUser('id')) }}" class="{!! !Sentinel::getUser()->employee ? 'isDisabled' : '' !!}" title="{{ __('basic.user_data')}}" >
									<img class="img_button" src="{{ URL::asset('icons/settings.png') }}" alt="messages"/></a>
								</li>
								<li><a href="{{ route('auth.logout') }}" title="{{ __('welcome.logout')}}" >
									<img class="img_button" src="{{ URL::asset('icons/logout.png') }}" alt="messages"/>
								</a></li>
								<li class="icon"><a href="javascript:void(0);" class="icon" onclick="myTopNav()">
									<i class="fa fa-bars" style="color: #A7BBEE"></i>
								</a></li>
							@else
								<li><a href="{{ route('auth.login.form') }}">@lang('welcome.login')</a></li>
								<li><a href="{{ route('auth.register.form') }}">@lang('welcome.register')</a></li>
							@endif
						</ul>
					</nav>
					<section class="section_top_nav" id="section_top_nav">
						<span class="close_topnav">
							@if(file_exists('../public/storage/company_img/logo.png'))
								<img src="{{ URL::asset('storage/company_img/logo.png')}}" alt="company_logo"/>
							@else 
								<img src="{{ URL::asset('icons/myIntranet.png')}}" alt="company_logo"/>
							@endif
							<i class=" fas fa-times"></i></span>
						<div class="topnav " id="myTopnav">
							<div class="">
								<a class="button_nav dashboard_button active" href="{{ route('dashboard') }}" title="{{ __('welcome.dashboard') }}">
									<span class="button_nav_img arrow_dashboard"></span>
									<p class="button_nav_text">@lang('welcome.home')</p>
								</a>
							</div>

							@if(in_array('Poruke', $moduli))
								@if(Sentinel::getUser()->hasAccess(['posts.view']) || in_array('posts.view', $permission_dep) )
									<div class="div_posts">
										<a class="button_nav load_button posts_button isDisabled  {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('posts.index') }}" title="{{ __('basic.posts') }}">
											<span class="button_nav_img messages"><!-- <img class="" src="{{ URL::asset('../icons/messages_grey.png') }}" alt="Profile image"  /> -->
												<span class="line_btn">
													@if(PostController::countComment_all() >0)<span class="count_comment">{{ PostController::countComment_all() }}</span>@endif  
												</span>
											</span>
											<p class="button_nav_text">@lang('basic.posts')</p>
										</a>
									</div>
								@endif
							@endif
							@if(in_array('Dokumenti', $moduli))
								@if(Sentinel::getUser()->hasAccess(['documents.view']) || in_array('documents.view', $permission_dep) )
									<div class="">
										<a class="button_nav load_button documents_button isDisabled {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('documents.index') }}" title="{{ __('basic.documents') }}">
											<span class="button_nav_img documents "><!-- <img class="" src="{{ URL::asset('../icons/documents_grey.png') }}" alt="Profile image"  /> --></span>
											<p class="button_nav_text">@lang('basic.documents')</p>
										</a>
									</div>
								@endif
							@endif
							@if(in_array('Kalendar', $moduli))
								@if(Sentinel::getUser()->hasAccess(['events.view']) || in_array('events.view', $permission_dep) )
									<div class="">
										<a class="button_nav load_button events_button isDisabled  {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('events.index') }}" title="{{ __('calendar.events') }}" >
											<span class="button_nav_img calendar"><!-- <img class="" src="{{ URL::asset('../icons/calendar_grey.png') }}" alt="Profile image"  /> --></span>
											<p class="button_nav_text">@lang('calendar.calendar')</p>
										</a>
									</div>
								@endif
							@endif
							<!--Provjera kod superadmina ima li korisnik modul-->
							@if(in_array('Ankete', $moduli))
								@if(Sentinel::getUser()->hasAccess(['questionnaires.view']) || in_array('questionnaires.view', $permission_dep) )
									<div class="">
										<a class="button_nav load_button questionnaires_button isDisabled {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('questionnaires.index') }}"  title="{{ __('questionnaire.questionnaires') }}">
											<span class="button_nav_img questionnaire"><!-- <img class="" src="{{ URL::asset('../icons/list_grey.png') }}" alt="Profile image"  /> --></span>
											<p class="button_nav_text">@lang('questionnaire.questionnaires')</p>	
										</a>
									</div>
								@endif
							@endif
							@if(in_array('Oglasnik',$moduli))
								@if(Sentinel::getUser()->hasAccess(['ads.view']) || in_array('ads.view', $permission_dep) )
									<div class="">
										<a class="button_nav load_button oglasnik_button isDisabled {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('oglasnik') }}" title="{{ __('basic.ads') }}">
											<span class="button_nav_img ads"><!-- <img class="" src="{{ URL::asset('../icons/ads_grey.png') }}" alt="Profile image"  /> --></span>
											<p class="button_nav_text">@lang('basic.ads')</p>	
										</a>	
									</div>
								@endif
							@endif
							@if(in_array('Kampanje', $moduli))
								@if(Sentinel::getUser()->hasAccess(['campaigns.view']) || in_array('campaigns.view', $permission_dep) )
									<div class="">
										<a class="button_nav load_button campaigns_button isDisabled  {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('campaigns.index') }}" title="{{ __('basic.campaigns') }}">
											<span class="button_nav_img campaign"><!-- <img class="" src="{{ URL::asset('../icons/messages_grey.png') }}" alt="Profile image"  /> --></span>
											<p class="button_nav_text">@lang('basic.campaigns')</p>	
										</a>	
									</div>
								@endif
							@endif
							@if(in_array('Pogodnosti', $moduli))							
								@if(Sentinel::getUser()->hasAccess(['benefits.view']) || in_array('benefits.view', $permission_dep) )
									<div class="">
										<a class="button_nav load_button benefits_button isDisabled {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('benefits.index') }}" title="{{ __('basic.benefits') }}">
											<span class="button_nav_img benefits"><!-- <img class="" src="{{ URL::asset('../icons/messages_grey.png') }}" alt="Profile image"  /> --></span>
											<p class="button_nav_text">@lang('basic.benefits')</p>
										</a>	
									</div>
								@endif
							@endif
						</div>
					</section>
				</header>
				<div class="container col-sm-12 col-md-12 col-lg-12">
					@if(Sentinel::check())				
						@if(Sentinel::getUser()->employee)
							@yield('content')
						@else
							<section class="padd_20">
								<h2>@lang('ctrl.not_registered')</h2>
							</section>
						@endif
					@endif
				</div>
				<span hidden class="locale" >{{ App::getLocale() }}</span>
				<span id="hiddenId"></span>
				@include('Centaur::notifications', ['modal' => 'true'])
			</section>
		@endif
		<span hidden id="employee_id">{!! Sentinel::getUser()->employee ? Sentinel::getUser()->employee->id : null !!}</span>
		<!-- Scripts -->
			<script>
				// Enable pusher logging - don't include this in production
				//Pusher.logToConsole = true;
				var employee_id = $('#employee_id').text();

				var pusher = new Pusher('4a26c2ee9ed5420ded10', {
										cluster: 'eu'
										});

				var channel = pusher.subscribe('message_receive');
				channel.bind('my-event', function(data) {
					if(employee_id == data.show_alert_to_employee) {
						if(location.pathname != "/posts") {
						//	alert(JSON.stringify(data.message));
						  	$('.all_post').load(location.origin + ' .all_post>div');
						}
					}
				});
			</script>
			<!-- Latest compiled and minified Bootstrap JavaScript -->
			<!-- Bootstrap js -->
			<script src="{{ URL::asset('/../node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
			<script src="{{ URL::asset('/../node_modules/popper.js/dist/umd/popper.min.js') }}"></script>
			<!-- Restfulizer.js - A tool for simulating put,patch and delete requests -->
			<script src="{{ asset('/../restfulizer.js') }}"></script>
			
			<!--Awesome icons -->
			<script src="{{ URL::asset('/../node_modules/@fortawesome/fontawesome-free/js/all.min.js') }}"></script>
		
			<!-- Jquery modal -->
			<script src="{{ URL::asset('/../node_modules/jquery-modal/jquery.modal.min.js') }}"></script>

			<!-- Scripts -->
			<script src="{{URL::asset('/../js/nav_active.js') }}"></script>
			<script src="{{URL::asset('/../js/open_admin.js') }}"></script>
			<script src="{{URL::asset('/../js/efc_toggle.js') }}"></script>
			<script src="{{URL::asset('/../js/set_height.js') }}"></script>
			<script src="{{URL::asset('/../js/calendar.js') }}"></script>

			<!-- Pignoise calendar -->
			<script src="{{ URL::asset('/../node_modules/moment/moment.js') }}"></script>
			<script src="{{ URL::asset('/../node_modules/pg-calendar/dist/js/pignose.calendar.min.js') }}"></script>

			<!-- Datatables -->
			<script src="{{ URL::asset('/../dataTables/datatables.min.js') }}"></script>
			
			<!-- tinymce js -->
			<script src="{{ URL::asset('/node_modules/tinymce/tinymce.min.js') }}" ></script>
			
			@if(session()->has('modal'))
				<script>
					$("#modal_notification").modal();
					$('.row.notification').modal();
					$('#schedule_modal').modal();
				</script>
			@endif

		<!-- End Scripts -->
		@stack('script')		
    </body>
</html>
