<div class="modal-header">
    <h3 class="panel-title">@lang('basic.edit_goal') (OKR)</h3>
</div>
<div class="modal-body">
    <form class="form_okr form_edit" accept-charset="UTF-8" role="form" method="post" action="{{ route('okrs.update', $okr->id) }}">
        <div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
            <label>@lang('basic.name')</label>
            <input name="name" type="text" class="form-control" value="{{  $okr->name }}" maxlength="191" required>
            {!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
            <label>@lang('basic.employee')</label>
            <select class="form-control" name="employee_id"  >
                <option value="" disabled selected></option>
                @foreach ($employees as $employee)
                    <option name="employee_id" value="{{ $employee->id }}" {!! $okr->employee_id == $employee->id ? 'selected' : '' !!}>{{  $employee->last_name . ' ' . $employee->first_name }}</option>
                @endforeach	
            </select>
            {!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('year'))  ? 'has-error' : '' }}">
            <label class="capitalize">@lang('basic.year')</label>
            <select name="year" id="year" required>
                @for ($i = 0; $i < 10; $i++)
                    <option value="{{ date('Y') +$i }}" {!! (date('Y') +$i ) == date('Y',strtotime($okr->start_date)) ? 'selected' : '' !!} >{{ date('Y') +$i }}</option>
                @endfor
            </select>
            {!! ($errors->has('year') ? $errors->first('year', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('quarter'))  ? 'has-error' : '' }}">
            <label>Kvartal</label>
            <select name="quarter" id="quarter" required>
                <option value="q1" {!! date('m',strtotime($okr->start_date) ) == 1 ? 'selected' : '' !!} >Q1 [01.01.-31.03.]</option>
                <option value="q2" {!! date('m',strtotime($okr->start_date) ) == 4 ? 'selected' : '' !!} >Q2 [01.04.-30.06.]</option>
                <option value="q3" {!! date('m',strtotime($okr->start_date) ) == 7 ? 'selected' : '' !!} >Q3 [01.07.-30.09.]</option>
                <option value="q2" {!! date('m',strtotime($okr->start_date) ) == 10 ? 'selected' : '' !!}>Q2 [01.10.-31.12.]</option>
            </select>
            {!! ($errors->has('quarter') ? $errors->first('quarter', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('progress'))  ? 'has-error' : '' }}">
            <label>@lang('basic.progress')</label>
            <input name="progress" class="form-control" type="number" id="progress" max="100" step="5"  value="{!! $okr->progress ?  $okr->progress : 0 !!}">
            {!! ($errors->has('progress') ? $errors->first('progress', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('comment'))  ? 'has-error' : '' }}">
            <label>@lang('basic.comment')</label>
            <textarea name="comment" type="text" class="form-control" maxlength="21845"  >{{ $okr->comment}}</textarea>
            {!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="servis form-group">
            <label>Status</label>
            <label for="status_0">Duplico OKR</label>
            <input class="" type="radio" name="status"  id="status_0" value="0" {!! $okr->status == 0 || ! $okr->status ? 'checked' : '' !!} />
            <label for="status_1">Timski OKR</label>
            <input class="" type="radio" name="status"  id="status_1" value="1" {!! $okr->status == 1 ? 'checked' : '' !!}/>
        </div>
        {{ method_field('PUT') }}
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
