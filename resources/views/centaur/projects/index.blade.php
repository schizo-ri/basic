@extends('Centaur::admin')

@section('title', __('basic.projects'))

@section('content')
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['projects.create']) || in_array('projects.create', $permission_dep))
				<a class="btn-new" href="{{ route('projects.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
			<span class="show_button_upload" title="Upload"><i class="fas fa-upload"></i></span>
			<form class="upload_file" action="{{ action('ProjectController@importProject') }}" method="POST" enctype="multipart/form-data">
				<button class="btn-new">Upload</button>
				<input type="file" name="file" required />
				@csrf
			</form>
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($projects) > 0)
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>ERP ID</th>
							<th>@lang('basic.name')</th>
							<th>@lang('basic.object')</th>
							<th>@lang('basic.manager')</th>
							<th>@lang('basic.site_manager')</th>
							<th>@lang('basic.customer')</th>
							<th>oib</th>
							<th>@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($projects as $project)
							<tr>
								<td>{{ $project->erp_id }}</td>
								<td>{{ $project->name }}</td>
								<td>{{ $project->object }}</td>
								<td>{!! $project->employee_id && $project->employee ? $project->employee->user->first_name . ' ' .  $project->employee->user->last_name : '' !!}</td>
								<td>{!! $project->employee_id2 && $project->employee2 ? $project->employee2->user->first_name . ' ' .  $project->employee2->user->last_name : '' !!}</td>
								<td>{!! $project->customer ? $project->customer->name : '' !!}
									{!! $project->customer_oib && $customers->where('oib',filter_var($project->customer_oib , FILTER_SANITIZE_NUMBER_INT))->first() ? $customers->where('oib',filter_var($project->customer_oib , FILTER_SANITIZE_NUMBER_INT))->first()->name : '' !!}
								</td>
								<td>{{ filter_var($project->customer_oib, FILTER_SANITIZE_NUMBER_INT) }}</td>
								<td class="center">								
									@if(Sentinel::getUser()->hasAccess(['projects.update']) || in_array('projects.update', $permission_dep))
										<a href="{{ route('projects.edit', $project->id) }}" class="btn-edit"  title="{{ __('basic.edit')}}" rel="modal:open">
												<i class="far fa-edit"></i>
										</a>
									@endif
									@if ( count($project->hasProjectWorkTask) > 0)
										@if(Sentinel::getUser()->hasAccess(['project_work_tasks.create']) || in_array('project_work_tasks.create', $permission_dep))
											<a href="{{ route('project_work_tasks.edit', ['project_id' => $project->id ]) }}" class="btn-edit"  rel="modal:open" title="Kategorije elektroradova na projektu">
												<i class="fas fa-tasks"></i>
											</a>
										@endif
									@else
										@if(Sentinel::getUser()->hasAccess(['project_work_tasks.create']) || in_array('project_work_tasks.create', $permission_dep))
											<a href="{{ route('project_work_tasks.create', ['project_id' => $project->id ]) }}" class="btn-edit"  rel="modal:open" title="Kategorije elektroradova na projektu">
												<i class="fas fa-tasks"></i>
											</a>
										@endif
									@endif
									@if( count($project->locco) == 0 && count($project->afterhour) == 0 && (Sentinel::getUser()->hasAccess(['projects.delete']) || in_array('projects.delete', $permission_dep)) )
										<a href="{{ route('projects.destroy', $project->id) }}" class="action_confirm btn-delete danger" data-method="delete" title="{{ __('basic.delete')}}" data-token="{{ csrf_token() }}">
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