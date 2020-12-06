@extends('Centaur::layout')

@section('title', __('basic.campaigns'))

@section('content')
<div class="index_page noticeboard_index">
	<main class="col-lg-12 col-xl-12 index_main main_noticeboard float_right">
		<section>
			<div class="page-header header_document">
				<a class="link_back" href="{{ url()->previous() }}"><i class="fas fa-chevron-left"></i> @lang('basic.campaigns')</a> | {{ $campaign->name }}
				<div class="campaign_start_date"><i class="far fa-calendar-alt"></i>@lang('absence.start_date'): {!! ' ' . $campaign->start_date ? date('d.m.Y', strtotime($campaign->start_date)) : '' !!}</div>
			</div>
			<main class="all_notices all_sequences">
				<header class="page-header">
					<div class="index_table_filter">
						<label>
							<input type="search" id="mySearch_noticeboard" placeholder="{{ __('basic.search')}}" title="Type ... " class="input_search" autofocus>
						</label>
						@if(Sentinel::getUser()->hasAccess(['campaign_sequences.create']) || in_array('campaign_sequences.create', $permission_dep))
							<a class="btn btn-primary btn-new" href="{{ route('campaign_sequences.create',['id' => $campaign->id ]) }}"  title="{{ __('basic.add_campaign')}}">
								<i class="fas fa-plus"></i>
							</a>
						@endif
					</div>
				</header>
				<section class="section_emails bg_white">
					@if(count($campaignSequences))	
						<div class="emails" id="sortable">
							@foreach ($campaignSequences as $sequence)
								<article class="col-xs-12 col-sm-49 col-md-32 col-lg-24 col-xl-19 emails_email_body" id="{{ $sequence->id }}">
									@php
										$send_interval = explode("-",$sequence->send_interval);
									@endphp
									
									<a class="campaign_mail notice_link" href="{{ route('campaign_mail', ['sequence_id' => $sequence->id ]) }}" rel="modal:open">
										<header class="ad_header">
											{!! $sequence->text !!}
										</header>
										<div class="email_main">
											<p class="email_title" title="{{ $sequence->subject }}">
												{{ str_limit($sequence->subject, 40)}}
											</p>
											<span class="emails_order_no">
												@lang('basic.order'): <span class="order_no" >{{  $sequence->order }}</span> 
											</span>
											<span class="emails_time_shift">
												@lang('basic.time_shift'): {!! count($send_interval) == 1 ? __('basic.'. $send_interval[0] ) : $send_interval[0] . ' ' . __('basic.'. $send_interval[1] ) !!}
											</span>
										</div>
									</a>
									<div class="sequence_links">
										@if(Sentinel::getUser()->hasAccess(['campaign_sequences.delete']) || in_array('campaign_sequences.delete', $permission_dep) && !$works->where('department_id',$department->id)->first())
											<a href="{{ route('campaign_sequences.destroy', $sequence->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
												<i class="far fa-trash-alt"></i>
											</a>
										@endif
										@if(Sentinel::getUser()->hasAccess(['campaign_sequences.update']) || in_array('campaign_sequences.update', $permission_dep))
											<a href="{{ route('campaign_sequences.edit', $sequence->id) }}" class="btn-edit edit" title="{{ __('basic.edit_campaigns')}}">
												<i class="far fa-edit"></i>
											</a>
											<a href="{{ action('CampaignSequenceController@test_mail_open', ['id' => $sequence->id]) }}" class="btn-edit sendEmail" title="{{ __('basic.sendEmail')}}" rel="modal:open"><i class="far fa-envelope"></i></a>
										@endif
									</div>
								</article>
							@endforeach
						</div>
					@else
						<div class="placeholder">
							<img class="" src="{{ URL::asset('icons/placeholder_notice.png') }}" alt="Placeholder image" />
							<p>@lang('basic.no_email1')
								<label type="text" class="add_new">
									<i style="font-size:11px" class="fa">&#xf067;</i>
								</label>
								@lang('basic.no_email2')
							</p>
						</div>
					@endif
				</section>
			</main>
		</section>
	</main>
</div>
<script>
	$( function () {
		if($('.noticeboard_index').length > 0) {

			$('.main_noticeboard .header_document .link_back').click(function(e){
            e.preventDefault();
            var url = location['origin'] +'/campaigns';
            
            $('.container').load( url + ' .container > div', function() {
                $.getScript( '/../js/datatables.js');
                $.getScript( '/../js/filter_table.js');                    
                $.getScript( '/../restfulizer.js');
                $.getScript( '/../js/event.js');
                $.getScript( '/../js/campaign.js');
               /*  $('.collapsible').click(function(event){        
                    $(this).siblings().toggle();
                }); */
                
            });
            window.history.pushState( location.href, 'Title',  url);

		}); 
		$('.sendEmail').click(function(){
		//	$.getScript('/../js/validate.js');
		});
		}
	});
</script>
@stop