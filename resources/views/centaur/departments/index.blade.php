@extends('Centaur::admin')

@section('title', __('basic.departments'))

	@section('content')
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['departments.create']) || in_array('departments.create', $permission_dep))
				<a class="btn-new" href="{{ route('departments.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">	
		<div class="table-responsive">
			@if(count($departments))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.company')</th>
							<th>@lang('basic.name')</th>
							<th>@lang('basic.level')</th>
							<th>@lang('basic.roof')</th>
							<th>e-mail</th>
							<th>@lang('basic.manager')</th>
							<th class="not-export-column">@lang('basic.options')</th>
					</thead>
					<tbody>
						@foreach ($departments as $department)
							<tr>
								<td>{{ $department->company['name'] }}</td>
								<td><a href="{{ route('works.index', ['department_id' => $department->id] ) }}">{{ $department->name }}</a></td>
								<td>{{ $department->level1 }}</td>
								<td>
									{!! $department->level2 ? $departments->where('id', $department->level2)->first()->name : '' !!}
								</td>
								<td>{{ $department->email }}</td>
								<td>{!! $department->employee ? $department->employee->user->first_name . ' ' .  $department->employee->user->last_name : '' !!}</td>
								<td class="center">
									<!-- <button class="collapsible option_dots float_r"></button> -->
									@if(Sentinel::getUser()->hasAccess(['departments.update']) || in_array('departments.update', $permission_dep))
										<a href="{{ route('departments.edit', $department->id) }}" class="btn-edit" title="{{ __('basic.edit_department')}}" rel="modal:open">
												<i class="far fa-edit"></i>
										</a>
									@endif
									@if(! $department_roles->where('department_id', $department->id)->first())
										@if(Sentinel::getUser()->hasAccess(['department_roles.create']) || in_array('department_roles.create', $permission_dep))
											<a href="{{ route('department_roles.create',['department_id' => $department->id]) }}" class="btn-edit" title="Dodaj dopuštenja" rel="modal:open">
												<i class="far fa-check-square"></i>
											</a>
										@endif
									@else
										@if(Sentinel::getUser()->hasAccess(['department_roles.update']) || in_array('department_roles.update', $permission_dep))
											<a href="{{ route('department_roles.edit', $department_roles->where('department_id', $department->id)->first()->id ) }}" class="btn-edit" title="Ispravi dopuštenje" rel="modal:open">
												<i class="far fa-check-square"></i>
											</a>
										@endif
									@endif
									@if(Sentinel::getUser()->hasAccess(['works.create']) || in_array('works.create', $permission_dep))
										<a href="{{ route('works.create',['department_id' => $department->id]) }}" class="btn-edit" title="{{ __('basic.add_work')}}" rel="modal:open" >
											<i class="fas fa-plus"></i>
										</a>
									@endif
									@if(!$works->where('department_id',$department->id)->first() && Sentinel::getUser()->hasAccess(['departments.delete']) || in_array('departments.delete', $permission_dep))
									<a href="{{ route('departments.destroy', $department->id) }}" class="action_confirm btn-delete danger" style="display:none" data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
										<i class="far fa-trash-alt"></i>
									</a>
									@endif
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@else
				<span class="no_data">@lang('basic.no_data')</span>
			@endif
		</div>
	</main>

@stop