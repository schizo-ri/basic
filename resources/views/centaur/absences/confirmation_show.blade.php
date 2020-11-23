<div class="modal-header">
	<h3 class="panel-title">{!! $absence->approve != null ? __('absence.request_approved') : __('absence.approve_absence') !!}</h3>
</div>
<div class="modal-body">
	@if( $absence->approve != null )
		<div class="odobreno">
			<p>@lang('absence.approved_by'): {!! $absence->approved_id != null ? $absence->approved->user['first_name'] . ' ' . $absence->approved->user['last_name'] : '' !!}</p>
			<p>Status: {!! $absence->approve == 1 ? __('absence.approved') : __('absence.not_approved') !!}
				@if($absence->approve_reason != null && $absence->approve_reason != '' )
					- {{ $absence->approve_reason }}
				@endif
			</p>
			<p>@lang('absence.aprove_date'): {!! $absence->approved_date ? date('d.m.Y', strtotime( $absence->approved_date )) : '' !!}</p>
			<p>
				@lang('absence.change_approval')
				<a class="btn1" href=""  id="da">@lang('absence.yes')</a>
				<a class="btn1" href="{{ route('dashboard') }}" id="ne" rel="modal:close">@lang('absence.no')</a>
			</p>
		</div>
	@endif
	<div class="odobrenje" {!!  $absence->approve != null ? 'style="display:none"' : '' !!} >
		<form name="contactform" method="get" action="{{ route('confirmation_update') }}">
			<input type="hidden" name="id" value="{{ $absence->id}}">
			<input type="hidden" name="approve_date" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
			<div class="form-group {{ ($errors->has('approve_reason')) ? 'has-error' : '' }}">
				<label>@lang('absence.reason')</label>
				<input class="form-control" type="text" name="approve_reason" ><br>
				{!! ($errors->has('approve_reason') ? $errors->first('approve_reason', '<p class="text-danger">:message</p>') : '') !!}	
			</div>
			<div class="form-group">
				<input type="radio" name="approve" id="approve" value="1" checked> <label for="approve">@lang('absence.approved')</label>
				<input type="radio" name="approve" id="not_approve" value="0" > <label for="not_approve">@lang('absence.not_approved')</label><br>
			</div>
			<div class="form-group">
				<label for="email">@lang('absence.email_send')</label><br>
				<input type="radio" name="email" value="1" id="send" checked><label for="send"> @lang('absence.send_email')</label><br>
				<input type="radio" name="email" value="0" id="no_send" ><label for="no_send"> @lang('absence.dont_send_email')</label>
			</div>
			<input class="btn-submit" type="submit" value="{{ __('basic.confirm') }}">
		</form>
	</div>
</div>
<script>
	$('#da').click(function(){
		$('.odobrenje').show();
	});
</script>

