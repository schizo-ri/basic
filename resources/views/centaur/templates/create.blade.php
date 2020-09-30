<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
		<meta charset="UTF-8">
		<meta name="description" content="Portal za zaposlenike">
		<meta name="author" content="Jelena Juras">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>@lang('basic.add_notice')</title>
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
		<!-- JS modal -->
			<link rel="stylesheet" href="{{ URL::asset('/../node_modules/jquery-modal/jquery.modal.min.css') }}" type="text/css" />
		<!-- CSS -->
			<link rel="stylesheet" href="{{ URL::asset('/../css/campaign.css') }}"/>
			<link rel="stylesheet" href="{{ URL::asset('/../css/modal.css') }}"/>
			<link rel="stylesheet" href="{{ URL::asset('/../css/basic.css') }}"/>
		<!-- ICON -->
			<link rel="shortcut icon" href="{{ asset('img/icon.ico') }}">
		<!--Unlayer -->
			<script src="https://editor.unlayer.com/embed.js"></script>
		<!--Jquery -->
			<script src="{{ URL::asset('/../node_modules/jquery/dist/jquery.min.js') }}"></script>
		
		@stack('stylesheet')
	</head>
	<body>
        <form class="form_template template_create" id="form_template" accept-charset="UTF-8" role="form" method="post" action="{{ route('templates.store') }}" enctype="multipart/form-data" >
			<header>
				{{ csrf_field() }}
				<div class="unlayer container">
					<button  class="btn-submit" {{-- (click)="exportHtml()" --}}>@lang('basic.save')</button>
					<email-editor></email-editor>
					{{-- 	<input class="btn-submit" type="submit" value="{{ __('basic.save')}}"> --}}
					<a class="btn-back" href="{{ url()->previous() .'/#templates' }}">
						@lang('basic.back')
					</a>
				</div>
				<input name="module" value="basic" hidden/>
				<h3 class="panel-title">@lang('basic.create_template')</h3>
				<!-- <select class="template">
					<option selected disabled></option>

					@foreach($templates as $template)
						<option value="{{ $template->id }}">{{ $template->id }}</option>
					@endforeach
				</select> -->
				<div class="form-group title">
					<input name="title" id="title" placeholder="{{__('basic.title')}}" value="{{ old('title') }}" require />
				</div>
			</header>
            <main class="main_campaign">
				<div class="{!! count($templates) > 0 ? 'col-md-12 col-xl-10' : 'col-12' !!}" id="editor-container"></div>
                @if(count($templates) > 0 )
                    <div class="col-md-12 col-xl-2" id="template-container"></div>
                @endif
            </main>
        </form>
        <span hidden class="locale" >{{ App::getLocale() }}</span>
        <span hidden class="dataArrTemplates">{{ ($templates) }}</span>
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
		<script src="{{URL::asset('/../js/open_modal.js') }}"></script>
		<script src="{{URL::asset('/../js/template_create.js') }}"></script>

		<script>
			
		</script>
        @if(session()->has('modal'))
			<script>
				$('.row.notification').modal();
			</script>
		@endif
		@stack('script')		
    </body>
</html>