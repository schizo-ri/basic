<!DOCTYPE html>
<html lang="hr">
	<head>
        <meta charset="utf-8">
        <style>
			body { 
				font-family: DejaVu Sans, sans-serif;
			}
			#mail_template #header, #mail_template #footer {
				height: auto;
				border: none;
				padding: 10px 15px;
				text-align: center;
				clear: both;
				overflow-wrap: break-word;
			}
			#mail_template #header {
				font-size: 15px;
				font-weight: bold;
			}
			#mail_template #footer {
				font-size: 12px;
			}
			#mail_template #body {
				height: auto;
				border: none;
				font-size: 13px;
				padding: 15px;
				clear: both;
				overflow-wrap: break-word;
				overflow: hidden;
				line-height: 16px;
			}
			.MsgBody .Object {
				margin: 0;
    			display: inline-block;
			}
			.MsgBody .Object-hover {
				margin:0;
			}
			.odobri, .link { 
				cursor: pointer;
				min-width: 100px;
				height: auto;
				background-color: white;
				border: 1px solid #cccccc;
				border-radius: 5px;
				box-shadow: 5px 5px 8px #cccccc;
				text-align: center !important;
				padding: 10px;
				background: #007cc3 !important;
				color: white !important;
				font-weight: bold;
				font-size: 12px;
				margin: 10px;
				float: left;
				margin-left: 0;
			}
			.link{
				min-width:100px;
				background-color:white;
				border: 1px solid #cccccc;
				border-radius: 5px;
				box-shadow: 5px 5px 8px #cccccc;
				text-align: center !important;
				padding:10px;
				color:#007cc3 !important;
				font-weight:bold;
				margin:15px;
				cursor:pointer;
			}
			.marg_20 {
				margin-bottom:20px;
			}
			.marg_top_20 {
				margin-top:20px;
			}
			.company_logo {
                max-height: 20px;
				max-width: 85px;
				margin: 10px 0;
			}
			div p {
				margin: 0;
				padding: 5px;
			}
		</style>
	</head>
	<body>
        <div style="width: 500px;max-width:100%;margin:auto;" id="mail_template">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="header" style="{!! $mail_style ? $mail_style->style_header : '' !!}">
				@if(isset( $text_header ) && count($text_header) > 0)
					@foreach ($text_header as $key => $text)
						@php
							$style = '';
							if( $mail_style && $mail_style->style_header_input ) {
								$el_style = explode('|', $mail_style->style_header_input );
							}
							if( isset($el_style) ) {
								if( count($el_style) > 0 && isset( $el_style[ $key ])) {
									$style = $el_style[ $key ];
								} else {
									$style = $el_style[0];
								}
							}
						@endphp
						<p style="{{ $style }}">{{ $text }}</p>
					@endforeach
				@else
					<p>Zahtjev za ispravak izostanka</p>
				@endif
            </div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body" style="{!! $mail_style ? $mail_style->style_body : '' !!}">
				@if( isset($text_body) && $text_body && count($text_body) > 0)
					@foreach ($text_body as $key => $text)
						@php
							$style = '';
							if( $mail_style && $mail_style->style_body_input ) {
								$el_style = explode('|', $mail_style->style_body_input );
							}
							if( isset($el_style) ) {
								if( count($el_style) > 0 && isset($el_style[ $key ])) {
									$style = $el_style[ $key ];
								} else {
									$style = $el_style[0];
								}
							}
						@endphp
						<p style="{{ $style }}">{{ $text }}</p>
					@endforeach
				@else
					<p>@lang('absence.i'), {{ $absence->employee->user['first_name']   . ' ' . $absence->employee->user['last_name'] }}</p>
					<p>molim izmjenu zahtjeva </p>
					<p>Stari zahtjev: {{ $absence->absence['name'] }} - {{ date("d.m.Y", strtotime($absence->start_date)) }} {!! $absence->end_date ?  ' do ' . date("d.m.Y", strtotime( $absence->end_date)) : '' !!}
						@if( $absence->absence['mark'] == "IZL")
							{{  'od ' . $absence->start_time  . ' - ' .  $absence->end_time }}
						@endif
					</p>
					<p>Zahtjev za promjenom:  {{ $type }} - {{ date("d.m.Y", strtotime($request->start_date)) }} {!! $request->end_date ? ' do ' . date("d.m.Y", strtotime( $request->end_date)) : '' !!} {{ ' - ' . $dani_zahtjev . ' ' . __ ('absence.days') }}
						@if( $request->type == "IZL")
							{{  'od ' . $request->start_time  . ' - ' .  $request->end_time }}
						@endif
					</p>
					<p class="marg_20">
						{{ $request->comment }}
						@if($request->type == "GO")
							<p>@lang('absence.unused') {{ $neiskoristeno_GO }} @lang('absence.vacation_days') </p>
						@endif
						@if($request->type == "SLD")
							<p>@lang('absence.unused') {{ $slobodni_dani }} @lang('absence.days_off')</p>
					@endif
					</p>
				@endif
				<form class="contactform" role="form" method="post" name="myForm" accept-charset="UTF-8" action="{{ route('absences.update', $absence->id ) }}" target="_blank" >
					<input name="employee_id" type="hidden" value="{{  $request->employee_id }}" />
					<input name="type" type="hidden" value="{{ $request->type }}" />
					<input name="start_date" type="hidden" value="{{ $request->start_date }}" >
					<input name="end_date" type="hidden" value="{{ $request->end_date }}" >
					<input name="start_time" type="hidden" value="{{ $request->start_time }}" >
					<input name="end_time" type="hidden" value="{{ $request->end_time }}" >
					<input name="comment" type="hidden" value="{{ $request->comment }}" >
					<input name="email" type="hidden" value="DA" >
					<input name="_token"  type="hidden" value="{{ csrf_token() }}">
					<input name="_method" type="hidden" value="PUT" >
				<!-- 	<input name="email" type="hidden"  value="DA"> -->
					<input class="odobri marg_top_20" type="submit" value="{{ __('basic.edit')}}" id="stil1" style="cursor:pointer">
				</form>
            </div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="footer"  style="{!! $mail_style ? $mail_style->style_footer : '' !!}">
				@if( isset($text_footer) && $text_footer && count($text_footer) > 0)
					@foreach ($text_footer as $key => $text)
						@php
							$style = '';
							if( $mail_style && $mail_style->style_footer_input ) {
								$el_style = explode('|', $mail_style->style_footer_input );
							}
							if( isset($el_style) ) {
								if( count($el_style) > 0 && isset($el_style[ $key ])) {
									$style = $el_style[ $key ];
								} else {
									$style = $el_style[0];
								}
							}
						@endphp
						<p style="{{ $style }}">{{ $text }}</p>
					@endforeach
				@endif
                @if(file_exists(public_path() . '/storage/company_img/logo.png'))
                    <img src="{{ URL::asset('storage/company_img/logo.png')}}" alt="company_logo" class="company_logo"/>
                @else
                    <p>{{ config('app.name') }}</p>
                @endif
            </div>
        </div>
	</body>
</html>