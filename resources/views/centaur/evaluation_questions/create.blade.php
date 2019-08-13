@extends('Centaur::layout')

@section('title', __('questionnaire.add_question'))

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">@lang('questionnaire.add_question')</h3>
            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('evaluation_questions.store') }}">
					<div class="form-group {{ ($errors->has('category_id'))  ? 'has-error' : '' }}">
						<label>@lang('questionnaire.evaluation_question')</label>
						<select  class="form-control"  name="category_id" value="{{ old('category_id') }}" autofocus required >
							<option value="" disabled selected></option>
							@foreach ($categories as $category)
								<option value="{{ $category->id }}" {!! isset($category_id) && $category_id == $category->id ? 'selected' : '' !!}>{{ $category->name }}</option>
							@endforeach
						</select>
						{!! ($errors->has('category_id') ? $errors->first('category_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
						<label>@lang('basic.name')</label>
						<input name="name" type="text" class="form-control" value="{{ old('name') }}"  required >
						{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('description'))  ? 'has-error' : '' }}">
						<label>@lang('basic.description')</label>
						<textarea  name="description" type="text" class="form-control" rows="3" required >{{ old('description') }}</textarea>
						{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					{{ csrf_field() }}
					<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
				</form>
            </div>
        </div>
    </div>
</div>
@stop