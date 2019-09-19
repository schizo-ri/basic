<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Jelena Juras">
		<meta name="description" content="Portal za zaposlenike">
        <title>@yield('title')</title>
		<meta name="csrf-token" content="{{ csrf_token() }}">

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
		
		<!-- CSS -->
		<link rel="stylesheet" href="{{ URL::asset('/../css/layout.css') }}"/>
		<link rel="stylesheet" href="{{ URL::asset('/../css/welcome.css') }}"/>
		<link rel="stylesheet" href="{{ URL::asset('/../css/basic.css') }}"/>
		<link rel="stylesheet" href="{{ URL::asset('/../css/modal.css') }}"/>
		<link rel="stylesheet" href="{{ URL::asset('/../css/dashboard.css') }}"/>
		<link rel="stylesheet" href="{{ URL::asset('/../css/index.css') }}"/>
		<link rel="stylesheet" href="{{ URL::asset('/../css/responsive_top_nav.css') }}"/>
		<link rel="stylesheet" href="{{ URL::asset('css/calendar.css') }}"/>
		<!--Jquery -->
		<script src="{{ URL::asset('/../node_modules/jquery/dist/jquery.min.js') }}"></script>
		<script src="{{ URL::asset('/../node_modules/chart.js/dist/Chart.js') }}"></script>
		@stack('stylesheet')
		<?php 
			use App\Http\Controllers\PostController;
		?>
    </head>
    <body>
		<section>
			@if (Sentinel::check())
			<header class="header_nav">
				<nav class="nav_top col-md-12 ">
					<a class="" href="#"><img src="{{ URL::asset('storage/company_img/logo.png')}}" alt="company_logo"/></a>
						<ul class="nav_ul float_right">
							@if (Sentinel::check())
								@if(Sentinel::inRole('administrator'))
									<li><a id="open-admin" ><img class="img_button" src="{{ URL::asset('icons/flash.png') }}" alt="messages"/></a></li>
								@endif
								<li><a href="{{ action('UserController@edit_user', Sentinel::getUser('id')) }}"><img class="img_button" src="{{ URL::asset('icons/settings.png') }}" alt="messages"/></a></li>
								<li><a href="{{ route('auth.logout') }}">
									<img class="img_button" src="{{ URL::asset('icons/logout.png') }}" alt="messages"/>
								</a></li>
							@else
								<li><a href="{{ route('auth.login.form') }}">@lang('welcome.login')</a></li>
								<li><a href="{{ route('auth.register.form') }}">@lang('welcome.register')</a></li>
							@endif
						</ul>
				</nav>
				<section class="padd_0">
					<div class="topnav col-sm-12 col-md-12 col-lg-12 col-xl-4 padd_0 float_left" id="myTopnav">
						<div class="col-sm-12 col-md-12 col-lg-6 float_left">
							<a class="button_nav active" href="{{ route('dashboard') }}">
								<span class="button_nav_img arrow_dashboard"></span>
								<p>@lang('welcome.home')</p>
							</a>
						</div>
						@if(Sentinel::getUser()->hasAccess(['posts.view']) || in_array('posts.view', $permission_dep) )
							<div class="col-sm-12 col-md-12 col-lg-6 float_left">
								<a class="button_nav load_button post_button isDisabled  {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('posts.index') }}">
									<span class="button_nav_img messages">
										<span class="line_btn">
											@if(PostController::countComment_all() >0)<span class="count_comment">{{ PostController::countComment_all() }}</span>@endif  
										</span>
									</span>
									<p>Poruke</p>
								</a>
							</div>
						@endif
						@if(Sentinel::getUser()->hasAccess(['documents.view']) || in_array('documents.view', $permission_dep) )
							<div class="col-sm-12 col-md-12 col-lg-6 float_left">
								<a class="button_nav load_button doc_button isDisabled {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('documents.index') }}">
									<span class="button_nav_img documents"></span>
									<p>Dokumenti</p>
								</a>
							</div>
						@endif
						@if(Sentinel::getUser()->hasAccess(['events.view']) || in_array('events.view', $permission_dep) )
							<div class="col-sm-12 col-md-12 col-lg-6 float_left">
								<a class="button_nav load_button event_button isDisabled" href="{{ route('events.index') }}" >
									<span class="button_nav_img calendar"></span>
									<p>Kalendar</p>
								</a>
							</div>
						@endif
						@if(Sentinel::getUser()->hasAccess(['questionnaires.view']) || in_array('questionnaires.view', $permission_dep) )
							<div class="col-sm-12 col-md-12 col-lg-6 float_left">
								<a class="button_nav load_button quest_button isDisabled {!! !Sentinel::getUser()->employee ? 'not_employee' : '' !!}" href="{{ route('questionnaires.index') }}">
									<span class="button_nav_img questionnaire"></span>
									<p>@lang('questionnaire.questionnaires')</p>	
								</a>
							</div>
						@endif
						@if(Sentinel::getUser()->hasAccess(['ads.view']) || in_array('ads.view', $permission_dep) )
							<div class="col-sm-12 col-md-12 col-lg-6 float_left">
								<a class="button_nav load_button ads_button isDisabled" href="{{ route('oglasnik') }}">
									<span class="button_nav_img ads"></span>
									<p>Njuškalo</p>	
								</a>	
							</div>
						@endif
						<a href="javascript:void(0);" class="icon" onclick="myTopNav()">
							<i class="fa fa-bars"></i>
						</a>
					</div>
				</section>
			</header>
			@endif
			<div class="container col-sm-12 col-md-12 col-lg-12">
				@yield('content')

				@if(Sentinel::check()  && Sentinel::inRole('administrator'))
					<section class="admin-panel padd_0">
						<ul class="" >
							@if (Sentinel::inRole('administrator'))
								<li class="{{ Request::is('users*') ? 'active' : '' }}"><a href="{{ route('users.index') }}" id="click_users">@lang('basic.users')</a></li>
								<li class="{{ Request::is('roles*') ? 'active' : '' }}"><a href="{{ route('roles.index') }}">@lang('basic.roles')</a></li>
								<li class="{{ Request::is('employees*') ? 'active' : '' }}"><a href="{{ route('employees.index') }}">@lang('basic.employees')</a></li>
								<li class="{{ Request::is('departments*') ? 'active' : '' }}"><a href="{{ route('departments.index') }}">@lang('basic.departments')</a></li>
								<!--<li class="{{ Request::is('department_roles*') ? 'active' : '' }}"><a href="{{ route('department_roles.index') }}">@lang('basic.department_roles')</a></li>-->
								<li class="{{ Request::is('works*') ? 'active' : '' }}"><a href="{{ route('works.index') }}">@lang('basic.works')</a></li>
								<li class="{{ Request::is('questionnaires*') ? 'active' : '' }}"><a href="{{ route('questionnaires.index') }}">@lang('questionnaire.questionnaires')</a></li>
								<li class="{{ Request::is('companies*') ? 'active' : '' }}"><a href="{{ route('companies.index') }}">@lang('basic.company')</a></li>
								<li class="{{ Request::is('emailings*') ? 'active' : '' }}"><a href="{{ route('emailings.index') }}">@lang('basic.emailings')</a></li>
							@endif
							@if (Sentinel::inRole('superadmin'))
								<li class="{{ Request::is('tables*') ? 'active' : '' }}"><a href="{{ route('tables.index') }}">@lang('basic.tables')</a></li>
							@endif
						</ul>
					</section>
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
		<link rel="stylesheet" href="{{ URL::asset('node_modules/jquery-modal/jquery.modal.min.css') }}" type="text/css" >

		<!-- Scripts -->
	    <script src="{{URL::asset('/../js/nav_active.js') }}"></script>
		<script src="{{URL::asset('/../js/open_admin.js') }}"></script>
		<script src="{{URL::asset('/../js/efc_toggle.js') }}"></script>
		<script src="{{URL::asset('/../js/set_height.js') }}"></script>
		<script src="{{ URL::asset('/../js/collaps.js') }}"></script>
		<script src="{{URL::asset('/../js/calendar.js') }}"></script>

		<!-- Pignoise calendar -->
		<script src="{{ URL::asset('/../node_modules/moment/moment.js') }}"></script>
		<script src="{{ URL::asset('/../node_modules/pg-calendar/dist/js/pignose.calendar.min.js') }}"></script>

		<!-- Datatables -->
		<script type="text/javascript" src="{{ URL::asset('/../dataTables/datatables.min.js') }}"></script>
		<script type="text/javascript" src="{{ URL::asset('/../dataTables/Buttons-1.5.6/js/buttons.print.min.js') }}"></script>

		<!-- Summernote js -->
		<link href="{{ URL::asset('/../node_modules/summernote/summernote-lite.css') }}" rel="stylesheet">
		<script src="{{ URL::asset('/../node_modules/summernote/summernote-lite.min.js') }}" ></script>
		<!-- Modal js -->
		<script src="{{URL::asset('/../js/open_modal.js') }}"></script>
		
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
