@extends('Centaur::layout')

@section('title', __('basic.documents'))
<script src="{{ URL::asset('node_modules/jquery/dist/jquery.js') }}"></script>
<script src="{{ URL::asset('node_modules/moment/moment.js') }}"></script>
@section('content')
<div class="index_page index_documents">
	<aside class="col-4 index_aside calendar_aside">
		@include('Centaur::side_calendar')
	</aside>
	<main class="col-lg-12 col-xl-8 index_main main_documents float_right">
		<section>
			<header class="page-header">
				<div class="index_table_filter">
					<label>
						<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
					</label>
					@if(Sentinel::getUser()->hasAccess(['campaigns.create']) || in_array('campaigns.create', $permission_dep))
						<a class="btn-new" href="{{ route('campaigns.create') }}" rel="modal:open">
							<i class="fas fa-plus"></i>
						</a>
					@endif
				</div>
			</header>
			<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="table-responsive">
					@if(count($campaigns))
						<table id="index_table" class="display table table-hover table_campaign">
							<thead>
								<tr>
									<th>@lang('basic.name')</th>
									<th>@lang('basic.description')</th>
									<th>@lang('basic.created_at')</th>
									<th>Status</th>
							<!--	<th>@lang('basic.recipient')</th>
									
									<th>@lang('absence.end_date')</th>
									<th>@lang('basic.repetition_period')</th> -->
									<th class="not-export-column">@lang('basic.options')</th>
							</thead>
							<tbody>
								@foreach ($campaigns as $campaign)
									<tr>
										<td>{{ $campaign->name }}</td>
										<td>{{ $campaign->description }}</td>
										<td>{{ date('d.m.Y', strtotime($campaign->created_at)) }}</td>
										<td></td>
										<!--<td>{{ "odjeli" }}</td>										
										<td>{{ date('d.m.Y', strtotime($campaign->end_date)) }}</td>
										<td>{{ $campaign->period }}</td>-->
										<td class="center">
											<button class="collapsible option_dots float_r"></button>
											
											@if( ! $campaign_sequences->where('campaign_id', $campaign->id)->first()  && Sentinel::getUser()->hasAccess(['campaigns.delete']) || in_array('campaigns.delete', $permission_dep) && !$works->where('department_id',$department->id)->first())
											<a href="{{ route('campaigns.destroy', $campaign->id) }}" style="display:none" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
												<i class="far fa-trash-alt"></i>
											</a>
											@endif
											@if(Sentinel::getUser()->hasAccess(['campaigns.update']) || in_array('campaigns.update', $permission_dep))
												<a href="{{ route('campaigns.edit', $campaign->id) }}" class="btn-edit" title="{{ __('basic.edit_campaigns')}}" style="display:none" rel="modal:open">
													<i class="far fa-edit"></i>
												</a>
											@endif
											@if(Sentinel::getUser()->hasAccess(['campaigns.update']) || in_array('campaigns.update', $permission_dep))
												<a href="{{ action('CampaignController@startCampaign', ['id' => $campaign->id ] ) }}" class="btn-edit sendEmail" title="{{ __('basic.sendEmail')}}" style="display:none" ><i class="far fa-envelope"></i></a>
											@endif
											@if(Sentinel::getUser()->hasAccess(['campaign_sequences.create']) || in_array('campaign_sequences.create', $permission_dep))
												<a href="{{ route('campaign_sequences.create', ['id' =>$campaign->id ]) }}" rel="modal:open" title="{{ __('basic.add_sequence') }}" style="display:none" ><i class="fas fa-plus"></i>
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
		</section>
	</main>
</div>
<script>
	$(function(){
		$.getScript( '/../js/datatables.js');
		
        $.getScript( '/../js/filter_table.js');    
		$('.collapsible').click(function(event){        
       		$(this).siblings().toggle();
		});
	});
</script>		
@stop