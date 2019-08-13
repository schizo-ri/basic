@extends('Centaur::layout')

@section('title', __('basic.departments'))

@section('content')
<div class="row">
	<div class="page-header">
		<a href="{{ route('departments.index') }}" class="load_page" >@lang('basic.departments')</a> / 
		<a href="{{ route('department_roles.index') }}" class="load_page" >@lang('basic.department_roles')</a>
        <div class='btn-toolbar pull-right'>
			@if(Sentinel::getUser()->hasAccess(['departments.create']) || in_array('departments.create', $permission_dep))
			    <a class="btn btn-primary btn-lg" href="{{ route('departments.create') }}">
					<i class="fas fa-plus"></i>
					@lang('basic.add_department')
				</a>
			@endif
        </div>
        <h1>@lang('basic.departments')</h1>
    </div>
    
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
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
							<th class="not-export-column">@lang('basic.options')</th>
					</thead>
					<tbody>
						@foreach ($departments as $department)
							<tr>
								<td>{{ $department->company['name'] }}</td>
								<td><a href="{{ route('works.index',['department_id' => $department->id]) }}">{{ $department->name }}</a></td>
								<td>{{ $department->level1 }}</td>
								<td>@if($department->level2){{ $departments->where('id', $department->level2)->first()->name }}@endif</td>
								<td>{{ $department->email }}</td>
								<td class="center">
									@if(Sentinel::getUser()->hasAccess(['departments.update']) || in_array('departments.update', $permission_dep))
										<a href="{{ route('departments.edit', $department->id) }}" class="btn-edit" title="{{ __('basic.edit_department')}}">
											 <i class="far fa-edit"></i>
										</a>
									@endif
									@if(! $department_roles->where('department_id', $department->id)->first())
										@if(Sentinel::getUser()->hasAccess(['department_roles.create']) || in_array('department_roles.create', $permission_dep))
											<a href="{{ route('department_roles.create',['department_id' => $department->id]) }}" class="btn-edit" title="Dodaj dopuštenja">
												<i class="far fa-check-square"></i>
											</a>
										@endif
									@else
										@if(Sentinel::getUser()->hasAccess(['department_roles.update']) || in_array('department_roles.update', $permission_dep))
											<a href="{{ route('department_roles.edit', $department_roles->where('department_id', $department->id)->first()->id ) }}" class="btn-edit" title="Ispravi dopuštenje">
												<i class="far fa-check-square"></i>
											</a>
										@endif
									@endif
									@if(Sentinel::getUser()->hasAccess(['works.create']) || in_array('works.create', $permission_dep))
										<a href="{{ route('works.create',['department_id' => $department->id]) }}" class="btn-edit" title="{{ __('basic.add_work')}}" >
											<i class="fas fa-plus"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['departments.delete']) || in_array('departments.delete', $permission_dep) && !$works->where('department_id',$department->id)->first())
									<a href="{{ route('departments.destroy', $department->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
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
	</div>
</div>

@stop