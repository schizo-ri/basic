<div class="modal-header">
	<h3 class="panel-title">@lang('basic.fuel_consumption') - {!! $fuels->first() ? $fuels->first()->car->registration : '' !!}</h3>
</div>
<div class="modal-body">
	<table class="table_fuel">
		<thead>
			<tr>
				<th>@lang('basic.date')</th>
				<th>@lang('basic.liters')</th>
				<th>@lang('basic.current_km')</th>
				<th>@lang('basic.average_consumption') [l/100km]</th>
				<th>@lang('basic.options') [l/100km]</th>
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
						<td>{!! $fuel_prev && $fuel->km - $fuel_prev->km > 0? round($fuel->liters / ($fuel->km - $fuel_prev->km)  * 100,2) : 0 !!}</td>
						<td>
							@if(Sentinel::getUser()->hasAccess(['fuels.view']) || in_array('fuels.view', $permission_dep))
								<a href="{{ route('fuels.edit',$fuel->id ) }}" class="edit_service btn-edit" title="{{ __('basic.fuel')}}" rel="modal:open">
									<i class="far fa-edit"></i>
								</a>
							@endif
							@if( Sentinel::getUser()->hasAccess(['fuels.delete']) || in_array('fuels.delete', $permission_dep))
								<a href="{{ route('fuels.destroy', $fuel->id) }}" class="action_confirm btn-delete danger edit_service " data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
									<i class="far fa-trash-alt"></i>
								</a>
							@endif
						</td>
					</tr>
				@endforeach
			@else 
				<tr>
					<td class="no-data" colspan="5" >@lang('basic.no_data')</td>
			@endif
		</tbody>
	</table>	
</div>
<script>
	$.getScript( '/../js/open_modal.js'); 

	$.getScript( '/../restfulizer.js');
</script>