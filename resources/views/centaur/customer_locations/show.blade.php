<div class="modal-header">
	<h3 class="panel-title">{{ $customer->name .' - '. __('basic.locations') }} 
		@if(Sentinel::getUser()->hasAccess(['customer_locations.create']) || in_array('customer_locations.create', $permission_dep))
			<a href="{{ route('customer_locations.create',['customer_id' => $customer->id]) }}" class="btn-new"  title="{{ __('basic.add_location')}}" rel="modal:open">
				<i class="fas fa-plus"></i>
			</a>
		@endif
	</h3>
</div>
<div class="modal-body">
	@if (count( $locations) > 0 )
	<table>
		<thead>
			<th>Adresa</th>
			<th>Grad</th>
			<th>Opcije</th>
		</thead>
		<tbody>
			@foreach ( $locations as $location )
				<tr>
					<td>{{ $location->address }}</td>
					<td>{{ $location->city }}</td>
					<td>
						@if(Sentinel::getUser()->hasAccess(['customer_locations.update']) || in_array('customer_locations.update', $permission_dep))
							<a href="{{ route('customer_locations.edit', $location->id) }}" class="btn-edit"  title="{{ __('basic.edit')}}" rel="modal:open">
								<i class="far fa-edit"></i>
							</a>
						@endif
						@if(Sentinel::getUser()->hasAccess(['customer_locations.delete']) || in_array('customer_locations.delete', $permission_dep) )
							<a href="{{ route('customer_locations.destroy', $location->id) }}" class="action_confirm btn-delete danger" data-method="delete" title="{{ __('basic.delete')}}" data-token="{{ csrf_token() }}">
								<i class="far fa-trash-alt"></i>
							</a>
						@endif
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
		
	@else
		<p>Nema unesenih lokacija</p>
	@endif
</div>