<div class="modal-header">
	<h3 class="panel-title">{{ $customer_location->customer->name }} - @lang('basic.edit_location')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('customer_locations.update', $customer_location->id ) }}">
		<fieldset>
			<div class="form-group {{ ($errors->has('address')) ? 'has-error' : '' }}">
				<input class="form-control" placeholder="{{ __('basic.address')}}" name="address" type="text" maxlength="100" value="{{ $customer_location->address }}" required />
				{!! ($errors->has('address') ? $errors->first('address', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('city')) ? 'has-error' : '' }}">
				<input class="form-control" placeholder="{{ __('basic.city')}}" name="city" type="text" maxlength="50" value="{{ $customer_location->city }}" required />
				{!! ($errors->has('city') ? $errors->first('city', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			@csrf
			@method('PUT')
			<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
		</fieldset>
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>