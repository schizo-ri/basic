<div class="modal-header">
	<h3 class="panel-title">@lang('basic.create_user')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('auth.register.attempt') }}">
		<fieldset>
			<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
				<input class="form-control" placeholder="E-mail" name="email" type="text" value="{{ old('email') }}">
				{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group  {{ ($errors->has('password')) ? 'has-error' : '' }}">
				<input class="form-control" placeholder="{{ __('welcome.password')}}" name="password" type="password">
				{!! ($errors->has('password') ? $errors->first('password', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group  {{ ($errors->has('password_confirmation')) ? 'has-error' : '' }}">
				<input class="form-control" placeholder="{{ __('welcome.conf_password')}}" name="password_confirmation" type="password">
				{!! ($errors->has('password_confirmation') ? $errors->first('password_confirmation', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			{{ csrf_field() }}
			<input class="btn-submit" type="submit" value="{{ __('welcome.signUp') }}">
		</fieldset>
	</form>
</div>
