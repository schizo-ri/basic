<div class="modal-header">
	<h3 class="panel-title">@lang('basic.edit_locco')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('loccos.update', $locco->id) }}" >
		<fieldset>
			<div class="form-group {{ ($errors->has('car_id')) ? 'has-error' : '' }}">
				<label>@lang('basic.car')</label>
				<select class="form-control" name="car_id" id="car_id" value="{{ old('car_id') }}" required >
					<option selected disabled ></option>
					@foreach ($cars as $car)
						<option name="car_id" value="{{ $car->id }}" {!! $locco->car_id == $car->id  ? 'selected' : '' !!} >{{ $car->registration }}</option>
					@endforeach
				</select>
				{!! ($errors->has('vozilo_id') ? $errors->first('vozilo_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}" required>
				<label>@lang('basic.employee')</label>
				<select  class="form-control" name="employee_id" >
					<label>@lang('basic.employee')</label>
					<option value="" selected disabled></option>
					@foreach ($employees as $employee)
						<option value="{{ $employee->id }}" {!! $locco->employee_id == $employee->id ? 'selected' : '' !!} >{{ $employee->user['first_name'] . ' ' .  $employee->user['last_name'] }}</option>
					@endforeach
				</select>
				{!! ($errors->has('department_id') ? $errors->first('department_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('date')) ? 'has-error' : '' }}">
				<label for="">@lang('basic.date')</label>
				<input class="form-control" name="date" type="datetime-local" value="{{ date('Y-m-d\TH:i', strtotime($locco->date )) }}" required />
				{!! ($errors->has('date') ? $errors->first('date', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('end_date')) ? 'has-error' : '' }}">
				<label for="">@lang('absence.end_date')</label>
				<input class="form-control" name="end_date" type="datetime-local" required value="{!! $locco->end_date ? date('Y-m-d\TH:i', strtotime($locco->end_date )) : '' !!}"  />
				{!! ($errors->has('date') ? $errors->first('date', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('starting_point')) ? 'has-error' : '' }}">
				<label>@lang('basic.starting_point')</label>
				<input class="form-control" placeholder="{{ __('basic.starting_point') }}" name="starting_point" type="text" value="{{ $locco->starting_point }}" required />
				{!! ($errors->has('starting_point') ? $errors->first('starting_point', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('destination')) ? 'has-error' : '' }}">
				<label>@lang('basic.destination')</label>
				<input class="form-control" placeholder="{{ __('basic.destination') }}" name="destination" type="text" value="{{ $locco->destination }}" required />
				{!! ($errors->has('destination') ? $errors->first('destination', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('start_km'))  ? 'has-error' : '' }}">
				<label>@lang('basic.start_km')</label>
				<input class="form-control" name="start_km" type="number" id="start_km" required value="{{ $locco->start_km }}"/>	
				{!! ($errors->has('start_km') ? $errors->first('start_km', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('end_km'))  ? 'has-error' : '' }}">
				<label>@lang('basic.end_km')</label>
				<input class="form-control" name="end_km" id="end_km" type="number" required value="{{ $locco->end_km }}"/>	
				{!! ($errors->has('end_km') ? $errors->first('end_km', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('distance'))  ? 'has-error' : '' }}">
				<label>@lang('basic.distance')</label>
				<input class="form-control" name="distance" id="distance" type="number"  value="{{ $locco->distance }}" readonly required/>	
				{!! ($errors->has('distance') ? $errors->first('distance', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group">
				<label>@lang('basic.comment')</label>
				<textarea class="form-control" name="comment" >{{ $locco->comment }}</textarea>
			</div>
			<div class="servis form-group">
				<label for="servis">@lang('basic.malfunction')</label>
				<input class="" type="checkbox" name="servis" value="servis" id="servis" value=""/>
			</div>
			@if ( $travel )
				<input type="hidden" name="travel_id" value="{{ $locco->travel_id }}"/>
			@else
				<div class="servis form-group">
					<label for="travel">@lang('basic.create_travel')</label>
					<input class="" type="checkbox" name="travel" value="travel" id="travel" {!! $locco->travel_id ? 'checked' : '' !!} />
				</div>
			@endif
			@method('PUT')
			{{ csrf_field() }}
			<input class="btn-submit" type="submit" id="submit" value="{{ __('basic.save')}}">
		</fieldset>
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
	$.getScript( '/../js/validate.js');
	$('#end_km').change(function() {
		var poc_km = $('#start_km').val();
		var zav_km = $('#end_km').val();
		var udaljenost = zav_km - poc_km;
		$('#distance').val(udaljenost);
		if (udaljenost < 0 ) {
			$('#distance').css('border','1px solid red');
			$('.btn-submit').attr('disabled', 'disabled');
		} else {
			$('#distance').css('border','1px solid #F0F4FF');
			$('.btn-submit').removeAttr('disabled');
		}
	});

	$('#car_id').change(function(){
		var car_id = $( this ).val();

		try {
			var token = $('meta[name="csrf-token"]').attr('content');

			$.ajax({
				url:  "last_km", 
				type: 'post',
				data: {
						'_token':  token,
						'car_id': car_id,                   
					}
			})
			.done(function( response ) {     
				var current_km = response;
				console.log(current_km);  
				$('#start_km').val(current_km);
			})
			.fail(function() {
				alert( "Došlo je do problema" );
			})
		} catch (error) {
			
		}
	});
</script>