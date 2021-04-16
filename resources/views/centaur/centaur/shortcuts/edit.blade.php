<div class="modal-header">
	<h3 class="panel-title">@lang('basic.edit_shortcut')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('shortcuts.update', $shortcut->id ) }}" >
		<fieldset>
			<div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
				<label for="">@lang('basic.title')</label>
				<input class="form-control" name="title" type="text" value="{{ $shortcut->title }}" maxlength="30" required />
				{!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			{{-- <div class="form-group {{ ($errors->has('color')) ? 'has-error' : '' }}">
				<label>@lang('basic.color')</label>
				<input class="form-control" name="color" type="color" value="{{ $shortcut->color }}" required />
				{!! ($errors->has('color') ? $errors->first('color', '<p class="text-danger">:message</p>') : '') !!}
			</div> --}}
			<div class="form-group {{ ($errors->has('url'))  ? 'has-error' : '' }}">
				<label>URL</label>
				<input class="form-control" name="url" type="url" value="{{ $shortcut->url }}" readonly/>
				{!! ($errors->has('url') ? $errors->first('url', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<input class="btn-submit" type="submit" id="submit" value="{{ __('basic.save')}}">
		</fieldset>
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
	$.getScript( '/../js/validate.js');
</script>