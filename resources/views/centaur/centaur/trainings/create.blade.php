<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_training')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('trainings.store') }}">
		<div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
			<label>@lang('basic.name')</label>
			<input name="name" type="text" class="form-control" value="{{ old('name') }}" maxlength="191" required >
			{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('description'))  ? 'has-error' : '' }}">
			<label>@lang('basic.description')</label>
			<input name="description" type="text" class="form-control" value="{{ old('description') }}" maxlength="191" required >
			{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('institution'))  ? 'has-error' : '' }}">
			<label>@lang('basic.institution')</label>
			<input name="institution" type="text" class="form-control" value="{{ old('institution') }}" maxlength="191" required >
			{!! ($errors->has('institution') ? $errors->first('institution', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		{{ csrf_field() }}
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
