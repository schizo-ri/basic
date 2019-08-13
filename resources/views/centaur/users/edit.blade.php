@extends('Centaur::layout')

@section('title', __('basic.edit_user'))

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">@lang('basic.edit_user')</h3>
            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('users.update', $user->id) }}">
                <fieldset>
                    <div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }}">
                        <input class="form-control" placeholder="{{ __('basic.f_name')}}" name="first_name" type="text" value="{{ $user->first_name }}" required />
                        {!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    <div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
                        <input class="form-control" placeholder="{{ __('basic.l_name')}}" name="last_name" type="text" value="{{ $user->last_name }}" required />
                        {!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    <div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
                        <input class="form-control" placeholder="E-mail" name="email" type="text" value="{{ $user->email }}" required >
                        {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    <h5>{{ __('basic.roles')}}</h5>
					@if (Sentinel::getUser()->id != $user->id || Sentinel::inRole('superadmin') )
						@foreach ($roles as $role)
							<div class="checkbox">
								<label>
									@if($role->slug != 'superadmin')
										 <input type="checkbox" name="roles[{{ $role->slug }}]" value="{{ $role->id }}" {{ $user->inRole($role) ? 'checked' : '' }}>
									{{ $role->name }}
									@endif
									@if(Sentinel::inRole('superadmin') && $role->slug == 'superadmin' )
										 <input type="checkbox" name="roles[{{ $role->slug }}]" value="{{ $role->id }}" {{ $user->inRole($role) ? 'checked' : '' }}>
									{{ $role->name }}
									@endif
								   
								</label>
							</div>
						@endforeach
					@else
						<p>{{ $user->roles->implode('name', ', ')  }}</p>
					@endif
                    <hr />
                    <div class="form-group  {{ ($errors->has('password')) ? 'has-error' : '' }}">
                        <input class="form-control" placeholder="{{ __('basic.password')}}" name="password" type="password" value="" >
                        {!! ($errors->has('password') ? $errors->first('password', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    <div class="form-group {{ ($errors->has('password_confirmation')) ? 'has-error' : '' }}">
                        <input class="form-control" placeholder="{{ __('basic.conf_password')}}" name="password_confirmation" type="password" />
                        {!! ($errors->has('password_confirmation') ? $errors->first('password_confirmation', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input name="_method" value="PUT" type="hidden">
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="{{ __('basic.edit')}}">
                </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
