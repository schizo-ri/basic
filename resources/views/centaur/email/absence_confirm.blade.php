<!DOCTYPE html>
<html lang="hr">
	<head>
		<meta charset="utf-8">
		
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
            color:white;
            font-weight:bold;
            font-size:14px;
            margin:15px;
            float:left;
        }

        .marg_20 {
            margin:20px 0;
        }
    </style>
	<body>
        <h4>@lang('absence.request') {{ $absence->absence['name'] }}  @lang('basic.for')
        @if($absence->absence['mark'] != "IZL")
            {{ date("d.m.Y", strtotime($absence->start_date)) . ' do ' . date("d.m.Y", strtotime($absence->end_date)) }} 
        @else
            {{ date("d.m.Y", strtotime($absence->start_date)) . ' od ' . $absence->start_time . ' do ' . $absence->end_time }}</h4>
        @endif
        <div><b>{{ $odobrenje }}</b></div>
        <div><b>{{ $absence->approve_reason }}</b></div>
		
        <br/> 
		
		<div><b>{{ 'Odobrio: ' . $odobrio }}</b></div>
	</body>
</html>
