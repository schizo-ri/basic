@extends('Centaur::layout')

@section('title', 'Priprema i mehanička obrada')

@section('content')
<span hidden class="today">{{ date('Y-m-d') }}</span>
<div class="page-header">
    <h1>Priprema i mehanička obrada</h1>
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
                            Sentinel::inRole('list_view'))
                            @php
                                $preparationRecords1 = $preparationRecords->where('preparation_id',$preparation->id);
                                $preparationRecord_today = $preparationRecords1->where('preparation_id',$preparation->id)->where('date', date('Y-m-d'))->first();
                            @endphp
                                <!-- Ispis pripreme -->  

                                    <a class="open_upload_link"><i class="fas fa-upload"></i></a>
                                    <div class="upload_links" >
                                        <h3>Upload</h3>
                                        
                                        @if(! Sentinel::inRole('subscriber'))                                        
                                            <form class="upload_file" action="{{ action('EquipmentListController@import') }}" method="POST" enctype="multipart/form-data">
                                                <div class="file-input-wrapper">
                                                    <button class="btn-file-input"><i class="fas fa-upload"></i> Upload</button>
                                                    <input type="file" name="file" required />
                                                    <input type="text" name="preparation_id" value="{{ $preparation->id }}" hidden/>
                                                </div>
                                                @csrf
                                            </form>
                                            @if( Sentinel::inRole('list_view') ||  Sentinel::inRole('administrator'))
                                                <form class="upload_file_replace" action="{{ action('EquipmentListController@import_with_replace') }}" method="POST" enctype="multipart/form-data" title ="Multiple replace">
                                                    <div class="file-input-wrapper">
                                                        <button class="btn-file-input"><i class="fas fa-exchange-alt"></i> Upload sa zamjenom</button>
                                                        <input type="file" name="file" required />
                                                        <input type="text" name="preparation_id" value="{{ $preparation->id }}" hidden/>
                                                    </div>
                                                    @csrf
                                                </form>
                                            @endif
                                            @if( Sentinel::inRole('administrator'))
                                                <form class="upload_file_replace" action="{{ action('EquipmentListController@importSiemens') }}" method="POST" enctype="multipart/form-data" title ="Multiple replace">
                                                    <div class="file-input-wrapper">
                                                        <button class="btn-file-input"><i class="fas fa-upload"></i> Upload Siemens Linde</button>
                                                        <input type="file" name="file" required />
                                                        <input type="text" name="preparation_id" value="{{ $preparation->id }}" hidden/>
                                                    </div>
                                                    @csrf
                                                </form>
                                            @endif 
                                        @endif           
                                    </div>
                                    <p class="tr row_preparation_text {!! $preparation->active == 1 ? 'active' : 'inactive' !!}">
                                        <span class="td text_preparation file_input">
                                        </span>
                                        <span class="td text_preparation project_no_input">{{ $preparation->project_no  }}</span>
                                        <span class="td text_preparation name_input">{{ $preparation->name }}</span>
                                        <span class="td text_preparation delivery_input">{!! $preparation->delivery ? date('d.m.Y', strtotime($preparation->delivery)) : '' !!}</span>
                                        <span class="td text_preparation manager_input">{{ $preparation->manager['first_name'] . ' ' . $preparation->manager['last_name']  }}</span>
                                        <span class="td text_preparation designed_input">{{ $preparation->designed['first_name'] . ' ' . $preparation->designed['last_name']  }}</span>
                                        <span class="td text_preparation date_input">{{ date('d.m.Y')}}</span>
                                        <span class="td text_preparation date_change preparation_input"   >
                                            @if (!Sentinel::inRole('moderator') && ! Sentinel::inRole('list_view') )
                                                @if ($preparationRecord_today)
                                                    <span class="date_{{ $preparationRecord_today->date }}">{{ $preparationRecord_today->preparation }}</span>
                                                @endif
                                            @endif
                                        </span>
                                        <!-- Mehanička obrada -->
                                        <span class="td text_preparation date_change mechanical_input">
                                            @if (!Sentinel::inRole('moderator')&& ! Sentinel::inRole('list_view') )
                                                @if ($preparationRecord_today)
                                                    <span class="date_{{ $preparationRecord_today->date }}">{{ $preparationRecord_today->mechanical_processing }}</span>
                                                @endif
                                            @endif
                                        </span>
                                         <!-- Oznake i dokumentacija -->
                                         <span class="td text_preparation date_change mechanical_input">
                                            @if (!Sentinel::inRole('moderator')&& ! Sentinel::inRole('list_view') )
                                                @if ($preparationRecord_today)
                                                    <span class="date_{{ $preparationRecord_today->date }}">{{ $preparationRecord_today->marks_documentation }}</span>
                                                @endif
                                            @endif
                                        </span>
                                        <!-- Upis opreme -->                                      
                                        <span class="td text_preparation equipment_input">    
                                            @if($equipmentLists->where('preparation_id', $preparation->id )->first())
                                                <a href="{{ route('equipment_lists.edit', $preparation->id ) }}" class="equipment_lists_open" rel="modal:open">Upis opreme</a>   
                                                <a href="{{ route('multiReplaceItem', ['preparation_id' => $preparation->id] ) }}" class="equipment_lists_open multi_replace" rel="modal:open">Zamjena</a> 
                                                @if (! Sentinel::inRole('list_view'))
                                                    @if($equipmentLists->where('preparation_id', $preparation->id )->first()->mark != null )
                                                        <a class="btn-file-input equipment_lists_mark" href="{{ action('EquipmentListController@export', ['id' => $preparation->id ]   ) }}" ><i class="fas fa-download"></i> Preuzmi oznake</a>
                                                    @endif
                                                @endif
                                            @else
                                                <small>Nema zapisa</small>
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
                                        <span class="td text_preparation option_input">
                                            @if (! Sentinel::inRole('list_view') )
                                                <a href="#" class="btn btn-edit">
                                                    <span class="glyphicon glyphicon-edit" aria-hidden="true" title="Ispravi"></span>
                                                </a>
                                                @if (Sentinel::inRole('administrator'))   
                                                    <a href="{{ route('preparations.destroy', $preparation->id) }}" class="action_confirm btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}" title="Obriši">
                                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                                    </a>
                                                    <a href="{{ action('PreparationController@close_preparation', $preparation->id) }}" class="btn" class="action_confirm">
                                                        <i class="fas fa-check"></i>
                                                        @if ($preparation->active == 1)Završi @else Vrati @endif                                                       
                                                    </a>
                                                @endif
                                            @endif                                           
                                        </span>
                                    </p>
                                   <!-- Edit pripreme -->
                                    @include('centaur.preparation_edit')
                                @if ($preparationRecords1->where('date', '<>', date('Y-m-d'))->first())
                                    <!-- Zapisi pripreme -->
                                    <div class="content" id="content_{{ $preparation->id }}">
                                        @foreach ( $preparationRecords1->where('date', '<>', date('d-m-Y')) as $record )
                                            @include('centaur.preparation_record')
                                        @endforeach
                                    </div>
                                @endif
                        @endif
                    @endforeach
                    <!-- Novi unos -->     
                    @if(! Sentinel::inRole('subscriber')  && ! Sentinel::inRole('list_view') && ! Sentinel::inRole('priprema'))
                        @include('centaur.preparation_create')
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.open_upload_link').click(function(){
        $( this ).next('.upload_links').modal();
    return false;
    });
    $('.table_preparations .inactive').hide();

    $('.show_inactive').click(function(){
        $('.table_preparations .inactive').toggle();
        $('.table_preparations .active').toggle();
        if($(this).text() == 'Prikaži neaktivne') {
            $(this).text('Prikaži aktivne');
            $('.upload_file').hide();
            $('.upload_file_replace').hide();
        } else {
            $(this).text('Prikaži neaktivne');
            $('.upload_file').show();
            $('.upload_file_replace').show();
        }
    });

    $('.upload_file input[type=file]').change(function(){
        $(this).parent().parent().submit();
    });
    $('.upload_file_replace input[type=file]').change(function(){
        $(this).parent().parent().submit();
    });
    $('.collapsible').click(function(){
        var id = $(this).attr('id');
      
        $(this).parent().parent().siblings('#content_'+id).toggle();
    });

    $('a.btn-edit').click(function(event ){
        event.preventDefault();
        $(this).parent().parent().next('.form_preparation').css('display','flex');
        $(this).parent().parent().hide();
    });
    $('a.btn-cancel').click(function(event ){
        event.preventDefault();
        $(this).parent().parent().prev('.row_preparation_text').show();
        $(this).parent().parent().hide();

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
            spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',
        
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
            spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',
        
            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
            };
    });
    $.getScript('/../js/filter.js');
</script>
@stop