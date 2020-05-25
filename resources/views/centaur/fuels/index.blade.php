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
						<td>{!! $fuel_prev ? round($fuel->liters / ($fuel->km - $fuel_prev->km)  * 100,2) : 0 !!}</td>
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
	/* $.modal.defaults = {
		closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
		escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
		clickClose: false,       // Allows the user to close the modal by clicking the overlay
		closeText: 'Close',     // Text content for the close <a> tag.
		closeClass: '',         // Add additional class(es) to the close <a> tag.
		showClose: true,        // Shows a (X) icon/link in the top-right corner
		modalClass: "modal",    // CSS class added to the element being displayed in the modal.
		// HTML appended to the default spinner during AJAX requests.
		spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

		showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
		fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
		fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
	}; */
	$.getScript( '/../restfulizer.js');
</script>