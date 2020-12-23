<!DOCTYPE html>
<html lang="hr">
	<head>
        <meta charset="utf-8">
		@include('Centaur::mail_style')
	</head>
	<body>
        <div style="width: 500px;max-width:100%;margin:auto;" id="mail_template">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="header" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_header : '' !!}">
				<p>Odsutan djelatnik za postavljeni zadatak</p>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_body : '' !!}">
				<p>Djelatnik  {{ $employeeTask->employee->user->first_name . ' ' . $employeeTask->employee->user->last_name }}</p>
				<p> ima postavljen zadatak {{ $employeeTask->task->task }} a danas je odsutan.</p>
				<p>Nema drugih djelatnika na tom zadatku.</p>
			
				<p>Zadatak postavio: {{ $employeeTask->task->employee->user->first_name . ' ' .  $employeeTask->task->employee->user->last_name }}</p>	
				<p class="">Početni datum: {{ date('d.m.Y', strtotime($employeeTask->created_at)) }}</p>
				<p class="">Interval: </p>
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