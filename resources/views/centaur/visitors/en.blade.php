<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
		<meta charset="UTF-8">
		<meta name="description" content="{{ Config::get('app.name') }}" >
		<meta name="author" content="Jelena Juras">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<title>Visitor</title>
		<!-- Bootstrap - Latest compiled and minified CSS -->
		<link rel="stylesheet" href="{{ URL::asset('/../node_modules/bootstrap/dist/css/bootstrap.min.css') }}"/>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <![endif]-->
		<!-- CSS -->
		<link rel="stylesheet" href="{{ URL::asset('/../css/css.css') }}"/>
		{{-- <link rel="stylesheet" href="{{ URL::asset('/../css/admin.css') }}"/> --}}
		<!-- ICON -->
		<link rel="shortcut icon" href="{{ asset('img/icon.ico') }}">
		<!--Awesome icons -->
		<link rel="stylesheet" href="{{ URL::asset('/../node_modules/@fortawesome/fontawesome-free/css/all.min.css') }}"/>
		<!--Jquery -->
		<script src="{{ URL::asset('/../node_modules/jquery/dist/jquery.min.js') }}"></script>
		<!-- Jquery modal -->
		<script src="{{ URL::asset('/../node_modules/jquery-modal/jquery.modal.min.js') }}"></script>
		@stack('stylesheet')
		@php	
			$card_id = substr($_SERVER['REQUEST_URI'],13);
		@endphp
	</head>
	<body class="body_visitors">
		<section>
			<header class="header_nav">
				<nav class="nav_top col-md-12 topnav">
					<span class="link_home"><img src="{{ URL::asset('storage/company_img/logo.png')}}" alt="company_logo"/></span>
					<ul class="nav_ul float_right">
						<li class="change_lang dropdown">
							<button class="dropbtn"><i class="fas fa-globe-americas"></i></button>
							<div class="dropdown-content">
								<a class="lang_link" href="{{ action('VisitorController@visitors_show_en',$card_id)}}"><img class="img_flag" src="{{ URL::asset('icons/flag/en-flag.png') }}" alt="flag en" title="EN" /></a>
								<a class="lang_link" href="{{ action('VisitorController@visitors_show_de',$card_id)}}"><img class="img_flag" src="{{ URL::asset('icons/flag/de-flag.png') }}" alt="flag en" title="DE" /></a>
								<a class="lang_link" href="{{ action('VisitorController@visitors_show_hr',$card_id) }}"><img class="img_flag" src="{{ URL::asset('icons/flag/hr-flag.png') }}" alt="flag hr" title="HR" /></a>
							</div>
						</li>
						{{-- <li class="language">
							<div class="lang_choose">
								<a href="#"></a>
								<img class="img_flag flag_hr" src="{{ asset('icons/flag/hr-flag.png') }}" /><img class="img_flag flag_de" src="{{ asset('icons/flag/de-flag.png') }}" /><img class="img_flag flag_en" src="{{ asset('icons/flag/en-flag.png') }}" />
							</div>			
						</li> --}}
						@if (Sentinel::check())
							<li><a href="{{ route('auth.logout') }}" title="{{ __('welcome.logout')}}" >
								<img class="img_button" src="{{ URL::asset('icons/logout.png') }}" alt="messages"/>
							</a></li>
						@endif
					</ul>
				</nav>
			</header>
			<div class="container">				
				<main class="visitors">
					<section class="hr col-md-12" >			
						<h1>Visitors safety instructions</h1>
						@include('Centaur::visitors.smjernice_en',['card_id' => $card_id])
						
						<p>* We process your personal information in accordance with Article 6 of the General Data Protection Regulation (GDPR), and in order to comply with the legal obligations of Duplico d.o.o. and protecting your key interests.</p>
						@if(! isset($_COOKIE['cookie_confirme'])  )
							<footer class="cookie">
								<span>We use cookies to ensure thet we give you the best experience on our website. If you continue to use this site, we will assume that you are happy with it.</span>
								<button class="close_cookie">OK</button>
								<a class="cookie_info" href="http://www.duplico.hr/en/privacy-protection-policy/" >Read more</a>
							</footer>
						@endif
					</section>
				</main>
				@include('Centaur::notifications', ['modal' => 'true'])
			</div>
		</section>
		<!-- Latest compiled and minified Bootstrap JavaScript -->
		<!-- Bootstrap js -->
		<script src="{{ URL::asset('/../node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
		<!-- Restfulizer.js - A tool for simulating put,patch and delete requests -->
		<script src="{{ asset('/../restfulizer.js') }}"></script>
		<!-- Jquery modal -->
		<script src="{{ URL::asset('/../node_modules/jquery-modal/jquery.modal.min.js') }}"></script>
		<script>
			$('.img_flag.flag_de').click(function(){
				url = window.location.origin + '/de' + window.location.pathname;
				window.location = url;
			});
			$('.img_flag.flag_en').click(function(){
				url = window.location.origin + '/en' + window.location.pathname;
				window.location = url;
			});
			$('.img_flag.flag_hr').click(function(){
			
			});
			$('.close_cookie').click(function(){
				$('.cookie').remove();
				document.cookie = 'cookie_confirme=Duplico_'+Math.random().toString(36).substring(7);				
			});
		</script>
		@if(session()->has('modal'))
			<script>
				$("#modal_notification").modal();
				$('.row.notification').modal();
				$('#schedule_modal').modal();
			</script>
		@endif
	</body>
</html>
