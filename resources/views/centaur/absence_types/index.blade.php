@php
	use App\Models\Absence;
@endphp
<header class="page-header">
	<div class="index_table_filter">
		<label>
			<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
		</label>
		@if(Sentinel::getUser()->hasAccess(['absence_types.create']) || in_array('absence_types.view', $permission_dep))
			<a class="btn-new" href="{{ route('absence_types.create') }}" rel="modal:open">
				<i class="fas fa-plus"></i>
			</a>
		@endif
	<!-- 	<a href="{{ route('templates.create') }}" class="btn-template " title="{{ __('basic.create_template')}}" ><i class="far fa-clone"></i></a> -->
		
	</div>
</header>
<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="table-responsive">
		@if(count($absenceTypes))
			<table id="index_table" class="display table table-hover">
				<thead>
					<tr>
						<th>@lang('basic.name')</th>
						<th>@lang('absence.mark')</th>
						<th class="not-export-column">@lang('basic.options')</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($absenceTypes as $absenceType)
						<tr>
							<td>{{ $absenceType->name }}</td>
							<td>{{ $absenceType->mark }}</td>
							<td class="center">
								<!-- <button class="collapsible option_dots float_r"></button> -->
								@if(Sentinel::getUser()->hasAccess(['absence_types.update']) || in_array('absence_types.update', $permission_dep))
									<a href="{{ route('absence_types.edit', $absenceType->id) }}" class="btn-edit" rel="modal:open">
										<i class="far fa-edit"></i>
									</a>
								@endif
								@if( ! Absence::where('type', $absenceType->id)->first() && Sentinel::getUser()->hasAccess(['absence_types.delete']) || in_array('absence_types.delete', $permission_dep) )
									<a href="{{ route('absence_types.destroy', $absenceType->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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
	