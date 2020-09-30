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
			background-color: #007cc3;
			border: 1px solid rgb(0, 102, 255);
			border-radius: 5px;
			box-shadow: 5px 5px 8px #888888;
			text-align:center;
			padding:10px;
			color:white !important;
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
        <h4>@lang('basic.vehicle_registration')</h4>
        <h4 style="font-weight: 400;">@lang('basic.manufacturer'): {{ $car->manufacturer }}</h4> 
        <h4 style="font-weight: 400;">@lang('basic.model'): {{ $car->model }}</h4>
        <h4 style="font-weight: 400;">@lang('basic.license_plate'): {{ $car->registration }}</h4>
        <h4 style="font-weight: 400;">@lang('basic.last_registration'): {{ date('d.m.Y', strtotime($car->last_registration)) }}</h4>
        
        <a href="{{ $url }}" class="odobri">{{ __('emailing.save_calendar') }}</a>
       {{--  <form accept-charset="UTF-8" method="post" target="_blank" action="{{ $url }}" >
            <input name="title" type="hidden" class="form-control" required value="{{ __('basic.vehicle_registration')  . ' - ' . $car->registration }}" >
            <input name="date" type="hidden" class="form-control" value="{{ Carbon\Carbon::now()->addWeekdays(7)->format('Y-m-d')}}" required>
            <input name="time1" class="form-control" type="hidden" value="08:00" required />
            <input name="time2" class="form-control" type="hidden" value="09:00" required />
            <textarea name="description" class="form-control" type="text" style="display:none" required >{{ __('basic.vehicle_registration')  . ' - ' . $car->registration }}</textarea>
			{{ csrf_field() }}
			<input class="odobri marg_top_20" type="submit" value="{{ __('emailing.save_calendar') }}">
		</form> --}}
	</body>
</html>