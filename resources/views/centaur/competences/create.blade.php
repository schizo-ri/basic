<div class="modal-header">
    <h3 class="panel-title">@lang('basic.new_category')</h3>
</div>
<div class="modal-body">
    <form accept-charset="UTF-8" role="form" method="post" action="{{ route('competences.store') }}">
        <div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
            <label>@lang('basic.name')</label>
            <input name="name" type="text" class="form-control" value="{{ old('name') }}" maxlength="100" required>
            {!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group clear_l {{ ($errors->has('description')) ? 'has-error' : '' }}">
			<label>@lang('basic.description')</label>
			<textarea name="description" class="form-control" type="text" maxlength="65535" >{{ old('description') }}</textarea>
			{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
		</div>
        <div class="form-group {{ ($errors->has('department_id'))  ? 'has-error' : '' }}">
            <label>@lang('basic.department')</label>
            <select class="form-control" name="department_id[]" value="{{ old('department_id') }}" multiple >
                @foreach($departments as $department)
                    <option name="department_id" value="{{ $department->id }}">{{ $department->name . '[' .$department->level1 . ']' }}</option>
                @endforeach	
            </select>
            {!! ($errors->has('department_id') ? $errors->first('department_id', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('work_id'))  ? 'has-error' : '' }}">
            <label>@lang('basic.work')</label>
            <select class="form-control" name="work_id[]" value="{{ old('work_id') }}" multiple >
                @foreach($works as $work)
                    <option name="work_id" value="{{ $work->id }}">{{ $work->name . ' ['. $work->department['name'].']' }}</option>
                @endforeach	
            </select>
            {!! ($errors->has('work_id') ? $errors->first('work_id', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
            <label>Ocjenjuje djelatnik</label>
            <select class="form-control" name="employee_id"  id="select_employee" value="{{ old('employee_id') }}" size="10" autofocus required >
                <option value="" disabled></option>
                @foreach ($employees as $employee)
                    <option name="employee_id" value="{{ $employee->id }}" >{{ $employee->user['last_name']  . ' ' . $employee->user['first_name'] }}</option>
                @endforeach	
            </select>
            {!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('status')) ? 'has-error' : '' }}">
			<label>Status</label>
			<label class="status" for="status_1">@lang('basic.active') <input name="status" type="radio" value="1" id="status_1"  /></label>
			<label class="status" for="status_0">@lang('basic.inactive') <input name="status" type="radio" value="0" id="status_0" checked /></label>
			{!! ($errors->has('start_date') ? $errors->first('start_date', '<p class="text-danger">:message</p>') : '') !!}
		</div>
        <div class="form-group">
            @for ($i = 0; $i < 5; $i++)
                <p class="overflow_hidd rating_group">
                    <span class="rating_span col-3 float_left"><input class="" name="rating[]" type="number" step="1" min="0" max="100" /></span>
                    <span class="rating_span col-9 float_left"><input name="r_description[]" type="text" /></span>
                </p>
            @endfor
            <span class="add_rating">Dodaj Ocjenu</span>
        </div>
        {{ csrf_field() }}
        <input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
    </form>
</div>
<script>
    $('.add_rating').on('click',function(){
        var num_rating = $('.rating_group').length;
        console.log(num_rating);
        $( this ).before('<p class="overflow_hidd rating_group"><span class="rating_span col-3 float_left"><input class="" name="rating[]" type="number" step="1" min="0" max="100" /></span><span class="rating_span col-9 float_left"><input name="r_description[]" type="text" /></span></p>');
    });
</script>