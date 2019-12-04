<header class="page-header">
	<div class="index_table_filter">
		<label>
			<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
		</label>
		@if(Sentinel::getUser()->hasAccess(['ad_categories.create']) || in_array('ad_categories.view', $permission_dep))
			<a class="btn-new" href="{{ route('ad_categories.create') }}" rel="modal:open">
				<i class="fas fa-plus"></i>
			</a>
		@endif
		<span class="change_view"></span>
	</div>
</header>
<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="table-responsive">
		@if(count($categories))
			<table id="index_table" class="display table table-hover">
				<thead>
					<tr>
						<th>@lang('basic.name')</th>
						<th class="not-export-column">@lang('basic.options')</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($categories as $category)
						<tr>
							<td><a href="{{ route('ads.index', ['category_id' => $category->id] ) }}" >{{ $category->name }}</a></td>
							<td class="center">
								<button class="collapsible option_dots float_r"></button>
								@if(Sentinel::getUser()->hasAccess(['ad_categories.update']) || in_array('ad_categories.update', $permission_dep))
									<a href="{{ route('ad_categories.edit', $category->id) }}" style="display:none" class="btn-edit" rel="modal:open">
											<i class="far fa-edit"></i>
									</a>
								@endif
								@if(Sentinel::getUser()->hasAccess(['ad_categories.delete']) || in_array('ad_categories.delete', $permission_dep))
									<a href="{{ route('ad_categories.destroy', $category->id) }}" style="display:none" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
										<i class="far fa-trash-alt"></i>
									</a>
								@endif
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		@else
			@lang('basic.no_data')
		@endif
	</div>
</main>
<script>
	$(function(){
		$.getScript( '/../js/filter_table.js');
	//	$.getScript( '/../js/collaps.js');
	$('.collapsible').click(function(event){        
       		$(this).siblings().toggle();
		});
	});
</script>
