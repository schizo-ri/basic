@extends('Centaur::layout')

@section('title', 'Priprema i mehanička obrada')

@section('content')
@php  
    use App\Http\Controllers\PreparationController;
    use App\Models\EquipmentList;
@endphp
<span hidden class="today">{{ date('Y-m-d') }}</span>
<span hidden class="roles">{{ $roles }}</span>
<div class="page-header">
    {{-- <div style="float:right">
        <span class="alert alert-danger" style="display: block; margin: 0;">Molim obrisati cache sa ctrl+f5 da se povuće novi dizajn</span>
    </div> --}}
    <div class="page_navigation pull-left">
        <a class="link_back " href="{{ route('preparations.index') }}">Priprema i mehanička obrada</a>
        <span>/</span>
        <span class="pull-left" >{!! $preparations->first() ? 'Projekt ' . $preparations->first()->first()->project_no : 'Nema zapisa...' !!}</span>
    </div>
    <div class='btn-toolbar pull-right'>
      {{--   @if( isset($_GET["active"]) && $_GET["active"] == 1)
            <span class="show_inactive"><a href="{{ action('PreparationController@preparations_active', ['active' => 0]) }}">Prikaži neaktivne</a></span>
        @elseif( isset($_GET["active"]) && $_GET["active"] == 0) 
            <span class="show_active"><a href="{{ action('PreparationController@preparations_active', ['active' => 1]) }}">Prikaži aktivne</a></span>
        @else 
            <span class="show_inactive"><a href="{{ action('PreparationController@preparations_active', ['active' => 0]) }}">Prikaži neaktivne</a></span>
        @endif --}}
        <label class="filter_empl">
            <input type="search" placeholder="Traži..." id="mySearch_preparation">
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
                        {{-- <span class="th date_input">Datum</span> --}}
                        <span class="th preparation_input">Priprema</span>
                        <span class="th mechanical_input">Mehanička obrada</span>
                        <span class="th mechanical_input">Oznake i dokumentacija</span>
                        <span class="th equipment_input">Oprema</span>
                       {{--  <span class="th history_input">Povijest</span> --}}
                       <span class="th option_input">Opcije</span>
                    </p>
                </div>
                <div class="tbody">
                    @foreach ($preparations as $proj_no => $preparation1)
                        @foreach ($preparation1 as $preparation)
                        @if (Sentinel::getUser()->id == $preparation->project_manager || 
                        Sentinel::getUser()->id == $preparation->designed_by || 
                        Sentinel::inRole('administrator') || 
                        Sentinel::inRole('subscriber') || 
                        Sentinel::inRole('priprema') || 
                        Sentinel::inRole('list_view') || 
                        Sentinel::inRole('upload_list') )
                            <!-- Ispis pripreme -->  
                            <p class="tr row_preparation_text {!! $preparation->active == 1 ? 'active' : 'inactive' !!} {{ str_replace(':','_', $proj_no)  }}" id="id_{{ $preparation->id }}">
                                @if ( $preparation->active == 1)
                                    <span class="td text_preparation file_input">
                                        <a class="open_upload_link"><i class="fas fa-upload"></i><span class="preparation_id" hidden>{{ $preparation->id }}</span></a>
                                        <span><a class="open_upload_link"><i class="fas fa-upload"></i><span class="preparation_id" hidden>{{ $preparation->id }}</span></a></span><span class="td text_preparation file_input"></span>
                                    </span>
                                @endif
                                <span class="td text_preparation project_no_input">{{ $preparation->project_no  }}</span>
                                <span class="td text_preparation name_input">{{ $preparation->name }}</span>
                                <span class="td text_preparation delivery_input">{!! $preparation->delivery ? date('d.m.Y', strtotime($preparation->delivery)) : '' !!}</span>
                                <span class="td text_preparation manager_input">{{ $preparation->manager['first_name'] . ' ' . $preparation->manager['last_name']  }}</span>
                                <span class="td text_preparation designed_input">{{ $preparation->designed['first_name'] . ' ' . $preparation->designed['last_name']  }}</span>
                              
                                <!-- Priprema -->
                                <span class="td text_preparation date_change preparation_input"  >
                                    @if (json_decode($preparation->preparation))
                                        @foreach(json_decode($preparation->preparation) as $key => $preparation1)
                                            <span class="status_{!! $preparation1 == 'N/A' ? 'N_A' : $preparation1 !!}">{{ $key }}<b></b></span>
                                        @endforeach
                                    @endif
                                </span>
                                <!-- Mehanička obrada -->
                                <span class="td text_preparation date_change mechanical_input">
                                    @if (json_decode($preparation->mechanical_processing))
                                        @foreach(json_decode($preparation->mechanical_processing) as $key => $mechanical)
                                            <span class="status_{!! $mechanical == 'N/A' ? 'N_A' : $mechanical !!}">{{ $key }}<b></b></span>
                                        @endforeach
                                    @endif
                               
                                </span>
                                <!-- Oznake i dokumentacija -->
                                <span class="td text_preparation date_change marks_input">
                                    @if (json_decode($preparation->marks_documentation))
                                        @foreach(json_decode($preparation->marks_documentation) as $key => $marks)
                                            <span class="status_{!! $marks == 'N/A' ? 'N_A' : $marks !!}">{{ $key }}<b></b></span>
                                        @endforeach
                                    @endif
                                   
                                </span>
                                <!-- Upis opreme -->
                                <span class="td text_preparation equipment_input">    
                                    @if ( $preparation->active == 1)
                                        @php
                                            $equipmentLists = EquipmentList::where('preparation_id', $preparation->id )->get();
                                        @endphp
                                        @if( count($equipmentLists)>0)
                                            @if ( $equipmentLists->where('level1',1)->first())
                                                @foreach ($equipmentLists->where('level1', 1) as $equipment_level1)
                                                    <a href="{{ route('equipment_lists.edit', $equipment_level1->id ) }}" class="equipment_lists_open"{{--  rel="modal:open" --}}>{{ $equipment_level1->product_number }}</a>
                                                @endforeach
                                            @else
                                                <a href="{{ route('equipment_lists.edit', $equipmentLists->first()->id ) }}" class="equipment_lists_open" {{-- rel="modal:open" --}}>Upis opreme</a>
                                            @endif
                                        
                                            <a href="{{ route('multiReplaceItem', ['preparation_id' => $preparation->id] ) }}" class="equipment_lists_open multi_replace" rel="modal:open">Zamjena</a> 
                                            @if (! Sentinel::inRole('list_view'))
                                                @if($equipmentLists->where('preparation_id', $preparation->id )->first()->mark != null )
                                                    <a class="btn-file-input equipment_lists_mark" href="{{ action('EquipmentListController@export', ['id' => $preparation->id ]   ) }}" ><i class="fas fa-download"></i> Preuzmi oznake</a>
                                                @endif
                                            @endif
                                        @else
                                            <span><small>Nema zapisa</small></span>
                                        @endif
                                        <span class="delivered_items">Isporučeno: {!! $preparation->delivered .' %'? $preparation->delivered :  '' !!}</span>
                                    @endif
                                </span>
                                <!-- Opcije -->
                                <span class="td text_preparation option_input">
                                    @if (! Sentinel::inRole('list_view') )
                                        <a href="#" class="btn btn-edit" id="edit_{{ $preparation->id }}">
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
                                <span hidden class="preparation_json">{{ json_encode($preparation->toArray()) }}</span>
                            </p>
                            <form class="form_preparation edit_preparation {{ $preparation->id }}" id="form_{{ $preparation->id }}" accept-charset="UTF-8" role="form" method="post" action="{{ route('preparations.update', $preparation->id) }}" >
                                <span class="input_preparation option_input">
                                    <input class="btn btn_spremi btn-preparation" type="submit" value="&#10004;" title="Ispravi">
                                    <a class="btn btn-cancel" >
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true" title="Poništi"></span>
                                    </a>
                                </span>
                            </form>
                            <!-- Edit pripreme -->
                              {{--   @include('centaur.preparation_edit') --}}
                        @endif
                        @endforeach
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
                <input type="text" class="prep_id" name="preparation_id" hidden />
            </div>
            @csrf
        </form>
        @if( Sentinel::inRole('list_view') ||  Sentinel::inRole('administrator'))
            <form class="upload_file_replace" action="{{ action('EquipmentListController@import_with_replace') }}" method="POST" enctype="multipart/form-data" title ="Import with replace replace">
                <div class="file-input-wrapper">
                    <button class="btn-file-input"><i class="fas fa-exchange-alt"></i> Upload sa zamjenom</button>
                    <input type="file" name="file" required />
                    <input type="text" class="prep_id" name="preparation_id" hidden />
                </div>
                @csrf
            </form>
        @endif
        @if( Sentinel::inRole('administrator') || Sentinel::inRole('moderator') || Sentinel::inRole('upload_list')|| Sentinel::inRole('list_view'))
            <form class="upload_file_replace" action="{{ action('EquipmentListController@importSiemens') }}" method="POST" enctype="multipart/form-data" title ="Import siemens">
                <div class="file-input-wrapper">
                    <button class="btn-file-input"><i class="fas fa-upload"></i> Upload Siemens Linde (hierarhija)</button>
                    <input type="file" name="file" required />
                    <input type="text" class="prep_id" name="preparation_id" hidden />
                </div>
                @csrf
            </form>
        @endif 
    @endif           
</div>
<span hidden class="users_json">{{ json_encode($users->toArray()) }}</span>
<span hidden class="priprema_json">{{ json_encode($priprema->toArray()) }}</span>
<span hidden class="mehanicka_json">{{ json_encode( $mehanicka->toArray()) }}</span>
<span hidden class="oznake_json">{{ json_encode( $oznake->toArray()) }}</span>
<script>
    $.getScript('/../js/preparation_show.js');

  /*   $('.collapsible_project').click(function(){
        var id = $(this).text();
        id = id.replace(':','_');
        if( $('.row_preparation_text.'+id).css('display') == 'flex' ) {
            $('.row_preparation_text.'+id).css('display','none');
            $('.row_preparation_text.'+id).find('span:not(.not_remove)').remove();
        } else {
            collapsProject (id);
             */
            /* $('.row_preparation_text.'+id).next('.form_preparation').remove(); */
     /*    }
    }); */
</script>
@stop