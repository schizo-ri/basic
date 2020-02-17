<a href="#close-modal" rel="modal:close" class="close-modal ">Close</a>
<div class="modal-header" id="first_anchor">
    <h2 class="">Lista opreme: {{ $equipments->first()->preparation1['project_no'] . ' - ' . $equipments->first()->preparation1['name'] }}</h2>
    <h4 class="">Datum isporuke: {!! $equipments->first()->preparation1['delivery'] ? date('d.m.Y', strtotime($equipments->first()->preparation1['delivery'] )) : '' !!}</h4>
    <label class="filter_empl">
        <input type="search" placeholder="Traži..." onkeyup="mySearchList()" id="mySearchList">
        <i class="clearable__clear">&times;</i>
    </label>
    <div class="filter_color">
        <span class="all">Sve</span>
        <a class="btn-file-input equipment_lists_mark " href="{{ action('EquipmentListController@exportList', ['id' => $preparation_id, 'status' => 'all' ] ) }}" ><i class="fas fa-download"></i></a>
        <span class="green" >Isporučeno</span>
        <a class="btn-file-input equipment_lists_mark green" href="{{ action('EquipmentListController@exportList', ['id' => $preparation_id, 'status' => 'ok' ] ) }}" ><i class="fas fa-download"></i></a>
        <span class="red" >Neisporučeno</span>
        <a class="btn-file-input equipment_lists_mark red" href="{{ action('EquipmentListController@exportList', ['id' => $preparation_id, 'status' => 'no' ] ) }}" ><i class="fas fa-download"></i></a>
        <span class="yellow" >Djelomično</span>
        <a class="btn-file-input equipment_lists_mark yellow" href="{{ action('EquipmentListController@exportList', ['id' => $preparation_id, 'status' => 'part' ] ) }}" ><i class="fas fa-download"></i></a>
        <span class="grey" >Zamjenjeno</span>
    </div>
