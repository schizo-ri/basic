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
		<form class="form_sequence" accept-charset="UTF-8" role="form" method="post" action="{{ route('campaign_sequences.store') }}">
			<section class="header_campaign">
				<input type="hidden" name="campaign_id" id="campaign_id" value="{{  $this_campaign->id }}">
				<textarea name="text_html" id="text_html" hidden ></textarea>
				<textarea name="text_json" id="text_json" hidden ></textarea>
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
					<h3 class="panel-title">@lang('basic.add_sequence')  {{$this_campaign->name }}{{--  {!! $this_campaign ? count($campaign_sequences)+1 : '' !!} --}} </h3>
				</header>
				<section class="campaign_set">
					<div class="campaign_date col-sm-12 col-md-4 float_left">
						<label for="start_date">@lang('absence.start_date')</label>
						<input class="" name="start_date" id="start_date" type="date" maxlength="255" value="{!! $this_campaign ?  $this_campaign->start_date : old('start_date') !!}" required />
						{!! ($errors->has('start_date') ? $errors->first('start_date', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="campaign_period col-sm-12 col-md-4 float_left {{ ($errors->has('send_interval'))  ? 'has-error' : '' }} " id="period" >
						<label class="label_period">@lang('basic.repetition_period')</label>
						<select  class="period" name="send_interval" value="{{ old('send_interval') }}" >
							<option value="no_repeat" >@lang('basic.no_repeat')</option>
							<option value="every_day" >@lang('basic.every_day')</option>
							<option value="once_week" >@lang('basic.once_week')</option>
							<option value="once_month" >@lang('basic.once_month')</option>
							<option value="once_year" >@lang('basic.once_year')</option>
							<option value="customized" >@lang('basic.customized')</option>
						</select>
					</div>
					<div class="campaign_interval  col-sm-12 col-md-4 float_left" id="interval"  >
						<label class="label_custom_interal">@lang('basic.custom_interal')</label>
						<input class=" input_interval" type="number" name="interval" value="" />
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
				<div id="editor-container"></div>
			</main>
		</form>

		<span hidden class="locale" >{{ App::getLocale() }}</span>

		<!-- Latest compiled and minified Bootstrap JavaScript -->
        <!-- Bootstrap js -->
		<script src="{{ URL::asset('/../node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
		<script src="{{ URL::asset('/../node_modules/popper.js/dist/umd/popper.min.js') }}"></script>
        <!-- Restfulizer.js - A tool for simulating put,patch and delete requests -->
        <script src="{{ asset('/../restfulizer.js') }}"></script>
		
		
		<script>	
			console.log(location);
			console.log(document.referrer);

			
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

		<!-- tinymce js -->
		<script src="{{ URL::asset('/node_modules/tinymce/tinymce.min.js') }}" ></script>
		
		@if(session()->has('modal'))
			<script>
				$('.row.notification').modal();
			</script>
		@endif
		@stack('script')		
    </body>
</html>