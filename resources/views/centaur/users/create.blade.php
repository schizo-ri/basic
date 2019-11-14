<div class="modal-header">
        <h3 class="panel-title">@lang('basic.create_user')</h3>
    </div>
    <div class="modal-body">
    <form accept-charset="UTF-8" role="form" method="post" action="{{ route('users.store') }}">
        <div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="{{ __('basic.f_name')}}" name="first_name" type="text" value="{{ old('first_name') }}" required />
            {!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="{{ __('basic.l_name')}}" name="last_name" type="text" value="{{ old('last_name') }}" required />
            {!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="E-mail" name="email" type="text" value="{{ old('email') }}" required >
            {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <h5>{{ __('basic.roles')}}</h5>
        @foreach ($roles as $role)
            <div class="checkbox ">
                <label>
                    @if($role->slug != 'superadmin')
                        <input type="checkbox" name="roles[{{ $role->slug }}]" value="{{ $role->id }}">
                        {{ $role->name }}
                    @endif
                    @if(Sentinel::inRole('superadmin') && $role->slug == 'superadmin' )
                        <input type="checkbox" name="roles[{{ $role->slug }}]" value="{{ $role->id }}">
                        {{ $role->name }}
                    @endif
                </label>
            </div>
        @endforeach
        <hr />
        <div class="form-group  {{ ($errors->has('password')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="{{ __('basic.password')}}" name="password" type="password" value="" required>
            {!! ($errors->has('password') ? $errors->first('password', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('password_confirmation')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="{{ __('basic.conf_password')}}" name="password_confirmation" type="password" />
            {!! ($errors->has('password_confirmation') ? $errors->first('password_confirmation', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="checkbox">
            <label>
                <input name="activate" type="checkbox" value="true" {{ old('activate') == 'true' ? 'checked' : ''}}> @lang('basic.activate')
            </label>
        </div>
        <input name="_token" value="{{ csrf_token() }}" type="hidden">
        <input class="btn btn-lg btn-primary btn-block" type="submit" value="{{ __('basic.save')}}">
    </form>
</div>