</div>
<div class="modal-body">
    <div>
        <form class="edit_list" accept-charset="UTF-8" role="form" method="post" action="{{ route('equipment_lists.store') }}">
            <div class="thead">
                <p class="tr">
                    <span class="th">Produkt</span> 
                    <span class="th">Oznaka</span> 
                    <span class="th">Naziv</span>
                    <span class="th">Jed.mj.</span>
                    <span class="th">Količina</span>
                    <span class="th">Isporučena količina</span>
                    <span class="th"></span>
                </p>
            </div>
            <div class="tbody">
                @if ( $equipments->where('level1',1)->first())
                    @foreach ($equipments->where('level1', 1) as $equipment_level1)
                        {{-- naslov --}}
                        <p class="tr row_preparation_text {!! $equipment_level1->replace_item == 1 ? 'removed_item' : '' !!} item_level1 collapsible" id="{{ $equipment_level1->id }}" >
                            @php
                                $listUpdates_item = $listUpdates->where('item_id', $equipment_level1->id );
                                $delivered = $equipment_level1->delivered;

                                foreach ($listUpdates_item as $listUpdate) {
                                    $delivered +=  $listUpdate->quantity;
                                }
                            @endphp
                                <span class="td text_preparation align_left padding_h_10">
                                    @if ($equipment_level1->replace_item == 1)
                                        @if($equipments->where('replaced_item_id', $equipment_level1->id )->first())
                                            <a class="link_to_replaced" href="#{{ $equipments->where('replaced_item_id', $equipment_level1->id )->first()->id }}" >
                                        @endif
                                    @elseif ($equipment_level1->replaced_item_id != null)
                                        @if ($equipments->where('id', $equipment_level1->replaced_item_id )->first())
                                            <a class="link_to_replaced" href="#{{ $equipments->where('id', $equipment_level1->replaced_item_id )->first()->id }}">
                                        @endif
                                    @endif
                                        {{ $equipment_level1->product_number}}
                                    @if ($equipment_level1->replace_item == 1)
                                        @if($equipments->where('replaced_item_id', $equipment_level1->id )->first())
                                            [ zamjena: {{ $equipments->where('replaced_item_id', $equipment_level1->id )->first()->product_number }} ]
                                        </a>
                                        @endif
                                    @elseif ($equipment_level1->replaced_item_id != null)
                                        @if ($equipments->where('id', $equipment_level1->replaced_item_id )->first())
                                            [ {{ $equipments->where('id', $equipment_level1->replaced_item_id )->first()->product_number }} ]
                                        </a>
                                        @endif
                                    @endif
                                </span>
                                <span class="td text_preparation">{{ $equipment_level1->mark }}</span>
                                <span class="td text_preparation">{{ $equipment_level1->name }}</span>
                                <span class="td text_preparation ">{{ $equipment_level1->unit }}</span>
                                <span class="td text_preparation quantity ">{{ $equipment_level1->quantity }}</span>
                                <span class="td text_preparation delivered">
                                    <input name="delivered[]" type="number" step="0.01"  title="Please enter number only" value="{{ $delivered }}" {!! ! Sentinel::inRole('priprema') && ! Sentinel::inRole('administrator') ? 'disabled' : '' !!} />    
                                    @if ( $delivered )
                                        @if (Sentinel::inRole('administrator') || Sentinel::inRole('list_view')  )
                                            <span class="arrow_down"><i class="fas fa-arrow-down"></i></span>  
                                            <span class="delivered_history">
                                                @if ( $equipment_level1->delivered)
                                                    <span class="item_delivered">
                                                        {{ date('d.m.Y',strtotime($equipment_level1->created_at)) . ' | ' . $equipment_level1->delivered . ' ' .  $equipment_level1->unit }}
                                                    </span>
                                                @endif
                                                @foreach ($listUpdates_item as $listUpdate)
                                                    <span class="item_delivered">
                                                        @if ($listUpdate->quantity)
                                                            {{ date('d.m.Y H:i',strtotime($listUpdate->created_at)) . ' | ' . $listUpdate->quantity  . ' ' .  $equipment_level1->unit}}
                                                        @endif
                                                    </span>
                                                @endforeach
                                            </span>
                                        @endif                                        
                                    @endif
                                </span>
                                <span class="td text_preparation replace">
                                    @if (Sentinel::inRole('list_view') || Sentinel::inRole('moderator') || Sentinel::inRole('administrator'))
                                        @if ($equipment_level1->replace_item == null && $equipment_level1->delivered < $equipment_level1->quantity || $delivered == 0  )
                                            <span class="action_confirm btn-file-input equipment_lists_mark" title="Zamjeni stavku" id="{{ $equipment_level1->id }}" ><i class="fas fa-exchange-alt"></i></span>
                                        @endif 
                                    @endif                                                                  
                                </span>
                        </p>
                        <span class="content">
                            @foreach ($equipments->where('stavka_id_level1',$equipment_level1->id) as $equipment_level2)
                                {{-- podnaslov --}} 
                                <p class="tr row_preparation_text {!! $equipment_level2->replace_item == 1 ? 'removed_item' : '' !!} item_level2 collapsible" id="{{ $equipment_level2->id }}" >
                                    @php
                                        $listUpdates_item = $listUpdates->where('item_id', $equipment_level2->id );
                                        $delivered = $equipment_level2->delivered;

                                        foreach ($listUpdates_item as $listUpdate) {
                                            $delivered +=  $listUpdate->quantity;
                                        }
                                    @endphp
                                        <span class="td text_preparation align_left padding_h_10">
                                            @if ($equipment_level2->replace_item == 1)
                                                @if($equipments->where('replaced_item_id', $equipment_level2->id )->first())
                                                    <a class="link_to_replaced" href="#{{ $equipments->where('replaced_item_id', $equipment_level2->id )->first()->id }}" >
                                                @endif
                                            @elseif ($equipment_level2->replaced_item_id != null)
                                                @if ($equipments->where('id', $equipment_level2->replaced_item_id )->first())
                                                    <a class="link_to_replaced" href="#{{ $equipment_level2->where('id', $equipment_level2->replaced_item_id )->first()->id }}">
                                                @endif
                                            @endif
                                                {{ $equipment_level2->product_number}}
                                            @if ($equipment_level2->replace_item == 1)
                                                @if($equipments->where('replaced_item_id', $equipment_level2->id )->first())
                                                    [ zamjena: {{ $equipments->where('replaced_item_id', $equipment_level2->id )->first()->product_number }} ]
                                                </a>
                                                @endif
                                            @elseif ($equipment_level2->replaced_item_id != null)
                                                @if ($equipments->where('id', $equipment_level2->replaced_item_id )->first())
                                                [ {{ $equipments->where('id', $equipment_level2->replaced_item_id )->first()->product_number }} ]
                                                </a>
                                                @endif
                                            @endif
                                        </span>
                                        <span class="td text_preparation">{{ $equipment_level2->mark }}</span>
                                        <span class="td text_preparation">{{ $equipment_level2->name }}</span>
                                        <span class="td text_preparation ">{{ $equipment_level2->unit }}</span>
                                        <span class="td text_preparation quantity ">{{ $equipment_level2->quantity }}</span>
                                        <span class="td text_preparation delivered">
                                            <input name="delivered[]" type="number" step="0.01"  title="Please enter number only" value="{{ $delivered }}" {!! ! Sentinel::inRole('priprema') && ! Sentinel::inRole('administrator') ? 'disabled' : '' !!} />    
                                            @if ( $delivered )
                                                @if (Sentinel::inRole('administrator') || Sentinel::inRole('list_view')  )
                                                    <span class="arrow_down"><i class="fas fa-arrow-down"></i></span>  
                                                    <span class="delivered_history">
                                                        @if ( $equipment_level2->delivered)
                                                            <span class="item_delivered">
                                                                {{ date('d.m.Y',strtotime($equipment_level2->created_at)) . ' | ' . $equipment_level2->delivered . ' ' .  $equipment_level2->unit }}
                                                            </span>
                                                        @endif
                                                        @foreach ($listUpdates_item as $listUpdate)
                                                            <span class="item_delivered">
                                                                @if ($listUpdate->quantity)
                                                                    {{ date('d.m.Y H:i',strtotime($listUpdate->created_at)) . ' | ' . $listUpdate->quantity  . ' ' .  $equipment_level2->unit}}
                                                                @endif
                                                            </span>
                                                        @endforeach
                                                    </span>
                                                @endif                                        
                                            @endif
                                        </span>
                                        <span class="td text_preparation replace">
                                            @if (Sentinel::inRole('list_view') || Sentinel::inRole('moderator') || Sentinel::inRole('administrator'))
                                                @if ($equipment_level2->replace_item == null && $equipment_level2->delivered < $equipment_level2->quantity || $delivered == 0  )
                                                    <span class="action_confirm btn-file-input equipment_lists_mark" title="Zamjeni stavku" id="{{ $equipment_level2->id }}" ><i class="fas fa-exchange-alt"></i></span>
                                                @endif 
                                            @endif                                                                  
                                        </span>
                                </p>
                                <span class="content">
                                    @foreach ($equipments->where('stavka_id_level2',$equipment_level2->id) as $equipment_level3)
                                        {{-- stavka --}}
                                            <p class="tr row_preparation_text {!! $equipment_level3->replace_item == 1 ? 'removed_item' : '' !!} item_level3" id="{{ $equipment_level3->id }}" >
                                                @php
                                                    $listUpdates_item = $listUpdates->where('item_id', $equipment_level3->id );
                                                    $delivered = $equipment_level3->delivered;
                
                                                    foreach ($listUpdates_item as $listUpdate) {
                                                        $delivered +=  $listUpdate->quantity;
                                                    }
                                                @endphp
                                                <span class="td text_preparation align_left padding_h_10">
                                                    @if ($equipment_level3->replace_item == 1)
                                                        @if($equipments->where('replaced_item_id', $equipment_level3->id )->first())
                                                            <a class="link_to_replaced" href="#{{ $equipments->where('replaced_item_id', $equipment_level3->id )->first()->id }}" >
                                                        @endif
                                                    @elseif ($equipment_level3->replaced_item_id != null)
                                                        @if ($equipments->where('id', $equipment_level3->replaced_item_id )->first())
                                                            <a class="link_to_replaced" href="#{{ $equipment_level3->where('id', $equipment_level3->replaced_item_id )->first()->id }}">
                                                        @endif
                                                    @endif
                                                        {{ $equipment_level3->product_number}}
                                                    @if ($equipment_level3->replace_item == 1)
                                                        @if($equipments->where('replaced_item_id', $equipment_level3->id )->first())
                                                            [ zamjena: {{ $equipments->where('replaced_item_id', $equipment_level3->id )->first()->product_number }} ]
                                                        </a>
                                                        @endif
                                                    @elseif ($equipment_level3->replaced_item_id != null)
                                                        @if ($equipments->where('id', $equipment_level3->replaced_item_id )->first())
                                                        [ {{ $equipments->where('id', $equipment_level3->replaced_item_id )->first()->product_number }} ]
                                                        </a>
                                                        @endif
                                                    @endif
                                                </span>
                                                <span class="td text_preparation">{{ $equipment_level3->mark }}</span>
                                                <span class="td text_preparation">{{ $equipment_level3->name }}</span>
                                                <span class="td text_preparation ">{{ $equipment_level3->unit }}</span>
                                                <span class="td text_preparation quantity ">{{ $equipment_level3->quantity }}</span>
                                                <span class="td text_preparation delivered">
                                                    <input name="delivered[]" type="number" step="0.01"  title="Please enter number only" value="{{ $delivered }}" {!! ! Sentinel::inRole('priprema') && ! Sentinel::inRole('administrator') ? 'disabled' : '' !!} />    
                                                    @if ( $delivered )
                                                        @if (Sentinel::inRole('administrator') || Sentinel::inRole('list_view')  )
                                                            <span class="arrow_down"><i class="fas fa-arrow-down"></i></span>  
                                                            <span class="delivered_history">
                                                                @if ( $equipment_level3->delivered)
                                                                    <span class="item_delivered">
                                                                        {{ date('d.m.Y',strtotime($equipment_level3->created_at)) . ' | ' . $equipment_level3->delivered . ' ' .  $equipment_level3->unit }}
                                                                    </span>
                                                                @endif
                                                                @foreach ($listUpdates_item as $listUpdate)
                                                                    <span class="item_delivered">
                                                                        @if ($listUpdate->quantity)
                                                                            {{ date('d.m.Y H:i',strtotime($listUpdate->created_at)) . ' | ' . $listUpdate->quantity  . ' ' .  $equipment_level3->unit}}
                                                                        @endif
                                                                    </span>
                                                                @endforeach
                                                            </span>
                                                        @endif                                        
                                                    @endif
                                                </span>
                                                <span class="td text_preparation replace">
                                                    @if (Sentinel::inRole('list_view') || Sentinel::inRole('moderator') || Sentinel::inRole('administrator'))
                                                        @if ($equipment_level3->replace_item == null && $equipment_level3->delivered < $equipment_level3->quantity || $delivered == 0  )
                                                            <span class="action_confirm btn-file-input equipment_lists_mark" title="Zamjeni stavku" id="{{ $equipment_level3->id }}" ><i class="fas fa-exchange-alt"></i></span>
                                                        @endif 
                                                    @endif                                                                  
                                                </span>
                                            </p>
                                    @endforeach
                                </span> 
                            @endforeach
                        </span>
                    @endforeach
                @else
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($list_dates as $date)
                        <div>
                            <h4>Lista {{ $i }} - {{ date('d.m.Y', strtotime( $date))}} </h4>
                            @foreach ($equipments->where('created_at', $date) as $equipment)
                                @php
                                    $listUpdates_item = $listUpdates->where('item_id', $equipment->id );
                                    $delivered = $equipment->delivered;

                                    foreach ($listUpdates_item as $listUpdate) {
                                        $delivered +=  $listUpdate->quantity;
                                    }
                                @endphp
                                <input name="id[]" value="{{ $equipment->id }}" hidden/>
                            
                                <p class="tr row_preparation_text {!! $equipment->replace_item == 1 ? 'removed_item' : '' !!}" id="{{ $equipment->id }}" >
                                    <span class="td text_preparation align_left padding_h_10">
                                        @if ($equipment->replace_item == 1)
                                            @if($equipments->where('replaced_item_id', $equipment->id )->first())
                                                <a class="link_to_replaced" href="#{{ $equipments->where('replaced_item_id', $equipment->id )->first()->id }}" >
                                            @endif
                                        @elseif ($equipment->replaced_item_id != null)
                                            @if ($equipments->where('id', $equipment->replaced_item_id )->first())
                                                <a class="link_to_replaced" href="#{{ $equipments->where('id', $equipment->replaced_item_id )->first()->id }}">
                                            @endif
                                        @endif
                                            {{ $equipment->product_number}}
                                        @if ($equipment->replace_item == 1)
                                            @if($equipments->where('replaced_item_id', $equipment->id )->first())
                                                [ zamjena: {{ $equipments->where('replaced_item_id', $equipment->id )->first()->product_number }} ]
                                            </a>
                                            @endif
                                        @elseif ($equipment->replaced_item_id != null)
                                            @if ($equipments->where('id', $equipment->replaced_item_id )->first())
                                            [ {{ $equipments->where('id', $equipment->replaced_item_id )->first()->product_number }} ]
                                            </a>
                                            @endif
                                        @endif
                                    </span>
                                    <span class="td text_preparation">{{ $equipment->mark }}</span>
                                    <span class="td text_preparation">{{ $equipment->name }}</span>
                                    <span class="td text_preparation ">{{ $equipment->unit }}</span>
                                    <span class="td text_preparation quantity ">{{ $equipment->quantity }}</span>
                                    <span class="td text_preparation delivered">
                                        <input name="delivered[]" type="number" step="0.01"  title="Please enter number only" value="{{ $delivered }}" {!! ! Sentinel::inRole('priprema') && ! Sentinel::inRole('administrator') ? 'disabled' : '' !!} />    
                                        @if ( $delivered )
                                            @if (Sentinel::inRole('administrator') || Sentinel::inRole('list_view')  )
                                                <span class="arrow_down"><i class="fas fa-arrow-down"></i></span>  
                                                <span class="delivered_history">
                                                    @if ( $equipment->delivered)
                                                        <span class="item_delivered">
                                                            {{ date('d.m.Y',strtotime($equipment->created_at)) . ' | ' . $equipment->delivered . ' ' .  $equipment->unit }}
                                                        </span>
                                                    @endif
                                                    @foreach ($listUpdates_item as $listUpdate)
                                                        <span class="item_delivered">
                                                            @if ($listUpdate->quantity)
                                                                {{ date('d.m.Y H:i',strtotime($listUpdate->created_at)) . ' | ' . $listUpdate->quantity  . ' ' .  $equipment->unit}}
                                                            @endif
                                                        </span>
                                                    @endforeach
                                                </span>
                                            @endif                                        
                                        @endif
                                    </span>
                                    <span class="td text_preparation replace">
                                        @if (Sentinel::inRole('list_view') || Sentinel::inRole('moderator') || Sentinel::inRole('administrator'))
                                            @if ($equipment->replace_item == null && $equipment->delivered < $equipment->quantity || $delivered == 0  )
                                                <span class="action_confirm btn-file-input equipment_lists_mark" title="Zamjeni stavku" id="{{ $equipment->id }}" ><i class="fas fa-exchange-alt"></i></span>
                                            @endif 
                                        @endif
                                    </span>
                                </p>
                            @endforeach
                        </div>
                        @php
                            $i++;
                        @endphp
                    @endforeach                    
                @endif
            </div>
            {{ csrf_field() }}
            
            @if ( Sentinel::inRole('priprema') || Sentinel::inRole('administrator'))
                <input class="btn btn-lg btn-primary store_changes" type="submit" value="Spremi">
            @endif
        </form>
        <form class="create_item" accept-charset="UTF-8" role="form" method="post" action="{{ route('equipment_lists.create') }}">
            <input name="preparation_id" id="preparation_id"  value="{{ $preparation_id }}" hidden />
            <input name="product_number" id="product_number" placeholder="Upiši broj produkta..." value="{{ old('product_number') }}" required/>
            <input name="mark" id="mark" placeholder="Upiši oznaku..." value="{{ old('mark') }}" required />
            <input name="name" id="name" placeholder="Upiši naziv..." value="{{ old('name') }}" required/>
            <input name="unit" id="unit" placeholder="Upiši jmj..." value="{{ old('unit') }}" required/>
            <input name="quantity" id="quantity" placeholder="Upiši količinu..." value="{{ old('quantity') }}" required />
            {{ csrf_field() }}    
            <input type="submit" value="upiši" />
        </form>
        <a href="#first_anchor"><i class="fas fa-arrow-up" ></i></a>
    </div>
