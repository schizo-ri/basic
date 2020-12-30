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
					<p>Zahtjev za odobrenje prekovremenih sati</p>
				@endif
            </div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_body : '' !!}">
				@if(count($text_body) > 0)
					@foreach ($text_body as $text)
						<p>{{ $text }}</p>
					@endforeach
				@endif

				<h4>Ja, {{ $afterhour->employee->user->first_name . ' ' . $afterhour->employee->user->last_name }} molim da mi se potvrdi izvršeni prekovremeni rad <br>
					@if($afterhour->project)
						za projekt: {{ $afterhour->project->erp_id . ' - ' . $afterhour->project->name }}<br>
					@endif
					@if($afterhour->erp_task_id)
						za zadatak {{ $task }}
					@endif
					za {{ date("d.m.Y", strtotime($afterhour->date)) . ' od ' . $afterhour->start_time  . ' do ' .  $afterhour->end_time }}</h4>
				<div><b>Napomena: </b></div>
				<div class="marg_20">
					{{ $afterhour->comment }}
				</div>		
				<form method="get" target="_blank" action="{{ route('confirmationAfterHours') }}">
					<input style="height: 34px;width: 100%; border-radius: 5px; border: 1px solid #ccc;" type="text" name="approved_reason" maxlength="191"><br>
					<input type="hidden" name="id" value="{{ $afterhour->id }}"><br>
					<div class="time">
						<label>Odobreno prekovremenih sati:</label>
						<input name="approve_h" class="date form-control" type="time" value="{!! isset( $interval ) ? $interval : '00:00' !!}" id="date1" required><i class="far fa-clock" style="border-radius: 5px; border: 1px solid #ccc"></i></i>
					</div>
					<input type="radio" name="approve" value="1" id="approve1" checked>  <label for="approve1" style="cursor:pointer">Potvrđeno</label>
					<input type="radio" name="approve" value="0" id="approve2" style="padding-left:20px;"> <label for="approve2" style="cursor:pointer">Odbijeno</label><br>
					{{ csrf_field() }}
					<input class="odobri" type="submit" value="Pošalji">
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