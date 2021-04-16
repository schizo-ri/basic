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
				@if( $absence->absence['mark'] == "BOL")
					@if( $absence->end_date )
						<p>Zatvoreno bolovanje</p>
					@else
						<p>Otvoreno bolovanje</p>
					@endif
				@else
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
					<p>Zahtjev</p>
					@endif
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
						@if(isset($variable[$key]) ) 
							<p style="{{ $style }}">{{ sprintf($text, $variable[$key]) }}</p>
						@else
							<p style="{{ $style }}">{{ $text }}</p>
						@endif
					@endforeach
				@else
					<p>@lang('absence.i'), {{ $absence->employee->user['first_name']   . ' ' . $absence->employee->user['last_name'] }}</p>
					<p>
						@if( $absence->absence['mark'] !=  "BOL")
							@lang('absence.please_approve')  {{ $absence->absence['name'] }} za
							{{ date("d.m.Y", strtotime($absence->start_date)) . ' do ' . date("d.m.Y", strtotime( $absence->end_date)) . ' - ' . $dani_zahtjev . ' ' . __ ('absence.days') }}
							@if( $absence->absence['mark'] == "IZL")
								{{  'od ' . $absence->start_time  . ' - ' .  $absence->end_time }}
							@endif
						@elseif( $absence->absence['mark'] == "BOL")
							@if( $absence->end_date )
								@lang ('absence.end_sicknes')
								{{ ' Zadnji dan je ' .  date("d.m.Y", strtotime($absence->end_date)) }} 
							@else
								@lang ('absence.sicknes')
								{{ 'od ' . date("d.m.Y", strtotime( $absence->start_date))  }}
							@endif
						@endif
					</p>
					<p>@lang('basic.comment'): </p>
					<p class="marg_20">
						{{ $absence->comment }}
						@if($absence->absence['mark'] == "GO")
							<p>@lang('absence.unused') {{ $neiskoristeno_GO }} @lang('absence.vacation_days') </p>
						@endif
						@if($absence->absence['mark'] == "SLD")
							<p>@lang('absence.unused') {{ $slobodni_dani }} @lang('absence.days_off')</p>
						@endif
					</p>
				@endif
				@if($absence->absence['mark'] != "BOL")
					@if($absence->approve != null )
						<p>Zahtjev je već obrađen.</p>
						<p>Status: {!! $absence->approve == 1 ? 'odobren' : 'odbijen' !!}</p>
						<p>Ako želiš promjeniti odobrenje pošalji odobrenje, u suprotnom nije potrebno ponovno odobravati.</p>
					@endif
					<form name="contactform" method="get" target="_blank" action="{{ route('confirmation') }}">
						<input style="height: 34px;width: 100%;border-radius: 5px;" type="text" name="approve_reason" value=""><br>
						<input type="hidden" name="id" value="{{ $absence->id }}"><br>
						<input type="radio" name="approve" value="1" id="approve1" style="cursor:pointer" checked> <label for="approve1" style="cursor:pointer"> @lang('absence.approved')</label>
						<input type="radio" name="approve" value="0" id="approve0" style="padding-left:20px; cursor:pointer"> <label for="approve0" style="cursor:pointer">@lang('absence.not_approve')</label><br>
						<input type="hidden" name="email" value="1" checked><br>
						<input class="odobri marg_top_20" type="submit" value="{{ __('basic.process') }}" style="cursor:pointer">
					</form>
				@endif
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