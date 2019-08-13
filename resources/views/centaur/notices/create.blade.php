<form accept-charset="UTF-8" role="form" method="post" action="{{ route('notices.store') }}" >
    <div class="modal-header">
        <a class="link_back" rel="modal:close">
            <img src="{{ URL::asset('icons/arrow_left2.png') }}" />
        </a>
        <input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
        <a class="schedule_notice">Schedule</a>
        <h3 class="panel-title">@lang('basic.add_notice')</h3>
    </div>
    <div class="modal-body">
    
        <div class="form-group {{ ($errors->has('to_department'))  ? 'has-error' : '' }}">
            <label>@lang('basic.to_department')</label>
            <select  class="form-control" name="to_department[]" value="{{ old('to_department') }}" multiple required >
                <option value="" disabled ></option>
                @foreach($departments0 as $department0)
                    <option value="{{ $department0->id }}">{{ $department0->name }}</option>
                @endforeach
                @foreach($departments1 as $department1)
                    <option value="{{ $department1->id }}">{{ $department1->name }}</option>
                    @foreach($departments2 as $department2)
                        @if($department2->level2 == $department1->id)
                        <option value="{{ $department2->id }}">-  {{ $department2->name }}</option>
                        @endif
                    @endforeach
                @endforeach
            </select>
            {!! ($errors->has('to_department') ? $errors->first('to_department', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('title'))  ? 'has-error' : '' }}">
            <label>@lang('basic.title')</label>
            <input name="title" type="text" class="form-control" value="{{ old('title') }}" required>
            {!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group last">
            <label>@lang('basic.notice'):</label>
            <textarea id="summernote" name="notice" required></textarea>
        </div>
        {{ csrf_field() }}
        
    </div>
</form>

<script>
    $(function() {
        $("#summernote").summernote({
       
        });
         $('.modal').addClass('modal_notice');
        var height = 0;
        var modal_height = $('.modal.modal_notice').height();
        var header_height =  $('.modal-header').height();
        var body_height =  modal_height - header_height - 65;
        $('.modal-body').height(body_height);
        
        $( '.form-group' ).each(function() {
                height += $( this ).height();
        });
        var height2 = height - $( '.form-group' ).filter(':nth-child(3n)').height();
        
        var last_height = body_height - height2 - 80;
        $('.form-group.last').height(last_height);
        $('.note-editable').height(last_height - 150 );
    });

    
    $( window ).resize(function() {
        var height = 0;
        var modal_height = $('.modal.modal_notice').height();
        var header_height =  $('.modal-header').height();
        var body_height =  modal_height - header_height - 65;
        $('.modal-body').height(body_height);
        
        $( '.form-group' ).each(function() {
                height += $( this ).height();
        });
        var height2 = height - $( '.form-group' ).filter(':nth-child(3n)').height();
        
        var last_height = body_height - height2 - 80;
        $('.form-group.last').height(last_height);
        $('.note-editable').height(last_height - 150 );
    });
</script>