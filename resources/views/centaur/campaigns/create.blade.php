<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_campaign')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('campaigns.store') }}">
		<fieldset>
			<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
				<input class="form-control" placeholder="{{ __('basic.name')}}" name="name" type="text" maxlength="255" value="{{ old('name') }}" required />
				{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
				<input class="form-control" placeholder="{{ __('basic.description')}}" name="description" type="text" maxlength="255" value="{{ old('description') }}" required />
				{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			{{ csrf_field() }}
			<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
		</fieldset>
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
	$.getScript( '/../js/validate.js');	
</script>