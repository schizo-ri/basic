<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="{{ URL::asset('node_modules/bootstrap/dist/css/bootstrap.min.css') }}"/>

		<!-- CSS -->
		<link rel="stylesheet" href="{{ URL::asset('css/layout.css') }}"/>
		<link rel="stylesheet" href="{{ URL::asset('css/welcome.css') }}"/>
		<link rel="stylesheet" href="{{ URL::asset('css/basic.css') }}"/>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
		<link rel="stylesheet" href="{{ URL::asset('css/welcome.css') }}" type="text/css" >
		<link rel="stylesheet" href="{{ URL::asset('css/modal.css') }}"/>
		<link rel="stylesheet" href="{{ URL::asset('/../css/modal.css') }}"/>

		<!-- JS modal -->
		<link rel="stylesheet" href="{{ URL::asset('node_modules/jquery-modal/jquery.modal.min.css') }}" type="text/css" />

		<!--Jquery -->
		<script src="{{ URL::asset('node_modules/jquery/dist/jquery.min.js') }}"></script>

		@stack('stylesheet')
		
    </head>
    <body>
		<section class="welcome_page">
			<section class="col-sm-7 col-md-7 col-lg-7 col-xl-7 float_left welcome">
				<div class="">
					<h2 class="title">Get Started</h2>
					<h4>Use your e-mail and password to log in</h4>
					<form class="form_login" accept-charset="UTF-8" role="form" method="post" action="{{ route('auth.login.attempt') }}">
						<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}"><img class="" src="{{ URL::asset('icons/email.png') }}" alt="email"  />
							<input class="form-control form_email" placeholder="E-mail" name="email" type="email" value="{{ old('email') }}">
							{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group  {{ ($errors->has('password')) ? 'has-error' : '' }}">
							<input class="form-control form_email" placeholder="{{ __('welcome.password')}}" name="password" type="password" value=""><img class="" src="{{ URL::asset('icons/unlock.png') }}" alt="unlock"  />
							{!! ($errors->has('password') ? $errors->first('password', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="checkbox">
							<label class="remember">@lang('welcome.remember')
								<input type="checkbox" name="remember" value="true" {{ old('remember') == 'true' ? 'checked' : ''}}="checked">
								<span class="checkmark"></span>
							</label>
							<a href="{{ route('auth.password.request.form') }}" class="forgot_pass" type="submit" rel="modal:open">@lang('welcome.forgot')</a>
						</div>
						{{ csrf_field() }}
						<input class="btn-login" type="submit" value="{{ __('welcome.login') }}">
					</form>
					<p class="terms">By log in you agree to Intranets <span>terms and conditions</span></p>
				<!--	<li><a href="{{ route('auth.register.form') }}" rel="modal:open" >@lang('welcome.register')</a></li>-->
				</div>
			</section>
			<section class="col-sm-5 col-md-5 col-lg-5 col-xl-5 float_left welcome_right">
				<div class="first_img"><img src="{{ URL::asset('icons/mask2.png')}}" alt="mask image"/></div>
				<div class="second_img"><img src="{{ URL::asset('icons/mask.png')}}" alt="mask image"/></div>
				<div class="third_img"><img src="{{ URL::asset('icons/mask3.png')}}" alt="mask image"/></div>
				@if(file_exists('../public/storage/company_img/logo.png'))
					<img src="{{ URL::asset('storage/company_img/logo.png')}}" class="company_logo" alt="company_logo"/>
				@else 
					<img src="{{ URL::asset('icons/intranet_logo.png')}}" class="company_logo" alt="company_logo"/>
				@endif
				
			</section>
		</section>		
		@include('Centaur::notifications', ['modal' => 'true'])
		@stack('script')
		<!-- Jquery modal -->
		<script src="{{ URL::asset('node_modules/jquery-modal/jquery.modal.min.js') }}"></script>
		<link rel="stylesheet" href="{{ URL::asset('node_modules/jquery-modal/jquery.modal.min.css') }}" type="text/css" >
		
		<!-- Modal js -->
		<script src="{{URL::asset('/../js/open_modal.js') }}"></script>
		<script>
			@if(session()->has('modal'))
				$('.row.notification').modal();
			@endif
		</script>
		@stack('script')
    </body>
</html>
