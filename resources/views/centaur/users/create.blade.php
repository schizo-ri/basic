<span class="mark_lines">
    <span class="mark1"></span>
    <span class="mark2"></span>
</span>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<div class="modal-header">
    <h3 class="panel-title">@lang('basic.create_user')</h3>
</div>
<div class="modal-body">
    <form class="form_create_user" accept-charset="UTF-8" role="form" method="post" action="{{ route('users.store') }}" enctype="multipart/form-data" >
        <div class="first_tab">
            <div class="form-group upload_user_photo">
                <label class="label_file" for="file">@lang('basic.upload_photo')
                    <span>
                        <img src="{{ URL::asset('icons/download.png') }}" />@lang('basic.upload_photo')</span>
                </label>
                <input type='file' id="file" name="fileToUpload" />
                <span id="file_name"></span>
            </div>
            <div class="form-group user_name {{ ($errors->has('first_name')) ? 'has-error' : '' }}">
                <label for="first_name">@lang('basic.f_name')</label>
                <input class="form-control" id="first_name" name="first_name" maxlength="191" type="text" value="{{ old('first_name') }}" required />
                {!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
            </div>
            <div class="form-group user_name {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
                <label for="last_name">@lang('basic.l_name')</label>
                <input class="form-control" name="last_name" maxlength="191" id="last_name" type="text" value="{{ old('last_name') }}" required />
                {!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
            </div>
            <div class="form-group email {{ ($errors->has('email')) ? 'has-error' : '' }}">
                <label for="email">E-mail</label>
                <input class="form-control" name="email" id="email" type="text" maxlength="191" value="{{ old('email') }}" required >
                {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
            </div>
            <div class="form-group  {{ ($errors->has('password')) ? 'has-error' : '' }}">
                <label>@lang('basic.password')</label>
                <input class="form-control" maxlength="191" name="password" id="password" type="password" value="" required>
                {!! ($errors->has('password') ? $errors->first('password', '<p class="text-danger">:message</p>') : '') !!}
            </div>
            <div class="form-group  {{ ($errors->has('password_confirmation')) ? 'has-error' : '' }}">
                <label>@lang('basic.conf_password')</label>
                <input class="form-control" name="password_confirmation" id="conf_password" type="password" />
                {!! ($errors->has('password_confirmation') ? $errors->first('password_confirmation', '<p class="text-danger">:message</p>') : '') !!}
            </div>
            <button class="btn-next" type="button">@lang('basic.next')</button>
            <a class="btn-cancel" type="button" rel="modal:close">@lang('basic.cancel')</a>
        </div>
        <div class="second_tab">
            <div class="form-group">
                <label>@lang('basic.roles')</label>
                <div class="checkbox ">
                    @foreach ($roles as $role)
                        <label for="role{{ $role->id }}">
                            @if($role->slug != 'superadmin')
                                <input type="checkbox" class="roles" id="role{{ $role->id }}" name="roles[{{ $role->slug }}]" value="{{ $role->id }}">
                                {{ $role->name }}
                            @endif
                            @if(Sentinel::inRole('superadmin') && $role->slug == 'superadmin' )
                                <input type="checkbox" class="roles" name="roles[{{ $role->slug }}]" value="{{ $role->id }}">
                                {{ $role->name }}
                            @endif
                        </label>
                    @endforeach  
                </div>  
            </div>
            <div class="form-group checkbox">
                <p>
                    <label for="activate">@lang('basic.activate')</label>
                    <input name="activate" type="checkbox" id="activate" value="true" {{ old('activate') == 'true' ? 'checked' : ''}}> 
                </p>
            </div>
            {{ csrf_field() }}
            <div class="submit_element">
                <input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
                <a class="btn-back" type="button">@lang('basic.back')</a>
            </div>  
        </div>
    </form>
</div>
<script>
    $.getScript('/js/validate.js');
</script>