<div class="modal-header">
	<h3 class="panel-title">@lang('basic.edit_sequence')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('campaign_sequences.update', $campaign_sequence->id) }}">
		<fieldset>
			<div class="form-group {{ ($errors->has('campaign_id'))  ? 'has-error' : '' }}">
				<label>@lang('basic.campaign')</label>
				<select  class="form-control" name="campaign_id" value="{{ old('campaign_id') }}" required >
					@foreach($campaigns as $campaign)
						<option value="{{ $campaign->id }}" {!! $campaign->id == $campaign_sequence->campaign_id ? 'selected' : '' !!} >{{ $campaign->name }}</option>
					@endforeach				
				</select>
				{!! ($errors->has('campaign_id') ? $errors->first('campaign_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('text')) ? 'has-error' : '' }}">
				<textarea name="text" id="mytextarea" maxlength="16777215" required >{!! $campaign_sequence->text  !!}</textarea>
				{!! ($errors->has('text') ? $errors->first('text', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group datum float_l">
				<label for="start_date">@lang('absence.start_date')</label>
				<input class="form-control" name="start_date" id="start_date" type="date" maxlength="255" value="{{ date('Y-m-d',strtotime($campaign_sequence->start_date )) }}" required />
				{!! ($errors->has('start_date') ? $errors->first('start_date', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('send_interval'))  ? 'has-error' : '' }} clear_l" id="period">
				<label class="label_period">@lang('basic.repetition_period')</label>
				<select  class="form-control period" name="send_interval" value="{{ old('send_interval') }}" {!! ! is_array($send_interval) ? 'required' : 'style="display: none"' !!}>
					<option value="no_repeat"  {!! ! is_array($send_interval) && $send_interval == 'no_repeat' ? 'selected' : ''  !!} >@lang('basic.no_repeat')</option>
					<option value="every_day" {!! ! is_array($send_interval) && $send_interval == 'every_day' ? 'selected' : ''  !!} >@lang('basic.every_day')</option>
					<option value="once_week" {!! ! is_array($send_interval) && $send_interval == 'once_week' ? 'selected' : ''  !!} >@lang('basic.once_week')</option>
					<option value="once_month" {!! ! is_array($send_interval) && $send_interval == 'once_month' ? 'selected' : ''  !!} >@lang('basic.once_month')</option>
					<option value="once_year" {!! ! is_array($send_interval) && $send_interval == 'once_year' ? 'selected' : ''  !!} >@lang('basic.once_year')</option>
					<option value="customized" {!! is_array($send_interval) ? 'selected' : ''  !!} >@lang('basic.customized')</option>
				</select>
			</div>
			<div class="form-group clear_l" id="interval"  {!! is_array($send_interval) ? 'style="display: block"' : ''  !!}>
				<label class="label_custom_interal">@lang('basic.custom_interal')</label>
				<input class="form-control input_interval" type="number" name="interval" value="{!! is_array($send_interval) ? $send_interval['0'] : ''  !!}" {!! is_array($send_interval) ? 'required' : ''  !!}  />
				<select  class="form-control select_period" name="period" {!! is_array($send_interval) ? 'required' : ''  !!} >
					<option value="day" {!! is_array($send_interval) && $send_interval[1] == 'day' ? 'selected' : '' !!} >@lang('basic.day')</option>
					<option value="week" {!! is_array($send_interval) && $send_interval[1] == 'week' ? 'selected' : '' !!}>@lang('basic.week')</option>
					<option value="month" {!! is_array($send_interval) && $send_interval[1] == 'month' ? 'selected' : '' !!}>@lang('basic.month')</option>
					<option value="year" {!! is_array($send_interval) && $send_interval[1] == 'year' ? 'selected' : '' !!} >@lang('basic.year')</option>
				</select>
			</div>
			{{ method_field('PUT') }}
			{{ csrf_field() }}
			<input class="btn-submit" type="submit" value="{{ __('basic.edit')}}">
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