@extends('Centaur::layout')

@section('title', 'Roles')

@section('content')
<div class="row">    
<div class="page-header">
	<div class='btn-toolbar pull-right'>
		@if(Sentinel::getUser()->hasAccess(['roles.create']) || in_array('roles.create', $permission_dep))
			<a class="btn btn-primary btn-lg" href="{{ route('roles.create') }}">
				<i class="fas fa-plus"></i>
				Create Role
			</a>
		@endif
	</div>
	<h1>Roles</h1>
</div>

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			<table id="index_table" class="display table table-hover">
				<thead>
					<tr>
						<th>Name</th>
						<th>Slug</th>
						<th>Permissions</th>
						<th>Options</th>
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
<!-- Datatables -->
<script type="text/javascript" src="{{ URL::asset('dataTables/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dataTables/JSZip-2.5.0/jszip.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dataTables/pdfmake-0.1.36/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dataTables/pdfmake-0.1.36/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dataTables/Buttons-1.5.6/js/buttons.print.min.js') }}"></script>

<script src="{{ URL::asset('js/datatables.js') }}"></script>
@stop
