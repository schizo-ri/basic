<div class="modal-header">
    <h3 class="panel-title">@lang('basic.add_task')</h3>
</div>
<div class="modal-body">
    <form class="form_okr form_store" accept-charset="UTF-8" role="form" method="post" action="{{ route('key_result_tasks.store') }}">
        <div class="form-group {{ ($errors->has('keyresult_id')) ? 'has-error' : '' }}">
            <label>Ključni rezultat [Key result ]</label>
            <select class="form-control" name="keyresult_id" value="{{ old('keyresult_id') }}" required >
                <option value="" selected disabled></option>
                @if ( $this_keyResult )
                    <option name="keyresult_id" value="{{ $this_keyResult->id }}" selected >{{ $this_keyResult->name }}</option>
                @else
                    @foreach ($keyResults as $keyResult)
                        <option name="keyresult_id" value="{{ $keyResult->id }}" {!! $keyResults_id && $keyResults_id == $keyResult->id ? 'selected' : '' !!}>{{ $keyResult->name }}</option>
                    @endforeach	
                @endif
            </select>
            {!! ($errors->has('keyresult_id') ? $errors->first('keyresult_id', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
            <label>@lang('basic.name')</label>
            <input name="name" type="text" class="form-control" value="{{ old('name') }}" maxlength="191" required autofocus >
            {!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
            <label>@lang('basic.employee')</label>
            <select class="form-control" name="employee_id" value="{{ old('employee_id') }}"  >
                <option value="" selected disabled></option>
                @foreach ($employees as $employee)
                    <option name="employee_id" value="{{ $employee->id }}">{{ $employee->last_name . ' ' . $employee->first_name }}</option>
                @endforeach	
            </select>
            {!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('end_date'))  ? 'has-error' : '' }}">
            <label>@lang('absence.end_date')</label>
            <input name="end_date" type="date" class="form-control" id="end_date" value="{!! old('end_date') ? old('end_date') : $this_keyResult ? date('Y-m-d', strtotime( $this_keyResult->end_date)) : null !!}" required>
            {!! ($errors->has('end_date') ? $errors->first('end_date', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('comment'))  ? 'has-error' : '' }}">
            <label>@lang('basic.comment')</label>
            <textarea name="comment" type="text" class="form-control" maxlength="21845"  >{{ old('comment') }}</textarea>
            {!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        {{ csrf_field() }}
        <input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
    </form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
     $.getScript('/../js/okr_store.js');
    $('#year').on('change',function(){
        $('#end_date').val( $( this ).val() + '-12-31' );
    });
</script>
