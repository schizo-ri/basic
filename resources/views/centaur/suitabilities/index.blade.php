@extends('Centaur::layout')

@section('title', __('basic.benefits'))

@section('content')
<div class="index_page ads_index">
	<main class="col-xs-12 col-sm-12 col-md-12  index_main benefit_main float_right">
		<section>
			<div class="page-header header_document">
				<a class="link_back" href="{{ url()->previous() }}"><span class="curve_arrow_left"></span></a>
				@lang('basic.benefits') 
			</div>
			<main class="main_benefits">
				<header class="header_benefits">
					<div class="index_table_filter">
						<div class="float_left col-xs-3 col-sm-6 col-md-6 height100 position_rel padd_0">
							<img class="img_search" src="{{ URL::asset('icons/search.png')  }}" alt="Search"/>
							<input type="text" id="mySearch" placeholder="{{ __('basic.search')}}" title="{{ __('basic.search')}}" class="input_search" >
						</div>
						@if(Sentinel::getUser()->hasAccess(['benefits.create']) || in_array('benefits.create', $permission_dep))
							<div class="float_right col-xs-3 col-sm-6 col-md-6 height100 padd_0 position_rel ">
								<div class='add_benefit float_right '>
										<a class="btn btn-primary btn-new" href="{{ route('suitabilities.create') }}"  title="{{ __('basic.add_benefit')}}" rel="modal:open">
											<i class="fas fa-plus"></i>
										</a>
								</div>
							</div>
						@endif
					</div>
				</header>	
				@if(isset($benefits) && count($benefits) >0)
					<div class="benefits_scroll"><button id="left-button" class="scroll_left"></button>
						<div class="main_benefits_head ">
							@foreach($benefits as $benefit)
								@if ( $benefit->status == 1 || (Sentinel::getUser()->hasAccess(['benefits.create']) || in_array('benefits.create', $permission_dep)) )
									@php
										$benefit_img = '';
											$path_benefit = 'img/benefits/' . $benefit->id . '/';
											if(file_exists($path_benefit)){
												$benefit_img = array_diff(scandir($path_benefit), array('..', '.', '.gitignore'));
											} else {
												$benefit_img = '';
											}
									@endphp
									<div class="benefit_title panel" id="{{ $benefit->id }}" >
										<span class="bnf_img">
											@if($benefit_img)
												<img class="" src="{{ URL::asset($path_benefit . end($benefit_img)) }}" alt="{{ $benefit->title }}" title="{{ $benefit->title }}"  />
											@endif
										</span>
										<p class="bnf_title">{{ $benefit->title }}</p>
									</div>
								@endif
							@endforeach
						</div><button id="right-button" class="scroll_right"></button>
					</div>				
					<div class="main_benefits_body">
						@foreach($benefits as $benefit)
							@if ( $benefit->status == 1 || (Sentinel::getUser()->hasAccess(['benefits.create']) || in_array('benefits.create', $permission_dep)) )
								@php
									$benefit_img = '';
									$path_benefit = 'img/benefits/' . $benefit->id . '/';
									if(file_exists($path_benefit)){
										$benefit_img = array_diff(scandir($path_benefit), array('..', '.', '.gitignore'));
									} else {
										$benefit_img = '';
									}
									$benefit_files = '';
									$path_benefit_files = 'benefits/' . $benefit->id . '/';
									if(file_exists($path_benefit_files)){
										$benefit_files = array_diff(scandir($path_benefit_files), array('..', '.', '.gitignore'));
									} else {
										$benefit_files = '';
									}
								@endphp											
								<div class="benefit_body" id="_{{ $benefit->id }}" >
									@if(Sentinel::getUser()->hasAccess(['benefits.update']) || in_array('benefits.update', $permission_dep))
										<a class="btn-edit" href="{{ route('suitabilities.edit', $benefit->id) }}"  title="{{ __('basic.add_benefit')}}" rel="modal:open">
											<img class="img_statistic" src="{{ asset('icons/edit.png') }}" alt="edit" />
											<span>Edit</span>
										</a>
									@endif
									<div class="col-12 bnf_img">
										@if($benefit_img)
											<img class="" src="{{ asset($path_benefit . end($benefit_img )) }}" alt="{{ $benefit->title }}" title="{{ $benefit->title }}"  />
										@endif
									</div>
									<div class="col-xs-12 col-sm-8 float_l bnf_main">								
										<h4>{{ $benefit->title }}:</h4>
										<p class="bnf_description">{!! $benefit->description !!}</p>
										<div class="benefit_documents">
											@if( is_array($benefit_files))
												<p>@lang('basic.documents')</p>
												@foreach($benefit_files as $file)
													<p><a href="{{ asset($path_benefit_files . $file) }}" class="link_to_file">{{ $file }}</a></p>
												@endforeach
											@endif
										</div>
									</div>
									<div class="col-xs-12 col-sm-4 float_l contact_info">
										<p>@lang('basic.contact_person')</p><p>@lang('basic.contact_person')</p>
										<label><i class="fas fa-user"></i> {{ $benefit->contact }}</label>
										<p class="last">@lang('basic.contact_info')</p>
										<label><i class="fas fa-mobile-alt"></i> {{ $benefit->phone }}</label>
										<label><a href="mailto:{{ $benefit->email }}?Subject={{$benefit->title }}" target="_blank"><i class="far fa-envelope"></i> {{ $benefit->email }}</a></label>
									</div>
									
								</div>
							@endif
						@endforeach
					</div>
				@else
					<div class="placeholder_div">
						<div class="placeholder">
							<img class="" src="{{ asset('icons/placeholder_ad.png') }}" alt="Placeholder image" />
							<p>@lang('basic.no_benefits')</p>
						</div>
					</div>
				@endif
				
			</main>
		</section>
	</main>
</div>
<script>
	$.getScript( '/../js/filter.js');
	$.getScript( '/../js/benefit.js');
	$.getScript( '/../js/open_modal.js');
</script>
@stop