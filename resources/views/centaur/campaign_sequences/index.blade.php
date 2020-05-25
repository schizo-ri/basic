<header class="page-header">
	<div class="index_table_filter">
		<label>
			<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
		</label>
		@if(Sentinel::getUser()->hasAccess(['campaign_sequences.create']) || in_array('campaign_sequences.create', $permission_dep))
			<a class="btn-new" href="{{ route('campaign_sequences.create') }}" rel="modal:open">
				<i class="fas fa-plus"></i>
			</a>
		@endif
	</div>
</header>
<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="table-responsive">
		@if(count($campaign_sequences))
			<table id="index_table" class="display table table-hover sequence_table">
				<thead>
					<tr>
						<th>@lang('basic.campaigne')</th>
						<th>@lang('basic.text')</th>
						<th>@lang('absence.start_date')</th>
						<th>@lang('basic.interval')</th>
						<th class="not-export-column">@lang('basic.options')</th>
				</thead>
				<tbody>
					@foreach ($campaign_sequences as $sequence)
						<tr>
							<td>{{ $sequence->campaign['name'] }}</td>
							<td>{!! str_limit( strip_tags($sequence->text), 100)  !!}</td>
							<td>{!! $sequence->start_date ? date('d.m.Y', strtotime($sequence->start_date)) : '' !!}</td>
							<td>{{ $sequence->send_interval }}</td>
							<td class="center">
								<!-- <button class="collapsible option_dots float_r"></button> -->								
								@if(Sentinel::getUser()->hasAccess(['campaign_sequences.delete']) || in_array('campaign_sequences.delete', $permission_dep) && !$works->where('department_id',$department->id)->first())
								<a href="{{ route('campaign_sequences.destroy', $sequence->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
									<i class="far fa-trash-alt"></i>
								</a>
								@endif
								@if(Sentinel::getUser()->hasAccess(['campaign_sequences.update']) || in_array('campaign_sequences.update', $permission_dep))
									<a href="{{ route('campaign_sequences.edit', $sequence->id) }}" class="btn-edit" title="{{ __('basic.edit_campaigns')}}" rel="modal:open">
										<i class="far fa-edit"></i>
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
		$('.collapsible').click(function(event){        
       		$(this).siblings().toggle();
		});
	});
</script>