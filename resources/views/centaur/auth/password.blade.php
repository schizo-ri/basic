@extends('Centaur::layout')

@section('title', __('ctrl.password'))

@section('content')
<div class="row password_reset">
    <div class="col-md-4 offset-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">@lang('ctrl.password')</h3>
            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('auth.password.reset.attempt', $code) }}">
                <fieldset>
                    <div class="form-group  {{ ($errors->has('password')) ? 'has-error' : '' }}">
                        <input class="form-control" placeholder="Password" name="password" type="password" value="">
                        {!! ($errors->has('password') ? $errors->first('password', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    <div class="form-group  {{ ($errors->has('password_confirmation')) ? 'has-error' : '' }}">
                        <input class="form-control" placeholder="Confirm Password" name="password_confirmation" type="password" value="">
                        {!! ($errors->has('password_confirmation') ? $errors->first('password_confirmation', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    {{ csrf_field() }}
                    <input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
                </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@stop