</div>
<script>
$(function() {
    $('.collapsible').click(function(event){    
        $(this).next('.content').toggle();
    });
    var id_row;
    $('.link_to_replaced').click(function(){
        id_row = $( this ).attr('href').replace('#', '');
        $('#'+id_row).css('font-weight','bold');
        $('#'+id_row).css('border','2px solid red');
        $( '.link_to_replaced' ).prop("disabled", true);
        
        setTimeout(return_css, 2000);
    });
    var return_css = function(){
        $('#'+id_row).css('font-weight','normal');
        $('#'+id_row).css('border-left','none');
        $('#'+id_row).css('border-right','none');
        $('#'+id_row).css('border-bottom','1px solid #ccc');
        $('#'+id_row).css('border-top','1px solid #ccc');     
      
    };


    var equipment_lists_height = $('.modal.equipment_lists').height();
    var id; // item id

    var el_replace;

    $('.action_confirm').click(function(){
       if( confirm("Sigurno želiš zamjeniti stavku?") ) {

            $('.modal .create_item').show();
            $('.modal.equipment_lists').scrollTop(equipment_lists_height);
            $('#product_number').focus();

            id = $( this ).attr('id');

            el_replace = $( this );
            
            el_replace.parent().parent().removeClass('all_delivered');
            el_replace.parent().parent().removeClass('not_delivered');
            el_replace.parent().parent().removeClass('partial');
            el_replace.parent().parent().addClass('removed_item');
            el_replace.parent().prev('.delivered').find('input').attr('disabled','disabled');
            el_replace.remove();

       }  else {
            return false;
       }
     
    });

    $('.create_item').submit(function(e){
        e.preventDefault();

        var preparation_id = $('#preparation_id').val();
        var product_number = $('#product_number').val();
        var mark = $('#mark').val();
        var name = $('#name').val();
        var unit = $('#unit').val();
        var quantity = $('#quantity').val();
        var token = $('meta[name="csrf-token"]').attr('content');
        var url_update = location.origin + '/equipment_lists/' + preparation_id +'/edit/';   
        
        $.ajax({
            url:  'addItem', 
            type: 'post',
            data: {
                    '_token':  token,
                    'preparation_id': preparation_id,
                    'product_number': product_number,
                    'mark': mark,
                    'name': name,
                    'unit': unit,
                    'quantity': quantity,
                    'replaced_item_id': id
                }         
        })
        .done(function( msg ) {
            
            $.ajax({
                type: 'POST',
                url: 'replaceItem',
                data: {'id':id,
                        '_token':  $('meta[name="csrf-token"]').attr('content') },
                success: function(data){
                   /* el_replace.parent().parent().removeClass('all_delivered');
                    el_replace.parent().parent().removeClass('not_delivered');
                    el_replace.parent().parent().removeClass('partial');
                    el_replace.parent().parent().addClass('removed_item');
                    el_replace.parent().prev('.delivered').find('input').attr('disabled','disabled');
                    el_replace.remove();*/
                    
                },
            });
            $('.modal').load( url_update );
            console.log( "Stavka je spremljena!" );
          
        })
        .fail(function() {
            alert( "Spremanje nije uspjelo" );
        })
    });
   
    $('.arrow_down').click(function(){
        $( this ).siblings('.delivered_history').toggle();
    });

    var delivered = 0;
    var quantity = 0;
    $.each( $('.row_preparation_text'), function( index, value ) {
        if( $( this ).hasClass('removed_item')) {
            //
        } else {
            delivered = $( this ).children('.delivered').find('input').val();
            quantity =  $( this ).children('.quantity').text();

            if( delivered == 'undefined' || delivered == '' || delivered == '0') {
                $( this ).addClass('not_delivered');
            } else if(delivered == quantity || delivered > parseInt(quantity)) {
                $( this ).addClass('all_delivered');          
            } else if(delivered < parseInt(quantity)) {
                $( this ).addClass('partial');          
            }
        }      
    });

    $('.text_preparation.delivered>input').change(function(){
        delivered = $( this ).val();
        quantity = $( this ).parent().prev().text();
       
        if( delivered == 'undefined' || delivered == '' || delivered == '0') {
            $( this ).parent().parent().removeClass('all_delivered');  
            $( this ).parent().parent().removeClass('partial');   
            $( this ).parent().parent().addClass('not_delivered');  
        } 
        if((delivered == parseInt(quantity)) || (delivered > parseInt(quantity))) {
            $( this ).parent().parent().removeClass('not_delivered');
            $( this ).parent().parent().removeClass('partial');
            $( this ).parent().parent().addClass('all_delivered');          
        }
        if(delivered < parseInt(quantity)) {
            $( this ).parent().parent().removeClass('not_delivered');
            $( this ).parent().parent().removeClass('all_delivered');
            $( this ).parent().parent().addClass('partial');          
        }
    });

    var inputs = $(".text_preparation.delivered > input");

    $(inputs).keypress(function(e){
        if (e.keyCode == 13){
            inputs[inputs.index(this)+1].focus();
            e.preventDefault();
        }
    });

    var color;
    var status;

    $('.filter_color>span').click(function(){
        color = this.className;

        if (color == 'red') {
            status = 'not_delivered';
        } else if (color == 'green') {
            status = 'all_delivered';
        } else if (color == 'yellow') {
            status = 'partial';
        } else if (color == 'grey') {
            status = 'removed_item';
        }

        $.each($('.modal .row_preparation_text'), function( index, value ) {
            if(color == 'all') {
                $( this ).show();
            } else {
                if( $(this).hasClass(status)) {
                    $( this ).show();
                } else {
                    $( this ).hide();
                }
            }           
        });       
    });
});   
$('body').on($.modal.AFTER_CLOSE, function(event, modal) {
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