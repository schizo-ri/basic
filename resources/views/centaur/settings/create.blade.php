<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_table')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('settings.store') }}">
		<fieldset>
			<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
				<label>@lang('basic.name')</label>
				<input class="form-control" placeholder="{{ __('basic.name')}}" name="name" type="text" maxlength="50" value="{{ old('name') }}" required />
				{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
				<label>@lang('basic.description')</label>
				<input name="description" type="text" class="form-control" placeholder="{{ __('basic.description')}}" maxlength="255"  value="{{ old('description') }}"  >
				{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('value')) ? 'has-error' : '' }}">
				<label>@lang('basic.value')</label>
				<input name="value" type="text" class="form-control" placeholder="{{ __('basic.value')}}" maxlength="255"  value="{{ old('value') }}"  >
				{!! ($errors->has('value') ? $errors->first('value', '<p class="text-danger">:message</p>') : '') !!}
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