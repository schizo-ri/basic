<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
		<meta charset="UTF-8">
		<meta name="description" content="Portal za zaposlenike">
		<meta name="author" content="Jelena Juras">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>@lang('basic.edit_notice')</title>
        <!-- Bootstrap - Latest compiled and minified CSS -->
		<link rel="stylesheet" href="{{ URL::asset('/../node_modules/bootstrap/dist/css/bootstrap.min.css') }}"/>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
		
		<!--Awesome icons -->
		<link rel="stylesheet" href="{{ URL::asset('/../node_modules/@fortawesome/fontawesome-free/css/all.min.css') }}"/>

		<!-- JS modal -->
		<link rel="stylesheet" href="{{ URL::asset('/../node_modules/jquery-modal/jquery.modal.min.css') }}" type="text/css" />

		<!-- CSS -->
		<link rel="stylesheet" href="{{ URL::asset('/../css/campaign.css') }}"/>
		<link rel="stylesheet" href="{{ URL::asset('/../css/basic.css') }}"/>
		<!-- ICON -->
		<link rel="shortcut icon" href="{{ asset('img/icon.ico') }}">
		<script src="//editor.unlayer.com/embed.js"></script>
         {{-- Select2 --}}
         <link href="{{ URL::asset('/../select2-develop/dist/css/select2.min.css') }}" />
		<!--Jquery -->
		<script src="{{ URL::asset('/../node_modules/jquery/dist/jquery.min.js') }}"></script>
		@stack('stylesheet')
	</head>
	<body>
        <form class="form_sequence notice_edit" id="notice_form" accept-charset="UTF-8" role="form" method="post" action="{{ route('notices.update', $notice->id ) }}" enctype="multipart/form-data" >
            <section class="header_campaign">
                <!-- <textarea name="text_html" id="text_html" hidden >{{ $notice->notice }}</textarea>
                <textarea name="text_json" id="text_json" hidden >{{ $notice->text_json }}</textarea> -->
                <header>
                    {{ csrf_field() }}
					{{ method_field('PUT') }}
                    <div class="unlayer container">
                        <button  class="btn-submit" {{-- (click)="exportHtml()" --}}>@lang('basic.edit')</button>
                        <email-editor></email-editor>
                        {{-- 	<input class="btn-submit" type="submit" value="{{ __('basic.save')}}"> --}}
                        <a class="btn-back" href="{{ route('noticeboard') }}" >
                            @lang('basic.back')
                        </a>
                    </div>
                    <h3 class="panel-title">@lang('basic.edit_notice')</h3>
                </header>
                <section class="campaign_set">
                    <div class="form-group col-sm-12 col-md-6 float_left {{ ($errors->has('title'))  ? 'has-error' : '' }}">
                        <label>@lang('basic.title')</label>
                        <input name="title" type="text" class="form-control" value="{{ $notice->title }}" required>
                        {!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    <div class="form-group col-sm-12 col-md-6 float_left to_department {{ ($errors->has('to_department'))  ? 'has-error' : '' }} ">
                        <label  >@lang('basic.to_department')</label>
                        <select name="to_department[]" class="select_filter form-control js-example-basic-multiple js-states select2-hidden-accessible" value="{{ old('to_department') }}" multiple required >
                            <option disabled ></option>
                            @foreach($departments->where('level1', 0) as $department0)
                                <option value="{{ $department0->id }}" {!! in_array( $department0->id, $notice_departments) ? 'selected' : '' !!}>[L0] {{ $department0->name }}</option>
                                @foreach($departments->where('level2', $department0->id ) as $department1)
                                    <option value="{{ $department1->id }}" class="padd_l_20" {!! in_array( $department1->id, $notice_departments) ? 'selected' : '' !!}>[L1] {{ $department1->name }}</option>
                                    @foreach($departments->where('level2', $department1->id ) as $department2)
                                        <option value="{{ $department2->id }}" class="padd_l_40" {!! in_array( $department2->id, $notice_departments) ? 'selected' : '' !!}>[L2] {{ $department2->name }}</option>
                                    @endforeach	
                                @endforeach	
                            @endforeach	
                        </select>
                    </div>
                    <div class="form-group col-sm-12 col-md-6 float_left">
                        <label class="label_file" for="file">Top notice image<span><img src="{{ URL::asset('icons/download.png') }}" />Upload image</span></label>
                        <input type='file' id="file" name="fileToUpload" >
                    </div>
                    <div class="form-group col-sm-12 col-md-6 float_left {{ ($errors->has('schedule_date'))  ? 'has-error' : '' }}" >
                        <label class="label_schedule">@lang('basic.schedule')</label>
                        <p class="schedule col-sm-12 col-md-6 float_left">
                            <input type="radio" name="schedule_set" id="no-send" class="select_save" value="2" checked />
                                <label class="schedule_set" for="no-send">@lang('basic.dont_send_mail')</label>
                            <input type="radio" name="schedule_set" id="send" class="select_save" value="0" />
                                <label class="schedule_set" for="send">@lang('basic.send')</label>
                            <input type="radio" name="schedule_set" id="schedule" class="select_save" value="1" />
                                <label class="schedule_set"  for="schedule">@lang('basic.schedule')</label>
                            <i class="far fa-question-circle show_popUp"></i>            
                        </p>
                        <p class="pop-up">@lang('basic.schedule_notice')</p>
                        <div class="col-sm-12 col-md-6 float_left"></div>
                        <input name="schedule_date" class="schedule_date" type="date" class="form-control" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}" required />
                        <input name="schedule_time" class="schedule_date" type="time" class="form-control" value="08:00" required />
                        {!! ($errors->has('schedule_date') ? $errors->first('schedule_date', '<p class="text-danger">:message</p>') : '') !!}
                        <p class="schedule col-sm-12 col-md-6 float_left">
                            
                    </div>
                    <!-- <div class="form-group last {{ ($errors->has('notice')) ? 'has-error' : '' }}">
                        <label>@lang('basic.notice'):</label>
                        <textarea name="notice" id="mytextarea" maxlength="16777215">{{ old('notice') }}</textarea>
                        {!! ($errors->has('notice') ? $errors->first('notice', '<p class="text-danger">:message</p>') : '') !!}
                    </div> -->
            </section>
            <main class="main_campaign">
                <div class="{!! count($templates) > 0 ? 'col-10' : 'col-12' !!}" id="editor-container"></div>
                @if(count($templates) > 0 )
                    <div class="col-2" id="template-container"></div>
                @endif
            </main>
        </form>
        <span hidden class="locale" >{{ App::getLocale() }}</span>
        <span hidden class="dataArr">{{ ($notice->text_json) }}</span>
        <span hidden class="dataArr_html">{{ $notice->notice }}</span>
        <span hidden class="dataArrTemplates">{{ ($templates) }}</span>
		<!-- Latest compiled and minified Bootstrap JavaScript -->
        <!-- Bootstrap js -->
		<script src="{{ URL::asset('/../node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
		<script src="{{ URL::asset('/../node_modules/popper.js/dist/umd/popper.min.js') }}"></script>
        <!-- Restfulizer.js - A tool for simulating put,patch and delete requests -->
        <script src="{{ asset('/../restfulizer.js') }}"></script>
		<!--Awesome icons -->
		<script src="{{ URL::asset('/../node_modules/@fortawesome/fontawesome-free/js/all.min.js') }}"></script>
	
		<!-- Jquery modal -->
		<script src="{{ URL::asset('/../node_modules/jquery-modal/jquery.modal.min.js') }}"></script>
		
		<!--Unlayer modal -->
		{{-- <script src="{{ URL::asset('/../node_modules/react-email-editor/umd/react-email-editor.min.js') }}"></script> --}}

		<!-- Scripts -->
		<script src="{{URL::asset('/../js/open_modal.js') }}"></script>
		<script src="{{URL::asset('/../js/notice_edit.js') }}"></script>
        {{--  Select2 find --}}
        <script src="{{ URL::asset('/../select2-develop/dist/js/select2.min.js') }}"></script>
        <script src="{{ URL::asset('/../select2-develop/dist/js/i18n/hr.js') }}"></script>
		@if(session()->has('modal'))
			<script>
				$('.row.notification').modal();
			</script>
		@endif
        <script>
            $(function() {        
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
           /*  var expanded = false;
            function showCheckboxesDepartment() {
                var checkboxes1 = document.getElementById("checkboxes1");
                if (!expanded) {
                    checkboxes1.style.display = "block";
                    expanded = true;
                } else {
                    checkboxes1.style.display = "none";
                    expanded = false;
                }
            } */
            if( $('.select_filter').length > 0 ) {
                $('.select_filter').select2({
                    dropdownParent: $('.to_department'),
                    matcher: matchCustom,
                    width: 'resolve',
                    placeholder: {
                        id: '-1', // the value of the option
                    },
                    theme: "classic",
                });
            }
            function matchCustom(params, data) {
                // If there are no search terms, return all of the data
                if ($.trim(params.term) === '') {
                return data;
                }

                // Do not display the item if there is no 'text' property
                if (typeof data.text === 'undefined') {
                return null;
                }

                // `params.term` should be the term that is used for searching
                // `data.text` is the text that is displayed for the data object
                var value = params.term;
                var search_Array = value.split(" ");
                /* console.log(value);
                console.log(search_Array); */
                if( search_Array.length == 1 ) {
                    if (data.text.toLowerCase().indexOf(search_Array[0]) > -1) {
                        var modifiedData = $.extend({}, data, true);
                        return modifiedData;
                    }
                } else if( search_Array.length == 2 ) {
                    if (data.text.toLowerCase().indexOf(search_Array[0]) > -1 && data.text.toLowerCase().indexOf(search_Array[1]) > -1) {
                        var modifiedData = $.extend({}, data, true);
                        return modifiedData;
                    }
                } else if( search_Array.length == 3 ) {
                    if (data.text.toLowerCase().indexOf(search_Array[0]) > -1 && data.text.toLowerCase().indexOf(search_Array[1]) > -1 && data.text.toLowerCase().indexOf(search_Array[2]) > -1) {
                        var modifiedData = $.extend({}, data, true);
                        return modifiedData;
                    }
                } else if( search_Array.length == 4 ) {
                    if (data.text.toLowerCase().indexOf(search_Array[0]) > -1 && data.text.toLowerCase().indexOf(search_Array[1]) > -1 && data.text.toLowerCase().indexOf(search_Array[2]) > -1  && data.text.toLowerCase().indexOf(search_Array[3]) > -1) {
                        var modifiedData = $.extend({}, data, true);
                        return modifiedData;
                    }
                }  else if( search_Array.length == 5 ) {
                    if (data.text.toLowerCase().indexOf(search_Array[0]) > -1 && data.text.toLowerCase().indexOf(search_Array[1]) > -1 && data.text.toLowerCase().indexOf(search_Array[2]) > -1  && data.text.toLowerCase().indexOf(search_Array[3]) > -1 && data.text.toLowerCase().indexOf(search_Array[4]) > -1) {
                        var modifiedData = $.extend({}, data, true);
                        return modifiedData;
                    }
                } 
                // Return `null` if the term should not be displayed
                return null;
            }
            $.getScript( '/../js/filter.js');  

        </script>

		@stack('script')		
    </body>
</html>