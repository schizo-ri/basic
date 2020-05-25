@extends('Centaur::layout')

@section('title', __('basic.campaigns'))

@section('content')
<div class="index_page index_documents">

	<main class="col-lg-12 col-xl-8 index_main main_documents float_right">
		<section>
			<div class="page-header header_document">
				<a class="link_back" href="{{ url()->previous() }}"><span class="curve_arrow_left"></span></a>
				@lang('basic.campaign_sequences')
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
									<th>@lang('basic.campaign')</th>
									<th>@lang('basic.text')</th>
									<th>@lang('absence.start_date')</th>
									<th>@lang('basic.interval')</th>
									<th class="not-export-column">@lang('basic.options')</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($campaignSequences as $sequence)
									<tr>
										<td><a class="campaign_mail" href="{{ route('campaign_mail', ['sequence_id' => $sequence->id ]) }}" rel="modal:open">{{ $sequence->campaign['name'] }}</a></td>
										<td></td>
										<td>{!! $sequence->start_date ? date('d.m.Y', strtotime($sequence->start_date)) : '' !!}</td>
										<td>@if ($sequence->send_interval) @lang('basic.'. $sequence->send_interval ) @endif</td>
										<td class="center">
											<!-- <button class="collapsible option_dots float_r"></button>	 -->							
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
	$('.campaign_mail').click(function(){
		console.log("campaign_mail1");
    });
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
<!-- @if( Sentinel::getUser()->hasAccess(['campaigns.delete']) || in_array('campaigns.delete', $permission_dep) && !$works->where('department_id',$department->id)->first())
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
						
					</div>
				</div>
			</div>
		</header>
		@if(count($campaignSequences)>0)
			<main class="index_main">
				<section>
					<div class="table-responsive">
						<table id="index_table" class="display table table-hover sequence_table">
							<thead>
								
							</thead>
							<tbody>
								
							</tbody>
						</table>
					</div>
				</section>
			</main>
		@else
			<p class="no_data">@lang('basic.no_data')</p>
		@endif
</div> -->