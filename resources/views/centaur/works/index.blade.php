<header class="page-header">
	<div class="index_table_filter">
		<label>
			<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
		</label>
		@if(Sentinel::getUser()->hasAccess(['works.create']) || in_array('works.create', $permission_dep))
			<a class="btn-new" href="{{ route('works.create') }}"  rel="modal:open">
				<i class="fas fa-plus"></i>
			
			</a>
		@endif
	</div>
</header>
<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="table-responsive">
		@if(count($works))
			<table id="index_table" class="display table table-hover">
				<thead>
					<tr>
						<th>@lang('basic.department')</th>
						<th>@lang('basic.name')</th>
						<th>@lang('basic.job_description')</th>
						<th>@lang('basic.director')</th>
						<th class="not-export-column">@lang('basic.options')</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($works as $work)
						<tr>
							<td>{{ $work->department['name'] }}</td>
							<td>{{ $work->name }}</td>
							<td>{{ $work->job_description }}</td>
							<td>{!!  $work->employee ? $work->employee['first_name'] . ' ' . $work->employee['last_name'] : '' !!}</td>
							<td class="center">
								<!-- <button class="collapsible option_dots float_r"></button> -->
								@if(Sentinel::getUser()->hasAccess(['works.update']) || in_array('works.update', $permission_dep))
									<a href="{{ route('works.edit', $work->id) }}" class="btn-edit" rel="modal:open" >
											<i class="far fa-edit"></i>
									</a>
								@endif
								@if( !$employees->where('work_id',$work->id)->first() && Sentinel::getUser()->hasAccess(['works.delete']) || in_array('works.delete', $permission_dep))
									<a href="{{ route('works.destroy', $work->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}" >
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
		/* $('.collapsible').click(function(event){        
       		$(this).siblings().toggle();
		}); */
	});
	$.getScript( '/../restfulizer.js');
</script>		