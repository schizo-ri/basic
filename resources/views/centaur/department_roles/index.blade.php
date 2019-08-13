@extends('Centaur::layout')

@section('title', __('basic.dep_permissions'))

@section('content')
<div class="row">
    <div class="page-header">
        <a href="{{ route('departments.index') }}" class="load_page" >@lang('basic.departments')</a> / 
		<a href="{{ route('department_roles.index') }}" class="load_page" >@lang('basic.department_roles')</a>
		<div class='btn-toolbar pull-right'>
			@if(Sentinel::getUser()->hasAccess(['department_roles.create']) || in_array('department_roles.create', $permission_dep))
			    <a class="btn btn-primary btn-lg" href="{{ route('department_roles.create') }}">
					<i class="fas fa-plus"></i>
					@lang('basic.add_permissions')
				</a>
			@endif
        </div>
        <h1>@lang('basic.dep_permissions')</h1>
    </div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($department_roles))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.department')</th>
							<th>@lang('basic.dep_permissions')</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($department_roles as $department_role)
							<tr>
								<td>{{ $department_role->department['name'] }}</td>
								<td>{{ $department_role->permissions }}</td>
								<td class="center">
									@if(Sentinel::getUser()->hasAccess(['department_roles.update']) || in_array('department_roles.update', $permission_dep))
										<a href="{{ route('department_roles.edit', $department_role->id) }}" class="btn-edit">
											 <i class="far fa-edit"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['department_roles.delete']) || in_array('department_roles.delete', $permission_dep))
										<a href="{{ route('department_roles.destroy', $department_role->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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