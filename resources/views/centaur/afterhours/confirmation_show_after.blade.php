<div class="modal-header">
	<h3 class="panel-title">{!! $afterHour->approve != null ? __('absence.request_approved') : __('absence.approve_absence') !!}</h3>
</div>
<div class="modal-body">
	@if( $afterHour->approve == 1 || $afterHour->approve == '0' )
		<div class="odobreno">
			<p>@lang('absence.approved_by'): {!! $afterHour->approved_id != null ? $afterHour->approved->user['first_name'] . ' ' . $afterHour->approved->user['last_name'] : '' !!}</p>
			<p>Status: {!! $afterHour->approve == 1 ? __('absence.approved') : __('absence.not_approved') !!}
				@if($afterHour->approved_reason != null && $afterHour->approved_reason != '' )
					- {{ $afterHour->approved_reason }}
				@endif
			</p>
			<p>@lang('absence.aprove_date'): {!! $afterHour->approved_date ? date('d.m.Y', strtotime( $afterHour->approved_date )) : '' !!}</p>
			<p>
				@lang('absence.change_approval')
				<a class="btn1" id="da">@lang('absence.yes')</a>
				<a class="btn1" href="{{ route('dashboard') }}" id="ne" rel="modal:close">@lang('absence.no')</a>
			</p>
		</div>
	@endif
	<div class="odobrenje" {!! $afterHour->approve == 1 || $afterHour->approve == '0' ? 'style="display:none"' : '' !!} >
		<form class="confirmation_update_form" method="post" action="{{ route('confirmation_update_after', $afterHour->id) }}">
			<input type="hidden" name="id" value="{{ $afterHour->id}}">
			<input type="hidden" name="approve_date" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
			<div class="form-group">
				@if ($afterHour->approve == 1 || $afterHour->approve == '0')
					<input name="approve_h" style="border-radius:5px;" class="odobreno_h" type="time" value="{!! $afterHour->approve_h ? $afterHour->approve_h : '00:00' !!}" required>
				@else
					<input name="approve_h" style="border-radius:5px;" class="odobreno_h" type="time" value="{!! isset($interval) ? $interval : '00:00' !!}" required>
				@endif
			</div>
			<div class="form-group {{ ($errors->has('approved_reason')) ? 'has-error' : '' }}">
				<label>@lang('absence.reason')</label>
				<input class="form-control" type="text" name="approved_reason" ><br>
				{!! ($errors->has('approved_reason') ? $errors->first('approved_reason', '<p class="text-danger">:message</p>') : '') !!}	
			</div>
			<div class="form-group">
				<input type="radio" name="approve" id="approve" value="1" checked> <label for="approve">@lang('absence.approved')</label>
				<input type="radio" name="approve" id="not_approve" value="0" > <label for="not_approve">@lang('absence.is_not_approved')</label><br>
			</div>
			<div class="form-group">
				<label for="email">@lang('absence.email_send')</label><br>
				<input type="radio" name="email" value="1" id="send" checked><label for="send"> @lang('absence.send_email')</label><br>
				<input type="radio" name="email" value="0" id="no_send" ><label for="no_send"> @lang('absence.dont_send_email')</label>
			</div>
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<input class="btn-submit" type="submit" value="{{ __('basic.confirm') }}">
		</form>
	</div>
</div>
<script>
$('#da').click(function(){
		$('.odobrenje').show();
		$('.confirmation_update_form').on('submit',function(e){
			if (! confirm("Sigurno želiš promijeniti odobrenje zahtjeve?")) {
				return false;
			} else {
				e.preventDefault();
				url = $(this).attr('action');
				form_data = $(this).serialize(); 
			
				approve = $( '#filter_approve' ).val();
				type = $('#filter_types').val();
				month = $('#filter_years').val();
				employee_id =  $('#filter_employees').val();
				url_load = location.href + '?month='+month+'&type='+type+'&employee_id='+employee_id+'&approve='+approve;
				token = $( this ).attr('data-token');

				console.log(url);
				console.log(form_data);
				console.log(url_load);
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				$.ajax({
					url: url,
					type : 'get',
					data: form_data,
					beforeSend: function(){
						$('body').prepend('<div id="loader"></div>');
					},
					success: function( response ) {
						$('tbody').load(url_load + " tbody>tr",function(){
							$('#loader').remove();
							$.modal.close();
							$('<div class="modal"><div class="modal-header">'+response+'</div></div>').appendTo('body').modal();
						});
					}, 
					error: function(xhr,textStatus,thrownError) {
						console.log("validate eror " + xhr + "\n" + textStatus + "\n" + thrownError);                            
					}
				});
			}
		}); 
	});
</script>