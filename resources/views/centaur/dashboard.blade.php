@extends('Centaur::layout')

@section('title', __('welcome.dashboard'))

@section('content')

    @if (Sentinel::check())
		
    @else
		<div class="row">
			<div class="jumbotron">
				<h1>@lang('welcome.welcome')</h1>
				<p>@lang('welcome.must_login')</p>
				<p><a class="btn btn-primary btn-lg" href="{{ route('auth.login.form') }}" role="button">Log In</a></p>
			</div>
		</div>
    @endif

    <?php
        $user = Sentinel::findById(1);

        // var_dump(Activation::create($user));
    ?>

@stop