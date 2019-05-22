@extends('Centaur::layout')

@section('title', __('basic.roles'))

@section('content')
    <div class="page-header">
        @if(Sentinel::getUser()->hasAccess(['roles.create']))
			<div class='btn-toolbar pull-right'>
				<a class="btn btn-primary btn-lg" href="{{ route('roles.create') }}">
					<i class="fas fa-plus"></i>
					@lang('basic.create_role')
				</a>
			</div>
		@endif
        <h1>@lang('basic.roles')</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table id="index_table" class="display table table-hover">
                    <thead>
                        <tr>
                            <th>@lang('basic.name')</th>
                            <th>Slug</th>
                            <th>@lang('basic.permissions')</th>
                            <th>@lang('basic.options')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->slug }}</td>
                                <td>{{ implode(", ", array_keys($role->permissions)) }}</td>
                                <td class="center">
                                    @if(Sentinel::getUser()->hasAccess(['roles.update']))
										<a href="{{ route('roles.edit', $role->id) }}" class="btn-edit">
											 <i class="far fa-edit"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['roles.delete']))
										@if (! $userRoleIds->contains($role->id))
										<a href="{{ route('roles.destroy', $role->id) }}" class="btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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
<script src="{{ URL::asset('js/datatables.js') }}"></script>
@stop
