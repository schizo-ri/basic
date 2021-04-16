<div class="modal-header">
    <h3 class="panel-title">ispravi tip gradilišta</h3>
</div>
<div class="modal-body">
    <form accept-charset="UTF-8" role="form" method="post" action="{{ route('construction_sites.update', $construction_site->id ) }}">
        <div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
            <label>@lang('basic.name')</label>
            <input name="name" type="text" class="form-control" value="{{  $construction_site->name }}" maxlength="191" required>
            {!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        {{ csrf_field() }}
		{{ method_field('PUT') }}
        <input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
    </form>
</div>