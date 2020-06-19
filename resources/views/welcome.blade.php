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
					<a href="{{ url('/home') }}">Home</a>
				@else
					<a href="{{ route('auth.login.form') }}">Login</a>
					@if (Route::has('register'))
						<a href="{{ route('auth.register.form') }}">Register</a>
					@endif
                @endauth
			</div>
			<div class="row">
			<div class="title m-b-md">
					PROIZVODNJA
			</div>
		
		@stack('script')
    </body>
</html>
