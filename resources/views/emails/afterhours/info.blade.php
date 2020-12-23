<!DOCTYPE html>
<html lang="hr">
	<head>
        <meta charset="utf-8">
		@include('Centaur::mail_style')
	</head>
	<body>
        <div style="width: 500px;max-width:100%;margin:auto;" id="mail_template">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="header" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_header : '' !!}">
                <p>Prijavljeni prekovremeni sati</p>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_body : '' !!}">
                <p>Djelatnik, {{ $afterhour->employee->user->first_name . ' ' . $afterhour->employee->user->last_name }} poslao je zahtjev za odobrenje izvršenog prekovremenog rada<br>
                    za projekt: {{ $afterhour->project->erp_id . ' - ' . $afterhour->project->name }}<br>
                    za {{ date("d.m.Y", strtotime( $afterhour->date)) . ' od ' . $afterhour->start_time  . ' do ' .  $afterhour->end_time }}</p>

                <div><b>Napomena: </b></div>
                <div class="marg_20">
                    {{ $afterhour->comment }}
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