@extends('Centaur::layout')

@section('title', 'Users')

@section('content')
    <div class="page-header">
        <div class='btn-toolbar pull-right'>
            <a href="{{ route('users.create') }}" rel="modal:open"><img class="" src="{{ URL::asset('icons/plus.png') }}" alt="arrow" /></a>
            <label class="filter_empl"><input type="text" class="mySearch" id="mySearch" placeholder="Search..." title="Type in..."></label>
        </div>
        <h1>Users</h1>
        
       
    </div>
    <div class="">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @foreach ($users as $user)
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <div class="panel panel-default">
                        <div class="panel-body text-center">
                            <img src="//www.gravatar.com/avatar/{{ md5($user->email) }}?d=mm" alt="{{ $user->email }}" class="img-circle">
                            @if (!empty($user->first_name . $user->last_name))
                                <h4>{{ $user->first_name . ' ' . $user->last_name}} <span class="user_color" style="background-color:{{ $user->color }} "></span></h4>
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
                            <a href="{{ route('users.edit', $user->id) }}" class="btn" rel="modal:open">
                                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                Edit
                            </a>
                            <a href="{{ route('users.destroy', $user->id) }}" class="btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}">
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                Delete
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <script>
        
        $( document ).ready(function(){
            $("#mySearch").keyup( function() {
                var value = $(this).val().toLowerCase();
                $(".panel").parent().filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
        </script>
@stop
