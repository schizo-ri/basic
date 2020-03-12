<header class="page-header">
	<div class="index_table_filter">
		<label>
			<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
		</label>
		@if(Sentinel::inRole('superadmin'))
			<a class="btn-new" href="{{ route('settings.create') }}" rel="modal:open">
				<i class="fas fa-plus"></i>
			</a>
		@endif
		{{-- <span class="change_view"></span> --}}
	</div>
</header>
<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="table-responsive">
		@if(count($settings))
			<table id="index_table" class="display table table-hover">
				<thead>
					<tr>
						<th>@lang('basic.name')</th>
						<th>@lang('basic.description')</th>						
						<th>@lang('basic.value')</th>
						<th class="not-export-column">@lang('basic.options')</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($settings as $setting)
						<tr>
							<td>{{ $setting->name }}</td>
							<td>{{ $setting->description }}</td>
							<td>{{ $setting->value }}</td>						
							<td class="center">
								<button class="collapsible option_dots float_r"></button>
								@if(Sentinel::inRole('superadmin') && Sentinel::getUser()->hasAccess(['settings.update']))
									<a href="{{ route('settings.edit', $setting->id) }}" style="display:none" class="btn-edit" title="{{ __('basic.edit')}}" rel="modal:open">
										<i class="far fa-edit"></i>
									</a>
								@endif								
								@if(Sentinel::inRole('superadmin') && Sentinel::getUser()->hasAccess(['settings.delete']))
									<a href="{{ route('settings.destroy', $setting->id) }}" style="display:none" class="action_confirm btn-delete danger" title="{{ __('basic.delete')}}" data-method="delete" data-token="{{ csrf_token() }}">
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
			   console.log("klik collapsible");
		});
	});
	$.getScript( '/../restfulizer.js');
</script>