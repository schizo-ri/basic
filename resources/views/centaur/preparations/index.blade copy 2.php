@extends('Centaur::layout')

@section('title', 'Priprema i mehanička obrada')

@section('content')
@php  set_time_limit(120);use App\Models\PreparationRecord;use App\Models\EquipmentList;  @endphp
<span hidden class="today">{{ date('Y-m-d') }}</span>
<div class="page-header">
    <h1>Priprema i mehanička obrada</h1>

    <span class="employee_roles" hidden>{{ Sentinel::getUser()->roles->implode('slug', ', ') }}</span>
     <div class='btn-toolbar pull-right'>
        <span class="show_inactive">Prikaži neaktivne</span>
        <label class="filter_empl">
            <input type="search" placeholder="Traži..." onkeyup="mySearch_preparation()" id="mySearch_preparation">
            <i class="clearable__clear">&times;</i>
        </label>
         <!--  <a href="{{ route('preparations.create') }}" rel="modal:open"><img class="" src="{{ URL::asset('icons/plus.png') }}" alt="arrow" /></a>-->
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <div class="table table-hover table_preparations" id="index_table">
                <div class="thead">
                    <p class="tr">
                        <span class="th file_input"></span>
                        <span class="th project_no_input">Broj</span>
                        <span class="th name_input">Naziv</span>
                        <span class="th delivery_input">Datum isporuke</span>
                        <span class="th manager_input">Voditelj projekta</span>
                        <span class="th designed_input">Projektirao</span>
                        <span class="th date_input">Datum</span>
                        <span class="th preparation_input">Priprema</span>
                        <span class="th mechanical_input">Mehanička obrada</span>
                        <span class="th mechanical_input">Oznake i dokumentacija</span>
                        <span class="th equipment_input">Oprema</span>
                        <span class="th history_input">Povijest</span>
                       <span class="th option_input">Opcije</span>
                    </p>
                </div>
                <div class="tbody">
                    @foreach ($preparations as $preparation)
                        @if (Sentinel::getUser()->id == $preparation->project_manager || 
                            Sentinel::getUser()->id == $preparation->designed_by || 
                            Sentinel::inRole('administrator') || 
                            Sentinel::inRole('subscriber') || 
                            Sentinel::inRole('priprema') || 
                            Sentinel::inRole('list_view') || 
                            Sentinel::inRole('upload_list') )
                            @php
                                $preparationRecords1 = $preparationRecords->where('preparation_id',$preparation->id);
                                $preparationRecord_today = $preparationRecords1->where('preparation_id',$preparation->id)->where('date', date('Y-m-d'))->first();
                            @endphp
                            <!-- Ispis pripreme -->  
                            @if ( $preparation->active == 1)
                                <a class="open_upload_link" id="{{ $preparation->id }}" ><i class="fas fa-upload"></i></a>
                                
                            @endif
                            <p class="tr row_preparation_text {!! $preparation->active == 1 ? 'active' : 'inactive' !!} {{ $preparation->id }}" title="{{ $preparation->id }}">
                                <span class="td text_preparation file_input">
                                </span>
                                <span class="td text_preparation project_no_input">{{ $preparation->project_no  }}</span>
                                <span class="td text_preparation name_input">{{ $preparation->name }}</span>
                                <span class="td text_preparation delivery_input">{!! $preparation->delivery ? date('d.m.Y', strtotime($preparation->delivery)) : '' !!}</span>
                                <span class="td text_preparation manager_input">{{ $preparation->manager['first_name'] . ' ' . $preparation->manager['last_name']  }}</span>
                                <span class="td text_preparation designed_input">{{ $preparation->designed['first_name'] . ' ' . $preparation->designed['last_name']  }}</span>
                                <span class="td text_preparation date_input">{{ date('d.m.Y')}}</span>
                                <span class="td text_preparation date_change preparation_input">
                                    @if ( $preparation->active == 1)
                                        @if (!Sentinel::inRole('moderator') && ! Sentinel::inRole('list_view') )
                                            @if ($preparationRecord_today)
                                                <span class="date_{{ $preparationRecord_today->date }}">{{ $preparationRecord_today->preparation }}</span>
                                            @endif
                                        @endif
                                    @endif
                                </span>
                                <!-- Mehanička obrada -->
                                <span class="td text_preparation date_change mechanical_input">
                                    @if ( $preparation->active == 1)
                                        @if (!Sentinel::inRole('moderator')&& ! Sentinel::inRole('list_view') )
                                            @if ($preparationRecord_today)
                                                <span class="date_{{ $preparationRecord_today->date }}">{{ $preparationRecord_today->mechanical_processing }}</span>
                                            @endif
                                        @endif
                                    @endif
                                </span>
                                    <!-- Oznake i dokumentacija -->
                                <span class="td text_preparation date_change mechanical_input">
                                    @if ( $preparation->active == 1)
                                        @if (!Sentinel::inRole('moderator')&& ! Sentinel::inRole('list_view') )
                                            @if ($preparationRecord_today)
                                                <span class="date_{{ $preparationRecord_today->date }}">{{ $preparationRecord_today->marks_documentation }}</span>
                                            @endif
                                        @endif
                                    @endif
                                </span>
                                <!-- Upis opreme -->
                                <span class="td text_preparation equipment_input">  
                                    @if ( $preparation->active == 1)
                                        @if($equipmentLists->where('preparation_id', $preparation->id )->first())
                                            @if ( $equipmentLists->where('preparation_id', $preparation->id )->where('level1',1)->first())
                                                @foreach ($equipmentLists->where('preparation_id', $preparation->id )->where('level1', 1) as $equipment_level1)
                                                    <a href="{{ route('equipment_lists.edit', ['id' => $preparation->id, 'equipment_level1' => $equipment_level1 ] ) }}" class="equipment_lists_open" rel="modal:open">{{ $equipment_level1->product_number }}</a>
                                                @endforeach
                                            @else
                                                <a href="{{ route('equipment_lists.edit', $preparation->id ) }}" class="equipment_lists_open" rel="modal:open">Upis opreme</a>
                                            @endif
                                        
                                            <a href="{{ route('multiReplaceItem', ['preparation_id' => $preparation->id] ) }}" class="equipment_lists_open multi_replace" rel="modal:open">Zamjena</a> 
                                            @if (! Sentinel::inRole('list_view'))
                                                @if($equipmentLists->where('preparation_id', $preparation->id )->first()->mark != null )
                                                    <a class="btn-file-input equipment_lists_mark" href="{{ action('EquipmentListController@export', ['id' => $preparation->id ]   ) }}" ><i class="fas fa-download"></i> Preuzmi oznake</a>
                                                @endif
                                            @endif
                                        @else
                                            <small>Nema zapisa</small>
                                        @endif
                                    @endif  
                                </span>
                                <!-- Povijest zapisa -->
                                <span class="td text_preparation history_input">
                                    @if ( !Sentinel::inRole('moderator') && ! Sentinel::inRole('list_view'))
                                        @if ($preparationRecords1->where('date', '<>', date('Y-m-d'))->first())
                                            <button class="arrow_collaps {!! $preparationRecords1->where('date', '<>', date('Y-m-d'))->first() ? 'collapsible' : '' !!}" id="{{ $preparation->id }}" type="button" {!! $preparationRecords1->where('date', '<>', date('Y-m-d'))->first() ? 'style="cursor:pointer"' : '' !!}><i class="fas fa-caret-down"></i></button> 
                                        @else
                                            <small>Nema povijesti</small>
                                        @endif
                                    @endif
                                </span>
                                    <!-- Opcije -->
                                <span class="td text_preparation option_input" >
                                    @if( $preparation->active == 1)
                                        @if (! Sentinel::inRole('list_view') )
                                            <a href="#" class="btn btn-edit-preparation">
                                                <span class="glyphicon glyphicon-edit" aria-hidden="true" title="Ispravi"></span>
                                            </a>
                                            @if (Sentinel::inRole('administrator'))   
                                                <a href="{{ route('preparations.destroy', $preparation->id) }}" class="action_confirm btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}" title="Obriši">
                                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                                </a>
                                            @endif
                                        @endif   
                                    @endif
                                    @if (Sentinel::inRole('administrator'))
                                        <a href="{{ action('PreparationController@close_preparation', $preparation->id) }}" class="btn" class="action_confirm">
                                            <i class="fas fa-check"></i>
                                            @if ($preparation->active == 1)Završi @else Vrati @endif
                                        </a>
                                    @endif
                                </span>
                            </p>
                            <!-- Edit pripreme -->
                           

                            @if ($preparationRecords1->where('date', '<>', date('Y-m-d'))->first())
                                <!-- Zapisi pripreme -->
                                <div class="content" id="content_{{ $preparation->id }}">
                                    <span class="json_content" hidden >{{ json_encode ( $preparation->toArray() ) }} </span>
                                    <span class="json_records" hidden>{{ json_encode ( $preparationRecords1->toArray() ) }} </span>
                                  
                                </div>
                            @endif
                        @endif
                    @endforeach
                    <!-- Novi unos -->     
                    @if( Sentinel::inRole('moderator') || Sentinel::inRole('voditelj') || Sentinel::inRole('administrator') || Sentinel::inRole('upload_list'))
                        @include('centaur.preparation_create')
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="upload_links" >
    <h3>Upload</h3>
    @if(! Sentinel::inRole('subscriber'))
        <form class="upload_file" action="{{ action('EquipmentListController@import') }}" method="POST" enctype="multipart/form-data">
            <div class="file-input-wrapper">
                <button class="btn-file-input"><i class="fas fa-upload"></i> Upload</button>
                <input type="file" name="file" required />
                <input type="text" name="preparation_id" class="preparation_id"  hidden/>
            </div>
            @csrf
        </form>
        @if( Sentinel::inRole('list_view') ||  Sentinel::inRole('administrator'))
            <form class="upload_file_replace" action="{{ action('EquipmentListController@import_with_replace') }}" method="POST" enctype="multipart/form-data" title ="Multiple replace">
                <div class="file-input-wrapper">
                    <button class="btn-file-input"><i class="fas fa-exchange-alt"></i> Upload sa zamjenom</button>
                    <input type="file" name="file" required />
                    <input type="text" name="preparation_id" class="preparation_id" hidden/>
                </div>
                @csrf
            </form>
        @endif
        @if( Sentinel::inRole('administrator') || Sentinel::inRole('moderator') || Sentinel::inRole('upload_list'))
            <form class="upload_file_replace" action="{{ action('EquipmentListController@importSiemens') }}" method="POST" enctype="multipart/form-data" title ="Multiple replace">
                <div class="file-input-wrapper">
                    <button class="btn-file-input"><i class="fas fa-upload"></i> Upload Siemens Linde</button>
                    <input type="file" name="file" required />
                    <input type="text" name="preparation_id" class="preparation_id"  hidden/>
                </div>
                @csrf
            </form>
        @endif 
    @endif
