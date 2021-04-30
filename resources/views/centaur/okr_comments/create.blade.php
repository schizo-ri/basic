<div class="modal-header">
    <h3 class="panel-title">Dodaj komentar</h3>
</div>
<div class="modal-body">
    <form class="form_okr form_store" accept-charset="UTF-8" role="form" method="post" action="{{ route('okr_comments.store') }}" >
        <input type="hidden" name="okr_id" value="{{ $okr_id }}">
        <div class="form-group {{ ($errors->has('comment'))  ? 'has-error' : '' }}">
            <label>@lang('basic.comment')</label>
            <textarea name="comment" type="text" class="form-control" maxlength="21845"  >{{ old('comment') }}</textarea>
            {!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        @csrf
        <input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
    </form>
</div>
<script>
    $.getScript('/../js/okr.js');
</script>