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
					<p>{{ $employeeTask->task->employee->user->first_name . ' ' . $employeeTask->task->employee->user->last_name }} je postavio zadatak</p>
				@endif
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_body : '' !!}">
				@if(count($text_body) > 0)
					@foreach ($text_body as $text)
						<p>{{ $text }}</p>
					@endforeach
				@else
					<p class="">Zadatak: <b>{{ $employeeTask->task->task }}</b></p>
					<p class="">Zaduženi djelatnik: {{ $employeeTask->employee->user->first_name . ' ' . $employeeTask->employee->user->last_name }}</p>				
					<p class="">Status: {!! $employeeTask->task->active == 1 ? 'aktivan' : 'neaktivan' !!}</p>
				@endif
				<form name="contactform" method="get" target="_blank" action="{{ route('tasks_confirm') }}">
					<input type="hidden" name="id" value="{{ $employeeTask->id }}" />
					<input style="height: 34px; width: 100%;border-radius: 5px;" type="text" name="comment" required ><br>
					<input class="odobri" type="submit" value="Potvrdi izvršenje">
				</form>
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