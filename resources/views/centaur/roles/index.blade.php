@extends('Centaur::layout')

@section('title', __('basic.roles'))

@section('content')
<div class="row">    
<div class="page-header">
	<div class='btn-toolbar pull-right'>
		@if(Sentinel::getUser()->hasAccess(['roles.create']) || in_array('roles.create', $permission_dep))
			<a class="btn btn-primary btn-lg" href="{{ route('roles.create') }}">
				<i class="fas fa-plus"></i>
				@lang('basic.create_role')
			</a>
		@endif
	</div>
	<h1>@lang('basic.roles')</h1>
</div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			<table id="index_table" class="display table table-hover">
				<thead>
					<tr>
						<th>@lang('basic.name')</th>
						<th>@lang('absence.mark')</th>
						<th>@lang('basic.permissions')</th>
						<th class="not-export-column">@lang('basic.options')</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($roles as $role)
						<tr>
							<td>{{ $role->name }}</td>
							<td>{{ $role->slug }}</td>
							<td>{{ implode(", ", array_keys($role->permissions)) }}</td>
							<td class="center">
								@if(Sentinel::getUser()->hasAccess(['roles.update']) || in_array('roles.update', $permission_dep))
									<a href="{{ route('roles.edit', $role->id) }}" class="">
										 <i class="far fa-edit"></i>
									</a>
								@endif
								@if(Sentinel::getUser()->hasAccess(['roles.delete']) || in_array('roles.delete', $permission_dep))
									@if (! $userRoleIds->contains($role->id))
										<a href="{{ route('roles.destroy', $role->id) }}" class="action_confirm danger" data-method="delete" data-token="{{ csrf_token() }}">
										   <i class="far fa-trash-alt"></i>
										</a>
									@endif
								@endif
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@stop
