@extends('Centaur::admin')

@section('title', __('basic.department_roles'))

@section('content')
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['department_roles.create']) || in_array('department_roles.create', $permission_dep))
				<a class="btn-new" href="{{ route('department_roles.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
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
							@php
								$role_permissions = explode(",",$department_role->permissions);
							@endphp
							<tr>
								<td>{{ $department_role->department['name'] }}</td>
								<td>
									@for ($i = 0; $i < count($role_permissions); $i++)
									<span class="role _{{ $i }}">{{ $role_permissions[$i] }}, </span>
								@endfor
								<span class="more">+ {{ count($role_permissions)-2 }} @lang('basic.more')</span>
								<span class="hide">@lang('basic.hide')</span>		</td>
								<td class="center">
									<!-- <button class="collapsible option_dots float_r"></button> -->
									@if(Sentinel::getUser()->hasAccess(['department_roles.update']) || in_array('department_roles.update', $permission_dep))
										<a href="{{ route('department_roles.edit', $department_role->id) }}" title="{{ __('basic.edit_roles') }}" class="btn-edit" rel="modal:open">
												<i class="far fa-edit"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['department_roles.delete']) || in_array('department_roles.delete', $permission_dep))
										<a href="{{ route('department_roles.destroy', $department_role->id) }}" title="{{ __('basic.delete_roles') }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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