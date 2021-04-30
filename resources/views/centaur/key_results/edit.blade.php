<div class="modal-header">
    <h3 class="panel-title">@lang('basic.edit_keyresult') (OKR)</h3>
</div>
<div class="modal-body">
    <form class="form_okr form_edit" accept-charset="UTF-8" role="form" method="post" action="{{ route('key_results.update', $key_result->id ) }}">
        <div class="form-group {{ ($errors->has('okr_id')) ? 'has-error' : '' }}">
            <label>OKR</label>
            <select class="form-control" name="okr_id" value="{{ old('okr_id') }}" required >
                <option value="" selected disabled></option>
                @if (Sentinel::inRole('uprava'))
                    @foreach ($okrs as $okr)
                        <option name="okr_id" value="{{ $okr->id }}" {!! $key_result->okr_id == $okr->id ? 'selected' : '' !!}>{{  $okr->name }}</option>
                    @endforeach	
                @else
                    <option name="okr_id" value="{{ $key_result->okr->id }}" selected >{{  $key_result->okr->name }}</option>
                @endif
            </select>
            {!! ($errors->has('okr_id') ? $errors->first('okr_id', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
            <label>@lang('basic.name')</label>
            <input name="name" type="text" class="form-control" value="{{ $key_result->name }}" maxlength="191" required autofocus>
            {!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
            <label>@lang('basic.employee')</label>
            <select class="form-control" name="employee_id" value="{{ old('employee_id') }}"  >
                <option value="" selected disabled></option>
                @foreach ($employees as $employee)
                    <option name="employee_id" value="{{ $employee->id }}"  {!! $key_result->employee_id == $employee->id ? 'selected' : '' !!}>{{  $employee->last_name . ' ' . $employee->first_name }}</option>
                @endforeach	
            </select>
            {!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('progress'))  ? 'has-error' : '' }}">
            <label>@lang('basic.progress')</label>
            <input name="progress" class="form-control" type="number" id="progress" max="100" step="5"  value="{!! $key_result->progress ?  $key_result->progress : 0 !!}">
            {!! ($errors->has('progress') ? $errors->first('progress', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('end_date'))  ? 'has-error' : '' }}">
            <label>@lang('absence.end_date')</label>
            <input name="end_date" type="date" class="form-control" id="end_date" value="{{ date('Y-m-d',strtotime($key_result->end_date )) }}" required>
            {!! ($errors->has('end_date') ? $errors->first('end_date', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('comment'))  ? 'has-error' : '' }}">
            <label>@lang('basic.comment')</label>
            <textarea name="comment" type="text" class="form-control" maxlength="21845"  >{{ $key_result->comment }}</textarea>
            {!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        {{ method_field('PUT') }}
		{{ csrf_field() }}
        <input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
    </form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
    $.getScript('/../js/okr.js');
    $('#year').on('change',function(){
        $('#end_date').val( $( this ).val() + '-12-31' );
    });
</script>
