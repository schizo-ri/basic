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

	$.getScript( '/../restfulizer.js');
</script>
