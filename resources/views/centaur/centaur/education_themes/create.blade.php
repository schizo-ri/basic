<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_educationTheme')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('education_themes.store') }}">
		<div class="form-group {{ ($errors->has('education_id'))  ? 'has-error' : '' }}">
			<label>@lang('basic.education')</label>
			<select  class="form-control"  name="education_id" value="{{ old('education_id') }}" required >
				<option value="" disabled selected></option>
				@foreach ($educations as $education)
					<option value="{{ $education->id }}" {!! isset($education_id) && $education_id == $education->id ? 'selected' : '' !!}>{{ $education->name }}</option>
				@endforeach
			</select>
			{!! ($errors->has('education_id') ? $errors->first('education_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
			<label>@lang('basic.educationTheme')</label>
			<input name="name" type="text" class="form-control" value="{{ old('name') }}" maxlength="100" required>
			{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		{{ csrf_field() }}
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
	</form>
</div>