@extends('Centaur::layout')

@section('title', __('basic.edit_role'))

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">@lang('basic.edit_role')</h3>
            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('roles.update', $role->id) }}">
                <fieldset>
                    <div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
                        <input class="form-control" placeholder="{{ __('basic.name')}}" name="name" type="text" value="{{ $role->name }}" required />
                        {!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    <div class="form-group {{ ($errors->has('slug')) ? 'has-error' : '' }}">
                        <input class="form-control" placeholder="{{ __('absence.mark')}}" name="slug" type="text" value="{{ $role->slug }}" required />
                        {!! ($errors->has('slug') ? $errors->first('slug', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
					
                    <h5>@lang('basic.permissions'):</h5>
					@foreach($tables as $table)
						@foreach($methodes as $methode)
							<div class="checkbox">
								<label>
									<input type="checkbox" name="permissions[{{$table}}.{{$methode}}]" value="1" 
									{!! $role->hasAccess($table . '.' . $methode) ? 'checked' : '' !!} />
									{{$table}}.{{$methode}}
								</label>
							</div>
						@endforeach
					@endforeach
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