<div class="modal-header">
    <a class="link_back" rel="modal:close">
        <img src="{{ URL::asset('icons/arrow_left2.png') }}" />
	</a>
	@if( Sentinel::getUser()->hasAccess(['campaigns.delete']) || in_array('campaigns.delete', $permission_dep) && !$works->where('department_id',$department->id)->first())
		<a href="{{ route('campaigns.destroy', $campaign->id) }}" class="action_confirm btn-delete modal_delete" data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
			<i class="far fa-trash-alt"></i> @lang('basic.delete')
		</a>
		<div class="active_status">
			<form id="form_edit_campaign" accept-charset="UTF-8" role="form" method="post" action="{{ route('campaigns.update', $campaign->id) }}">
				<input class="form-control" placeholder="{{ __('basic.name')}}" name="name" type="hidden" maxlength="255" value="{{ $campaign->name }}"  />
				<input class="form-control" placeholder="{{ __('basic.description')}}" name="description" type="hidden" maxlength="255" value="{{ $campaign->description }}"  />
				<label class="float_l container_radio" hidden>
					<input type="radio" name="type" value="one_time" {!! $campaign->type == 'one_time' ? 'checked' : '' !!} />
					<span class="checkmark"></span>
				</label>
				<label class="float_l container_radio" hidden>
					<input type="radio" name="type" value="evergreen" {!! $campaign->type == 'evergreen' ? 'checked' : '' !!} />
					<span class="checkmark"></span>
				</label>
				<input class="form-control date_time float_l" placeholder="{{ __('absence.start_date')}}" name="start_date" type="hidden" value="{{  $campaign->start_date }}"  />
				<input class="form-control date_time float_l" placeholder="{{ __('absence.start_time')}}" name="start_time" type="hidden" value="{{ $campaign->start_time }}"  />
				
				<label class="float_l container_radio status_checked"> @lang('basic.active')
					<input type="radio" name="active" value="1" {!! $campaign->active == 1 ? 'checked' : '' !!} />
					<span class="checkmark active"></span>
				</label>
				<label class="float_l container_radio status_checked ">@lang('basic.inactive')
					<input type="radio" name="active" value="0" {!! $campaign->active == 0 ? 'checked' : '' !!} />
					<span class="checkmark inactive"></span>
				</label>
				{{ method_field('PUT') }}
				{{ csrf_field() }}
				<input class="btn-submit" type="submit" value="{{ __('basic.edit')}}" hidden>
			</form>
		</div>	
	@endif

	<h3 class="panel-title">{{ $campaign->name }}</h3>
	<p>{{ $campaign->description }}</p>
	
