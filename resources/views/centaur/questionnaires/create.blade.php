@extends('Centaur::layout')

@section('title', __('questionnaire.add_questionnaire'))

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">@lang('questionnaire.add_questionnaire')</h3>
            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('questionnaires.store') }}">
					<div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
						<label>@lang('basic.name')</label>
						<input name="name" type="text" class="form-control" value="{{ old('name') }}" autofocus required >
						{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('description'))  ? 'has-error' : '' }}">
						<label>@lang('basic.description')</label>
						<textarea name="description" type="text" rows="3" class="form-control" required>{{ old('description') }}
						</textarea>
						{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="aktivna form-group {{ ($errors->has('status'))  ? 'has-error' : '' }}">
						<label>Status</label>
						<input type="radio" class="" name="status" value="0" checked />@lang('basic.inactive')  
						<input type="radio" class="" name="status" value="1" />@lang('basic.active') 
						{!! ($errors->has('status') ? $errors->first('status', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					{{ csrf_field() }}
					<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
				</form>
            </div>
        </div>
    </div>
</div>
@stop