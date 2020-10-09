@extends('Centaur::admin')

@section('title', __('basic.customers'))

@section('content')
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
		</div>
		@if(Sentinel::getUser()->hasAccess(['customers.create']) || in_array('customers.create', $permission_dep))
			<a class="btn-new" href="{{ route('customers.create') }}" rel="modal:open">
				<i class="fas fa-plus"></i>
			</a>
		@endif
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($customers) > 0)
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.name')</th>
							<th>@lang('basic.address')</th>
							<th>@lang('basic.city')</th>
							<th>@lang('basic.oib')</th>
							<th>@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($customers as $customer)
							<tr>
								<td>{{ $customer->name }}</td>
								<td>{{ $customer->address }}</td>
								<td>{{ $customer->city }}</td>
								<td>{{ $customer->oib }}</td>
								<td class="center">
									<!-- <button class="collapsible option_dots float_r"></button> -->
									@if(Sentinel::getUser()->hasAccess(['customers.update']) || in_array('customers.update', $permission_dep))
										<a href="{{ route('customers.edit', $customer->id) }}" class="btn-edit"  title="{{ __('basic.edit')}}" rel="modal:open">
												<i class="far fa-edit"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['customers.delete']) || in_array('customers.delete', $permission_dep) )
										<a href="{{ route('customers.destroy', $customer->id) }}" class="action_confirm btn-delete danger" data-method="delete" title="{{ __('basic.delete')}}" data-token="{{ csrf_token() }}">
											<i class="far fa-trash-alt"></i>
										</a>
									@endif
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@else
				p class="no_data">@lang('basic.no_data')</p>
			@endif
		</div>
	</main>
	
@stop