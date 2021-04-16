@extends('Centaur::layout')

@section('title', __('questionnaire.edit_rating'))

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">@lang('questionnaire.edit_rating')</h3>
            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('evaluation_ratings.update', $evaluationRating->id) }}">
					<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
						<label>@lang('basic.name')</label>
						<input name="name" type="text" class="form-control" value="{{ $evaluationRating->name }}"  required >
						{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('rating')) ? 'has-error' : '' }}">
						<label>@lang('questionnaire.rating')</label>
						<input name="rating" type="text" pattern="[0-9]" class="form-control" value="{{ $evaluationRating->rating }}" required >
						{!! ($errors->has('rating') ? $errors->first('rating', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					<input class="btn-submit" type="submit" value="{{ __('basic.edit')}}" id="stil1">
				</form>
            </div>
        </div>
    </div>
</div>
@stop