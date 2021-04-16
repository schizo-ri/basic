<div class="modal-header">
	<h3 class="panel-title">@lang('basic.edit_service')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('vehical_services.update', $vehical_service->id) }}" >
		<fieldset>
			<div class="form-group {{ ($errors->has('car_id')) ? 'has-error' : '' }}">
				<label>@lang('basic.car')</label>
				<select class="form-control" name="car_id" value="{{ old('car_id') }}" required >
					@if(count($cars)>0)
						@foreach ($cars as $car)
							<option name="car_id" value="{{ $car->id }}" {!! $vehical_service->id == $car->id  ? 'selected' : '' !!}>{{ $car->registration }}</option>
						@endforeach
					@endif
				</select>
				{!! ($errors->has('vozilo_id') ? $errors->first('vozilo_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>			
			<div class="form-group {{ ($errors->has('date')) ? 'has-error' : '' }}">
				<label for="">@lang('basic.date')</label>
				<input class="form-control" name="date" type="date" value="{{ $vehical_service->date }}" required />
				{!! ($errors->has('date') ? $errors->first('date', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('price')) ? 'has-error' : '' }}">
				<label>@lang('basic.price')</label>
				<input class="form-control" name="price" type="number" step="0.01" value="{{ $vehical_service->price }}" required />
				{!! ($errors->has('price') ? $errors->first('price', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('km'))  ? 'has-error' : '' }}">
				<label>@lang('basic.current_km')</label>
				<input class="form-control" name="km" type="number" id="start_km" value="{{ $vehical_service->km }}"/>	
				{!! ($errors->has('km') ? $errors->first('km', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('comment'))  ? 'has-error' : '' }}">
				<label>@lang('basic.comment')</label>
				<textarea class="form-control" name="comment" type="text" id="comment" >{{ $vehical_service->comment }}</textarea>	
				{!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<input class="btn-submit" type="submit" id="submit" value="{{ __('basic.save')}}">
		</fieldset>
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
