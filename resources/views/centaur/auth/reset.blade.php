<div class="modal-header">
    <h3 class="panel-title">@lang('ctrl.reset')</h3>
</div>
<div class="modal-body">
    <form accept-charset="UTF-8" role="form" method="post" action="{{ route('auth.password.request.attempt') }}">
    <fieldset>
        <div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="E-mail" name="email" type="text" value="{{ old('email') }}">
            {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        {{ csrf_field() }}
        <input class="btn-submit" type="submit" value="Help!">
    </fieldset>
    </form>
</div>