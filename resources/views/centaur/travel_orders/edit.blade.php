@php
	/* dd(get_defined_vars()); */
@endphp
<div class="modal-header">
	<h3 class="panel-title">@lang('basic.edit_travel')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('travel_orders.update', $travel->id) }}">
		<div class="form-group datum {{ ($errors->has('date')) ? 'has-error' : '' }}">
			<label>@lang('basic.date')</label>
			<input name="date" type="date" class="form-control" value="{{ $travel->date }}" required>
			{!! ($errors->has('date') ? $errors->first('date', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
			<label for="">@lang('basic.employee')</label>
			<select class="form-control" name="employee_id" required>
				<option selected disabled></option>
				@foreach ($employees as $employee)
					<option value="{{ $employee->id }}" {!! $travel->employee_id == $employee->id ? 'selected' : '' !!} >{{ $employee->user['first_name'] . ' ' .  $employee->user['last_name'] }}</option>
				@endforeach
			</select>
			{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('car_id')) ? 'has-error' : '' }}">
			<label for="">@lang('basic.car')</label>
			<select class="form-control" name="car_id" required>
				<option selected disabled></option>
				@foreach ($cars as $car)
					<option value="{{ $car->id }}" {!! $travel->car_id == $car->id ? 'selected' : '' !!} >{{ $car->model . ' ' .  $car->registration }}</option>
				@endforeach
			</select>
			{!! ($errors->has('car_id') ? $errors->first('car_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
	
		<div class="form-group {{ ($errors->has('locco_id')) ? 'has-error' : '' }}">
			<label for="">@lang('basic.locco')</label>
			<select class="form-control" name="locco_id" >
				<option selected ></option>
				@foreach ($loccos as $locco)
					<option value="{{ $locco->id }}" {!! $travel->locco_id == $locco->id ? 'selected' : '' !!}>{{ $locco->date }} {!! $locco->employee ? ', ' . $locco->employee->last_name : '' !!}  {{ ', ' . $locco->destination }}</option>
				@endforeach
			</select>
			{!! ($errors->has('locco_id') ? $errors->first('locco_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('destination')) ? 'has-error' : '' }}">
			<label>@lang('basic.destination')</label>
			<input name="destination" type="text" class="form-control" value="{{ $travel->destination }}" required >
			{!! ($errors->has('destination') ? $errors->first('destination', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
			<label>@lang('basic.description')</label>
			<input name="description" type="text" class="form-control" value="{{ $travel->description }}" >
			{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('days')) ? 'has-error' : '' }}">
			<label>@lang('basic.days_no') @lang('absence.days')</label>
			<input name="days" type="number" class="form-control" value="{{ $travel->days }}" required>
			{!! ($errors->has('days') ? $errors->first('days', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group datum {{ ($errors->has('start_date')) ? 'has-error' : '' }}">
			<label>@lang('absence.start_date')</label>
			<input name="start_date" type="datetime-local" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($travel->start_date )) }}" required>
			{!! ($errors->has('start_date') ? $errors->first('start_date', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group datum {{ ($errors->has('end_date')) ? 'has-error' : '' }}">
			<label>@lang('absence.end_date')</label>
			<input name="end_date" type="datetime-local" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($travel->end_date )) }}" required>
			{!! ($errors->has('end_date') ? $errors->first('end_date', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		@if(Sentinel::getUser()->hasAccess(['travel_orders.update']))
			<div class="form-group {{ ($errors->has('calculate_employee')) ? 'has-error' : '' }}">
				<label for="">@lang('basic.calculate_employee')</label>
				<select class="form-control" name="calculate_employee" >
					<option selected disabled></option>
					@foreach ($employees as $employee)
						<option value="{{ $employee->id }}"  {!! $travel->calculate_employee == $employee->id ? 'selected' : '' !!}>{{ $employee->user['first_name'] . ' ' .  $employee->user['last_name'] }}</option>
					@endforeach
				</select>
				{!! ($errors->has('calculate_employee') ? $errors->first('calculate_employee', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('advance')) ? 'has-error' : '' }}">
				<label>@lang('basic.advance')</label>
				<input name="advance" type="number" step="0.01" class="form-control" value="{{  $travel->advance }}" >
				{!! ($errors->has('days') ? $errors->first('days', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group datum {{ ($errors->has('advance_date')) ? 'has-error' : '' }}">
				<label>@lang('basic.advance_date')</label>
				<input name="advance_date" type="date" class="form-control" value="{{ $travel->advance_date }}" >
				{!! ($errors->has('advance_date') ? $errors->first('advance_date', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('rest_payout')) ? 'has-error' : '' }}">
				<label>@lang('basic.rest_payout')</label>
				<input name="rest_payout" type="number" step="0.01" class="form-control" value="{{ $travel->rest_payout }}" >
				{!! ($errors->has('rest_payout') ? $errors->first('rest_payout', '<p class="text-danger">:message</p>') : '') !!}
			</div>
		@endif
		{{ csrf_field() }}
		{{ method_field('PUT') }}
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
		<a href="" class="modal_close float_r" rel="modal:close">@lang('basic.cancel')</a>
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
$.getScript( '/../js/validate.js');
</script>