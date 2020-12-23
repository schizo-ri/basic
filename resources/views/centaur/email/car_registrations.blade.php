<!DOCTYPE html>
<html lang="hr">
	<head>
        <meta charset="utf-8">
		@include('Centaur::mail_style')
	</head>
	<body>
        <div style="width: 500px;max-width:100%;margin:auto;" id="mail_template">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="header" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_header : '' !!}">
				<p>@lang('basic.vehicle_registration')</p>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_body : '' !!}">
				<p style="font-weight: 400;">@lang('basic.manufacturer'): {{ $car->manufacturer }}</p> 
				<p style="font-weight: 400;">@lang('basic.model'): {{ $car->model }}</p>
				<p style="font-weight: 400;">@lang('basic.license_plate'): {{ $car->registration }}</p>
				<p style="font-weight: 400;">@lang('basic.last_registration'): {{ date('d.m.Y', strtotime($car->last_registration)) }}</p>
				
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

      
