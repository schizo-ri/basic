@extends('Centaur::admin')

@section('title', __('basic.trainings'))

@section('content')
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['trainings.create']) || in_array('trainings.view', $permission_dep))
				<a class="btn-new" href="{{ route('trainings.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($trainings))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.name')</th>
							<th>@lang('basic.description')</th>
							<th>@lang('basic.institution')</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($trainings as $training)
							<tr>
								<td>{{ $training->name }}</td>
								<td>{{ $training->description }}</td>
								<td>{{ $training->institution }}</td>
								<td class="center">
									<!-- <button class="collapsible option_dots float_r"></button> -->
									@if(Sentinel::getUser()->hasAccess(['trainings.update']) || in_array('trainings.update', $permission_dep))
										<a href="{{ route('trainings.edit', $training->id) }}" class="btn-edit" rel="modal:open">
												<i class="far fa-edit"></i>
										</a>
									@endif
									@if( count($training->hasEmployeeTrainings) == 0 && Sentinel::getUser()->hasAccess(['trainings.delete']) || in_array('trainings.delete', $permission_dep))
										<a href="{{ route('ad_categories.destroy', $training->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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
		/* $(function(){
			$.getScript( '/../js/filter_table.js');
		$('.collapsible').click(function(event){        
				$(this).siblings().toggle();
			});
		});
		$.getScript( '/../restfulizer.js'); */
	</script>
@stop