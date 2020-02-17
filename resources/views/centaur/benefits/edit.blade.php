<span hidden class="locale" >{{ App::getLocale() }}</span>
<div class="modal-header">
	<h3 class="panel-title">@lang('basic.edit_benefit')</h3>
</div>
<div class="modal-body">
	<form class="form_benefit" accept-charset="UTF-8" role="form" method="post" action="{{ route('benefits.update', $benefit->id ) }}">
		<fieldset>
			<div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
				<label>@lang('basic.name')</label>
				<input name="name" type="text" class="form-control" maxlength="255" value="{{ $benefit->name }}" required >
				{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('description'))  ? 'has-error' : '' }}">
				<label>@lang('basic.description')</label>
				<textarea name="description" type="text" rows="4" maxlength="255" class="form-control" required>{{ $benefit->description }}</textarea>
				{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('comment')) ? 'has-error' : '' }}">
				<textarea name="comment" id="mytextarea"  maxlength="16777215"  >{!! $benefit->comment !!}</textarea>
				{!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('url'))  ? 'has-error' : '' }}">
				<label>URL</label>
				<input name="url" type="url" class="form-control" maxlength="255" value="{{ $benefit->url }}"  >
				{!! ($errors->has('url') ? $errors->first('url', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('url2'))  ? 'has-error' : '' }}">
				<label>URL</label>
				<input name="url2" type="url" class="form-control" maxlength="255" value="{{ $benefit->url2 }}"  >
				{!! ($errors->has('url2') ? $errors->first('url2', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group">
				<label>Status</label>
				<input type="radio" id="status_0" name="status" value="0" {!! $benefit->status == 0 ? 'checked' : '' !!} /><label for="status_0">@lang('basic.inactive')</label>
				<input type="radio" id="status_1" name="status" value="1"  {!! $benefit->status == 1 ? 'checked' : '' !!}} /><label for="status_1">@lang('basic.active')</label>
			</div>
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
		</fieldset>
	</form>
</div>
<script>
	$.getScript( '/../js/validate.js');
	$.getScript( '/../js/tinymce.js');
	$('body').on($.modal.CLOSE, function(event, modal) {
		$.getScript('/../node_modules/tinymce/tinymce.min.js');
	});
</script>
