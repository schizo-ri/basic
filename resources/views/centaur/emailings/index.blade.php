<header class="page-header">
	<div class="index_table_filter">
		<label>
			<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
		</label>
		@if(Sentinel::getUser()->hasAccess(['emailings.create']) || in_array('emailings.create', $permission_dep))
			<a class="btn-new" href="{{ route('emailings.create') }}" rel="modal:open">
				<i class="fas fa-plus"></i>
			</a>
		@endif
	</div>
</header>
<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="table-responsive">
		@if(count($emailings))
			<table id="index_table" class="display table table-hover">
				<thead>
					<tr>
						<th>@lang('basic.model')</th>
						<th>@lang('basic.method')</th>
						<th>@lang('basic.sent_to_dep')</th>
						<th>@lang('basic.sent_to_empl')</th>
						<th class="not-export-column">@lang('basic.options')</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($emailings as $emailing)
						<tr>
							<td>{{ $models[ $emailing->table['name']] }}</td>
							<td>{{ $methods[ $emailing->method ] }}</td>
							<td>
								@if($emailing->sent_to_dep)
									@foreach(explode(",", $emailing->sent_to_dep) as $prima_dep)
									{{  $departments->where('id', $prima_dep)->first()->email  }}<br>
									@endforeach
								@endif
							</td>
							<td>
								@if($emailing->sent_to_empl)
									@foreach(explode(",", $emailing->sent_to_empl) as $prima)
										{{  $employees->where('id', $prima)->first()->email  }}<br>
									@endforeach
								@endif
							</td>
							<td class="center">
								<button class="collapsible option_dots float_r"></button>
								@if(Sentinel::getUser()->hasAccess(['emailings.update']) || in_array('emailings.update', $permission_dep))
									<a href="{{ route('emailings.edit', $emailing->id) }}" style="display:none"  title="{{ __('basic.edit')}}" class="btn-edit" rel="modal:open">
											<i class="far fa-edit"></i>
									</a>
								@endif
								@if(Sentinel::getUser()->hasAccess(['works.delete']) || in_array('works.delete', $permission_dep) && !$employees->where('work_id',$work->id)->first())
									<a href="{{ route('emailings.destroy', $emailing->id) }}" style="display:none" title="{{ __('basic.delete')}}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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
	$.getScript( '/../restfulizer.js');
	$.getScript( '/../js/validate.js');
</script>