</div>

<script>
    var id;
    $('.collapsible').click(function(){
        var this_el = $( this);
        id = $(this).attr('id');
        if( ! $('#content_'+id + ' .tr.preparation_record_list').length ) {
            var json_content = JSON.parse($(this).parent().parent().siblings('#content_'+id).find('.json_content').text());
            var json_records = JSON.parse($(this).parent().parent().siblings('#content_'+id).find('.json_records').text());
            
            console.log(json_content.active);
             /*  console.log(json_records); */
            $.each(json_records, function( index1, value1 ) {
                /* console.log( index1 + ": " + value1 ); */
                var rec_id = this.id;
                var url_update =location.origin + '/preparation_records/'+ rec_id;
                var element_append = '<p class="tr preparation_record_list"><span class="td text_preparation file_input"></span><span class="td text_preparation project_no_input"></span><span class="td text_preparation name_input"></span><span class="td text_preparation delivery_input"></span><span class="td text_preparation manager_input"></span><span class="td text_preparation designed_input"></span><span class="td text_preparation date_input">'+this.date+'</span><span class="td text_preparation preparation_input wrap" {!! Sentinel::inRole('moderator') ? 'hidden' : '' !!}  >' + this.preparation + '</span><span class="td text_preparation mechanical_input wrap" {!! Sentinel::inRole('moderator') ? 'hidden' : '' !!} >' + this.mechanical_processing + '</span><span class="td text_preparation marks_input wrap" {!! Sentinel::inRole('moderator') ? 'hidden' : '' !!} >' + this.marks_documentation + '</span><span class="td text_preparation equipment_input"></span><span class="td text_preparation history_input"></span><span class="td text_preparation option_input">';
                if(json_content.active == 1) { // ako je projekt aktivan
                    element_append +='<a href="#" class="btn btn-edit" title="Ispravi"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a><a href="'+url_update+'" class="action_confirm btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}" title="Obriši"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>';
                }
                element_append +='</span></p>'; 
                if(json_content.active == 1) { // ako je projekt aktivan
                    element_append += '<form class="form_preparation" accept-charset="UTF-8" role="form" method="post" action="'+ url_update +'" ><span class="input_preparation file_input"></span><span class="input_preparation project_no_input"></span><span class="input_preparation name_input"></span><span class="input_preparation delivery_input"></span><span class="input_preparation manager_input"></span><span class="input_preparation designed_input"></span><span class="input_preparation date_input">' + this.date + '</span><span class="input_preparation preparation_input"><textarea name="preparation" cols="30" rows="3" placeholder="Priprema..." {!! Sentinel::inRole('moderator') ? 'readonly ' : '' !!} >' + this.preparation + '</textarea></span><span class="input_preparation mechanical_input"><textarea name="mechanical_processing" cols="30" rows="3" placeholder="Mehanička obrada ..." {!! Sentinel::inRole('moderator') ? 'readonly' : '' !!}  >' + this.mechanical_processing + '</textarea></span><span class="input_preparation marks_input"><textarea name="marks_documentation" cols="30" rows="3" placeholder="Oznake i dokumentacija ..." {!! Sentinel::inRole('moderator') ? 'readonly' : '' !!} >' + this.marks_documentation + '</textarea></span><input name="_token" value="{{ csrf_token() }}" type="hidden"><input name="_method" value="PUT" type="hidden"><span class="input_preparation option_input"><input class="btn  btn_spremi btn-preparation" type="submit" value="&#10004; Spremi"><a class="btn btn-cancel2" ><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>Poništi</a></span></form>';
                }
                this_el.parent().parent().siblings('#content_'+id).append(element_append);

            });
        }
        $(this).parent().parent().siblings('#content_'+id).toggle();
        $('a.btn-edit').click(function(event ){
            event.preventDefault();
            $(this).parent().parent().next('.form_preparation').css('display','flex');
            $(this).parent().parent().hide();
        });
        $('a.btn-cancel2').click(function(event ){
            event.preventDefault();
            $(this).parent().parent().prev('p').show();
            $(this).parent().parent().hide();

        });
        $.getScript( '/../restfulizer.js');
    });

    $('.open_upload_link').click(function(){
        var preparation_id = $( this ).attr('id');
        
        $('.preparation_id').text(preparation_id);
        $('.upload_links').modal();
    });
    $('.table_preparations .inactive').hide();

    $('.show_inactive').click(function(){
        $('.table_preparations .inactive').toggle();
        $('.table_preparations .active').toggle();
        if($(this).text() == 'Prikaži neaktivne') {
            $(this).text('Prikaži aktivne');
            $('.open_upload_link').hide();
            $('.upload_file').hide();
            $('.upload_file_replace').hide();
        } else {
            $(this).text('Prikaži neaktivne');
            $('.upload_file').show();
            $('.upload_file_replace').show();
            $('.open_upload_link').show();
        }
    });

    $('.upload_file input[type=file]').change(function(){
        $(this).parent().parent().submit();
    });
    $('.upload_file_replace input[type=file]').change(function(){
        $(this).parent().parent().submit();
    });
    $('a.btn-edit-preparation').click(function(event ) {
        event.preventDefault();
        var this_el = $( this);
        id = $(this).parent().parent().attr('title');
        console.log(id);
        var json_content = JSON.parse($(this).parent().parent().siblings('#content_'+id).find('.json_content').text());
        var url_update =location.origin + '/preparation_records/'+ id;
        var element_append = '<form class="form_preparation" accept-charset="UTF-8" role="form" method="post" action="' + url_update + '"><span class="input_preparation file_input"></span><span class="input_preparation project_no_input"><input  name="project_no" type="text" value="{{ $preparation->project_no }}" maxlength="30" required autofocus {!! Sentinel::inRole('subscriber') ? 'readonly style="border:none"' : '' !!} /></span><span class="input_preparation name_input"><input class=""  name="name" type="text" value="{{ $preparation->name }}" maxlength="100"  {!! Sentinel::inRole('subscriber') ? 'readonly style="border:none"' : '' !!}  /></span><span class="input_preparation delivery_input"><input class="" name="delivery" type="date" {!! Sentinel::inRole('subscriber') ? 'readonly ' : '' !!} value="{{ $preparation->delivery }}" /></span><span class="input_preparation manager_input"><select name="project_manager" class="project_manager" required {!! Sentinel::inRole('subscriber') ? 'readonly ' : ''  !!}><option disabled selected >Voditelj projekta</option>@foreach ($users as $user)@if ($user->first_name && $user->last_name)<option value="{{ $user->id }}" {!! $user->id  == $preparation->project_manager ? 'selected' : '' !!}>{{ $user->first_name . ' ' .  $user->last_name}}</option>@endif @endforeach</select></span><span class="input_preparation designed_input"><select name="designed_by" class="designed_by" required {!! Sentinel::inRole('subscriber') ? 'readonly ' : '' !!}><option disabled selected >Projektant</option>@foreach($users as $user) @if ($user->first_name && $user->last_name) <option value="{{ $user->id }}" {!! $user->id  == $preparation->designed_by ? 'selected' : '' !!}>{{ $user->first_name . ' ' .  $user->last_name}}</option>@endif @endforeach</select></span><span class="input_preparation date_input"></span><span class="input_preparation preparation_input"><textarea name="preparation" cols="30" rows="3" placeholder="Priprema..." {!! Sentinel::inRole('moderator') ? 'readonly ' : '' !!} >{!! $preparationRecord_today ? $preparationRecord_today->preparation : '' !!}</textarea></span><span class="input_preparation mechanical_input"><textarea name="mechanical_processing" cols="30" rows="3" placeholder="Mehanička obrada ..." {!! Sentinel::inRole('moderator') ? 'readonly' : '' !!}  >{!!  $preparationRecord_today ? $preparationRecord_today->mechanical_processing : '' !!}</textarea></span><span class="input_preparation marks_input"><textarea name="marks_documentation" cols="30" rows="3" placeholder="Oznake i dokumentacija ..." {!! Sentinel::inRole('moderator') ? 'readonly' : '' !!} >{!! $preparationRecord_today ? $preparationRecord_today->marks_documentation : '' !!}</textarea></span><input name="_token" value="{{ csrf_token() }}" type="hidden"><input name="_method" value="PUT" type="hidden"><span class="input_preparation equipment_input"></span><span class="input_preparation history_input"></span><span class="input_preparation option_input"><input class="btn btn_spremi btn-preparation" type="submit" value="&#10004;" title="Ispravi"><a class="btn btn-cancel" ><span class="glyphicon glyphicon-remove" aria-hidden="true" title="Poništi"></span></a></span></form>';
        $(element_append).insertAfter('.row_preparation_text.'+id);
        $(this).parent().parent().next('.form_preparation').css('display','flex');
        $(this).parent().parent().hide();
        $('a.btn-cancel').click(function(event ){
            event.preventDefault();
            $(this).parent().parent().prev('.row_preparation_text').show();
            $(this).parent().parent().hide();

        });
    });
   
    $('a.btn-cancel2').click(function(event ){
        event.preventDefault();
        $(this).parent().parent().prev('p').show();
        $(this).parent().parent().hide();

    });
    $('.equipment_lists_open').click(function(){
        $.modal.defaults = {
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: false,       // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal equipment_lists",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: "<div id='loader'><span class='ajax-loader1'></span></div>",
        
            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
            };
    });
    $('.open_upload_link').click(function(){
        $.modal.defaults = {
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: false,       // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: "<div id='loader'><span class='ajax-loader1'></span></div>",
        
            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
            };
    });

    $('#file').change(function(e){
        $('.file_to_upload').text(e.target.files[0].name);
        $('.submit_createForm').removeAttr('disabled');
    });

    var project_manager;
    var designed_by;
    $( ".create_preparation" ).submit(function( event ) {
        project_manager = $(this).find('.project_manager');
        designed_by= $(this).find('.designed_by');
     
        if( ! project_manager.val() ) {
            event.preventDefault();
           $( project_manager ).css('border','2px solid red');
        } else {
            $( project_manager ).css('border','1px solid rgb(169,169,169)');
        }
        if( ! designed_by.val() ) {
            event.preventDefault();
           $( designed_by ).css('border','2px solid red');
        } else {
            $( designed_by ).css('border','1px solid rgb(169,169,169)');
        }
        if( $( designed_by ).val() &&  $( project_manager ).val() ) {
            $( ".create_preparation" ).unbind();
        }
    });

    $.getScript('/../js/filter.js');
</script>
@stop