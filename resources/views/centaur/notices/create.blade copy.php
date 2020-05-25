<!-- Jquery validation -->
<script src="{{ URL::asset('/../node_modules/jquery-validation/dist/jquery.validate.js') }}"></script>
<script src="{{ URL::asset('/../node_modules/jquery-validation/dist/additional-methods.js') }}"></script>
@if (App::isLocale('hr'))
	<script src="{{ URL::asset('/../node_modules/jquery-validation/dist/localization/messages_hr.js') }}"></script>
@endif
<form id="notice_form" accept-charset="UTF-8" role="form" method="post" action="{{ route('notices.store') }}" enctype="multipart/form-data" >
    <div class="modal-header">
        <a class="link_back" rel="modal:close">
            <img src="{{ URL::asset('icons/arrow_left2.png') }}" />
        </a>
        <input class="btn-submit" type="submit" value="{{ __('basic.send')}}" id="stil1">
    <!--     <input class="schedule_notice" type="submit" value="{{ __('basic.schedule')}}" id="stil1">
    
        <script>
            $('.schedule_notice').click(function(event){
                $('.modal-body').prepend('<input class="schedule_input" name="schedule" type="hidden" value="true">');

            });
        </script>-->
        <h3 class="panel-title">@lang('basic.add_notice')</h3>
    </div>
    <div class="modal-body">
        <div class="form-group {{ ($errors->has('to_department'))  ? 'has-error' : '' }}">
            <label>@lang('basic.to_department')</label>
            @foreach($departments0 as $department0)
                <div class="col-12">
                    <span class="float_l col-xs-6 col-sm-6 col-lg-4  col-xl-3" ><input class="float_l" type="checkbox" name="to_department[]" id="{{ $department0->id }}" value="{{ $department0->id }}"/><label for="{{ $department0->id }}">{{ $department0->name }}</label></span>
                    @foreach($departments2 as $department2)
                        @if ($department2->level2 == $department0->id )
                            <span class="float_l col-xs-6 col-sm-6 col-lg-4  col-xl-3" ><input type="checkbox" name="to_department[]" id="{{ $department2->id }}" value="{{ $department2->id }}"/><label for="{{ $department2->id }}">{{ $department2->name }}</label></span>
                        @endif
                    @endforeach
                    @foreach($departments1 as $department1)
                            @if ($department1->level2 == $department0->id )
                                <span class="float_l col-xs-6 col-sm-6 col-lg-4  col-xl-3" ><input type="checkbox" name="to_department[]" id="{{ $department1->id }}" value="{{ $department1->id }}"/><label for="{{ $department1->id }}">{{ $department1->name }}</label></span>
                            @endif
                            @foreach($departments2 as $department2)
                              
                                @if ($department2->level2 == $department1->id )
                                    <span class="float_l col-xs-6 col-sm-6 col-lg-4  col-xl-3" ><input type="checkbox" name="to_department[]" id="{{ $department2->id }}" value="{{ $department2->id }}"/><label for="{{ $department2->id }}">{{ $department2->name }}</label></span>
                                @endif
                            @endforeach
                    @endforeach
                </div>
            @endforeach
            {!! ($errors->has('to_department') ? $errors->first('to_department', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <!--
        <div class="form-group {{ ($errors->has('to_department'))  ? 'has-error' : '' }}">
            <label>@lang('basic.to_department')</label>
            <select  class="form-control" name="to_department[]" value="{{ old('to_department') }}" required multiple  >
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
        </div>-->
        <div class="form-group {{ ($errors->has('title'))  ? 'has-error' : '' }}">
            <label>@lang('basic.title')</label>
            <input name="title" type="text" class="form-control" value="{{ old('title') }}" required>
            {!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group">
            <label class="label_file" for="file">Top notice image<span><img src="{{ URL::asset('icons/download.png') }}" />Upload image</span></label>
            <input type='file' id="file" name="fileToUpload" >
        </div>
        <div class="form-group  {{ ($errors->has('schedule_date'))  ? 'has-error' : '' }}" >
            <label>@lang('basic.schedule')</label>
            <p class="schedule">
                <input type="radio" name="schedule_set" id="send" class="select_save" value="0" checked />
                    <label for="send">@lang('basic.send')</label>
                <input type="radio" name="schedule_set" id="schedule" class="select_save" value="1" />
                    <label for="schedule">@lang('basic.schedule')</label>
                <i class="far fa-question-circle show_popUp"></i>            
            </p>
            <p class="pop-up">@lang('basic.schedule_notice')</p>
            <input name="schedule_date" class="schedule_date" type="date" class="form-control" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}" required />
            <input name="schedule_time" class="schedule_date" type="time" class="form-control" value="" />
            {!! ($errors->has('schedule_date') ? $errors->first('schedule_date', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group last {{ ($errors->has('notice')) ? 'has-error' : '' }}">
            <label>@lang('basic.notice'):</label>
            <textarea name="notice" id="mytextarea" maxlength="16777215">{{ old('notice') }}</textarea>
            {!! ($errors->has('notice') ? $errors->first('notice', '<p class="text-danger">:message</p>') : '') !!}
        </div>       
        {{ csrf_field() }}
    </div>
</form>

<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
	$.getScript( '/../js/tinymce.js');

	$('body').on($.modal.CLOSE, function(event, modal) {
		$.getScript('/../node_modules/tinymce/tinymce.min.js');
	});	
   
    $(function() {        
        var height = 0;
        var body_height = 0;
        var modal_height = $('.modal.modal_notice').height();
        var modal_width =  $('.modal.modal_notice').width();
        var header_height =  $('.modal-header').height();
        if(modal_width> 450) {
            body_height =  modal_height - header_height - 65;
        }  else {
            body_height =  modal_height - header_height - 30;
        }
        
        $('.modal-body').height(body_height);
       
        if(modal_height > 1000 ) {
            $( '.form-group' ).each(function() {
                height += $( this ).height();
            });
            var height2 = height - $( '.form-group' ).filter(':nth-child(5n)').height();
        
            var last_height = body_height - height2 - 80;
            $('.form-group.last').height(last_height - 60);
            $('.note-editable').height(last_height - 160 );
        }

        $('.select_save').click(function(){
            if($(this).val() == 1 ) {
                $('.schedule_date').show();
                $('.btn-submit').val("Snimi");
            } else {
                $('.schedule_date').hide();
                $('.btn-submit').val("Pošalji");
            }
        });

        $('.show_popUp').click(function(){  
            $('.pop-up').toggle();
        });

        var mouse_is_inside = false;

        $('.show_popUp').hover(function(){ 
                mouse_is_inside=true; 
            }, function(){ 
                mouse_is_inside=false; 
        });

        $("body").mouseup(function(){ 
            if(! mouse_is_inside) 
                $('.pop-up').hide();
        });
      
    });
    $( window ).resize(function() {
        var height = 0;
        var modal_height = $('.modal.modal_notice').height();
        var header_height =  $('.modal-header').height();
        var body_height =  modal_height - header_height - 65;
        $('.modal-body').height(body_height);
        
        if(modal_height > 1000 ) {
            $( '.form-group' ).each(function() {
                    height += $( this ).height();
            });
            var height2 = height - $( '.form-group' ).filter(':nth-child(5n)').height();
            
            var last_height = body_height - height2 - 80;
            $('.form-group.last').height(last_height - 60);
            $('.note-editable').height(last_height - 160 );
        }
    });
    $.getScript( '/../js/validate2.js');
</script>