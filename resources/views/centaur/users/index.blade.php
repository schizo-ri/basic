@extends('Centaur::layout')

@section('title', 'Users')

@section('content')
    <div class="page-header">
        @if(Sentinel::getUser()->hasAccess(['users.create']))
			<div class='btn-toolbar pull-right'>
				<a class="btn btn-primary btn-lg" href="{{ route('users.create') }}">
				   <i class="fas fa-plus"></i>
					@lang('basic.create_user')
				</a>
			</div>
		@endif
        <h1>@lang('basic.users')</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @foreach ($users as $user)
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <div class="panel panel-default">
                        <div class="panel-body text-center">
                            <img src="//www.gravatar.com/avatar/{{ md5($user->email) }}?d=mm" alt="{{ $user->email }}" class="img-circle">
                            @if (!empty($user->first_name . $user->last_name))
                                <h4>{{ $user->first_name . ' ' . $user->last_name}}</h4>
                                <p>{{ $user->email }}</p>
                            @else
                                <h4>{{ $user->email }}</h4>
                            @endif
                        </div>
                        <ul class="list-group">
                            <li class="list-group-item">
                            @if ($user->roles->count() > 0)
                                {{ $user->roles->implode('name', ', ') }}
                            @else
                                <em>No Assigned Role</em>
                            @endif
                            </li>
                        </ul>
                        <div class="panel-footer">
                            @if(Sentinel::getUser()->hasAccess(['users.update']))
								<a href="{{ route('users.edit', $user->id) }}" class="btn-edit">
									<i class="far fa-edit"></i>
									Edit
								</a>
							@endif
							@if(Sentinel::getUser()->hasAccess(['users.delete']))
								<a href="{{ route('users.destroy', $user->id) }}" class="btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
								   <i class="far fa-trash-alt"></i>
									Delete
								</a>
							@endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    {!! $users->render() !!}
@stop
