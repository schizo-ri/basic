<div class="modal-header">
	<h3 class="panel-title">@lang('basic.car')</h3>
</div>
<div class="modal-body">
	<label for="">@lang('basic.manufacturer')</label>
		<p>{{ $car->manufacturer }}</p>
	<label for="">@lang('basic.model')</label>
		<p>{{ $car->model }}</p>
	<label for="">@lang('basic.license_plate')</label>
		<p>{{ $car->registration }}</p>
	<label for="">@lang('basic.chassis')</label>
		<p>{{ $car->chassis }}</p>
	<label for="">ENC</label>
		<p>{{ $car->enc }}</p>
	<label for="">@lang('basic.first_registration')</label>
		<p>{{ $car->first_registration }}</p>
	<label for="">@lang('basic.last_registration')</label>
		<p>{{ $car->last_registration }}</p>
	<label for="">@lang('basic.last_service')</label>
		<p>{{ $car->last_service }}</p>
	<label for="">@lang('basic.current_km')</label>
		<p>{{ $car->current_km }}</p>
	<label for="">@lang('basic.department')</label>
		<p>{{ $car->department['name'] }}</p>
	<label for="">@lang('basic.employee')</label>
		<p>{!! $car->employee_id ? $car->employee->user['first_name'] . ' ' .  $car->employee->user['last_name'] : '' !!}</p>
	<label for="travel">@lang('basic.private_car')</label>	
		<p>{!!  $car->private == 1 ? 'DA' : 'NE' !!}</p>
</div>