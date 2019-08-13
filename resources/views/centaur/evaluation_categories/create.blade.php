@extends('Centaur::layout')

@section('title', __('questionnaire.add_category'))

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">@lang('questionnaire.add_category')</h3>
            </div>
            <div class="panel-body">
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
        </div>
    </div>
</div>
@stop