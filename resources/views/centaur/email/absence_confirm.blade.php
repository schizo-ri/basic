<!DOCTYPE html>
<html lang="hr">
	<head>
        <meta charset="utf-8">
        @include('Centaur::mail_style')
	</head>
	<body>
        <div style="width: 500px;max-width:100%;margin:auto;" id="mail_template">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="header" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_header : '' !!}">
               <p>Status zahtjeva za izostanak</p>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_body : '' !!}">
                <h4>@lang('absence.request') {{ $absence->absence['name'] }} - {{ $absence->employee->user['first_name']   . '_' . $absence->employee->user['last_name'] }}  @lang('basic.for') 
                @if($absence->absence['mark'] != "IZL")
                    <p>{{ date("d.m.Y", strtotime($absence->start_date)) . ' do ' . date("d.m.Y", strtotime($absence->end_date)) }} </p>
                @else
                    <p>{{ date("d.m.Y", strtotime($absence->start_date)) . ' od ' . $absence->start_time . ' do ' . $absence->end_time }}</p>
                @endif
				</h4>
                <p><b>{{ $odobrenje }}</b></p>
                <p><b>{{ $absence->approve_reason }}</b></p>
                
                <br/> 
                <p><b>{{ 'Odluku donio: ' . $odobrio }}</b></p>
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