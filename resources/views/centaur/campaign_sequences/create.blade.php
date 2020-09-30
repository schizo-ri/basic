<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
		<meta charset="UTF-8">
		<meta name="description" content="Portal za zaposlenike">
		<meta name="author" content="Jelena Juras">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>@lang('basic.add_sequence')</title>
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
		<link rel="stylesheet" href="{{ URL::asset('/../css/basic.css') }}"/>
		<!-- ICON -->
		<link rel="shortcut icon" href="{{ asset('img/icon.ico') }}">
		<script src="//editor.unlayer.com/embed.js"></script>
		<!--Jquery -->
		<script src="{{ URL::asset('/../node_modules/jquery/dist/jquery.min.js') }}"></script>
		@stack('stylesheet')
	</head>
	<body>
		<form class="form_sequence create" accept-charset="UTF-8" role="form" method="post" action="{{ route('campaign_sequences.store') }}">
			<section class="header_campaign">
				<input type="hidden" name="campaign_id" id="campaign_id" value="{{  $this_campaign->id }}">
				<header>
					{{ csrf_field() }}
					<div class="unlayer container">
						<button  class="btn-submit" {{-- (click)="exportHtml()" --}}>@lang('basic.save')</button>
						<email-editor></email-editor>
						{{-- 	<input class="btn-submit" type="submit" value="{{ __('basic.save')}}"> --}}
						<a class="btn-back" href="{{ url()->previous() }}">
							@lang('basic.back')
						</a>
					</div>
					<h3 class="panel-title">@lang('basic.add_sequence') - {{$this_campaign->name }}{{--  {!! $this_campaign ? count($campaign_sequences)+1 : '' !!} --}} </h3>
				</header>
				<section class="campaign_set">
					<div class="campaign_subject col-sm-12 col-md-6 float_left {{ ($errors->has('subject'))  ? 'has-error' : '' }}">
						<label for="subject">@lang('basic.subject')</label>
						<input type="text" name="subject" maxlength="255" id="subject" value="{{ old('subject') }}" required>
						{!! ($errors->has('subject') ? $errors->first('subject', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="campaign_interval col-sm-12 col-md-3 float_left" id="interval"  >
						<label class="label_custom_interal">@lang('basic.time_shift')</label>
						<input class="input_interval" type="number" name="interval" value="" />
						<select  class=" select_period" name="period"  >
							<option value="day" >@lang('basic.day')</option>
							<option value="week">@lang('basic.week')</option>
							<option value="month">@lang('basic.month')</option>
							<option value="year">@lang('basic.year')</option>
						</select>
					</div>
				</section>
			</section>
			<main class="main_campaign">
				<div class="{!! count($templates) > 0 ? 'col-10' : 'col-12' !!}" id="editor-container"></div>
                @if(count($templates) > 0 )
                    <div class="col-2" id="template-container"></div>
                @endif
			</main>
		</form>
		<span hidden class="locale" >{{ App::getLocale() }}</span>
		<span hidden class="dataArrTemplates">{{ ($templates) }}</span>
		<textarea name="text_html" id="text_html" hidden ></textarea>
        <textarea name="text_json" id="text_json" hidden ></textarea>
		<!-- Latest compiled and minified Bootstrap JavaScript -->
        <!-- Bootstrap js -->
		<script src="{{ URL::asset('/../node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
		<script src="{{ URL::asset('/../node_modules/popper.js/dist/umd/popper.min.js') }}"></script>
        <!-- Restfulizer.js - A tool for simulating put,patch and delete requests -->
        <script src="{{ asset('/../restfulizer.js') }}"></script>
		
		<script>	
		//	$.getScript( '/../js/validate.js');	 DUPLO SNIMI
		</script>

		<!--Awesome icons -->
		<script src="{{ URL::asset('/../node_modules/@fortawesome/fontawesome-free/js/all.min.js') }}"></script>
	
		<!-- Jquery modal -->
		<script src="{{ URL::asset('/../node_modules/jquery-modal/jquery.modal.min.js') }}"></script>
		
		<!--Unlayer modal -->
		{{-- <script src="{{ URL::asset('/../node_modules/react-email-editor/umd/react-email-editor.min.js') }}"></script> --}}

		<!-- Scripts -->
		<script src="{{URL::asset('/../js/open_modal.js') }}"></script>
		<script src="{{URL::asset('/../js/campaign_sequences.js') }}"></script>

	
		@if(session()->has('modal'))
			<script>
				$('.row.notification').modal();
			</script>
		@endif
		@stack('script')		
    </body>
</html>