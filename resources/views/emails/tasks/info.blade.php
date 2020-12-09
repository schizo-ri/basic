<!DOCTYPE html>
<html lang="hr">
	<head>
        <meta charset="utf-8">
		<style>
			body { 
				font-family: DejaVu Sans, sans-serif;
			}
            #mail_template #header, #mail_template #footer {
                height: auto;
                border: none;
                padding: 10px 15px;
				text-align: center;
				clear: both;
				overflow-wrap: break-word;
            }
			#mail_template #header {
				font-size: 16px;
				font-weight: bold;
			}
			#mail_template #footer {
				font-size: 12px;
			}
            #mail_template #body {
                height: auto;
                border: none;
                font-size: 14px;
				padding: 15px;
				clear: both;
				overflow-wrap: break-word;
				line-height: 16px;
			}
			.odobri{
				width:150px;
				height:40px;
				background-color:white;
				border: 1px solid rgb(0, 102, 255);
				border-radius: 5px;
				box-shadow: 5px 5px 8px #888888;
				text-align:center;
				padding:10px;
				color:black;
				font-weight:bold;
				font-size:12px;
				margin:15px;
				float:left;
				cursor:pointer
			}
			.marg_20 {
				margin-bottom:20px;
			}
            .padd_l_20 {
				padding-left:20px;
			}
			.marg_top_20 {
				margin-top:20px;
			}
		</style>
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
				<p>{{ $task->employee->user->first_name . ' ' . $task->employee->user->last_name }} je postavio zadatak</p>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_body : '' !!}">
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
				<p>{{ config('app.name') }}</p>
            </div>
        </div>
	</body>
</html>