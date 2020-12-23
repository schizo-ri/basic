<!DOCTYPE html>
<html lang="hr">
	<head>
        <meta charset="utf-8">
		@include('Centaur::mail_style')
	</head>
	<body>
        <div style="width: 500px;max-width:100%;margin:auto;" id="mail_template">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="header" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_header : '' !!}">
                <p>Odobrenje izostanka - privremeni djelatnik</p>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_body : '' !!}">
                <p>
                    @if($absence->absence['mark'] == "VIK")
                        @lang('absence.req_received')
                    @else
                        {{ __('absence.request') .' '. $absence->absence_type['name']  }}  @lang('basic.for')
                        @if($absence->absence['mark'] != "IZL")
                            {{ date("d.m.Y", strtotime($absence->start_date)) . ' do ' . date("d.m.Y", strtotime($absence->end_date)) }} 
                        @else
                            {{ date("d.m.Y", strtotime($absence->start_date)) . ' od ' . $absence->start_time . ' do ' . $absence->end_time }}</h4>
                        @endif
                    @endif
                </p>
                <p>{{ $odobrenje }}</p>
                <p>{{ $absence->approve_reason }}</p>
                <p> {{ 'Odobrio: ' . $odobrio }}</p>
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