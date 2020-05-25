<header class="page-header">
	<div class="index_table_filter">
		<label>
			<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
		</label>
		@if(Sentinel::getUser()->hasAccess(['templates.create']) || in_array('templates.create', $permission_dep))
			<a href="{{ route('templates.create') }}" class="btn-new " title="{{ __('basic.create_template')}}" >
				<i class="fas fa-plus"></i>
			</a>
		@endif
	</div>
</header>
<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="table-responsive">
		@if(count($templates))
			<table id="index_table" class="display table table-hover">
				<thead>
					<tr>
						<th>@lang('basic.title')</th>
						<th>@lang('basic.module')</th>
						<th class="not-export-column">@lang('basic.options')</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($templates as $template)
						<tr>
							<td>{{ $template->title }}</td>
							<td><a href="{{ route('templates.show', $template->id ) }}" rel="modal:open">{{ $template->module }}</a></td>
							<td>
								@if(Sentinel::getUser()->hasAccess(['templates.update']) || in_array('templates.update', $permission_dep))
									<a href="{{ route('templates.edit',$template->id ) }}" class="edit_service btn-edit" title="{{ __('basic.edit')}}" >
										<i class="far fa-edit"></i>
									</a>
								@endif
								@if( Sentinel::getUser()->hasAccess(['templates.delete']) || in_array('templates.delete', $permission_dep))
									<a href="{{ route('templates.destroy', $template->id) }}" class="action_confirm btn-delete danger edit_service " data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
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
	
	});
	$.getScript( '/../restfulizer.js');
	$.getScript( '/../js/validate.js');
</script>
