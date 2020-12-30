<!DOCTYPE html>
<html lang="hr">
	<head>
        <meta charset="utf-8">
		@include('Centaur::mail_style')
	</head>
	<body>
        <div style="width: 500px;max-width:100%;margin:auto;" id="mail_template">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="header" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_header : '' !!}">
                @if(count($text_header) > 0)
					@foreach ($text_header as $text)
						<p>{{ $text }}</p>
					@endforeach
				@else
					<p>Odluka uprave o izostanku</p>
				@endif
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_body : '' !!}">
				@if(count($text_body) > 0)
					@foreach ($text_body as $text)
						<p>{{ $text }}</p>
					@endforeach
				@else
					<h4>Odlukom Uprave promjenjen je zahtjev za djelatnika {{ $absence->employee->user['first_name']   . ' ' . $absence->employee->user['last_name'] }}, {{ $absence->absence['name'] }} </h4>
					<h4>
						@if($absence->absence['mark'] !=  "BOL")
							<p>za  {{ date("d.m.Y", strtotime($absence->start_date)) . ' do ' . date("d.m.Y", strtotime( $absence->end_date)) . ' - ' . $dani_zahtjev . ' ' . __ ('absence.days') }}</p>
						@elseif($absence->absence['mark'] == "BOL")
							<p>@lang ('absence.sicknes')</p>
							<p>{{ date("d.m.Y", strtotime($absence->start_date)) . ' do ' . date("d.m.Y", strtotime( $absence->end_date)) . ' - ' . $dani_zahtjev . ' dana' }}</p>
						@endif
						@if( $absence->absence['mark'] == "IZL")
							<p>{{  'od ' . $absence->start_time  . ' - ' .  $absence->end_time }}</p>
						@endif
					</h4>
					<p><b>@lang('basic.comment'): </b></p>
					<p class="marg_20">{{ $absence->comment }}</p>
					<p><b>Odluku donio: {{ Sentinel::getUser()->first_name . ' ' .  Sentinel::getUser()->last_name }}</b></p>
				@endif
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="footer"  style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_footer : '' !!}">
				@if(count($text_footer) > 0)
					@foreach ($text_footer as $text)
						<p>{{ $text }}</p>
					@endforeach
				@endif
				@if(file_exists('../public/storage/company_img/logo.png'))
					<img src="{{ URL::asset('storage/company_img/logo.png')}}" alt="company_logo" class="company_logo"/>
				@else
					<p>{{ config('app.name') }}</p>
				@endif
            </div>
        </div>
	</body>
</html>
		
