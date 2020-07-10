@php
	
@endphp
<header class="page-header">
	<div class="index_table_filter">
		<label>
			<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
		</label>
		@if(Sentinel::getUser()->hasAccess(['vehical_services.create']) || in_array('vehical_services.create', $permission_dep))
			<a class="btn-new" href="{{ route('vehical_services.create') }}" rel="modal:open">
				<i class="fas fa-plus"></i>
			</a>
		@endif
	</div>
</header>
<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="table-responsive">
		@if (count($vehicalServices) > 0)
			<table id="table_id" class="display table table-hover sort_1_desc">
				<thead>
					<tr>
						<th>@lang('basic.date')</th>
						<th>@lang('basic.price')</th>
						<th>@lang('basic.current_km')</th>
						<th>@lang('basic.employee')</th>
						<th>@lang('basic.comment')</th>
						<th class="not-export-column">@lang('basic.options')</th>
					</tr>
				</thead>
				<tbody class="">
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
				</tbody>
			</table>
		@else
			<p class="no_data">@lang('basic.no_data')</p>
		@endif
	</div>
</main>
<script>
	$(function(){
		
		$.getScript( '/../js/filter_table.js');
		$.getScript( '/../js/open_modal.js'); 
		$.getScript( '/../restfulizer.js');
		
	});
</script>
