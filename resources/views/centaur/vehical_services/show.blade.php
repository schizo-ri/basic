<div class="modal-header">
	<h3 class="panel-title">@lang('basic.vehical_services') {!! $vehicalServices->first() ? '- ' . $vehicalServices->first()->car->registration : '' !!}</h3>
</div>
<div class="modal-body">
	<table class="table_service">
		<thead>
			<tr>
				<th>@lang('basic.date')</th>
				<th>@lang('basic.price')</th>
				<th>@lang('basic.current_km')</th>
				<th>@lang('basic.employee')</th>
				<th>@lang('basic.comment')</th>
				<th>@lang('basic.options')</th>
			</tr>
		</thead>
		<tbody>
			@if (count($vehicalServices) > 0)
				@foreach ($vehicalServices as $service)
					<tr>
						<td>{{ date('d.m.Y', strtotime($service->date)) }}</td>
						<td>{{ $service->price }}</td>
						<td>{{ $service->km }}</td>
						<td>{{ $service->employee->user['last_name'] }}</td>
						<td>{{ $service->comment }}</td>
						<td>
							@if(Sentinel::getUser()->hasAccess(['vehical_services.view']) || in_array('vehical_services.view', $permission_dep))
								<a href="{{ route('vehical_services.edit',$service->id ) }}" class="edit_service btn-edit" title="{{ __('basic.vehical_services')}}" rel="modal:open">
									<i class="far fa-edit"></i>
								</a>
							@endif
							@if( Sentinel::getUser()->hasAccess(['vehical_services.delete']) || in_array('vehical_services.delete', $permission_dep))
								<a href="{{ route('vehical_services.destroy', $service->id) }}" class="action_confirm btn-delete danger edit_service " data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
									<i class="far fa-trash-alt"></i>
								</a>
							@endif
						</td>
					</tr>
				@endforeach
			@else 
				<tr>
					<td class="no-data" colspan="6" >@lang('basic.no_data')</td>
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
