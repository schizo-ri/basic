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
		<script src="{{ URL::asset('/../node_modules/chart.js/dist/Chart.js') }}"></script>
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
		<section>
			@if (Sentinel::check())
				<header class="header_nav">
					<nav class="nav_top col-md-12 topnav">
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
					<section class="section_top_nav padd_0">
						<div class="topnav col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 padd_0 float_left" id="myTopnav">
							<div class="col-sm-12 col-md-6 col-lg-6 float_left">
								<a class="button_nav active" href="{{ route('dashboard') }}">
									<span class="button_nav_img arrow_dashboard"></span>
									<p>@lang('welcome.home')</p>
								</a>
							</div>

							@if(in_array('Poruke', $moduli))
								@if(Sentinel::getUser()->hasAccess(['posts.view']) || in_array('posts.view', $permission_dep) )
									<div class="col-sm-12 col-md-6 col-lg-6 float_left div_posts">
										<a class="button_nav load_button posts_button isDisabled  {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('posts.index') }}">
											<span class="button_nav_img messages">
												<span class="line_btn">
													@if(PostController::countComment_all() >0)<span class="count_comment">{{ PostController::countComment_all() }}</span>@endif  
												</span>
											</span>
											<p>@lang('basic.posts')</p>
										</a>
									</div>
								@endif
							@endif
							@if(in_array('Dokumenti', $moduli))
								@if(Sentinel::getUser()->hasAccess(['documents.view']) || in_array('documents.view', $permission_dep) )
									<div class="col-sm-12 col-md-6 col-lg-6 float_left">
										<a class="button_nav load_button documents_button isDisabled {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('documents.index') }}">
											<span class="button_nav_img documents"></span>
											<p>@lang('basic.documents')</p>
										</a>
									</div>
								@endif
							@endif
							@if(in_array('Kalendar', $moduli))
								@if(Sentinel::getUser()->hasAccess(['events.view']) || in_array('events.view', $permission_dep) )
									<div class="col-sm-12 col-md-6 col-lg-6 float_left">
										<a class="button_nav load_button events_button isDisabled  {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('events.index') }}" >
											<span class="button_nav_img calendar"></span>
											<p>@lang('calendar.calendar')</p>
										</a>
									</div>
								@endif
							@endif
							<!--Provjera kod superadmina ima li korisnik modul-->
							@if(in_array('Ankete', $moduli))
								@if(Sentinel::getUser()->hasAccess(['questionnaires.view']) || in_array('questionnaires.view', $permission_dep) )
								
									<div class="col-sm-12 col-md-6 col-lg-6 float_left">
										<a class="button_nav load_button questionnaires_button isDisabled {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('questionnaires.index') }}">
											<span class="button_nav_img questionnaire"></span>
											<p>@lang('questionnaire.questionnaires')</p>	
										</a>
									</div>
								@endif
							@endif
							@if(in_array('Oglasnik',$moduli))
								@if(Sentinel::getUser()->hasAccess(['ads.view']) || in_array('ads.view', $permission_dep) )
									<div class="col-sm-12 col-md-6 col-lg-6 float_left">
										<a class="button_nav load_button oglasnik_button isDisabled {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('oglasnik') }}">
											<span class="button_nav_img ads"></span>
											<p>@lang('basic.ads')</p>	
										</a>	
									</div>
								@endif
							@endif
							@if(in_array('Kampanje', $moduli))
								@if(Sentinel::getUser()->hasAccess(['campaigns.view']) || in_array('campaigns.view', $permission_dep) )
									<div class="col-sm-12 col-md-6 col-lg-6 float_left">
										<a class="button_nav load_button campaigns_button isDisabled  {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('campaigns.index') }}">
											<span class="button_nav_img ads"></span>
											<p>@lang('basic.campaigns')</p>	
										</a>	
									</div>
								@endif
							@endif
							@if(in_array('Pogodnosti', $moduli))							
								@if(Sentinel::getUser()->hasAccess(['benefits.view']) || in_array('benefits.view', $permission_dep) )
									<div class="col-sm-12 col-md-6 col-lg-6 float_left">
										<a class="button_nav load_button benefits_button isDisabled {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('benefits.show', 1) }}">
											<span class="button_nav_img ads"></span>
											<p>@lang('basic.benefits')</p>
										</a>	
									</div>
								@endif
							@endif
						</div>
					</section>
				</header>
			@endif
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
			<span id="hiddenId"></span>
			@include('Centaur::notifications', ['modal' => 'true'])
		</section>
		<script>
			function myTopNav() {
				var x = $("#myTopnav");
				if (x.hasClass("responsive") && x.hasClass("topnav") ) {
					x.removeClass("responsive");
				} else {
					x.addClass("responsive");
				} 
			}

			$("a[rel='modal:open']").addClass('disable');

			$( document ).ready(function() {
				$("a[rel='modal:open']").removeClass('disable');
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
		@stack('script')		
    </body>
</html>
