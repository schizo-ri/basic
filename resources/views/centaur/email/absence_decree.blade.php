<!DOCTYPE html>
<html lang="hr">
	<head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{ URL::asset('css/absence.css') }}"/>
	</head>
	@include('Centaur::mail_style')
	<body>
		<div style="width: 500px;max-width:100%;margin:auto;" id="mail_template">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="header" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_header : '' !!}">
                <p>Odluka uprave o izostanku</p>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_body : '' !!}">
				<h4>Odlukom Uprave djelatnik {{ $absence->employee->user['first_name']   . ' ' . $absence->employee->user['last_name'] }} koristi {{ $absence->absence['name'] }} </h4>
				<h4>
					@if($absence->absence['mark'] !=  "BOL")
						za 
						{{ date("d.m.Y", strtotime($absence->start_date)) . ' do ' . date("d.m.Y", strtotime( $absence->end_date)) . ' - ' . $dani_zahtjev . ' ' . __ ('absence.days') }}
					@elseif($absence->absence['mark'] == "BOL")
						@lang ('absence.sicknes')
						{{ date("d.m.Y", strtotime($absence->start_date)) . ' do ' . date("d.m.Y", strtotime( $absence->end_date)) . ' - ' . $dani_zahtjev . ' dana' }}
					@endif
					@if( $absence->absence['mark'] == "IZL")
						{{  'od ' . $absence->start_time  . ' - ' .  $absence->end_time }}
					@endif
				</h4>
				<p><b>@lang('basic.comment'): </b></p>
				<p class="marg_20">{{ $absence->comment }}</p>
				<p><b>Odluku donio: {{ Sentinel::getUser()->first_name . ' ' .  Sentinel::getUser()->last_name }}</b></p>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="footer"  style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_footer : '' !!}">
				@if(file_exists('../public/storage/company_img/logo.png'))
					<img src="{{ URL::asset('storage/company_img/logo.png')}}" alt="company_logo" class="company_logo"/>
				@else
					<p>{{ config('app.name') }}</p>
				@endif
            </div>
		</div>
	</body>
</html>