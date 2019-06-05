@extends('Centaur::layout')

@section('title', __('basic.edit_permissions'))

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">@lang('basic.edit_permissions')</h3>
            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('department_roles.update', $departmentRole->id ) }}">
                <fieldset>
					<div class="form-group {{ ($errors->has('department_id')) ? 'has-error' : '' }}" >
						<label>{{ $departmentRole->department['name'] }}</label>
						<input hidden  name="department_id" value="{{ $departmentRole->department_id }}">
						{!! ($errors->has('department_id') ? $errors->first('department_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>
                    <h5>@lang('basic.permissions'):</h5>
					@foreach($tables as $table)
						@foreach($methodes as $methode)
							<div class="checkbox">
								<label>
									<input type="checkbox" name="permissions[{{$table}}.{{$methode}}]" value="1"
									{!! in_array($table . '.' . $methode , $permissions)  ? 'checked' : '' !!} />
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
