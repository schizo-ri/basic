<div class="modal-header">
	<h3 class="panel-title">{!! $absence->approve != null ? __('absence.request_approved') : __('absence.approve_absence') !!}</h3>
</div>
<div class="modal-body">
	@if( $absence->approve == 1 || $absence->approve == '0')
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
				<a class="btn1" id="da">@lang('absence.yes')</a>
				<a class="btn1" href="{{ route('dashboard') }}" id="ne" rel="modal:close">@lang('absence.no')</a>
			</p>
		</div>
	@endif
	<div class="odobrenje" {!!  $absence->approve == 1 || $absence->approve == '0' ? 'style="display:none"' : '' !!} >
		<form class="confirmation_update_form" method="get" action="{{ route('confirmation_update', $absence->id ) }}">
			<input type="hidden" name="id" value="{{ $absence->id}}">
			<input type="hidden" name="approve_date" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
			<div class="form-group {{ ($errors->has('approve_reason')) ? 'has-error' : '' }}">
				<label>@lang('absence.reason')</label>
				<input class="form-control" type="text" name="approve_reason" ><br>
				{!! ($errors->has('approve_reason') ? $errors->first('approve_reason', '<p class="text-danger">:message</p>') : '') !!}	
			</div>
			<div class="form-group">
				<input type="radio" name="approve" id="approve" value="1" {!!  $absence->approve == 1 ? 'checked' : '' !!} > <label for="approve">@lang('absence.approved')</label>
				<input type="radio" name="approve" id="not_approve" value="0"  {!!  $absence->approve == 0 ? 'checked' : '' !!}> <label for="not_approve">@lang('absence.is_not_approved')</label><br>
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
				id = $('input[name=id]').val();

				console.log(url);
				console.log(form_data);
				console.log(url_load);
				/* console.log(type); */
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
						$('tr#requestAbs_'+id).load(url_load + " tr#requestAbs_"+id+" td",function(){
							if( type == 3) {
								$('.absence_end_date').hide();
								$('.absence_time').show();
							} else if( type != 'all' &&  type != 3) {
								$('.absence_end_date').show();
								$('.absence_time').hide();
							}

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

