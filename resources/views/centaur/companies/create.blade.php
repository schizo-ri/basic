<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_company')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('companies.store') }}" enctype="multipart/form-data">
		<fieldset>
			<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
				<input class="form-control" placeholder="{{ __('basic.name')}}" name="name" type="text" value="{{ old('name') }}" required />
				{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('address')) ? 'has-error' : '' }}">
				<input class="form-control" placeholder="{{ __('basic.address')}}" name="address" type="text" value="{{ old('address') }}" required />
				{!! ($errors->has('address') ? $errors->first('address', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('city')) ? 'has-error' : '' }}">
				<input class="form-control" placeholder="{{ __('basic.city')}}" name="city" type="text" value="{{ old('city') }}" required />
				{!! ($errors->has('city') ? $errors->first('city', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('oib')) ? 'has-error' : '' }}">
				<input class="form-control" placeholder="{{ __('basic.oib')}}" name="oib" type="text" value="{{ old('oib') }}" required />
				{!! ($errors->has('oib') ? $errors->first('oib', '<p class="text-danger">:message</p>') : '') !!}
			</div>
				<div class="form-group {{ ($errors->has('director')) ? 'has-error' : '' }}">
				<input class="form-control" placeholder="{{ __('basic.director')}}" name="director" type="text" value="{{ old('director') }}" required />
				{!! ($errors->has('director') ? $errors->first('director', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
				<input class="form-control" placeholder="E-mail" name="email" type="email" value="{{ old('email') }}">
				{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('phone')) ? 'has-error' : '' }}">
				<input class="form-control" placeholder="{{ __('basic.phone')}}" name="phone" type="text" value="{{ old('phone') }}">
				{!! ($errors->has('phone') ? $errors->first('phone', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group">
				<label>Logo</label>
				<input class="" type="file" name="fileToUpload" required>
			</div>
			{{ csrf_field() }}
			<input class="btn btn-lg btn-primary btn-block" type="submit" value="{{ __('basic.save')}}">
		</fieldset>
	</form>
</div>
