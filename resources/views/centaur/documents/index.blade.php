@extends('Centaur::layout')

@section('title', __('basic.documents'))

@section('content')
<div class="index_page index_documents">
	<main class="col-md-12 index_main main_documents float_right">
		<section>
			<div class="page-header header_document">
				<a class="link_back" href="{{ url()->previous() }}" ><span class="curve_arrow_left"></span></a>
				@lang('basic.documents')
				@if(count($documents)>0)
					<span class="show float_r">@lang('basic.show')<i class="fas fa-caret-down"></i></span>
					<span class="hide float_r">@lang('basic.hide')<i class="fas fa-caret-up"></i></span>
				@endif
				<div class="preview_doc">
					<button id="left-button" class="scroll_left"></button>
					@if(Sentinel::getUser()->hasAccess(["documents.create"]) || in_array("documents.create", $permission_dep) )
						<a class="add_new new_document" href="{{ route('documents.create') }}" class="" rel="modal:open"><i style="font-size:11px" class="fa">&#xf067;</i></a>
					@endif
					@foreach ($documents as $doc)
						<span class="thumb_container">
							<span class="thumbnail" title="{{ $doc->path . $doc->title }}" >
								<div class="ajax-content">
									<?php  
										$open = $doc->path . $doc->title;
									?>
									@if(file_exists('icons/' . pathinfo($open, PATHINFO_EXTENSION) . '.png'))
										<a href="{{ asset($open) }}" target="_blank"><img class="doc_icons" src="{{ URL::asset('icons/' . pathinfo($open, PATHINFO_EXTENSION) . '.png' )  }}" /></a>
									@endif
								</div>
							</span>
							<span class="thumb_name" title="{{ $doc->title }}">{{ $doc->title }}</span>
							<span class="thumb_time">{{ Carbon\Carbon::parse($doc->created_at)->diffForHumans()  }}</span>
						</span>
					@endforeach
					<button id="right-button" class="scroll_right"></button>
				</div>
			</div>
			<main class="all_documents">
				<div class="table-responsive">
					<header class="page-header">
						<div class="index_table_filter">
							<label>
								<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
							</label>
						{{-- <span class="change_view"></span> --}}
							@if(Sentinel::getUser()->hasAccess(["documents.create"]) || in_array("documents.create", $permission_dep) )
								<a class="add_new" href="{{ route('documents.create') }}" class="" rel="modal:open">
									<i style="font-size:11px" class="fa">&#xf067;</i>
									{{--  @lang("basic.add_document") --}}
								</a>
							@endif
							
						</div>
					</header>
					<section class="page-main">
						@if(count($documents)>0)
							<table id="index_table" class="display table table-hover">
								<thead>
									<tr>
										<th class="not-export-column no-sort"></th><!--type -->
										<th>@lang('basic.title')</th>
									<!--<th>@lang('basic.employee')</th>-->
									<!--<th>@lang('basic.path')</th>-->
										<th>Type</th>
										<th>@lang('basic.date')</th>
										@if (Sentinel::inRole('administrator'))
											<th class="not-export-column no-sort"></th>
										@endif
									</tr>
								</thead>
								<tbody>
									@foreach ($documents as $document)
										<?php  
											$open = $document->path . $document->title;
										?>
										<tr>
											<td style="text-align: center;">
												<span class="{{ pathinfo($open, PATHINFO_EXTENSION) }}"></span>
												<!--
												@if(file_exists('icons/' . pathinfo($open, PATHINFO_EXTENSION) . '.png'))
													<img class="doc_icons" src="{{ URL::asset('icons/' . pathinfo($open, PATHINFO_EXTENSION) . '.png' )  }}" /> 
												@endif -->
											</td><!--type -->
											<td><a href="{{ asset($open) }}" target="_blank">{{ $document->title }}</a></td>
									<!--	<td>{{ $document->employee->user['first_name'] . ' ' .  $document->employee->user['last_name'] }}</td>-->
									<!--	<td>{{ $document->path }}</td>-->
											<td>{{ __('doc_type.' . pathinfo($open, PATHINFO_EXTENSION) ) }}</td>
											<td>{{ Carbon\Carbon::parse($document->created_at)->diffForHumans()  }}</td>
											@if (Sentinel::inRole('administrator'))
												<td class="options center">
													@if(Sentinel::getUser()->hasAccess(['documents.delete']) || in_array('documents.delete', $permission_dep))
														<a href="{{ route('documents.destroy', $document->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
															<i class="far fa-trash-alt"></i>
														</a>
													@endif
												</td>
											@endif
										</tr>
									@endforeach
								</tbody>
							</table>
						@else
							<div class="placeholder">
								<img class="" src="{{ URL::asset('icons/placeholder_document.png') }}" alt="Placeholder image" />
								<p> @lang('basic.no_file1')
									@if(Sentinel::getUser()->hasAccess(["documents.create"]) || in_array("documents.create", $permission_dep) )
									@lang('basic.no_file2')
										<label type="text" class="add_new" rel="modal:open" >
											<i style="font-size:11px" class="fa">&#xf067;</i>
										</label>
										@lang('basic.no_file3')
									@endif
								</p>
							</div>
						@endif
					</section>
				</div>
			</main>
		</section>
	</main>
</div>

@stop