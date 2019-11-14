@extends('Centaur::layout')

@section('title', 'Naslovnica')

@section('content')
<div clas="col-md-12">
	
	<div clas="col-md-12">
		@if(isset($absence))
			<div class="odobreno">
			<h3>@lang('absence.request_approved')</h3>
			<p>@lang('absence.approved') {{ $absence->approved->user['first_name'] . ' ' . $absence->approved->user['last_name'] }}</p>
			Status {!! $absence->approve == 1 ? __('absence.approved') : __('absence.not_approved') !!}
		@if($absence->approve_reason != null && $absence->approve_reason != '' )
			- {{ $absence->approve_reason }}
		@endif

		<p>@lang('absence.aprove_date') {{ $absence->approve_date }}</p>

		<h4>@lang('absence.change_approval')

		<a class="btn1" id="da">@lang('absence.yes')</a>
		<a class="btn1" href="{{ route('dashboard') }}" id="ne">@lang('absence.ne')</a></h4>
		
		@endif
		@if(isset($absence))
			<div class="odobrenje" hidden>
		@else
			<div class="odobrenje" >
		@endif
			<h3>@lang('absence.approve_absence')</h3>
				<form name="contactform" method="get" action="{{ route('confirmation_update') }}">
					<input style="height: 34px;width: 100%;border-radius: 5px;" type="text" name="approve_reason" ><br>
					<input type="hidden" name="id" value="{{ $absence->id}}"><br>
					<input type="radio" name="approve" value="1" checked> @lang('absence.approved')
					<input type="radio" name="approve" value="0" style="padding-left:20px;"> @lang('absence.not_approved')<br>
					<input type="hidden" name="approve_date" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}"><br>
					<div class="form-group">
						<label for="email">@lang('absence.email_send')</label><br>
						<input type="radio" name="email" value="1" checked> @lang('absence.send_email')<br>
						<input type="radio" name="email" value="0"> @lang('absence.dont_send_email')
					</div>
					<input class="odobri" type="submit" value="{{ __('basic.send_mail') }}">
				</form>
			</div>
	</div>
</div>
	<script>
	$('#da').click(function(){
		$('.odobrenje').show();
	});
	$('#ne').click(function(){
		$('.odobrenje').hide();
	});
	</script>
@stop
