<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_table')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('tables.store') }}" enctype="multipart/form-data">
		<fieldset>
			<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
				<label>@lang('basic.name')</label>
				<input class="form-control" placeholder="{{ __('basic.name')}}" name="name" type="text" value="{{ old('name') }}" required />
				{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
				<label>@lang('basic.description')</label>
				<input name="description" type="text" class="form-control" value="{{ old('description') }}" required >
				{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('emailing')) ? 'has-error' : '' }}">
				<label>@lang('basic.emailing')</label>
				<select class="form-control" name="emailing">
						<option value="0" >@lang('basic.inactive')</option>
						<option value="1" >@lang('basic.active')</option>
				</select>
				{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
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