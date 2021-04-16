<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_shortcut')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('shortcuts.store') }}" >
		<fieldset>
			<div class="form-group {{ ($errors->has('table')) ? 'has-error' : '' }}" >
				<label>Modul</label>
				<select class="form-control" name="table" required value="{{ old('table') }}" required >
					<option value="" disabled selected ></option>
					@foreach($tables as $table)
						<option value="{{ $table->id}}" >{{ $table->description }}</option>
					@endforeach
				</select>
				{!! ($errors->has('table') ? $errors->first('table', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
				<label for="">@lang('basic.title')</label>
				<input class="form-control" name="title" type="text" value="{{ old('title') }}" maxlength="30" required />
				{!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			{{-- <div class="form-group {{ ($errors->has('url'))  ? 'has-error' : '' }}">
				<label>URL</label>
				<input class="form-control" name="url" type="url" value="{{ old('url') }}" readonly/>
				{!! ($errors->has('url') ? $errors->first('url', '<p class="text-danger">:message</p>') : '') !!}
			</div> --}}
			{{ csrf_field() }}
			<input class="btn-submit" type="submit" id="submit" value="{{ __('basic.save')}}">
		</fieldset>
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
	$.getScript( '/../js/validate.js');
</script>