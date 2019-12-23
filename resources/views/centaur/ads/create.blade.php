<!-- Jquery validation -->
<script src="{{ URL::asset('/../node_modules/jquery-validation/dist/jquery.validate.js') }}"></script>
<script src="{{ URL::asset('/../node_modules/jquery-validation/dist/additional-methods.js') }}"></script>
@if (App::isLocale('hr'))
	<script src="{{ URL::asset('/../node_modules/jquery-validation/dist/localization/messages_hr.js') }}"></script>
@endif
<div class="modal-header">
		<h3 class="panel-title">@lang('basic.add_ad')</h3>
	</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('ads.store') }}" enctype="multipart/form-data" >
		<div class="form-group {{ ($errors->has('category_id'))  ? 'has-error' : '' }}">
			<label>@lang('basic.ad_category')</label>
			<select  class="form-control" name="category_id" value="{{ old('category_id') }}" required>
				<option value="" disabled selected></option>
				@foreach ($categories as $category)
					<option value="{{ $category->id }}" {!! isset($category_id) && $category_id == $category->id ? 'selected' : '' !!}>{{ $category->name }}</option>
				@endforeach
			</select>
			{!! ($errors->has('category_id') ? $errors->first('category_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('subject'))  ? 'has-error' : '' }}">
			<label>@lang('basic.subject')</label>
			<input name="subject" type="text" class="form-control" value="{{ old('subject') }}" required >
			{!! ($errors->has('subject') ? $errors->first('subject', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('description'))  ? 'has-error' : '' }}">
			<label>@lang('basic.description'):</label>
			<textarea name="description" rows="5" required></textarea>
			{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group">
			<label class="label_file" for="file">Dodaj sliku<span><img src="{{ URL::asset('icons/download.png') }}" />Upload image</span></label>
			<input type='file' id="file" name="fileToUpload" >
		</div>

		<div class="form-group {{ ($errors->has('price'))  ? 'has-error' : '' }}">
			<label>@lang('basic.price')</label>
			<input name="price" type="text" class="form-control" value="{{ old('price') }}" maxlength="100" >
			{!! ($errors->has('price') ? $errors->first('price', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		{{ csrf_field() }}
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
		<a href="#" rel="modal:close" class="btn-close">@lang('basic.cancel')</a>
	</form>
</div>
<script>
	$( "form" ).validate({
		rules: {
			subject: {
				required: true,
				maxlength: 150
			},
			description: {
				required: true,
				maxlength: 65535
			}
		}
	});
</script>