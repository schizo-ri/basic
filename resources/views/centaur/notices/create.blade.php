<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
		<meta charset="UTF-8">
		<meta name="description" content="Portal za zaposlenike">
		<meta name="author" content="Jelena Juras">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>@lang('basic.add_notice')</title>
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
		<script src="https://editor.unlayer.com/embed.js"></script>
		<!--Jquery -->
		<script src="{{ URL::asset('/../node_modules/jquery/dist/jquery.min.js') }}"></script>
		@stack('stylesheet')
	</head>
	<body>
        <form class="form_sequence notice_create" id="notice_form" accept-charset="UTF-8" role="form" method="post" action="{{ route('notices.store') }}" enctype="multipart/form-data" >
            <section class="header_campaign">
                <header>
                    {{ csrf_field() }}
                    <div class="unlayer container">
                        <button  class="btn-submit" {{-- (click)="exportHtml()" --}}>@lang('basic.save')</button>
                        <email-editor></email-editor>
                        {{-- 	<input class="btn-submit" type="submit" value="{{ __('basic.save')}}"> --}}
                        <a class="btn-back" href="{{ url()->previous() }}">
                            @lang('basic.back')
                        </a>
                    </div>
                    <h3 class="panel-title">@lang('basic.add_notice')</h3>
                </header>
                <section class="campaign_set">
                    <div class="form-group col-sm-12 col-md-6 float_left {{ ($errors->has('title'))  ? 'has-error' : '' }}">
                        <label>@lang('basic.title')</label>
                        <input name="title" type="text" class="form-control" value="{{ old('title') }}" required>
                        {!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    <div class="form-group col-sm-12 col-md-6 float_left {{ ($errors->has('to_department'))  ? 'has-error' : '' }} ">
                        <div class="selectBox department" onclick="showCheckboxesDepartment()" >
                            <!-- <select > -->
                                <label  >@lang('basic.to_department')</label>
                            <!-- </select> -->
                            <div class="overSelect"></div>
                        </div>
                        <div id="checkboxes1" >
                            <input type="search"  placeholder="{{ __('basic.search')}}"  id="mySearch1">
                            @foreach($departments0 as $department0)
                                <div class="col-12">
                                    <label for="{{  '0_'.$department0->id }}" class="col-12 float_left panel1" >
                                        <input name="to_department[]" type="checkbox" id="{{ '0_'.$department0->id }}" value="{{ $department0->id }}" />
                                        <span>{{ $department0->name }}</span>
                                    </label>
                                    @foreach($departments2 as $department2)
                                        @if ($department2->level2 == $department0->id )
                                            <label for="{{  '2_'.$department2->id }}" class="col-offset-1 col-md-10 float_left panel1" >
                                                <input name="to_department[]" type="checkbox" id="{{ '1_'.$department2->id }}"  value="{{ $department2->id }}"  />
                                                <span>{{ $department2->name }}</span>
                                            </label>
                                        @endif
                                    @endforeach
                                    @foreach($departments1 as $department1)
                                        @if ($department1->level2 == $department0->id )
                                            <label for="{{  '1_'.$department1->id }}" class="col-12 float_left panel1" >
                                                <input name="to_department[]" type="checkbox" id="{{ '1_'.$department1->id }}"  value="{{ $department1->id }}"  />
                                                <span>{{ $department1->name }}</span>
                                            </label>
                                        @endif
                                        @foreach($departments2 as $department2)
                                            
                                            @if ($department2->level2 == $department1->id )
                                                <label for="{{  '2_'.$department2->id }}" class="col-offset-1 col-md-10 float_left panel1" >
                                                    <input name="to_department[]" type="checkbox" id="{{ '2_'.$department2->id }}" value="{{ $department2->id }}"  />
                                                    <span>{{ $department2->name }}</span>
                                                </label>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group col-sm-12 col-md-6 float_left">
                        <label class="label_file" for="file">@lang('basic.top_image') (jpg,png,gif)<span><img src="{{ URL::asset('icons/download.png') }}" />Upload image</span></label>
                        <input type='file' id="file" name="fileToUpload" >
                        <span id="file_name"></span>
                    </div>
                    <div class="form-group col-sm-12 col-md-6 float_left {{ ($errors->has('schedule_date'))  ? 'has-error' : '' }}" >
                        <label class="label_schedule">@lang('basic.schedule')</label>
                        <p class="schedule col-sm-12 col-md-6 float_left">
                            <input type="radio" name="schedule_set" id="send" class="select_save" value="0" checked />
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
        <span hidden class="dataArrTemplates">{{ ($templates) }}</span>
		<!-- Latest compiled and minified Bootstrap JavaScript -->
        <!-- Bootstrap js -->
		<script src="{{ URL::asset('/../node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
		<script src="{{ URL::asset('/../node_modules/popper.js/dist/umd/popper.min.js') }}"></script>
        <!-- Restfulizer.js - A tool for simulating put,patch and delete requests -->
        <script src="{{ asset('/../restfulizer.js') }}"></script>
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
                $('#file').change(function(){
                    $('#file_name').text(  $('input[type=file]').val() );
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
            var expanded = false;
            function showCheckboxesDepartment() {
                var checkboxes1 = document.getElementById("checkboxes1");
                if (!expanded) {
                    checkboxes1.style.display = "block";
                    expanded = true;
                } else {
                    checkboxes1.style.display = "none";
                    expanded = false;
                }
            }
            $.getScript( '/../js/filter.js');  

        </script>

		<!--Awesome icons -->
		<script src="{{ URL::asset('/../node_modules/@fortawesome/fontawesome-free/js/all.min.js') }}"></script>
	
		<!-- Jquery modal -->
		<script src="{{ URL::asset('/../node_modules/jquery-modal/jquery.modal.min.js') }}"></script>
		
		<!--Unlayer modal -->
		{{-- <script src="{{ URL::asset('/../node_modules/react-email-editor/umd/react-email-editor.min.js') }}"></script> --}}

		<!-- Scripts -->
        {{-- <script src="{{URL::asset('/../js/all.js') }}"></script> --}}
        <script src="{{URL::asset('/../js/open_modal.js') }}"></script>
		<script src="{{URL::asset('/../js/notice_create.js') }}"></script>
	
		@if(session()->has('modal'))
			<script>
				$('.row.notification').modal();
			</script>
		@endif
		@stack('script')		
    </body>
</html>