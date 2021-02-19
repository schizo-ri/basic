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
            color:black;
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
		<h4>Zahtjev za ispravak izostanka</h4>
		<br/> 
		<div>
			<p>@lang('absence.i'), {{ $absence->employee->user['first_name']   . ' ' . $absence->employee->user['last_name'] }}</p>
			<p>molim izmjenu zahtjeva </p>
			<p>Stari zahtjev: {{ $absence->absence['name'] }} - {{ date("d.m.Y", strtotime($absence->start_date)) }} {!! $absence->end_date ?  ' do ' . date("d.m.Y", strtotime( $absence->end_date)) : '' !!}
				@if( $absence->absence['mark'] == "IZL")
					{{  'od ' . $absence->start_time  . ' - ' .  $absence->end_time }}
				@endif
			</p>
			<p>Zahtjev za promjenom:  {{ $type }} - {{ date("d.m.Y", strtotime($request->start_date)) }} {!! $request->end_date ? ' do ' . date("d.m.Y", strtotime( $request->end_date)) : '' !!} {{ ' - ' . $dani_zahtjev . ' ' . __ ('absence.days') }}
				@if( $request->type == "IZL")
					{{  'od ' . $request->start_time  . ' - ' .  $request->end_time }}
				@endif
			</p>
			<p class="marg_20">
				{{ $request->comment }}
				@if($request->type == "GO")
					<p>@lang('absence.unused') {{ $neiskoristeno_GO }} @lang('absence.vacation_days') </p>
				@endif
			</p>
			</div>
			<div>
				<form class="contactform" role="form" method="post" name="myForm" accept-charset="UTF-8" action="{{ route('absences.update', $absence->id ) }}" target="_blank" >
					<input name="employee_id" type="hidden" value="{{  $request->employee_id }}" />
					<input name="type" type="hidden" value="{{ $request->type }}" />
					<input name="start_date" type="hidden" value="{{ $request->start_date }}" >
					<input name="end_date" type="hidden" value="{{ $request->end_date }}" >
					<input name="start_time" type="hidden" value="{{ $request->start_time }}" >
					<input name="end_time" type="hidden" value="{{ $request->end_time }}" >
					<input name="comment" type="hidden" value="{{ $request->comment }}" >
					<input name="_token"  type="hidden" value="{{ csrf_token() }}">
					<input name="_method" type="hidden" value="PUT" >
				<!-- 	<input name="email" type="hidden"  value="DA"> -->
					<input class="odobri marg_top_20" type="submit" value="{{ __('basic.edit')}}" id="stil1" style="cursor:pointer">
				</form>
			</div>
	</body>
</html>