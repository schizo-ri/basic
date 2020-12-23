<!DOCTYPE html>
<html lang="hr">
	<head>
        <meta charset="utf-8">
		@include('Centaur::mail_style')
	</head>
	<body>
        <div style="width: 500px;max-width:100%;margin:auto;" id="mail_template">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="header" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_header : '' !!}">

            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_body : '' !!}">
				<h4>{{ __ ('emailing.with_day') . ' ' . date("d.m.Y", strtotime($employee->reg_date)) . ' ' . __ ('emailing.employed_worker') . ' ' . $employee->user['first_name'] . ' ' . $employee->user['last_name'] . ' ' .  __ ('emailing.in_the_workplace') . ' ' . $employee->work['name'] }}</h4>
				<br/>
				<div style="margin-bottom: 20px">
					<p><b>@lang('basic.comment'): </b></p>
					<p style="padding-left: 20px">{{ $employee->comment }}</p>
				</div>
				<div style="margin-bottom: 20px">
					<p><b>IT odjel: </b>Molim kontaktirati {{ $employee->user['first_name'] . ' ' . $employee->user['last_name'] }} za sve potrebne informacije.</p>
				</div>
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