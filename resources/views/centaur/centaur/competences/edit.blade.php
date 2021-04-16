<div class="modal-header">
    <h3 class="panel-title">@lang('basic.new_category')</h3>
</div>
<div class="modal-body">
    <form accept-charset="UTF-8" role="form" method="post" action="{{ route('competences.update', $competence->id) }}">
        <div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
            <label>@lang('basic.name')</label>
            <input name="name" type="text" class="form-control" value="{{  $competence->name }}" maxlength="191" required>
            {!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('type'))  ? 'has-error' : '' }}">
            <label>@lang('basic.type')</label>
            <select name="type" class="form-control" value="{{ old('type') }}"  required>
                <option value="o" {!!  $competence->type == 'o' ? 'selected' : '' !!}>Opća</option>
                <option value="s" {!!  $competence->type == 's' ? 'selected' : '' !!}>Specifična</option>
            </select>
            {!! ($errors->has('type') ? $errors->first('type', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        {{ csrf_field() }}
		{{ method_field('PUT') }}
        <input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
    </form>
</div>