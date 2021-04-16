<div class="modal-header">
	<h3 class="panel-title">Unesi potrošnju</h3>
</div>
<div class="modal-body energy_consumptions">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('energy_consumptions.store') }}" enctype="multipart/form-data">
		<fieldset>
			<div class="form-group {{ ($errors->has('location_id'))  ? 'has-error' : '' }}">
				<label>Lokacija</label>
				<select class="form-control" name="location_id" required >
					<option selected disabled></option>
					@foreach($locations as $location)
						<option name="location_id" value="{{ $location->id }}">{{ $location->name }}</option>
					@endforeach	
				</select>
				{!! ($errors->has('location_id') ? $errors->first('location_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('energy_id'))  ? 'has-error' : '' }}">
				<label>Energent</label>
				<select class="form-control" name="energy_id" required>
					<option selected disabled></option>
					@foreach($energySources as $energy)
						<option name="energy_id" value="{{ $energy->id }}" >{{ $energy->name }}</option>
					@endforeach	
				</select>
				<span id="no_counter" hidden ></span>
				{!! ($errors->has('energy_id') ? $errors->first('energy_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('date')) ? 'has-error' : '' }}">
				<label>@lang('basic.date')</label>
				<input class="form-control" name="date" type="date" value="{!! old('date') ? old('date') : Carbon\Carbon::now()->format('Y-m-d') !!}" required />
				{!! ($errors->has('date') ? $errors->first('date', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('counter')) ? 'has-error' : '' }}">
				<label>Stanje brojila</label>
				<p class="last_counter">Zadnje upisano stanje: <span></span></p>
				<p class="result">Razika od prošlog upisa <span id="result"></span></p>
				<input name="counter" type="number" class="form-control" id="counter" maxlength="20" value="{{ old('counter') }}" required >
				<br>
				<label class="hidden hidden_counter">Stanje brojila 2</label>
				<p class="last_counter2 hidden hidden_counter">Zadnje upisano stanje: <span></span></p>
				<p class="result2 hidden hidden_counter">Razika od prošlog upisa <span id="result2"></span></p>
				<input class="hidden hidden_counter" name="counter2" type="number" class="form-control" id="counter2" maxlength="20" value="{{ old('counter2') }}" required disabled  >
				{!! ($errors->has('counter') ? $errors->first('counter', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('comment'))  ? 'has-error' : '' }}" style="padding-top: 10px">
				<label>@lang('basic.comment') </label>
				<textarea class="form-control" maxlength="65535" name="comment"></textarea>
				{!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			{{ csrf_field() }}
			<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
		</fieldset>
	</form>
</div>
<script>
	$.getScript('/../js/energy.js');
	
</script>