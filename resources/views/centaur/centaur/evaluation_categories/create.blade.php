
<div class="modal-header">
	<h2 class="col-6 float_l">@lang('questionnaire.add_category')</h2>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('evaluation_categories.store') }}">
		<div class="form-group {{ ($errors->has('questionnaire_id'))  ? 'has-error' : '' }}">
			<label>@lang('questionnaire.questionnaire')</label>
			<select  class="form-control"  name="questionnaire_id" value="{{ old('questionnaire_id') }}" autofocus required >
				<option value="" disabled selected></option>
				@foreach ($questionnaires as $questionnaire)
					<option value="{{ $questionnaire->id }}" {!! isset($questionnaire_id) && $questionnaire_id == $questionnaire->id ? 'selected' : '' !!}>{{ $questionnaire->name }}</option>
				@endforeach
			</select>
			{!! ($errors->has('questionnaire_id') ? $errors->first('questionnaire_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
			<label>@lang('basic.name')</label>
			<input name="name" type="text" class="form-control" value="{{ old('name') }}"  required >
			{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('coefficient')) ? 'has-error' : '' }}">
			<label>@lang('questionnaire.coef')</label>
			<input name="coefficient" type="text" pattern="[0-9]+(\,[0-9]{0,2})?%?" class="form-control" value="{{ old('coefficient') }}" required >
			{!! ($errors->has('coefficient') ? $errors->first('coefficient', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		{{ csrf_field() }}
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
	</form>
</div>
