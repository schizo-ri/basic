<div class="modal-header">
	<h3 class="panel-title">{{ $customer->name }} - @lang('basic.add_location')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('customer_locations.store') }}">
		<fieldset>
			<input type="hidden" name="customer_id"value="{{ $customer->id }}">
			<div class="form-group {{ ($errors->has('address')) ? 'has-error' : '' }}">
				<input class="form-control" placeholder="{{ __('basic.address')}}" name="address" type="text" maxlength="100" value="{{ old('address') }}" required />
				{!! ($errors->has('address') ? $errors->first('address', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('city')) ? 'has-error' : '' }}">
				<input class="form-control" placeholder="{{ __('basic.city')}}" name="city" type="text" maxlength="50" value="{{ old('city') }}" required />
				{!! ($errors->has('city') ? $errors->first('city', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			{{ csrf_field() }}
			<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
		</fieldset>
	</form>
</div>