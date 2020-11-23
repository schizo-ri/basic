<span hidden class="locale" >{{ App::getLocale() }}</span>
<div class="modal-header">
    <h3 class="panel-title">@lang('basic.edit_user')</h3>
</div>
<div class="modal-body">
    <form accept-charset="UTF-8" class="form_user form_edit_user" role="form" method="post" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data" >
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
                <input class="form-control" id="first_name" name="first_name" maxlength="191" type="text" value="{{ $user->first_name }}" required />
                {!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
            </div>
            <div class="form-group user_name {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
                <label for="last_name">@lang('basic.l_name')</label>
                <input class="form-control" name="last_name" maxlength="191" id="last_name" type="text" value="{{ $user->last_name}}" required />
                {!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
            </div>
            <div class="form-group email {{ ($errors->has('email')) ? 'has-error' : '' }}">
                <label for="email">E-mail</label>
                <input class="form-control" name="email" id="email" type="text" maxlength="191" value="{{ $user->email}}" required >
                {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
            </div>
            
            <button class="btn-next" type="button">@lang('basic.next')</button>
            <a class="btn-cancel" type="button" rel="modal:close">@lang('basic.cancel')</a>
           
        </div>
        <div class="second_tab">
            <div class="form-group  {{ ($errors->has('password')) ? 'has-error' : '' }}">
                <label>@lang('basic.password')</label>
                <input class="form-control" maxlength="191" name="password" id="password" type="password" value="" >             
            </div>
            <div class="form-group {{ ($errors->has('password_confirmation')) ? 'has-error' : '' }}">
                <label>@lang('basic.conf_password')</label>
                <input class="form-control" name="password_confirmation" id="conf_password" type="password" />
            </div>
            <div class="form-group" >
                <label>{{ __('basic.roles')}}</label>
                <div class="checkbox">
                        @foreach ($roles as $role)
                            <label>
                                @if($role->slug != 'superadmin')
                                    <input type="checkbox" class="roles" id="role{{ $role->id }}" name="roles[{{ $role->slug }}]" value="{{ $role->id }}" {!! $user->inRole($role) ? 'checked' : '' !!} />
                                    {{ $role->name }}
                                @endif
                                @if(Sentinel::inRole('superadmin') && $role->slug == 'superadmin' )
                                    <input type="checkbox" class="roles" id="role{{ $role->id }}" name="roles[{{ $role->slug }}]" value="{{ $role->id }}" {!! $user->inRole($role) ? 'checked' : '' !!} {!! (Sentinel::getUser()->id != $user->id || Sentinel::inRole('superadmin') ) ? 'disabled' : '' !!} />
                                    {{ $role->name }}
                                @endif
                            </label>
                        
                        @endforeach
                </div>
            </div>
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <input class="btn-submit" type="submit" value="{{ __('basic.edit')}}">
                <a class="btn-back" type="button">@lang('basic.back')</a>
        </div>
    </form>
</div>
</div>
<script>
    $.getScript('/js/validate.js');
</script>