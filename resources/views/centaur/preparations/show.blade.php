@extends('Centaur::layout')

@section('title', 'Proizvodnja'/* . $preparations->first()->project_no */)

@section('content')
@php  
    use App\Http\Controllers\PreparationController;
    use App\Models\EquipmentList;  
@endphp
<span hidden class="today">{{ date('Y-m-d') }}</span>
<span hidden class="roles">{{ $roles }}</span>
<div class="page-header">
    <div class="page_navigation pull-left">
        <a class="link_back " href="{{ route('preparations.index') }}">Proizvodnja</a>
        <span>/</span>
        <span class="pull-left" >{!! $preparations->first() ? 'Projekt ' . $preparations->first()->project_no : 'Nema zapisa...' !!}</span>
    </div>
   {{--  <div style="float:right"><span class="alert alert-danger" style="display: block; margin: 0;">Molim obrisati cache sa ctrl+f5 da se povuće novi dizajn</span></div> --}}
    {{-- <h1>Priprema i mehanička obrada</h1> --}}
    <div class='btn-toolbar pull-right'>
        @if( isset($_GET["active"]) && $_GET["active"] == 1)
            <span class="active">Aktivni projekti</span>
        @elseif( isset($_GET["active"]) && $_GET["active"] == 0) 
            <span class="inactive"></span>
        @else 
            <span class="active"></span>
        @endif
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
                    @foreach ($preparations as $preparation)
                        <span class="project_show" id="collaps_{{ $preparation->project_no  }}"></span> 
                        <!-- Ispis pripreme -->  
                        <p class="tr row_preparation_text {!! $preparation->active == 1 ? 'active' : 'inactive' !!} {{ str_replace(':','_', $preparation->project_no)  }}" id="id_{{ $preparation->id }}" style="display: none">
                            <span class="td text_preparation option_input not_remove">
                                @if ( Sentinel::getUser()->hasAccess(['preparations.update']) )
                                    <a class="btn btn-edit" id="edit_{{ $preparation->id }}" ><span class="glyphicon glyphicon-edit not_remove" aria-hidden="true" title="Ispravi" ></span></a>
                                @endif
                                @if ( Sentinel::getUser()->hasAccess(['preparations.delete']))
                                    <a href="{{ route('preparations.destroy', $preparation->id) }}"  class="action_confirm btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}" title="Obriši"><span class="glyphicon glyphicon-remove not_remove" aria-hidden="true" ></span>
                                @endif
                                @if ( Sentinel::getUser()->hasAccess(['preparations.create']) )
                                    <a href="{{ action('PreparationController@close_preparation', $preparation->id) }}" class="btn" id="close_preparation" class="action_confirm" ><i class="fas fa-check"></i>
                                        @if ($preparation->active == 1)Završi @else Vrati @endif  
                                    </a>
                                @endif
                                @if ( Sentinel::inRole('priprema')  || Sentinel::inRole('administrator') || Sentinel::getUser()->email == 'borislav.peklic@duplico.hr'  )
                                    <a href="{{ action('PreparationController@finished', $preparation->id) }}" class="btn finish action_confirm" id="finish_preparation{{ $preparation->finish }}" >
                                        @if ( $preparation->finish == 0) Ormar je spreman @else Ormar nije spreman @endif  
                                    </a>
                                @endif
                                @if (Sentinel::inRole('skladiste_upload'))
                                    
                                    
                                @endif
                            </span>
                            <span hidden class="equipmentLists_json not_remove"></span>
                            <span hidden class="preparation_json not_remove">{{ json_encode($preparation->toArray()) }}</span>
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
                            {{-- @include('centaur.preparation_edit') --}}
                    @endforeach
                    <!-- Novi unos -->
                    @if(Sentinel::getUser()->hasAccess(['preparations.create']))
                        @include('centaur.preparation_create')
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="upload_links" >
    <h3>Upload</h3>
    @if(Sentinel::getUser()->hasAccess(['preparations.create']))
        <form class="upload_file" action="{{ action('EquipmentListController@import') }}" method="POST" enctype="multipart/form-data">
            <div class="file-input-wrapper">
                <button class="btn-file-input"><i class="fas fa-upload"></i> Upload</button>
                <input type="file" name="file" required />
                <input type="text" class="prep_id" name="preparation_id" hidden />
            </div>
            @csrf
        </form>
        @if( Sentinel::inRole('nabava') )
            <form class="upload_file_replace" action="{{ action('EquipmentListController@import_with_replace') }}" method="POST" enctype="multipart/form-data" title ="Import with replace replace">
                <div class="file-input-wrapper">
                    <button class="btn-file-input"><i class="fas fa-exchange-alt"></i> Upload sa zamjenom</button>
                    <input type="file" name="file" required />
                    <input type="text" class="prep_id" name="preparation_id" hidden />
                </div>
                @csrf
            </form>
        @endif
        <form class="upload_file_replace" action="{{ action('EquipmentListController@importSiemens') }}" method="POST" enctype="multipart/form-data" title ="Import siemens">
            <div class="file-input-wrapper">
                <button class="btn-file-input"><i class="fas fa-upload"></i> Upload Siemens Linde (hierarhija)</button>
                <input type="file" name="file" required />
                <input type="text" class="prep_id" name="preparation_id" hidden />
            </div>
            @csrf
        </form>
    @endif           
</div>
<span hidden class="users_json">{{ json_encode($users->toArray()) }}</span>
<span hidden class="priprema_json">{{ json_encode($priprema->toArray()) }}</span>
<span hidden class="mehanicka_json">{{ json_encode( $mehanicka->toArray()) }}</span>
<span hidden class="oznake_json">{{ json_encode( $oznake->toArray()) }}</span>
<script>
    $.getScript('/../js/preparation_index.js');
    
   /*  if( $('.row_preparation_text.'+id).css('display') == 'flex' ) {
        $('.row_preparation_text.'+id).css('display','none');
        $('.row_preparation_text.'+id).find('span:not(.not_remove)').remove();
    } else {
        collapsProject (id);
    } */
   
</script>
@stop