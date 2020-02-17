<div class="modal-header">
	<h3 class="panel-title">@lang('basic.fuel_consumption') - {{ $car->registration }}</h3>
</div>
<div class="modal-body">
	<table class="table_fuel">
		<thead>
			<tr>
				<th>@lang('basic.date')</th>
				<th>@lang('basic.liters')</th>
				<th>@lang('basic.current_km')</th>
				<th>@lang('basic.average_consumption') [l/100km]</th>
			</tr>
		</thead>
		<tbody>
			@if (count($fuels) > 0)
				@foreach ($fuels as $fuel)
					@php
						$fuel_prev = $fuels->where('date','<', $fuel->date)->first();
					@endphp
					<tr>
						<td>{{ date('d.m.Y', strtotime($fuel->date)) }}</td>
						<td>{{ $fuel->liters }}</td>
						<td>{{ $fuel->km }}</td>
						<td>{!! $fuel_prev ? round($fuel->liters / ($fuel->km - $fuel_prev->km)  * 100,2) : 0 !!}</td>
					</tr>
				@endforeach
			@else 
				<tr>
					<td class="no-data" colspan="4" >@lang('basic.no_data')</td>
			@endif
		</tbody>
	</table>
	
</div>
