<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_campaign')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('campaigns.store') }}">
		<fieldset>
			<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
				<label for="">@lang('basic.name')</label>
				<input class="form-control" placeholder="{{ __('basic.name')}}" name="name" type="text" maxlength="255" value="{{ old('name') }}" required />
				{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
				<label for="">@lang('basic.description')</label>
				<textarea class="form-control" placeholder="{{ __('basic.description')}}" name="description" type="text" rows="3" maxlength="255" required >{{ old('description') }}</textarea>
				{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="type form-group">
				<label for="">@lang('basic.campaign_type')</label>
				<label class="float_l container_radio">@lang('basic.one_time')  
					<input type="radio" name="type" value="one_time" checked />
					<span class="checkmark"></span>
				</label>
				<label class="float_l container_radio">@lang('basic.evergreen')
					<input type="radio" name="type" value="evergreen" />
					<span class="checkmark"></span>
				</label>
			</div>
			<div class="type form-group {{ ($errors->has('start_date')) ? 'has-error' : '' }}">
				<label for="">@lang('absence.start_date')</label>
				<input class="form-control date_time float_l" placeholder="{{ __('absence.start_date')}}" name="start_date" type="date" value="{{ old('start_date') }}" required />
				<input class="form-control date_time float_l" placeholder="{{ __('absence.start_time')}}" name="start_time" type="time" value="{{ old('start_time') }}" required />
				{!! ($errors->has('start_date') ? $errors->first('start_date', '<p class="text-danger">:message</p>') : '') !!}
			</div>			
			<div class="active_status form-group">
				<label for="">@lang('basic.active')</label>
				<label class="float_l container_radio status_checked"> @lang('basic.active')
					<input type="radio" name="active" value="1" checked />
					<span class="checkmark active"></span>
				</label>
				<label class="float_l container_radio status_checked ">@lang('basic.inactive')
					<input type="radio" name="active" value="0" />
					<span class="checkmark inactive"></span>
				</label>
			</div>
			{{ csrf_field() }}
			<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
			<a href="" class="modal_close float_r" rel="modal:close">@lang('basic.cancel')</a>
		</fieldset>
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
/* 	$.getScript( '/../js/validate.js');	 */
</script>