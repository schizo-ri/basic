@extends('Centaur::layout')

@section('title', __('basic.users'))

@section('content')
<div class="row">
    <div class="page-header">
        <div class='btn-toolbar pull-right'>
            @if(Sentinel::getUser()->hasAccess(['users.create']))
				<a class="btn btn-primary btn-lg" href="{{ route('users.create') }}">
					<i class="fas fa-plus"></i>
					@lang('basic.create_user')
				</a>
			@endif
        </div>
        <h1>@lang('basic.users')</h1>
    </div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($users))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.fl_name')</th>
							<th>email</th>
							<th>@lang('basic.b_day')</th>
							<th>@lang('basic.work')</th>
							<th>@lang('basic.dep_permissions')</th>
							<th>@lang('basic.permissions')</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($users as $user)
							<tr>
								<td>{{ $user->first_name . ' ' . $user->last_name }}</td>
								<td>{{ $user->email }}</td>
								<td>{!! isset($user->b_day) ? date('d.m.Y', strtotime($user->b_day)) : '' !!}</td>
								<td>{!! isset($user->work_id) ? $works->where('id', $user->work_id)->first()->department['name'] . ' - ' .  $works->where('id', $user->work_id)->first()->name : '' !!}</td>
								<td>{!! isset($user->work_id) &&  $departmentRoles->where('department_id', $user->work_id)->first() ? $departmentRoles->where('department_id', $user->work_id)->first()->permissions : '' !!}</td>
								<td>
									@if ($user->roles->count() > 0)
										{{ $user->roles->implode('name', ', ') }}
									@else
										<em>No Assigned Role</em>
									@endif
								</td>
								<td class="center">
									@if(Sentinel::getUser()->hasAccess(['users.update']))
										<a href="{{ route('users.edit', $user->id) }}" class="" title="{{ __('basic.edit_user') }}">
											 <i class="far fa-edit"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['employees.create']) && ! $employees->where('user_id',$user->id)->first())
										 <a href="{{ route('employees.create', ['user_id' => $user->id] ) }}" class="" title="{{ __('basic.add_employee') }}">
											<i class="fas fa-user-plus"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['employees.update']) && $employees->where('user_id',$user->id)->first())
										 <a href="{{ route('employees.edit',$employees->where('user_id',$user->id)->first()->id ) }}" class="" title="{{ __('basic.edit_employee') }}">
											<i class="fas fa-user-cog"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['users.delete'])&& !$employees->where('user_id',$user->id)->first())
										<a href="{{ route('users.destroy', $user->id) }}" class="action_confirm danger" data-method="delete" data-token="{{ csrf_token() }}">
											<i class="far fa-trash-alt"></i>
										</a>
									@endif
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@endif
		</div>
	</div>
</div>
@stop