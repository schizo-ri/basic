@extends('Centaur::layout')

@section('title', 'Priprema i mehanička obrada')

@section('content')
<div class="page-header" id="first_anchor">
    <div class="page_navigation pull-left">
        <a class="link_back " href="{{ route('preparations.index') }}">Priprema i mehanička obrada</a>
        <span>/</span>
        <a class="link_back " href="{{ route('preparations.show', $equipments->first()->preparation_id) }}">Projekt  {!! $equipments->first() ?  $equipments->first()->preparation1->project_no : '' !!}</a>
        <span>/</span>
        <span class="pull-left" >Zamjena na ormaru: {{ $equipments->first()->preparation1['project_no'] . ' - ' . $equipments->first()->preparation1['name'] }}</span>
    </div>
    <div class="header_filter">
        <div class="delivery_date pull-left" >
            <p class="">Datum isporuke: {!! $equipments->first()->preparation1['delivery'] ? date('d.m.Y', strtotime($equipments->first()->preparation1['delivery'] )) : '' !!}</p>
         </div>
         <label class="filter_empl pull-right">
            <input type="search" placeholder="Traži..." onkeyup="mySearchList()" id="mySearchList">
            <i class="clearable__clear">&times;</i>
        </label>
    </div>
</div>
<div class="modal-body">
    <div>
        <form class="edit_list" accept-charset="UTF-8" role="form" method="post" action="{{ route('multiReplaceStore') }}">
            <input name="preparation_id" id="preparation_id"  value="{{ $preparation_id }}" hidden />
            <div class="thead">
                <p class="tr">
                    <span class="th_50"></span> 
                    <span class="th_50">Zamjena</span> 
                </p>
                <p class="tr">
                    <span class="th_50">
                        <span class="th_10">Produkt</span> 
                        <span class="th_10">Oznaka</span>
                        <span class="th">Naziv</span>
                        <span class="th_10">Jed.mj.</span>
                        <span class="th_10">Količina</span>
                    </span> 
                    <span class="th_50">
                        <span class="th_10">Produkt</span> 
                        <span class="th_10">Oznaka</span>
                        <span class="th">Naziv</span>
                        <span class="th_10 align_center">Jed.mj.</span>
                        <span class="th_10 align_center">Količina</span>
                    </span> 
                </p>
            </div>
            <div class="tbody item_replace_list">
                @php
                    $i = 1;
                @endphp
                @foreach ($list_dates as $date)
                    <div>
                        <h4>Lista {{ $i }} - {{ date('d.m.Y', strtotime( $date))}} </h4>
                        @foreach ($equipments->where('created_at', $date) as $equipment)
                            @php
                                $listUpdates_item = $equipment->updates;
                                $delivered = $equipment->delivered;

                                foreach ($listUpdates_item as $listUpdate) {
                                    $delivered +=  $listUpdate->quantity;
                                }
                            @endphp
                            @if ($equipment->replace_item != 1 && ( $delivered == null || $delivered == 0 || $delivered < $equipment->quantity)    )
                                
                                <p class="tr row_preparation_text {!! $equipment->replace_item == 1 ? 'removed_item' : '' !!}" id="{{ $equipment->id }}" >
                                    <span class="td_50">
                                        <span class="td_10 text_preparation align_left padding_h_10">{{ $equipment->product_number}}  <span class="open_input"><i class="fas fa-exchange-alt"></i></span></span>
                                        <span class="td_10 text_preparation">{{ $equipment->mark }}</span>
                                        <span class="td text_preparation">{{ $equipment->name }}</span>
                                        <span class="td_10 text_preparation align_center">{{ $equipment->unit }}</span>
                                        <span class="td_10 text_preparation align_center quantity ">{{ $equipment->quantity }}</span>
                                    </span> 
                                    <span class="td_50 replace_items">
                                        
                                    </span> 
                                </p>
                            @endif
                        @endforeach
                    </div>
                    @php
                        $i++;
                    @endphp
                @endforeach
            </div>
            {{ csrf_field() }}
            <input class="btn btn-lg btn-primary store_changes" type="submit" value="Spremi">
        </form>
        <a href="#first_anchor"><i class="fas fa-arrow-up" ></i></a>
    </div>
</div>
<script>
    $('.open_input').click(function () {
        $( this ).parent().parent().siblings('.replace_items').css('display','flex');
        var id =  $( this ).parent().parent().parent().attr('id');

        $( this ).parent().parent().siblings('.replace_items').prepend('<input name="id[]" value="' + id +'" hidden/><span class="td_15 text_preparation align_left padding_h_10"><input name="product_number[]" maxlength="50" type="text" /></span><span class="td_15 text_preparation"><input name="mark[]" maxlength="255" type="text" /></span><span class="td_50 text_preparation"><textarea name="name[]" maxlength="255" type="text"></textarea></span><span class="td_10 text_preparation "><input name="unit[]" maxlength="20" type="text" /></span><span class="td_10 text_preparation quantity "><input name="quantity[]" maxlength="20" type="text" /></span>');
        
    })
     
</script>
@stop