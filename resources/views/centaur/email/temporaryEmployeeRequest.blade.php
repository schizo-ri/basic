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
					
				@endif
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_body : '' !!}">
                @if(count($text_body) > 0)
					@foreach ($text_body as $text)
						<p>{{ $text }}</p>
					@endforeach
				@else
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
                @endif
                <form name="contactform" method="get" target="_blank" action="{{ route('confirmationTemp') }}">
                    <input style="height: 34px;width: 100%;border-radius: 5px;" type="text" name="approve_reason" value=""><br>
                    <input type="hidden" name="id" value="{{ $temporaryEmployeeRequest->id }}"><br>
                    <input type="radio" name="approve" value="1" checked> @lang('absence.approved')
                    <input type="radio" name="approve" value="0" style="padding-left:20px;">  @lang('absence.not_approved')<br>
                    <input type="hidden" name="email" value="1" checked><br>
                    <input class="odobri marg_top_20" type="submit" value="{{ __('basic.send_mail') }}">
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