</div>
<div class="modal-body">
	
		<header class="header_campaign">
			<div class="index_table_filter">
				<div class="float_left col-8">
					<img class="img_search" src="{{ URL::asset('icons/search.png')  }}" alt="Search"/>
					<input type="text" id="mySearchTable" onchange="mySearchTable()" placeholder="{{ __('basic.search')}}" title="{{ __('basic.search')}}" class="input_search" >
				</div>
				<div class="float_right col-4">
					<div class='add_campaign float_right '>
						@if(Sentinel::getUser()->employee)
							<a class="btn btn-primary btn-new" href="{{ route('campaign_sequences.create',['id' => $campaign->id ]) }}"  title="{{ __('basic.add_campaign')}}">
								<i class="fas fa-plus"></i>
							</a>
						@endif
					</div>
				</div>
			</div>
		</header>
		@if(count($campaignSequences)>0)
		<main class="index_main">
			<div class="table-responsive">
					<table id="index_table" class="display table table-hover sequence_table">
						<thead>
							<tr>
								<th>@lang('basic.campaign')</th>
								<th>@lang('basic.text')</th>
								<th>@lang('absence.start_date')</th>
								<th>@lang('basic.interval')</th>
								<th class="not-export-column">@lang('basic.options')</th>
						</thead>
						<tbody>
							@foreach ($campaignSequences as $sequence)
								<tr>
									<td><a href="{{ route('campaign_mail', ['sequence_id' => $sequence->id ]) }}" rel="modal:open">{{ $sequence->campaign['name'] }}</a></td>
									<td></td>
									<td>{!! $sequence->start_date ? date('d.m.Y', strtotime($sequence->start_date)) : '' !!}</td>
									<td>{!! $sequence->send_interval ? __('basic.'.$sequence->send_interval) : '' !!}</td>
									<td class="center">
										<button class="collapsible option_dots float_r"></button>								
										@if(Sentinel::getUser()->hasAccess(['campaign_sequences.delete']) || in_array('campaign_sequences.delete', $permission_dep) && !$works->where('department_id',$department->id)->first())
										<a href="{{ route('campaign_sequences.destroy', $sequence->id) }}" style="display:none" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
											<i class="far fa-trash-alt"></i>
										</a>
										@endif
										@if(Sentinel::getUser()->hasAccess(['campaign_sequences.update']) || in_array('campaign_sequences.update', $permission_dep))
											<a href="{{ route('campaign_sequences.edit', $sequence->id) }}" style="display:none" class="btn-edit" title="{{ __('basic.edit_campaigns')}}">
												<i class="far fa-edit"></i>
											</a>
										@endif
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				
			</div>
		</main>
		@else
		<p class="no_data">@lang('basic.no_data')</p>
	@endif
</div>
<script>
	$('.header_campaign #mySearchTable').keyup(function(){
		var value = $(this).val().toLowerCase();
		console.log("filter");
		$("#index_table tbody tr").filter(function() {
		$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});	
	$('.collapsible').click(function(event){        
		$(this).siblings().toggle();
	});

	$('.status_checked').click(function(){
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
	});
	$.getScript( '/../restfulizer.js');
</script>
	{{-- <main class="col-lg-12 col-xl-8 index_main float_right">
		<section>
			<header class="header_campaign">
				<div class="filter">
					<div class="float_left col-6 height100 position_rel padd_0">
						<img class="img_search" src="{{ URL::asset('icons/search.png')  }}" alt="Search"/>
						<input type="text" id="mySearch" placeholder="{{ __('basic.search')}}" title="{{ __('basic.search')}}" class="input_search" >
					</div>
					<div class="float_right col-6 height100  position_rel padd_tb_5">
						<div class='add_campaign float_right '>
							@if(Sentinel::getUser()->employee)
								<a class="btn btn-primary btn-new" href="{{ route('campaigns.create') }}"  title="{{ __('basic.add_campaign')}}" rel="modal:open">
									<i class="fas fa-plus"></i>
								</a>
							@endif
						</div>
					</div>
				</div>
			</header>
			<main class="main_campaign">
				@if(isset($campaigns) && count($campaigns) >0)
					@foreach($campaigns as $campaign)
						@if ( $campaignSequences->where('campaign_id', $campaign->id)->first())
							@php
								$sequences = $campaignSequences->where('campaign_id', $campaign->id);
							@endphp
							<article class="campaign panel col-sm-12 col-md-12 col-lg-6 col-xl-6 float_left">
								<div>
									<header class="campaign_head">
										<h4>{{ $campaign->name }}</h4>
										<h4><em>{{ $campaign->description }}</em></h4>
									</header>
									<main class="campaign_main">
										@foreach ($sequences as $sequence)
											<p>{!! $sequence->text !!}</p>
										@endforeach
									</main>
									<footer>														
									</footer>
								</div>
							</article>
						@endif
					@endforeach
				@else 
					<div class="placeholder">
						<img class="" src="{{ URL::asset('icons/placeholder_ad.png') }}" alt="Placeholder image" />
						<p>@lang('basic.no_ad1')
							<label type="text" class="add_new" rel="modal:open" >
								<i style="font-size:11px" class="fa">&#xf067;</i>
							</label>
							@lang('basic.no_ad2')
						</p>
					</div>
				@endif
			</main>
		</section>
	</main> --}}
