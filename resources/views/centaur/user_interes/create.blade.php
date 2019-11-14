<div class="modal-header">
    <h3 class="panel-title">@lang('basic.my_interest')</h3>
</div>
<div class="modal-body">
    <form accept-charset="UTF-8" role="form" method="post" action="{{ route('user_interes.store') }}">
       @if($tag)
            <div class="form-group {{ ($errors->has('category')) ? 'has-error' : '' }}">
                <label>@lang('basic.interest')</label>
                <input name="category" type="text" class="form-control" maxlength="191" />
                <span>{{ __('basic.enter_interest_groups') }}</span>
                {!! ($errors->has('category') ? $errors->first('category', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        @else
            <div class="form-group description {{ ($errors->has('description')) ? 'has-error' : '' }}">
                <label>@lang('basic.about_me')</label>
                <textarea name="description" class="form-control" type="text" ></textarea>
                {!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        @endif
        {{ csrf_field() }}
        <input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1" />
        <a href="" class="modal_close float_r" rel="modal:close">@lang('basic.cancel')</a>
    </form>
</div>
