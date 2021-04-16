<div class="modal-header">
	<h3 class="panel-title">Ispravi lokaciju</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('energy_locations.update', $energyLocation->id ) }}" enctype="multipart/form-data">
		<fieldset>
			<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
				<label for="">@lang('basic.name')</label>
				<input class="form-control" name="name" type="text" maxlength="50" value="{{  $energyLocation->name }}" required />
				{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('address')) ? 'has-error' : '' }}">
				<label for="">@lang('basic.address')</label>
				<input class="form-control" name="address" type="text" maxlength="50" value="{{  $energyLocation->address }}" required />
				{!! ($errors->has('address') ? $errors->first('address', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('city')) ? 'has-error' : '' }}">
				<label for="">@lang('basic.city')</label>
				<input class="form-control" name="city" type="text" maxlength="20" value="{{  $energyLocation->city }}" required />
				{!! ($errors->has('city') ? $errors->first('city', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('phone')) ? 'has-error' : '' }}">
				<label for="">@lang('basic.phone')</label>
				<input class="form-control" placeholder="{{ __('basic.phone')}}" name="phone" maxlength="20" type="text" value="{{  $energyLocation->phone }}" required />
				{!! ($errors->has('phone') ? $errors->first('phone', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('comment'))  ? 'has-error' : '' }}" style="padding-top: 10px">
				<label>@lang('basic.comment') </label>
				<textarea class="form-control" maxlength="65535" name="comment">{{  $energyLocation->comment }}</textarea>
				{!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
		</fieldset>
	</form>
</div>