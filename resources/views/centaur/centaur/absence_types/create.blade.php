<div class="modal-header">
	<h3 class="panel-title">@lang('absence.add_abs_type')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('absence_types.store') }}">
		<div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
			<label>@lang('basic.name')</label>
			<input name="name" type="text" class="form-control" value="{{ old('name') }}" maxlength="50" required >
			{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('erp_id'))  ? 'has-error' : '' }}">
			<label>ERP ID</label>
			<input name="erp_id" type="text" class="form-control" maxlength="10" value="{{ old('erp_id') }}"  >
			{!! ($errors->has('erp_id') ? $errors->first('erp_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('mark'))  ? 'has-error' : '' }}">
			<label>@lang('absence.mark')</label>
			<input name="mark" type="text" class="form-control" maxlength="5" value="{{ old('mark') }}" required >
			{!! ($errors->has('mark') ? $errors->first('mark', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('min_days'))  ? 'has-error' : '' }}">
			<label>@lang('absence.min_days')</label>
			<input name="min_days" type="text" class="form-control" maxlength="2" value="{{ old('min_days') }}">
			{!! ($errors->has('min_days') ? $errors->first('min_days', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('max_days'))  ? 'has-error' : '' }}">
			<label>@lang('absence.max_days')</label>
			<input name="max_days" type="text" class="form-control" maxlength="2" value="{{ old('max_days') }}">
			{!! ($errors->has('max_days') ? $errors->first('max_days', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('temp'))  ? 'has-error' : '' }}">
			<label>Zahtjev - privremeni djelatnici </label>
			<input name="temp" type="checkbox" value="1">
			{!! ($errors->has('temp') ? $errors->first('temp', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('active'))  ? 'has-error' : '' }}">
			<label>@@lang('basic.active') </label>
			<input name="active" type="checkbox" value="1">
			{!! ($errors->has('active') ? $errors->first('active', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		{{ csrf_field() }}
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
