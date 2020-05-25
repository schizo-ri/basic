@extends('Centaur::layout')

@section('title', __('basic.campaigns'))

@section('content')
<div class="index_page index_documents">
	
	<main class="col-xs-12 col-sm-12 col-md-12 index_main main_documents float_right">
		<section>
			<div class="page-header header_document">
			
				@lang('basic.campaigns')
			</div>
			<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12 main_campaign">
				<div class="table-responsive">
					<header class="page-header">
						<div class="index_table_filter">
							<label>
								<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
							</label>
							@if(Sentinel::getUser()->hasAccess(['campaigns.create']) || in_array('campaigns.create', $permission_dep))
								<a class="btn-new add_new" href="{{ route('campaigns.create') }}" rel="modal:open">
									<i class="fas fa-plus"></i>
								</a>
							@endif
						</div>
					</header>
					@if(count($campaigns))
						<table id="index_table" class="display table table-hover table_campaign">
							<thead>
								<tr>
									<th>@lang('basic.name')</th>
									<th>@lang('basic.description')</th>
									<th>@lang('absence.start_date')</th>
									<th>Status</th>
									<th>@lang('basic.type')</th>
							<!--	<th>@lang('basic.recipient')</th>
									
									<th>@lang('absence.end_date')</th>
									<th>@lang('basic.repetition_period')</th> -->
									<th class="not-export-column">@lang('basic.options')</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($campaigns as $campaign)
									<tr>
										<td><a class="campaign_show" href="{{ route('campaign_sequences.show', $campaign->id ) }}" >{{ $campaign->name }}</a></td>
										<td>{{ $campaign->description }}</td>
										<td>{!! $campaign->start_date ? date('d.m.Y', strtotime($campaign->start_date)) :  __('basic.not_set') !!}</td>
										<td>{!! $campaign->active == 1 ? __('basic.active') : __('basic.inactive') !!}</td>
										<td>{{ $campaign->type }}</td>
										<!--<td>{{ "odjeli" }}</td>										
										<td>{{ date('d.m.Y', strtotime($campaign->end_date)) }}</td>
										<td>{{ $campaign->period }}</td>-->
										<td class="center">
											<!-- <button class="collapsible option_dots float_r"></button> -->
											@if($campaign->type == 'evergreen')
												@if(Sentinel::getUser()->hasAccess(['campaign_recipients.create']) || in_array('campaign_recipients.create', $permission_dep))
													<a href="{{ route('campaign_recipients.create', ['campaign_id' => $campaign->id]) }}" class="btn-edit" title="{{ __('basic.add_recipients')}}" rel="modal:open">
														<i class="fas fa-users"></i>
													</a>
												@endif
											@endif
											@if(Sentinel::getUser()->hasAccess(['campaigns.update']) || in_array('campaigns.update', $permission_dep))
												<a href="{{ route('campaigns.edit', $campaign->id) }}" class="btn-edit" title="{{ __('basic.edit_campaign')}}" rel="modal:open">
													<i class="far fa-edit"></i>
												</a>
											@endif
											<!-- @if(Sentinel::getUser()->hasAccess(['campaigns.update']) || in_array('campaigns.update', $permission_dep))
												<a href="{{ action('CampaignController@startCampaign', ['id' => $campaign->id ] ) }}" class="btn-edit sendEmail" title="{{ __('basic.sendEmail')}}" style="display:none" ><i class="far fa-envelope"></i></a>
											@endif -->
											<!-- @if(Sentinel::getUser()->hasAccess(['campaign_sequences.create']) || in_array('campaign_sequences.create', $permission_dep))
												<a href="{{ route('campaign_sequences.create', ['id' =>$campaign->id ]) }}" title="{{ __('basic.add_sequence') }}"  ><i class="fas fa-plus"></i>
												</a>
											@endif -->
											@if( ! $campaign_sequences->where('campaign_id', $campaign->id)->first() && Sentinel::getUser()->hasAccess(['campaigns.delete']) || in_array('campaigns.delete', $permission_dep) && !$works->where('department_id',$department->id)->first())
												<a href="{{ route('campaigns.destroy', $campaign->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
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
		</section>
	</main>
</div>
<script>
	
	$(function(){
		$.getScript( '/../js/datatables.js');
		
        $.getScript( '/../js/filter_table.js');    
		/* $('.collapsible').click(function(event){        
       		$(this).siblings().toggle();
		}); */
	});
	$.getScript( '/../js/open_modal.js');
	$.getScript( '/../js/campaign.js');	
</script>		
@stop