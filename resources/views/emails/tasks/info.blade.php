<!DOCTYPE html>
<html lang="hr">
	<head>
        <meta charset="utf-8">
		@include('Centaur::mail_style')
	</head>
	<body>
        <?php 
			use App\Models\Employee;
			switch ( $task->interval_period ) {
				case 'no_repeat':
					$interval = 'Bez ponavljanja';
					break;
				case 'every_day':
					$interval = 'Dnevno';
					break;
				case 'once_week':
					$interval = 'Tjedno';
					break;
				case 'once_month':
					$interval = 'Mjesečno';
					break;
				case 'once_year':
					$interval = 'Godišnje';
					break;
				default:
					$interval = $task->interval_period;
			}
		?>
        <div style="width: 500px;max-width:100%;margin:auto;" id="mail_template">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="header" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_header : '' !!}">
				@if(count($text_header) > 0)
					@foreach ($text_header as $text)
						<p>{{ $text }}</p>
					@endforeach
				@else
					<p>{{ $task->employee->user->first_name . ' ' . $task->employee->user->last_name }} je postavio zadatak</p>
				@endif
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_body : '' !!}">
				@if(count($text_body) > 0)
					@foreach ($text_body as $text)
						<p>{{ $text }}</p>
					@endforeach
				@endif
				<p class="">Zadatak: <b>{{ $task->task }}</b></p>
				<p class="">
                    @php
						$employee_ids = explode(",", $task->to_employee_id);
					@endphp
                    Zaduženi djelatnici: 
                    @foreach ($employee_ids as $id)
						@php
							$employee = Employee::where('id', $id)->first();
						@endphp
						<p class="padd_l_20">{{ $employee->user->first_name . ' ' . $employee->user->last_name }}</p>				
					@endforeach		
                </p>	
                <p class="">Početni datum: {{ date('d.m.Y',strtotime($task->start_date ))}}</p>
				<p class="">Interval: {{ $interval }} </p>			
				<p class="">Status: {!! $task->active == 1 ? 'aktivan' : 'neaktivan' !!}</p>
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