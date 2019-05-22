@extends('Centaur::layout')

@section('title', __('welcome.dashboard'))

@section('content')
<div class="row">
    @if (Sentinel::check())
    
    @else
        <div class="jumbotron">
            <h1>@lang('welcome.welcome')</h1>
            <p>@lang('welcome.must_login')</p>
            <p><a class="btn btn-primary btn-lg" href="{{ route('auth.login.form') }}" role="button">@lang('welcome.login')</a></p>
        </div>
    @endif

    <?php
        $user = Sentinel::findById(1);

        // var_dump(Activation::create($user));
    ?>
</div>
@stop