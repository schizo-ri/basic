<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_sequence') {!! $this_campaign ? count($campaign_sequences)+1 : '' !!} </h3>
</div>
<div class="modal-body">
	<form class="form_sequence" accept-charset="UTF-8" role="form" method="post" action="{{ route('campaign_sequences.store') }}">
		<fieldset>
			<div class="form-group {{ ($errors->has('campaign_id'))  ? 'has-error' : '' }}">
				<label>@lang('basic.campaign')</label>
				<select  class="form-control" name="campaign_id" value="{{ old('campaign_id') }}" required >
					<option selected disabled value=""></option>
					@foreach($campaigns as $campaign)
						<option value="{{ $campaign->id }}" {!! isset($this_campaign) && $this_campaign->id == $campaign->id ? 'selected' : '' !!}>{{ $campaign->name }}</option>
					@endforeach
				</select>
				{!! ($errors->has('campaign_id') ? $errors->first('campaign_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('text')) ? 'has-error' : '' }}">
				<textarea name="text" id="mytextarea" maxlength="16777215">{{ old('text') }}</textarea>
				{!! ($errors->has('text') ? $errors->first('text', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			
			@if (count($campaign_sequences) == 0 )
				<div class="form-group datum float_l">
					<label for="start_date">@lang('absence.start_date')</label>
					<input class="form-control" name="start_date" id="start_date" type="date" maxlength="255" value="{{ old('start_date') }}" required />
					{!! ($errors->has('start_date') ? $errors->first('start_date', '<p class="text-danger">:message</p>') : '') !!}
				</div>
			@else
				<div class="form-group {{ ($errors->has('send_interval'))  ? 'has-error' : '' }} clear_l" id="period">
					<label  class="label_period">@lang('basic.repetition_period')</label>
					<select  class="form-control period" name="send_interval" value="{{ old('send_interval') }}" required >
						<option value="no_repeat">@lang('basic.no_repeat')</option>
						<option value="every_day">@lang('basic.every_day')</option>
						<option value="once_week">@lang('basic.once_week')</option>
						<option value="once_month">@lang('basic.once_month')</option>
						<option value="once_year">@lang('basic.once_year')</option>
						<option value="customized">@lang('basic.customized')</option>
					</select>
				</div>
				<div class="form-group clear_l" id="interval" >
					<label class="label_custom_interal">@lang('basic.custom_interal')</label>
					<input class="form-control input_interval" type="number" name="interval" />
					<select  class="form-control select_period" name="period" value="{{ old('period') }}"  >
						<option value="day">@lang('basic.day')</option>
						<option value="week">@lang('basic.week')</option>
						<option value="month">@lang('basic.month')</option>
						<option value="year">@lang('basic.year')</option>
					</select>
				</div>
			@endif
			
			{{ csrf_field() }}
			<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
		</fieldset>
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
	$.getScript( '/../js/validate.js');
	$.getScript( '/../js/campaign_sequences.js');
	$.getScript( '/../js/tinymce.js');

	$('body').on($.modal.CLOSE, function(event, modal) {
		$.getScript('/../node_modules/tinymce/tinymce.min.js');
	});	
</script>
