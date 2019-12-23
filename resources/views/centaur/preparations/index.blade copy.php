@extends('Centaur::layout')

@section('title', 'Priprema i mehanička obrada')

@section('content')
<span hidden class="today">{{ date('Y-m-d') }}</span>
<div class="page-header">
    <h1>Priprema i mehanička obrada</h1>
     <div class='btn-toolbar pull-right'>
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
            <div class="table table-hover" id="index_table">
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
                        <span class="th equipment_input"> Oprema</span>
                        <span class="th history_input">Povijest</span>
                       <span class="th option_input">Opcije</span>
                    </p>
                </div>
                <div class="tbody">
                    @foreach ($preparations as $preparation)
                        @if (Sentinel::getUser()->id == $preparation->project_manager || Sentinel::getUser()->id == $preparation->designed_by || Sentinel::inRole('administrator') || Sentinel::inRole('subscriber'))
                            @php
                                $preparationRecords1 = $preparationRecords->where('preparation_id',$preparation->id);
                                $preparationRecord_today = $preparationRecords1->where('preparation_id',$preparation->id)->where('date', date('Y-m-d'))->first();
                            @endphp
                                <!-- Edit pripreme -->
                            @include('centaur.preparation_edit')
                            <!-- Ispis pripreme -->  
                                    <p class="tr row_preparation_text ">
                                        <span class="td text_preparation file_input"></span>
                                        <span class="td text_preparation project_no_input">{{ $preparation->project_no  }}</span>
                                        <span class="td text_preparation name_input">{{ $preparation->name }}</span>
                                        <span class="td text_preparation delivery_input">{!! $preparation->delivery ? date('d.m.Y', strtotime($preparation->delivery)) : '' !!}</span>
                                        <span class="td text_preparation manager_input">{{ $preparation->manager['first_name'] . ' ' . $preparation->manager['last_name']  }}</span>
                                        <span class="td text_preparation designed_input">{{ $preparation->designed['first_name'] . ' ' . $preparation->designed['last_name']  }}</span>
                                        <span class="td text_preparation date_input">{{ date('d.m.Y')}}</span>
                                        <span class="td text_preparation date_change preparation_input"   >
                                            @if (!Sentinel::inRole('moderator'))
                                                @if ($preparationRecord_today)
                                                    <span class="date_{{ $preparationRecord_today->date }}">{{ $preparationRecord_today->preparation }}</span>
                                                @endif
                                            @endif
                                        </span>
                                        <span class="td text_preparation date_change mechanical_input">
                                            @if (!Sentinel::inRole('moderator'))
                                                @if ($preparationRecord_today)
                                                    <span class="date_{{ $preparationRecord_today->date }}">{{ $preparationRecord_today->mechanical_processing }}</span>
                                                @endif
                                            @endif
                                        </span>
                                        
                                        <span class="td text_preparation equipment_input">    
                                            @if($equipmentLists->where('preparation_id', $preparation->id )->first())
                                                <a href="{{ route('equipment_lists.edit', $preparation->id ) }}" class="equipment_lists_open" rel="modal:open">Upis opreme</a>
                                            @else
                                                <small>Nema zapisa</small>
                                            @endif
                                        </span>
                                      
                                        <!-- Povijest zapisa -->
                                        <span class="td text_preparation history_input">
                                            @if (!Sentinel::inRole('moderator'))
                                                @if ($preparationRecords1->where('date', '<>', date('Y-m-d'))->first())
                                                    <button class="arrow_collaps {!! $preparationRecords1->where('date', '<>', date('Y-m-d'))->first() ? 'collapsible' : '' !!}"" type="button" {!! $preparationRecords1->where('date', '<>', date('Y-m-d'))->first() ? 'style="cursor:pointer"' : '' !!}><i class="fas fa-caret-down"></i></button> 
                                                @else
                                                    <small>Nema povijesti</small>
                                                @endif
                                            @endif
                                        </span>
                                        
                                        <span class="td text_preparation option_input">
                                            <a href="#" class="btn btn-edit">
                                                <span class="glyphicon glyphicon-edit" aria-hidden="true" title="Ispravi"></span>
                                            </a>
                                            @if (Sentinel::inRole('administrator'))   
                                                <a href="{{ route('preparations.destroy', $preparation->id) }}" class="action_confirm btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}" title="Obriši">
                                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                                </a>
                                            @endif
                                        </span>
                                    </p>
                                @if ($preparationRecords1->where('date', '<>', date('Y-m-d'))->first())
                                    <!-- Zapisi pripreme -->
                                    <div class="content">
                                        @foreach ( $preparationRecords1->where('date', '<>', date('d-m-Y')) as $record )
                                            @include('centaur.preparation_record')
                                        @endforeach
                                    </div>
                                @endif
                                @if(! Sentinel::inRole('subscriber'))
                                    <form class="upload_file" action="{{ action('EquipmentListController@import') }}" method="POST" enctype="multipart/form-data">
                                        <div class="file-input-wrapper">
                                            <button class="btn-file-input"><i class="fas fa-upload"></i></button>
                                            <input type="file" name="file" required />
                                            <input type="text" name="preparation_id" value="{{ $preparation->id }}" hidden/>
                                        </div>
                                        @csrf
                                    </form>
                                @endif
                        @endif
                    @endforeach
                    <!-- Novi unos -->     
                    @if(! Sentinel::inRole('subscriber'))
                        @include('centaur.preparation_create')
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.upload_file input[type=file]').change(function(){
        $(this).parent().parent().submit();
    });

    $('.collapsible').click(function(){
        $(this).parent().parent().next('.content').toggle();
    });

    $('a.btn-edit').click(function(event ){
        event.preventDefault();
        $(this).parent().parent().prev('.form_preparation').css('display','flex');
        $(this).parent().parent().hide();

    });
    $('a.btn-cancel').click(function(event ){
        event.preventDefault();
        $(this).parent().parent().next('.row_preparation_text').show();
        $(this).parent().parent().hide();

    });
    $('a.btn-cancel2').click(function(event ){
        event.preventDefault();
        $(this).parent().parent().next('p').show();
        $(this).parent().parent().hide();

    });
    $.getScript('/../js/filter.js');
</script>
@stop