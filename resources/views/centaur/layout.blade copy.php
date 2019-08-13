<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Jelena Juras">
		<meta name="description" content="Portal za zaposlenike">
        <title>@yield('title')</title>

        <!-- Bootstrap - Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
		
		<!--Awesome icons -->
		<link rel="stylesheet" href="{{ URL::asset('node_modules/@fortawesome/fontawesome-free/css/all.min.css') }}"/>
		
		<!-- Datatables -->
		<link rel="stylesheet" href="{{ URL::asset('node_modules/DataTables/datatables.min.css') }}"/>
		
		<!-- CSS -->
		<link rel="stylesheet" href="{{ URL::asset('css/layout.css') }}"/>
		<link rel="stylesheet" href="{{ URL::asset('css/welcome.css') }}"/>
		
		<!--Jquery -->
		<script src="{{ URL::asset('node_modules/jquery/dist/jquery.min.js') }}"></script>

		@stack('stylesheet')
		<?php use App\Http\Controllers\PostController;
			  use App\Http\Controllers\CompanyController;
			  
			if (Sentinel::check()) {
				//dohvaća dopuštenja odjela za korisnika
				try{
					$permission_dep = explode(',', Sentinel::getUser()->employee->work->department->departmentRole->toArray()[0]['permissions']);
				} catch (Exception $e) {
					$permission_dep = array();
				} 
			}
			//dohvaća module firme
			$moduli = CompanyController::getModules();
		?>
    </head>
    <body>
			@if (Sentinel::check())
				<aside class="side_navbar col-sm-12 col-md-2 col-lg-2">
					<a class="navbar-brand" href="/"><img src="{{ URL::asset('storage/company_img/logo.png')}}" /></a>
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav">
							<li class="{{ Request::is('/dashboard') ? 'active' : '' }}"><a href="{{ route('dashboard') }}">@lang('welcome.home')</a></li>
							@if (Sentinel::check())
								<!-- Check department permission and role permission -->
								@if(in_array('education.view', $permission_dep) || Sentinel::getUser()->hasAccess(['education.view'])) 
									<li class="{{ Request::is('education*') ? 'active' : '' }}"><a href="{{ route('education.index') }}" class="link1">Edukacije</a></li>
								@endif
								@if(in_array('documents.view', $permission_dep) || Sentinel::getUser()->hasAccess(['documents.view'])) 
									<li class="{{ Request::is('documents*') ? 'active' : '' }}"><a href="{{ route('documents.index') }}" class="link1">Dokumenti</a></li>
								@endif
								@if(Sentinel::inRole('administrator'))
									<li class="link_admin"><a id="open-admin">Admin panel</a></li>
								@endif
								<!--Provjera kod superadmina ima li korisnik modul-->
								@if(in_array('Ankete',$moduli))
									<li class="link_ads {{ Request::is('oglasnik*') ? 'active' : '' }}"><a href="{{ route('oglasnik') }}" class="link2">Njuškalo</a></li>
								@endif
								@if(in_array('posts.view', $permission_dep) || Sentinel::getUser()->hasAccess(['posts.view'])) 
									<li class="post_count {{ Request::is('posts*') ? 'active' : '' }}"><a href="{{ route('posts.index') }}" class="link1">Poruke @if(PostController::countComment_all() >0)<span class="count_comment">{{ PostController::countComment_all() }}</span>@endif  @if(PostController::countPost_all() >0)<span class="count_comment">{{ PostController::countPost_all() }}</span>@endif</a></li>
								@endif
								@if(in_array('events.view', $permission_dep) || Sentinel::getUser()->hasAccess(['events.view'])) 
									<li class="link_event {{ Request::is('events*') ? 'active' : '' }}"><a href="{{ route('events.index') }}" class="link3">Kalendar</a></li>
								@endif
								@if(in_array('absences.view', $permission_dep) || Sentinel::getUser()->hasAccess(['absences.view'])) 
									@if(Sentinel::inRole('administrator')) 
										<li class="link_event {{ Request::is('absences*') ? 'active' : '' }}"><a href="{{ route('absences.index') }}" class="link1">@lang('absence.absences')</a></li>
									@else
										<li class="link_event {{ Request::is('absences*') ? 'active' : '' }}"><a href="{{ route('absences.show',Sentinel::getUser()->employee->id ) }}" class="link1">@lang('absence.absences')</a></li>
									@endif
								@endif
							@endif
						</ul>
					</div><!-- /.navbar-collapse -->
				</aside>
				<nav class="navbar navbar-default col-sm-12 col-md-10 col-lg-10">
			@else
				<nav class="navbar navbar-default col-sm-12 col-md-12 col-lg-12">
			@endif
					<div class="container-fluid">
						<!-- Brand and toggle get grouped for better mobile display -->
						<div class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							@if (!Sentinel::check())<a class="navbar-brand" href="/"><img src="{{ URL::asset('storage/company_img/logo.png')}}" /></a>@endif
						</div>
						<!-- Collect the nav links, forms, and other content for toggling -->
						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" >
							<ul class="admin-panel nav navbar-nav "  >
								@if (Sentinel::check() && Sentinel::inRole('administrator'))
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
								@if (Sentinel::check() && Sentinel::inRole('superadmin'))
									<li class="{{ Request::is('tables*') ? 'active' : '' }}"><a href="{{ route('tables.index') }}">@lang('basic.tables')</a></li>
								@endif
							</ul>
							<ul class="nav navbar-nav navbar-right">
								@if (Sentinel::check())
									<li><p class="navbar-text">{{ Sentinel::getUser()->email }}</p></li>
									<li><a href="{{ route('auth.logout') }}">@lang('welcome.logout')</a></li>
								@else
									<li><a href="{{ route('auth.login.form') }}">@lang('welcome.login')</a></li>
									<li><a href="{{ route('auth.register.form') }}">@lang('welcome.register')</a></li>
								@endif
							</ul>
						</div><!-- /.navbar-collapse -->
					</div><!-- /.container-fluid -->
				</nav>
			<div class="container col-sm-12 col-md-10 col-lg-10">
				@include('Centaur::notifications')
				@yield('content')
			</div>
			
			<span id="hiddenId"></span>

        <!-- Latest compiled and minified Bootstrap JavaScript -->
        <!-- Bootstrap js -->
		<script src="{{ URL::asset('node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
		<script src="{{ URL::asset('node_modules/popper.js/dist/umd/popper.min.js') }}"></script>
        <!-- Restfulizer.js - A tool for simulating put,patch and delete requests -->
        <script src="{{ asset('restfulizer.js') }}"></script>
		
		<!--Awesome icons -->
		<script src="{{ URL::asset('node_modules/@fortawesome/fontawesome-free/js/all.min.js') }}"></script>
	
		<!-- Scripts -->
		<script src="{{ URL::asset('js/filter.js') }}" ></script>
		<script src="{{ URL::asset('js/filter_table.js') }}" ></script>
		<script src="{{URL::asset('js/filter_dropdown.js') }}" ></script>
	    <script src="{{URL::asset('js/nav.js') }}"></script>
		<script src="{{URL::asset('js/open_admin.js') }}"></script>
		<!-- Datatables -->
		<script type="text/javascript" src="{{ URL::asset('dataTables/datatables.min.js') }}"></script>
		<script type="text/javascript" src="{{ URL::asset('dataTables/JSZip-2.5.0/jszip.min.js') }}"></script>
		<script type="text/javascript" src="{{ URL::asset('dataTables/pdfmake-0.1.36/pdfmake.min.js') }}"></script>
		<script type="text/javascript" src="{{ URL::asset('dataTables/pdfmake-0.1.36/vfs_fonts.js') }}"></script>
		<script type="text/javascript" src="{{ URL::asset('dataTables/Buttons-1.5.6/js/buttons.print.min.js') }}"></script>
		
		<script src="{{ URL::asset('js/datatables.js') }}"></script>
		@stack('script')
    </body>
</html>
