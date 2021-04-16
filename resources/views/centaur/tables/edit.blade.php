<div class="modal-header">
	<h3 class="panel-title">@lang('basic.edit_table')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('tables.update', $table->id ) }}">
		<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
			<label>@lang('basic.name')</label>
			<input class="form-control" placeholder="{{ __('basic.name')}}" name="name" type="text" value="{{ $table->name }}" required />
			{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
			<label>@lang('basic.description')</label>
			<input name="description" type="text" class="form-control" value="{{ $table->description }}" required >
			{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('emailing')) ? 'has-error' : '' }}">
			<label>@lang('basic.emailing')</label>
			<select class="form-control" name="emailing">
					<option value="0" {!! $table->emailing == '0' ? 'selected' : '' !!} >@lang('basic.inactive')</option>
					<option value="1" {!! $table->emailing == '1' ? 'selected' : '' !!} >@lang('basic.active')</option>
			</select>
			{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		{{ csrf_field() }}
		{{ method_field('PUT') }}
		<input class="btn-submit" type="submit" value="{{ __('basic.edit')}}">
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
$.getScript( '/../js/validate.js');
</script>