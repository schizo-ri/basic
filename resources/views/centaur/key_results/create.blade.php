<div class="modal-header">
    <h3 class="panel-title">@lang('basic.add_keyresult')</h3>
</div>
<div class="modal-body">
    <form class="form_okr form_store" accept-charset="UTF-8" role="form" method="post" action="{{ route('key_results.store') }}">
        <div class="form-group {{ ($errors->has('okr_id')) ? 'has-error' : '' }}">
            <label>OKR</label>
            <select class="form-control" name="okr_id" value="{{ old('okr_id') }}" required >
                <option value="" selected disabled></option>
                @if( $this_okr ) 
                    <option name="okr_id" value="{{ $this_okr->id }}" selected >{{  $this_okr->name }}</option>
                @else
                    @foreach ($okrs as $okr)
                        <option name="okr_id" value="{{ $okr->id }}" {!! $okr_id && $okr_id == $okr->id ? 'selected' : '' !!}>{{  $okr->name }}</option>
                    @endforeach	
                @endif
            </select>
            {!! ($errors->has('okr_id') ? $errors->first('okr_id', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
            <label>@lang('basic.name')</label>
            <input name="name" type="text" class="form-control" value="{{ old('name') }}" maxlength="191" autofocus required >
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
            <input name="end_date" type="date" class="form-control" id="end_date" value="{!! old('end_date') ? old('end_date') : $this_okr ? date('Y-m-d', strtotime( $this_okr->end_date)) : null !!}" required>
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
    $('#year').on('change',function(){
        $('#end_date').val( $( this ).val() + '-12-31' );
    });
    $.getScript('/../js/okr_store.js');
  
</script>
