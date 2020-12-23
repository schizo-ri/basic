<!DOCTYPE html>
<html lang="hr">
	<head>
        <meta charset="utf-8">
        @include('Centaur::mail_style')
	</head>
	<body>
        <div style="width: 500px;max-width:100%;margin:auto;" id="mail_template">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="header" style="{!! $mail_style ? $mail_style->style_header : '' !!}">
				@if(count($text_header) > 0)
					@foreach ($text_header as $text)
						<p>{{ $text }}</p>
					@endforeach
				@else
					@if( $absence->absence['mark'] == "BOL")
						@if( $absence->end_date )
							<p>Zatvoreno bolovanje</p>
						@else
							<p>Otvoreno bolovanje</p>
						@endif
					@else
						<p>Zahtjev za izostanak</p>
					@endif
				@endif
            </div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body" style="{!! $mail_style ? $mail_style->style_body : '' !!}">
				@if(count($text_body) > 0)
					@foreach ($text_body as $text)
						<p>{{ $text }}</p>
					@endforeach
				@else
					<p>@lang('absence.i'), {{ $absence->employee->user['first_name']   . ' ' . $absence->employee->user['last_name'] }}</p>
					<p>
						@if( $absence->absence['mark'] !=  "BOL")
							@lang('absence.please_approve')  {{ $absence->absence['name'] }} za
							{{ date("d.m.Y", strtotime($absence->start_date)) . ' do ' . date("d.m.Y", strtotime( $absence->end_date)) . ' - ' . $dani_zahtjev . ' ' . __ ('absence.days') }}
							@if( $absence->absence['mark'] == "IZL")
								{{  'od ' . $absence->start_time  . ' - ' .  $absence->end_time }}
							@endif
						@elseif( $absence->absence['mark'] == "BOL")
							@if( $absence->end_date )
								@lang ('absence.end_sicknes').
								{{ ' Zadnji dan je ' .  date("d.m.Y", strtotime($absence->end_date)) }} 
							@else
								@lang ('absence.sicknes')
								{{ 'od ' . date("d.m.Y", strtotime( $absence->start_date))  }}
							@endif
						@endif
					</p>
					<p>@lang('basic.comment'): </p>
					<p class="marg_20">
						{{ $absence->comment }}
						@if($absence->absence['mark'] == "GO")
							<p>@lang('absence.unused') {{ $neiskoristeno_GO }} @lang('absence.vacation_days') </p>
						@endif
						@if($absence->absence['mark'] == "SLD")
							<p>@lang('absence.unused') {{ $slobodni_dani }} @lang('absence.days_off')</p>
						@endif
					</p>
					@if($absence->absence['mark'] != "BOL")
						<form name="contactform" method="get" target="_blank" action="{{ route('confirmation') }}">
							<input style="height: 34px;width: 100%;border-radius: 5px;" type="text" name="approve_reason" value=""><br>
							<input type="hidden" name="id" value="{{ $absence->id }}"><br>
							<input type="radio" name="approve" value="1" id="approve1" style="cursor:pointer" checked> <label for="approve1" style="cursor:pointer"> @lang('absence.approved')</label>
							<input type="radio" name="approve" value="0" id="approve0" style="padding-left:20px; cursor:pointer"> <label for="approve0" style="cursor:pointer">@lang('absence.not_approve')</label><br>
							<input type="hidden" name="email" value="1" checked><br>
							<input class="odobri marg_top_20" type="submit" value="{{ __('basic.process') }}" style="cursor:pointer">
						</form>
					@endif
				@endif
            </div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="footer"  style="{!! $mail_style ? $mail_style->style_footer : '' !!}">
				{{-- @if(count($text_footer) > 0)
					@foreach ($text_footer as $text)
						<p>{{ $text }}</p>
					@endforeach
				@endif --}}
				@if(file_exists('../public/storage/company_img/logo.png'))
					<img src="{{ URL::asset('storage/company_img/logo.png')}}" alt="company_logo" class="company_logo"/>
				@else
					<p>{{ config('app.name') }}</p>
				@endif
            </div>
        </div>
	</body>
</html>