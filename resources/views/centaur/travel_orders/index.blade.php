<header class="page-header">
	<div class="index_table_filter">
		<label>
			<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
		</label>
		@if(Sentinel::getUser()->hasAccess(['travel_orders.create']) || in_array('travel_orders.create', $permission_dep))
			<a href="{{ route('travel_orders.create') }}" class="btn-new " title="{{ __('basic.add_travel')}}" rel="modal:open" >
				<i class="fas fa-plus"></i>
			</a>
		@endif
		<span class="change_view"></span>
	</div>
</header>
<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="table-responsive">
		@if(count($travel_orders))
			<table id="index_table" class="display table table-hover">
				<thead>
					<tr>
						<th>@lang('basic.employee')</th>
						<th>@lang('basic.date')</th>
						<th>@lang('absence.start_date')</th>
						<th>@lang('absence.end_date')</th>
						<th>@lang('basic.destination')</th>
						<th>@lang('basic.car')</th>
						<th class="not-export-column">@lang('basic.options')</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($travel_orders as $travel)
						<tr>
							<td>{{ $travel->employee->user['first_name'] . ' ' .  $travel->employee->user['last_name'] }}</td>
							<td>{{ $travel->date }}</td>
							<td>{{ $travel->start_date }}</td>
							<td>{{ $travel->end_date }}</td>
							<td>{{ $travel->destination }}</td>
							<td>{{ $travel->car['registration'] }}</td>
							<td class="center">
								<!-- <button class="collapsible option_dots float_r"></button> -->
								@if(Sentinel::getUser()->hasAccess(['travel_orders.update']))
									<a href="{{ route('travel_orders.edit', $travel->id) }}" class="btn-edit" title="{{ __('basic.edit')}}" rel="modal:open">
										<i class="far fa-edit"></i>
									</a>
								@endif								
								@if(Sentinel::getUser()->hasAccess(['travel_orders.delete']))
									<a href="{{ route('travel_orders.destroy', $travel->id) }}" class="action_confirm btn-delete danger" title="{{ __('basic.delete')}}" data-method="delete" data-token="{{ csrf_token() }}">
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
	});
</script>