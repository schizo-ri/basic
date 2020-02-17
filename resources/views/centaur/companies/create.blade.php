<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_company')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('companies.store') }}" enctype="multipart/form-data">
		<fieldset>
			<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
				<input class="form-control" placeholder="{{ __('basic.name')}}" name="name" type="text" maxlength="100" value="{{ old('name') }}" required />
				{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('address')) ? 'has-error' : '' }}">
				<input class="form-control" placeholder="{{ __('basic.address')}}" name="address" type="text" maxlength="100" value="{{ old('address') }}" required />
				{!! ($errors->has('address') ? $errors->first('address', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('city')) ? 'has-error' : '' }}">
				<input class="form-control" placeholder="{{ __('basic.city')}}" name="city" type="text" maxlength="50" value="{{ old('city') }}" required />
				{!! ($errors->has('city') ? $errors->first('city', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('oib')) ? 'has-error' : '' }}">
				<input class="form-control" placeholder="{{ __('basic.oib')}}" name="oib" maxlength="20" type="text" value="{{ old('oib') }}" required />
				{!! ($errors->has('oib') ? $errors->first('oib', '<p class="text-danger">:message</p>') : '') !!}
			</div>
				<div class="form-group {{ ($errors->has('director')) ? 'has-error' : '' }}">
				<input class="form-control" placeholder="{{ __('basic.director')}}" name="director" maxlength="50" type="text" value="{{ old('director') }}" required />
				{!! ($errors->has('director') ? $errors->first('director', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
				<input class="form-control" placeholder="E-mail" name="email" type="email" maxlength="50" value="{{ old('email') }}">
				{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('phone')) ? 'has-error' : '' }}">
				<input class="form-control" placeholder="{{ __('basic.phone')}}" name="phone" maxlength="50" type="text" value="{{ old('phone') }}">
				{!! ($errors->has('phone') ? $errors->first('phone', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group">
				<label>Logo</label>
				<input class="" type="file" name="fileToUpload" required>
			</div>
			{{ csrf_field() }}
			<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
		</fieldset>
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
//$.getScript( '/../js/validate.js');
</script>