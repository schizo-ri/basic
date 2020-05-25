<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_recipient')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('sendTestSequence') }}" >
		<fieldset>
            <input name="sequence_id" type="hidden" value="{{ $sequence_id }}" required />
			<div class="form-group {{ ($errors->has('recipient')) ? 'has-error' : '' }}">
				<label>@lang('basic.add_recipient')</label>
				<input class="form-control" name="recipient" type="email" value="{{ old('recipient') }}" required />
				{!! ($errors->has('recipient') ? $errors->first('recipient', '<p class="text-danger">:message</p>') : '') !!}
			</div>			
			{{ csrf_field() }}
			<input class="btn-submit" type="submit" id="submit" value="{{ __('basic.send')}}">
		</fieldset>
	</form>
</div>
<script>
	$.getScript('/../js/validate.js');
</script>