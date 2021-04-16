<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_educationArticle')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('education_articles.store') }}" enctype="multipart/form-data">
		<div class="form-group {{ ($errors->has('theme_id'))  ? 'has-error' : '' }}">
			<label>@lang('basic.educationTheme')</label>
			<select  class="form-control"  name="theme_id" value="{{ old('theme_id') }}"  >
				<option value="" disabled selected></option>
				@foreach ($educationThemes as $educationTheme)
					<option value="{{ $educationTheme->id }}" {!! isset($theme_id) && $theme_id == $educationTheme->id ? 'selected' : '' !!}>{{ $educationTheme->name }}</option>
				@endforeach
			</select>
			{!! ($errors->has('theme_id') ? $errors->first('theme_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('subject'))  ? 'has-error' : '' }}" >
			<label>@lang('basic.subject')</label>
			<input class="form-control"  name="subject" type="text" value="{{ old('subject') }}" />
			{!! ($errors->has('subject') ? $errors->first('subject', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('article'))  ? 'has-error' : '' }}">
			<label>@lang('basic.article'):</label>
			<textarea id="tinymce_textarea" name="article" ></textarea>
			{!! ($errors->has('article') ? $errors->first('article', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="padd_tb_20 form-group {{ ($errors->has('status'))  ? 'has-error' : '' }}">
			<label>Status</label>
			<input type="radio" class="" name="status" value="0" id="status_neaktivan" checked /><label for="status_neaktivan">@lang('basic.inactive') </label>
			<input type="radio" class="" name="status" value="1" id="status_aktivan"  /><label for="status_aktivan">@lang('basic.active')</label>
			{!! ($errors->has('status') ? $errors->first('status', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		{{ csrf_field() }}
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
	</form>
</div>
<!-- Summernote -->
<link href="{{ URL::asset('node_modules/summernote/summernote-lite.css') }}" rel="stylesheet">
<script src="{{ URL::asset('node_modules/summernote/summernote-lite.min.js') }}" ></script>
<script>
	$.getScript( '/../js/tinymce.js'); 
</script>

