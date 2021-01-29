@extends('Centaur::admin')

@section('title', __('basic.work_tasks'))

@section('content')
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['work_tasks.create']) || in_array('work_tasks.create', $permission_dep))
				<a class="btn-new add_new" href="{{ route('work_tasks.create') }}" title="{{ __('basic.add_work_task')}}"  rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($workTasks))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.name')</th>
									<th>@lang('basic.description')</th>
									<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($workTasks as $workTask)
							<tr>
								<td>{{ $workTask->name }}</td>
								<td><pre>{!! $workTask->description !!}</pre></td>
								<td class="center">
									@if(Sentinel::getUser()->hasAccess(['work_tasks.update']) || in_array('work_tasks.update', $permission_dep))
										<a href="{{ route('work_tasks.edit', $workTask->id) }}" class="btn-edit" title="{{ __('basic.edit_work_task')}}" rel="modal:open">
											<i class="far fa-edit"></i>
										</a>
									@endif
									@if( count($workTask->hasWorkDiary) == 0  && ( Sentinel::getUser()->hasAccess(['work_tasks.delete']) || in_array('work_tasks.delete', $permission_dep) ) )
										<a href="{{ route('work_tasks.destroy', $workTask->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
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
@stop