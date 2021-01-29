<div class="modal-header">
	<h3 class="panel-title">@lang('basic.edit_fuel')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('fuels.update', $fuel->id) }}" >
		<fieldset>
			<div class="form-group {{ ($errors->has('car_id')) ? 'has-error' : '' }}">
				<label>@lang('basic.car')</label>
				<select class="form-control" name="car_id" value="{{ old('car_id') }}" required >
					<option selected disabled ></option>
					@if(count($cars)>0)
						@foreach ($cars as $car)
							<option name="car_id" value="{{ $car->id }}" {!! $fuel->car_id == $car->id  ? 'selected' : '' !!}>{{ $car->registration }}</option>
						@endforeach
					@endif
				</select>
				{!! ($errors->has('vozilo_id') ? $errors->first('vozilo_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>			
			<div class="form-group {{ ($errors->has('date')) ? 'has-error' : '' }}">
				<label for="">@lang('basic.date')</label>
				<input class="form-control" name="date" type="date" value="{{ $fuel->date }}" required />
				{!! ($errors->has('date') ? $errors->first('date', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('liters')) ? 'has-error' : '' }}">
				<label>@lang('basic.liters')</label>
				<input class="form-control" name="liters"type="number" step="0.01" value="{{ $fuel->liters }}" required />
				{!! ($errors->has('liters') ? $errors->first('liters', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('km'))  ? 'has-error' : '' }}">
				<label>@lang('basic.current_km')</label>
				<input class="form-control" name="km" type="number" id="start_km" required value="{{ $fuel->km }}"/>	
				{!! ($errors->has('km') ? $errors->first('km', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			{{ method_field('PUT') }}
			{{ csrf_field() }}
			<input class="btn-submit" type="submit" id="submit" value="{{ __('basic.save')}}">
		</fieldset>
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>