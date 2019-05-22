@extends('Centaur::layout')

@section('title', __('basic.edit_module'))

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">@lang('basic.edit_module')</h3>
            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('modules.update', $module->id) }}">
					<fieldset>
						<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="{{ __('basic.name')}}" name="name" type="text" value="{{ $module->name }}" />
							{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
							<input class="form-control" placeholder="{{ __('basic.description')}}" name="description" type="text" value="{{ $module->description }}" />
							{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						{{ csrf_field() }}
					{{ method_field('PUT') }}
						<input class="btn btn-lg btn-primary btn-block" type="submit" value="{{ __('basic.edit')}}">
					</fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@stop