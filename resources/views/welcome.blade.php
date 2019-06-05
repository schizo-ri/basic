<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
		<link rel="stylesheet" href="{{ URL::asset('css/welcome.css') }}" type="text/css" >
		
		<!--Jquery -->
		<script src="{{ URL::asset('node_modules/jquery/dist/jquery.min.js') }}"></script>
		
		@stack('stylesheet')
    </head>
    <body>
        <div class="flex-center position-ref full-height">
			<div class="top-right links">
				@auth
					<a href="{{ url('/home') }}">@lang('welcome.home')</a>
				@else
					<a href="{{ route('auth.login.form') }}">@lang('welcome.login')</a>
					@if (Route::has('auth.register.form'))
						<a href="{{ route('auth.register.form') }}">@lang('welcome.register')</a>
					@endif
                @endauth
			</div>
			<div class="content">
			<div class="title m-b-md">
				ICOM
			</div>
			<div class="links">
				<a href="#">Link1</a>
				<a href="#">Link2</a>
				<a href="#">Link3</a>
				<a href="#">Link4</a>
				<a href="#">Link5</a>
			</div>
		</div>
        </div>
		
		@stack('script')
    </body>
</html>
