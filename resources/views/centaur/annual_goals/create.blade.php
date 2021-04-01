<div class="modal-header">
    <h3 class="panel-title">@lang('basic.add_goal')</h3>
</div>
<div class="modal-body">
    <form accept-charset="UTF-8" role="form" method="post" action="{{ route('annual_goals.store') }}">
        <div class="form-group {{ ($errors->has('year'))  ? 'has-error' : '' }}">
            <label class="capitalize">@lang('basic.year')</label>
            <input name="year" type="number" step="1" min="2021" max="2099" class="form-control" id="year" value="{{ old('year') ? old('year') : date('Y') }}" required>
            {!! ($errors->has('year') ? $errors->first('year', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
            <label>@lang('basic.name')</label>
            <input name="name" type="text" class="form-control" value="{{ old('name') }}" maxlength="191" required>
            {!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('comment'))  ? 'has-error' : '' }}">
            <label>@lang('basic.comment')</label>
            <textarea name="comment" type="text" class="form-control" maxlength="21845"  >{{ old('comment') }}</textarea>
            {!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('end_date'))  ? 'has-error' : '' }}">
            <label>@lang('absence.end_date')</label>
            <input name="end_date" type="date" class="form-control" id="end_date" value="{{ old('end_date') ? old('end_date') : date('Y').'-12-31' }}" required>
            {!! ($errors->has('end_date') ? $errors->first('end_date', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        {{ csrf_field() }}
        <input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
    </form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
    $('#year').on('change',function(){
        $('#end_date').val( $( this ).val() + '-12-31' );
    });
</script>