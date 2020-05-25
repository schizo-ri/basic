@extends('Centaur::layout')

@section('title', __('basic.campaigns'))

@section('content')
<div class="index_page index_documents">
	
	<main class="col-lg-12  index_main main_documents float_right">
		<section>
			<div class="page-header header_document">
			
				@lang('basic.campaign_sequences') - {{ $campaign->name }}
			</div>
			<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12 main_campaign">
				<div class="table-responsive">
					<header class="page-header">
						<div class="index_table_filter">
							<label>
								<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
							</label>
							@if(Sentinel::getUser()->hasAccess(['campaign_sequences.create']) || in_array('campaign_sequences.create', $permission_dep))
								<a class="btn btn-primary btn-new" href="{{ route('campaign_sequences.create',['id' => $campaign->id ]) }}"  title="{{ __('basic.add_campaign')}}">
									<i class="fas fa-plus"></i>
								</a>
							@endif
						</div>
					</header>
					@if(count($campaignSequences))
						<table id="index_table" class="display table table-hover table_campaign">
							<thead>
								<tr>
									<th>@lang('basic.subject')</th>
									<th>@lang('basic.order')</th>
									<!-- <th>@lang('absence.start_date')</th> -->
									<th>@lang('basic.time_shift')</th>
									<th class="not-export-column">@lang('basic.options')</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($campaignSequences as $sequence)
								@php
									$send_interval = explode("-",$sequence->send_interval);
									
								@endphp
									<tr>
										<td><a class="campaign_mail" href="{{ route('campaign_mail', ['sequence_id' => $sequence->id ]) }}" rel="modal:open">{{ $sequence->subject }}</a></td>
										<td>{{  $sequence->order }}</td>
										<!-- <td>{!! $sequence->start_date ? date('d.m.Y', strtotime($sequence->start_date)) : '' !!}</td> -->
										<td>{!! count($send_interval) == 1 ? __('basic.'. $send_interval[0] ) : $send_interval[0] . ' ' . __('basic.'. $send_interval[1] ) !!}</td> 
										<td class="center">
											<!-- <button class="collapsible option_dots float_r"></button>		 -->						
											@if(Sentinel::getUser()->hasAccess(['campaign_sequences.delete']) || in_array('campaign_sequences.delete', $permission_dep) && !$works->where('department_id',$department->id)->first())
											<a href="{{ route('campaign_sequences.destroy', $sequence->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
												<i class="far fa-trash-alt"></i>
											</a>
											@endif
											@if(Sentinel::getUser()->hasAccess(['campaign_sequences.update']) || in_array('campaign_sequences.update', $permission_dep))
												<a href="{{ route('campaign_sequences.edit', $sequence->id) }}" class="btn-edit" title="{{ __('basic.edit_campaigns')}}">
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
		</section>
	</main>
</div>
<script>
	$('.header_campaign #mySearchTable').keyup(function(){
		var value = $(this).val().toLowerCase();
		console.log("filter");
		$("#index_table tbody tr").filter(function() {
		$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});	
	/* $('.collapsible').click(function(event){        
		$(this).siblings().toggle();
	}); */
	$('.link_back').click(function(e){
		e.preventDefault();
		var url = location['origin'] +'/campaigns';
		
		$('.container').load( url + ' .container > div', function() {		
			$.getScript( '/../js/datatables.js');
			$.getScript( '/../js/filter_table.js');                    
			$.getScript( '/../restfulizer.js');
			$.getScript( '/../js/event.js');
			$.getScript( '/../js/campaign.js');
			/* $('.collapsible').click(function(event){        
				$(this).siblings().toggle();
			}); */
		});		
	});

/* 	$('.status_checked').click(function(){
	var url = $(this).parents('form#form_edit_campaign').attr('action');
	var form = $(this).parents('form#form_edit_campaign');
	var form_data = form.serialize();
	
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});     
   
	$.ajax({
		url: url,
		type: "POST",
		data: form_data,
		success: function( response ) {
		   //alert("Podaci su spremljeni!")
		}, 
		error: function(xhr,textStatus,thrownError) {
			console.log("validate eror " + xhr + "\n" + textStatus + "\n" + thrownError);                            
		}
	  });
}); */
	$.getScript( '/../restfulizer.js');
</script>
@stop