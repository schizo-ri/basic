<span hidden class="locale" >{{ App::getLocale() }}</span>
<div class="modal-header">
    <h3 class="panel-title">@lang('basic.edit_role')</h3>
</div>
<div class="modal-body">
    <form accept-charset="UTF-8" role="form" class="roles_form" method="post" action="{{ route('roles.update', $role->id) }}">
        <div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="{{ __('basic.name')}}" name="name" type="text" value="{{ $role->name }}" required />
            {!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('slug')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="{{ __('absence.mark')}}" name="slug" type="text" value="{{ $role->slug }}" required />
            {!! ($errors->has('slug') ? $errors->first('slug', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        
        <h5>@lang('basic.permissions'): <span class="modal_filter"><input type="search" placeholder="{{ __('basic.search')}}" id="mySearch"></span></h5>
        @foreach($tables as $table_name => $table_description)
            @foreach($methodes as  $methode_name => $methode_description)
                <div class="checkbox col-6 float_l panel">
                    <label>
                        <input type="checkbox" name="permissions[{{$table_name}}.{{$methode_name}}]" value="1" {!! $role->hasAccess($table_name . '.' . $methode_name) ? 'checked' : '' !!}>
                        {{$table_description}}  - {{$methode_description}}
                    </label>
                </div>
            @endforeach
        @endforeach
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <input class="btn-submit" type="submit" value="{{ __('basic.edit')}}">
    </form>
</div>
<script>

    $.getScript( '/../js/validate.js');
</script>