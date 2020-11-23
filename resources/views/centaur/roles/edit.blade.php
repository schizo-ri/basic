<div class="modal-header">
	<h3 class="panel-title">Ispravi ulogu</h3>
</div>
<div class="modal-body">
    <form accept-charset="UTF-8" role="form" method="post" action="{{ route('roles.update', $role->id) }}">
    <fieldset>
        <div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="Name" name="name" type="text" value="{{ $role->name }}" />
            {!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('slug')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="slug" name="slug" type="text" value="{{ $role->slug }}" />
            {!! ($errors->has('slug') ? $errors->first('slug', '<p class="text-danger">:message</p>') : '') !!}
        </div>

        <h5>Permissions: <input type="search" placeholder="{{ __('basic.search')}}" id="mySearch"></span></h5>
        <button type="button" id="checkedAll">Označi sve</button><button type="button" id="uncheckedAll">Skini sve ovnake</button>
        @foreach ($modules as $module)
            @foreach ($permissions as $permission)
                <div class="checkbox panel">
                    <label>
                        <input type="checkbox" name="permissions[{{$module}}.{{ $permission }}]" value="1" {!! $role->hasAccess( $module .'.'. $permission ) ? 'checked' : '' !!}>
                        {{$module}}.{{ $permission }}
                    </label>
                </div>
            @endforeach
        @endforeach
        {{ csrf_field() }}
		{{ method_field('PUT') }}
        <input class="btn btn-lg btn-primary btn-block" type="submit" value="Update">
    </fieldset>
    </form>
</div>
<script>
    $("#mySearch").keyup( function() {
		var value = $(this).val().toLowerCase();
		$(".panel").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
    });
    $('#checkedAll').click(function(){
        $('input[type="checkbox"]:visible').prop('checked',true);
    });
    $('#uncheckedAll').click(function(){
        $('input[type="checkbox"]:visible').prop('checked',false);
    });
</script>