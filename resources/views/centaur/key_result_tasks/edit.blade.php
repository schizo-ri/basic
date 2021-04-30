<div class="modal-header">
    <h3 class="panel-title">@lang('basic.edit_task')</h3>
</div>
<div class="modal-body">
    <form class="form_okr form_edit" accept-charset="UTF-8" role="form" method="post" action="{{ route('key_result_tasks.update', $keyResultTask->id ) }}">
        <div class="form-group {{ ($errors->has('keyresult_id')) ? 'has-error' : '' }}">
            <label>Ključni rezultat [Key result ] {{ $keyResultTask->name }}</label>
            <input type="hidden" value="{{$keyResultTask->keyResult->id }}" name="keyresult_id" >
         {{--    <select class="form-control" name="keyresult_id" value="{{ old('keyresult_id') }}" required >
                <option value="" selected disabled></option>
                <option name="keyresult_id" value="{{ $keyResultTask->id }}" selected >{{ $keyResultTask->name }}</option>
                @foreach ($keyResults as $keyResult)
                    <option name="keyresult_id" value="{{ $keyResult->id }}" {!! $keyResultTask->keyresult_id == $keyResult->id  ? 'selected' : '' !!}>{{ $keyResult->name }}</option>
                @endforeach	
            </select> --}}
            {!! ($errors->has('keyresult_id') ? $errors->first('keyresult_id', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
            <label>@lang('basic.name')</label>
            <input name="name" type="text" class="form-control" value="{{  $keyResultTask->name }}" maxlength="191" required autofocus>
            {!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
            <label>@lang('basic.employee')</label>
            <select class="form-control" name="employee_id[]" value="{{ old('employee_id') }}" multiple >
              {{--   <option value="" selected disabled></option> --}}
                @foreach ($employees as $employee)
                    <option name="employee_id" value="{{ $employee->id }}" {!! in_array( $employee->id, $employee_ids)  ? 'selected' : '' !!}>{{ $employee->last_name . ' ' . $employee->first_name }}</option>
                @endforeach	
            </select>
            {!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('progress'))  ? 'has-error' : '' }}">
            <label>@lang('basic.progress')</label>
            <input name="progress" class="form-control" type="number" id="progress" max="100" step="5"  value="{!! $keyResultTask->progress ?  $keyResultTask->progress : 0 !!}">
            {!! ($errors->has('progress') ? $errors->first('progress', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('end_date'))  ? 'has-error' : '' }}">
            <label>@lang('absence.end_date')</label>
            <input name="end_date" type="date" class="form-control" id="end_date" value="{{ $keyResultTask->end_date }}" required>
            {!! ($errors->has('end_date') ? $errors->first('end_date', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('comment'))  ? 'has-error' : '' }}">
            <label>@lang('basic.comment')</label>
            <textarea name="comment" type="text" class="form-control" maxlength="21845"  >{{ $keyResultTask->comment }}</textarea>
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
