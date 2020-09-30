<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
		<meta charset="UTF-8">
		<meta name="description" content="Portal za zaposlenike">
		<meta name="author" content="Jelena Juras">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>@lang('basic.edit_sequence')</title>
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
		<form class="form_sequence edit" accept-charset="UTF-8" role="form" method="post" action="{{ route('campaign_sequences.update', $campaign_sequence->id ) }}">
			<section class="header_campaign">
				<input type="hidden" name="campaign_id" id="campaign_id" value="{{  $campaign_sequence->campaign_id }}">
				<input type="hidden" name="id" id="id" value="{{  $campaign_sequence->id }}">
				<!-- <textarea name="text_html" id="text_html" hidden >{{ $campaign_sequence->text }}</textarea>
				<textarea name="text_json" id="text_json" hidden >{{ $campaign_sequence->text_json }}</textarea> -->
				<header>
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					<div class="unlayer container">
						<button  class="btn-submit">@lang('basic.save')</button>
						<email-editor></email-editor>
						{{-- 	<input class="btn-submit" type="submit" value="{{ __('basic.save')}}"> --}}
						<a class="btn-back" href="{{ url()->previous() }}">
							@lang('basic.back')
						</a>
					</div>			
					<h3 class="panel-title">@lang('basic.edit_sequence') - {{ $campaign_sequence->campaign['name']  }}{{--  {!! $this_campaign ? count($campaign_sequences)+1 : '' !!} --}} </h3>
				</header>
				<section class="campaign_set">
					<div class="campaign_subject col-sm-12 col-md-6 float_left {{ ($errors->has('subject'))  ? 'has-error' : '' }}">
						<label for="subject">@lang('basic.subject')</label>
						<input type="text" name="subject" maxlength="255" id="subject" value="{{ $campaign_sequence->subject }}" required>
						{!! ($errors->has('subject') ? $errors->first('subject', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<!-- <div class="campaign_order col-sm-12 col-md-3 float_left {{ ($errors->has('order'))  ? 'has-error' : '' }}">
						<label for="order">@lang('basic.order')</label>
						<input type="radio" name="order" id="order1" value="1" required {!! $campaign_sequences->where('order', 1)->first() &&  $campaign_sequences->where('order', 1)->first()->order !=  $campaign_sequence->order ? 'disabled' : '' !!} {!! $campaign_sequence->order == 1 ? 'checked' : '' !!} ><label class="order_label" for="order1">1</label>
						<input type="radio" name="order" id="order2" value="2" required {!! $campaign_sequences->where('order', 2)->first() ? 'disabled' : '' !!} {!! $campaign_sequence->order == 2 ? 'checked' : '' !!} ><label class="order_label" for="order1">2</label>
						<input type="radio" name="order" id="order3" value="3" required {!! $campaign_sequences->where('order', 3)->first() ? 'disabled' : '' !!} {!! $campaign_sequence->order == 3 ? 'checked' : '' !!} ><label class="order_label" for="order1">3</label> 
						<input type="radio" name="order" id="order4" value="4" required {!! $campaign_sequences->where('order', 4)->first() ? 'disabled' : '' !!} {!! $campaign_sequence->order == 4 ? 'checked' : '' !!} ><label class="order_label" for="order1">4</label>
						<input type="radio" name="order" id="order5" value="5" required {!! $campaign_sequences->where('order', 5)->first() ? 'disabled' : '' !!} {!! $campaign_sequence->order == 5 ? 'checked' : '' !!} ><label class="order_label" for="order1">5</label>
						{!! ($errors->has('order') ? $errors->first('order', '<p class="text-danger">:message</p>') : '') !!}
					</div> -->
					<!-- <div class="campaign_date col-sm-12 col-md-4 float_left">
						<label for="start_date">@lang('absence.start_date')</label>
						<input class="" name="start_date" id="start_date" type="date" maxlength="255" value="{{ date('Y-m-d',strtotime($campaign_sequence->start_date )) }}" readonly />
						{!! ($errors->has('start_date') ? $errors->first('start_date', '<p class="text-danger">:message</p>') : '') !!}
					</div> -->
					<!-- <div class="campaign_period col-sm-12 col-md-4 float_left {{ ($errors->has('send_interval'))  ? 'has-error' : '' }} " id="period">
						<label class="label_period">@lang('basic.repetition_period')</label>
						<select  class="period" name="send_interval" value="{{ old('send_interval') }}" {!! ! is_array($send_interval) ? 'required' : 'style="display: none"' !!}>
							<option value="no_repeat"  {!! ! is_array($send_interval) && $send_interval == 'no_repeat' ? 'selected' : ''  !!} >@lang('basic.no_repeat')</option>
							<option value="every_day" {!! ! is_array($send_interval) && $send_interval == 'every_day' ? 'selected' : ''  !!} >@lang('basic.every_day')</option>
							<option value="once_week" {!! ! is_array($send_interval) && $send_interval == 'once_week' ? 'selected' : ''  !!} >@lang('basic.once_week')</option>
							<option value="once_month" {!! ! is_array($send_interval) && $send_interval == 'once_month' ? 'selected' : ''  !!} >@lang('basic.once_month')</option>
							<option value="once_year" {!! ! is_array($send_interval) && $send_interval == 'once_year' ? 'selected' : ''  !!} >@lang('basic.once_year')</option>
							<option value="customized" {!! is_array($send_interval) ? 'selected' : ''  !!} >@lang('basic.customized')</option>
						</select>
					</div> -->
					<div class="campaign_interval col-sm-12 col-md-3 float_left" id="interval"   {!! is_array($send_interval) ? 'style="display: block"' : ''  !!}>
						<label class="label_custom_interal">@lang('basic.custom_interal')</label>
						<input class="input_interval" type="number" name="interval" value="{!! is_array($send_interval) ? $send_interval['0'] : ''  !!}" {!! is_array($send_interval) ? 'required' : ''  !!}  />
						<select  class="select_period" name="period" {!! is_array($send_interval) ? 'required' : ''  !!} >
							<option value="day" {!! is_array($send_interval) && $send_interval[1] == 'day' ? 'selected' : '' !!} >@lang('basic.day')</option>
							<option value="week" {!! is_array($send_interval) && $send_interval[1] == 'week' ? 'selected' : '' !!}>@lang('basic.week')</option>
							<option value="month" {!! is_array($send_interval) && $send_interval[1] == 'month' ? 'selected' : '' !!}>@lang('basic.month')</option>
							<option value="year" {!! is_array($send_interval) && $send_interval[1] == 'year' ? 'selected' : '' !!} >@lang('basic.year')</option>
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
		<span hidden class="dataArr">{{ ($campaign_sequence->text_json) }}</span>
		<span hidden class="dataArrHtml">{{ ($campaign_sequence->text) }}</span>
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
		
		<!--Unlayer modal -->
		{{-- <script src="{{ URL::asset('/../node_modules/react-email-editor/umd/react-email-editor.min.js') }}"></script> --}}

		<!-- Scripts -->
		<script src="{{URL::asset('/../js/open_modal.js') }}"></script>
		<script src="{{URL::asset('/../js/campaign_sequences_edit.js') }}"></script>

		@if(session()->has('modal'))
			<script>
				$('.row.notification').modal();
			</script>
		@endif
		@stack('script')		
    </body>
</html>