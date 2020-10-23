<!DOCTYPE html>
<html lang="hr">
	<head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{ URL::asset('css/absence.css') }}"/>
        <style>
            body { 
                font-family: DejaVu Sans, sans-serif;
                max-width:500px;
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
                font-size:14px;
                margin:15px;
                float:left;
                custor:pointer
            }
            .marg_20 {
                margin-bottom:20px;
            }
            .marg_top_20 {
                margin-top:20px;
            }
        </style>
	</head>
	<body>
		<h4> @lang('absence.i'), {{ $temporaryEmployeeRequest->employee->user['first_name']   . ' ' . $temporaryEmployeeRequest->employee->user['last_name'] }}</h4>
		<h4>
			
			@lang('absence.please_approve')  {{ $temporaryEmployeeRequest->absence['name'] }} za
			{{ date("d.m.Y", strtotime($temporaryEmployeeRequest->start_date)) . ' do ' . date("d.m.Y", strtotime( $temporaryEmployeeRequest->end_date)) . ' - ' . $dani_zahtjev . ' ' . __ ('absence.days') }}
		
			@if( $temporaryEmployeeRequest->absence['mark'] == "IZL")
				{{  'od ' . $temporaryEmployeeRequest->start_time  . ' - ' .  $temporaryEmployeeRequest->end_time }}
			@endif
	    </h4>
		<div><b>@lang('basic.comment'): </b></div>
		<div class="marg_20">
			{{ $temporaryEmployeeRequest->comment }}
		</div>
		<form name="contactform" method="get" target="_blank" action="{{ route('confirmationTemp') }}">
			<input style="height: 34px;width: 100%;border-radius: 5px;" type="text" name="approve_reason" value=""><br>
			<input type="hidden" name="id" value="{{ $temporaryEmployeeRequest->id }}"><br>
			<input type="radio" name="approve" value="1" checked> @lang('absence.approved')
			<input type="radio" name="approve" value="0" style="padding-left:20px;">  @lang('absence.not_approved')<br>
			<input type="hidden" name="email" value="1" checked><br>
			<input class="odobri marg_top_20" type="submit" value="{{ __('basic.send_mail') }}">
		</form>
	</body>
</html>