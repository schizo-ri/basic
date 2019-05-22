<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
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
    </head>
    <body>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/">ICOM</a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse links" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        @if (Sentinel::check())
                            <li><p class="navbar-text">{{ Sentinel::getUser()->email }}</p></li>
                            <li><a href="{{ route('auth.logout') }}">@lang('welcome.logout')</a></li>
                        @else
                            <li><a href="{{ route('auth.login.form') }}">@lang('welcome.login')</a></li>
                            <li><a href="{{ route('client_requests.create') }}">@lang('welcome.register')</a></li>
                        @endif
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
		
        <div class="container layout col-xs-12 col-sm-12 col-md-12 col-lg-12">
           @if (Sentinel::check())
				<aside class="side-bar col-xs-12 col-sm-12 col-md-2 col-lg-2">
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
							<ul class="side-nav">
								<li class="{{ Request::is('/dashboard') ? 'active' : '' }}"><a href="{{ route('dashboard') }}">@lang('welcome.home')</a></li>
								@if (Sentinel::check())
									@if(Sentinel::getUser()->hasAccess(['users.view']))
										<li class="{{ Request::is('users*') ? 'active' : '' }}"><a href="{{ route('users.index') }}">@lang('basic.users')</a></li>
									@endif
									@if(Sentinel::getUser()->hasAccess(['roles.view']))
										<li class="{{ Request::is('roles*') ? 'active' : '' }}"><a href="{{ route('roles.index') }}">@lang('basic.roles')</a></li>
									@endif
									@if(Sentinel::getUser()->hasAccess(['clients.view']))
										<li class="{{ Request::is('clients*') ? 'active' : '' }} {!! !Sentinel::getUser()->hasAccess(['clients.view']) ? 'hide' : ''  !!}"><a href="{{ route('clients.index') }}">@lang('basic.clients')</a></li>
									@endif
									@if(Sentinel::getUser()->hasAccess(['modules.view']))
										<li class="{{ Request::is('modules*') ? 'active' : '' }} {!! !Sentinel::getUser()->hasAccess(['modules.view']) ? 'hide' : ''  !!}"><a href="{{ route('modules.index') }}">@lang('basic.modules')</a></li>
									@endif
									@if(Sentinel::getUser()->hasAccess(['client_requests.view']))
										<li class="{{ Request::is('client_requests*') ? 'active' : '' }} {!! !Sentinel::getUser()->hasAccess(['client_requests.view']) ? 'hide' : ''  !!}"><a href="{{ route('client_requests.index') }}">@lang('clients.requests')</a></li>
									@endif
								@endif
							</ul>
					</div><!-- /.navbar-collapse -->
				</aside>
			@endif
			@if (Sentinel::check())
				<main class="main col-xs-12 col-sm-12 col-md-10 col-lg-10">
			@else
				<main class="main col-xs-12 col-sm-12 col-md-12 col-lg-12">
			@endif
				@include('Centaur::notifications')
				@yield('content')
			</main>
        </div>

        <!-- Latest compiled and minified Bootstrap JavaScript -->
        <!-- Bootstrap js -->
		<script src="{{ URL::asset('node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
		<script src="{{ URL::asset('node_modules/popper.js/dist/umd/popper.min.js') }}"></script>
        <!-- Restfulizer.js - A tool for simulating put,patch and delete requests -->
        <script src="{{ asset('restfulizer.js') }}"></script>
		
		<!-- Datatables -->
		<script type="text/javascript" src="{{ URL::asset('node_modules/DataTables/datatables.min.js') }}"></script>
		
		<!--Awesome icons -->
		<script src="{{ URL::asset('node_modules/@fortawesome/fontawesome-free/js/all.min.js') }}"></script>
		
		@stack('script')
    </body>
</html>