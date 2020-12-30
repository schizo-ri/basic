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
					<p>Zapošljavanje novog djelatnika</p>
				@endif
            </div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_body : '' !!}">
				@if(count($text_body) > 0)
					@foreach ($text_body as $text)
						<p>{{ $text }}</p>
					@endforeach
				@else
					<h4>@lang('emailing.in_progress') {{ $employee->user['first_name'] . ' ' . $employee->user['last_name'] . ' ' .  __('emailing.in_the_workplace') . ' ' . $employee->work['name']  }}</h4>
					<div style="margin-bottom: 20px">
						<p><b>@lang('basic.comment'): </b></p>
						<p style="padding-left: 20px">{{ $employee->comment }}</p>
					</div>
					<div style="margin-bottom: 20px">
						<p><b>Pravni odjel: </b>Molim pripremiti ugovornu dokumentaciju za radno mjesto: {{ $employee->work['name'] }}</p>
					</div>
					<div style="margin-bottom: 20px">
						<p><b>Odjel nabave: </b>Molim nabaviti potrebnu radnu opremu za radno mjesto {{ $employee->work['name'] }}</p>
						<p style="padding-left: 20px">Konfekcijski broj:  {{ $employee->size }}</p>
						<p style="padding-left: 20px">Veličina cipela: {{ $employee->shoe_size }}</p>
					</div>
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