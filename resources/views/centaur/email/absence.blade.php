<!DOCTYPE html>
<html lang="hr">
	<head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{ URL::asset('css/absence.css') }}"/>
	</head>
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
	<body>
		<h4> @lang('absence.i'), {{ $absence->employee->user['first_name']   . ' ' . $absence->employee->user['last_name'] }}</h4>
		<h4>
			@if($absence->absence['mark'] !=  "BOL")
				@lang('absence.please_approve')  {{ $absence->absence['name'] }} za
				{{ date("d.m.Y", strtotime($absence->start_date)) . ' do ' . date("d.m.Y", strtotime( $absence->end_date)) . ' - ' . $dani_zahtjev . ' ' . __ ('absence.days') }}
			@elseif($absence->absence['mark'] == "BOL")
				@lang ('absence.sicknes')
				{{ date("d.m.Y", strtotime($absence->start_date)) . ' do ' . date("d.m.Y", strtotime( $absence->end_date)) . ' - ' . $dani_zahtjev . ' dana' }}
			@endif
			@if( $absence->absence['mark'] == "IZL")
				{{  'od ' . $absence->start_time  . ' - ' .  $absence->end_time }}
			@endif
	    </h4>
		<div><b>@lang('basic.comment'): </b></div>
		<div class="marg_20">
			{{ $absence->comment }}
			@if($absence->absence['mark'] == "GO")
				<p>@lang('absence.unused') {{ $neiskoristeno_GO }} @lang('absence.vacation_days') </p>
			@endif
			@if($absence->absence['mark'] == "SLD")
				<p>@lang('absence.unused') {{ $slobodni_dani }} @lang('absence.days_off')</p>
			@endif
		</div>
		<form name="contactform" method="get" target="_blank" action="{{ route('confirmation') }}">
			<input style="height: 34px;width: 100%;border-radius: 5px;" type="text" name="approve_reason" value=""><br>
			<input type="hidden" name="id" value="{{ $absence->id }}"><br>
			<input type="radio" name="approve" value="1" id="approve1" style="cursor:pointer" checked> <label for="approve1" style="cursor:pointer"> @lang('absence.approved')</label>
			<input type="radio" name="approve" value="0" id="approve0" style="padding-left:20px; cursor:pointer"> <label for="approve0" style="cursor:pointer">@lang('absence.not_approve')</label><br>
			<input type="hidden" name="email" value="1" checked><br>
			<input class="odobri marg_top_20" type="submit" value="{{ __('basic.process') }}" style="cursor:pointer">
		</form>
	</body>
</html>