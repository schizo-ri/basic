<!DOCTYPE html>
<html lang="hr">
	<head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{ URL::asset('css/absence.css') }}"/>
	</head>
	<style>
		body { 
			font-family: DejaVu Sans, sans-serif;
			font-size: 10px;
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
		<h4>Odlukom Uprave djelatnik {{ $absence->employee->user['first_name']   . ' ' . $absence->employee->user['last_name'] }} koristi {{ $absence->absence['name'] }} </h4>
		<h4>
			@if($absence->absence['mark'] !=  "BOL")
				za 
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
			
		</div>
		<div><b>Odluku donio: {{ Sentinel::getUser()->first_name . ' ' .  Sentinel::getUser()->last_name }}</b></div>
	</body>
</html>