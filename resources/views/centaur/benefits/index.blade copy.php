<header class="page-header">
	<div class="index_table_filter">
		<label>
			<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
		</label>
		@if(Sentinel::getUser()->hasAccess(['benefits.create']) || in_array('benefits.create', $permission_dep))
			<a class="btn-new" href="{{ route('benefits.create') }}" rel="modal:open">
				<i class="fas fa-plus"></i>
			</a>
		@endif
	</div>
</header>
<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="page-header header_document">
		<a class="link_back" href="{{ url()->previous() }}"><span class="curve_arrow_left"></span></a>
		@lang('basic.campaigns')
	</div>
	<div class="table-responsive">
		@if(count($benefits))
			<table id="index_table" class="display table table-hover">
				<thead>
					<tr>
						<th>@lang('basic.name')</th>
						<th>@lang('basic.description')</th>
						{{-- <th>@lang('basic.contact_person')</th>
						<th>E-mail</th>
						<th>@lang('basic.phone')</th> --}}
						<th>Status</th>
						<th class="not-export-column">@lang('basic.options')</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($benefits as $benefit)
						<tr>
							<td><a href="{{ route('benefits.show', $benefit->id ) }}" >{{ $benefit->title }}</a></td>
							<td>{!! str_limit(strip_tags($benefit->description), 100) !!}</td>
							{{-- <td>{{ $benefit->contact }}</td>
							<td>{{ $benefit->email }}</td>
							<td>{{ $benefit->phone }}</td> --}}
							<td>{!! $benefit->status == 1 ? 'aktivna' : 'neaktivna' !!}</td>
							<td class="center">
								<!-- <button class="collapsible option_dots float_r"></button> -->
								@if(Sentinel::getUser()->hasAccess(['benefits.update']) || in_array('benefits.update', $permission_dep))
									<a href="{{ route('benefits.edit', $benefit->id) }}" class="btn-edit" rel="modal:open">
											<i class="far fa-edit"></i>
									</a>
								@endif
								@if(Sentinel::getUser()->hasAccess(['benefits.delete']) || in_array('benefits.delete', $permission_dep))
									<a href="{{ route('benefits.destroy', $benefit->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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
		
	$('.collapsible').click(function(event){        
       		$(this).siblings().toggle();
		});
	});
	/* $.getScript( '/../js/open_modal.js'); */
</script>