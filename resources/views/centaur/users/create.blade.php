<div class="modal-header">
	<h3 class="panel-title">Novi korisnik</h3>
</div>
<div class="modal-body">
    <form accept-charset="UTF-8" role="form" method="post" action="{{ route('users.store') }}">
    <fieldset>
        <div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="First Name" name="first_name" type="text" value="{{ old('first_name') }}" />
            {!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="Last Name" name="last_name" type="text" value="{{ old('last_name') }}" />
            {!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}" >
            <input class="form-control" placeholder="E-mail" name="email" type="text" value="{{ old('email') }}" required>
            {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <h5>Roles</h5>
        @foreach ($roles as $role)
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="roles[{{ $role->slug }}]" value="{{ $role->id }}" >
                    {{ $role->name }}
                </label>
            </div>
        @endforeach
        <hr />
        <div class="form-group  {{ ($errors->has('password')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="Password" name="password" type="password" value="" required>
            {!! ($errors->has('password') ? $errors->first('password', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('password_confirmation')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="Confirm Password" name="password_confirmation" type="password" required/>
            {!! ($errors->has('password_confirmation') ? $errors->first('password_confirmation', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('color')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="Izaberi boju" name="color" type="color" required/>
            {!! ($errors->has('color') ? $errors->first('color', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="checkbox">
            <label>
                <input name="activate" type="checkbox" value="true" {{ old('activate') == 'true' ? 'checked' : ''}}> Activate
            </label>
        </div>
        {{ csrf_field() }}
        <input class="btn btn-lg btn-primary btn-block" type="submit" value="Create">
    </fieldset>
    </form>
</div>