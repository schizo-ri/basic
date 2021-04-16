@extends('Centaur::layout')

@section('title', 'Lista - '.$equipments->first()->preparation1['name'] )

@section('content')
@php
    ini_set('memory_limit','-1');
    
@endphp
<div class="page-header" id="first_anchor">
    <div class="page_navigation pull-left">
        <a class="link_back " href="{{ route('preparations.index') }}">Proizvodnja</a>
        <span>/</span>
        <a class="link_back " href="{{ route('preparations.show', $equipments->first()->preparation_id) }}">Projekt  {!! $equipments->first() ?  $equipments->first()->preparation1->project_no : '' !!}</a>
        <span>/</span>
        <span class="pull-left" >Lista opreme: {{ $equipments->first()->preparation1['project_no'] . ' - ' . $equipments->first()->preparation1['name'] }}</span>
    </div>
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
        <form class="edit_list" accept-charset="UTF-8" role="form" method="post" action="{{ route('equipment_lists.store') }}">
            <div class="thead">
                <p class="tr">
                    <span class="th">Produkt</span> 
                    <span class="th">Oznaka</span> 
                    <span class="th">Naziv</span>
                    <span class="th">Jed.mj.</span>
                    <span class="th">Količina</span>
                    <span class="th">Isporučena količina</span>
                    <span class="th">Imam na skladištu / Komentar</span>
                    <span class="th"></span>
                </p>
            </div>
            <div class="tbody">
                @if ( $equipments->where('level1',1)->first())
                     <!------------------------------ naslov -------------------------------------------->
                        <!-- <input name="id[]" value="{{ $equipment_level1->id }}" hidden/> -->
                        <p class="tr row_preparation_text_item1 {!! $equipment_level1->replace_item == 1 ? 'removed_item' : '' !!} item_level1 {!! count($equipments->where('stavka_id_level1',$equipment_level1->id))>0 ? 'collapsible' : '' !!}" id="{{ $equipment_level1->id }}" >
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
                            <span class="td text_preparation"></span>
                            <span class="td text_preparation"></span>
                            <span class="td text_preparation"></span>
                            <span class="td text_preparation">
                                @if (Sentinel::inRole('administrator') ) 
                                    <a href="{{ route('equipment_lists.destroy', $equipment_level1->id) }}" class="action_confirm btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}" title="obriši stavku">
                                        <i class="fas fa-trash-alt"></i>
                                    </a> 
                                @endif
                            </span>
                        </p>
                        <span class="content">
                            @foreach ($equipments->where('stavka_id_level1',$equipment_level1->id) as $equipment_level2)
                                <!------------------------------ podnaslov -------------------------------------------->
                                    <input name="id[{{ $equipment_level2->id }}]" value="{{ $equipment_level2->id }}" hidden/>
                                    <p class="tr level2 {!! $equipment_level2->replace_item == 1 ? 'removed_item' : '' !!} {!! count($equipments->where('stavka_id_level2', $equipment_level2->id))>0 ? 'row_preparation_text_item2 item_level2 collapsible' : 'row_preparation_text' !!}  " id="{{ $equipment_level2->id }}" >
                                        @php
                                            $listUpdates_item = $listUpdates->where('item_id', $equipment_level2->id );
                                            $delivered = $equipment_level2->delivered;

                                            $delivered += $listUpdates_item->sum('quantity');
                                        @endphp
                                        <span class="td text_preparation align_left padding_h_10">
                                            @if (count($equipments->where('stavka_id_level2',$equipment_level2->id))>0)
                                                <i class="fas fa-chevron-down"></i>
                                            @endif
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
                                        @if ( count($equipments->where('stavka_id_level2', $equipment_level2->id)) <= 0)
                                            <span class="td text_preparation ">{{ $equipment_level2->unit }}</span>
                                            <span class="td text_preparation quantity ">{{ $equipment_level2->quantity }}</span>
                                            <span class="td text_preparation delivered">
                                                <input name="delivered[{{ $equipment_level2->id }}]" type="number" step="0.01" title="Please enter number only" value="{{ $delivered }}" {!! ! Sentinel::inRole('priprema') && ! Sentinel::inRole('administrator') ? 'disabled' : '' !!} />   
                                                @if ( $delivered )
                                                    @if (Sentinel::inRole('administrator') || Sentinel::inRole('nabava')  )
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
                                                                        {{ date('d.m.Y H:i',strtotime($listUpdate->created_at)) . ' | ' . $listUpdate->quantity  . ' ' .  $equipment_level2->unit }} {!! $listUpdate->user ? $listUpdate->user->first_name : '' !!} <br>
                                                                      {{--   @if (Sentinel::inRole('administrator')) 
                                                                           <a href="{{ route('list_updates.destroy', $listUpdate->id) }}" class="action_confirm btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}" title="obriši update"> 
                                                                               <i class="fas fa-trash-alt"></i> 
                                                                            </a>
                                                                        @endif --}}
                                                                    @endif
                                                                </span>
                                                            @endforeach
                                                        </span>
                                                    @endif                                        
                                                @endif
                                            </span>
                                            <span class="td text_preparation delivered">
                                                <input name="quantity2[{{ $equipment_level2->id }}]" type="number" step="0.01" title="Imam na skladištu" value="{{  $equipment_level2->quantity2 }}" {!! ! Sentinel::inRole('priprema') && ! Sentinel::inRole('administrator') ? 'disabled' : '' !!} /> 
                                                <input name="comment[{{ $equipment_level2->id }}]" type="text" maxlength="191" title="Komentar" value="{{  $equipment_level2->comment }}" {!! ! Sentinel::inRole('priprema') && ! Sentinel::inRole('administrator') ? 'disabled' : '' !!} /> 
                                            </span>
                                            <span class="td text_preparation replace">
                                                @if (Sentinel::inRole('nabava') || Sentinel::inRole('administrator'))
                                                    @if ($equipment_level2->replace_item == null && $equipment_level2->delivered < $equipment_level2->quantity || $delivered == 0  )
                                                        <span class="action_confirm2 btn-file-input equipment_lists_mark" title="Zamjeni stavku" id="{{ $equipment_level2->id }}" ><i class="fas fa-exchange-alt"></i></span>
                                                    @endif 
                                                @endif
                                                @if (Sentinel::inRole('administrator') ) 
                                                    <a href="{{ route('equipment_lists.destroy', $equipment_level2->id) }}" class="action_confirm btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}" title="obriši stavku">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a> 
                                                @endif
                                            </span>
                                        @else
                                            <span class="td text_preparation"></span>
                                            <span class="td text_preparation"></span>
                                            <span class="td text_preparation"></span>
                                            <span class="td text_preparation">
                                                @if (Sentinel::inRole('administrator') ) 
                                                    <a href="{{ route('equipment_lists.destroy', $equipment_level2->id) }}" class="action_confirm btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}" title="obriši stavku">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a> 
                                                @endif
                                            </span>
                                        @endif
                                    </p>
                                    @if(count($equipments->where('stavka_id_level2',$equipment_level2->id) ) > 0)
                                        <span class="content">
                                            @foreach ($equipments->where('stavka_id_level2',$equipment_level2->id) as $equipment_level3)
                                                <!-- stavka -->
                                                    <input name="id[{{ $equipment_level3->id }}]" value="{{ $equipment_level3->id }}" hidden/>
                                                    <p class="tr row_preparation_text {!! $equipment_level3->replace_item == 1 ? 'removed_item' : '' !!} item_level3" id="{{ $equipment_level3->id }}" >
                                                        @php
                                                            $listUpdates_item = $listUpdates->where('item_id', $equipment_level3->id );
                                                            $delivered = $equipment_level3->delivered;
                                                        
                                                            $delivered += $listUpdates_item->sum('quantity');
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
                                                            <input name="delivered[{{ $equipment_level3->id }}]" type="number" step="0.01" title="Please enter number only" value="{{ $delivered }}"  {!! ! Sentinel::inRole('priprema') && ! Sentinel::inRole('administrator') ? 'disabled' : '' !!}  />    
                                                            
                                                            @if ( $delivered )
                                                                @if ( Sentinel::inRole('administrator') || Sentinel::inRole('nabava') )
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
                                                                                    {{ date('d.m.Y H:i',strtotime($listUpdate->created_at)) . ' | ' . $listUpdate->quantity  . ' ' .  $equipment_level3->unit  . ' | '}} {!! $listUpdate->user ? $listUpdate->user->first_name : '' !!}
                                                                                
                                                                                    {{--  @if (Sentinel::inRole('administrator'))
                                                                                        <a href="{{ route('list_updates.destroy', $listUpdate->id) }}" class="action_confirm btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}" title="obriši update">
                                                                                            <i class="fas fa-trash-alt"></i>
                                                                                        </a>
                                                                                    @endif --}}
                                                                                
                                                                                @endif
                                                                            </span>
                                                                        @endforeach
                                                                    </span>
                                                                @endif                                        
                                                            @endif
                                                            
                                                        </span>
                                                        <span class="td text_preparation delivered">
                                                            <input name="quantity2[{{ $equipment_level3->id }}]" type="number" step="0.01" title="Imam na skladištu" value="{{  $equipment_level3->quantity2 }}" {!! ! Sentinel::inRole('priprema') && ! Sentinel::inRole('administrator') ? 'disabled' : '' !!} />
                                                            <input name="comment[{{ $equipment_level3->id }}]" type="text" maxlength="191" title="Komentar" value="{{  $equipment_level3->comment }}" {!! ! Sentinel::inRole('priprema') && ! Sentinel::inRole('administrator') ? 'disabled' : '' !!} />  
                                                        </span>
                                                        <span class="td text_preparation replace">
                                                            @if (Sentinel::inRole('nabava') || Sentinel::inRole('administrator'))
                                                                @if ($equipment_level3->replace_item == null && $equipment_level3->delivered < $equipment_level3->quantity || $delivered == 0  )
                                                                    <span class="action_confirm2 btn-file-input equipment_lists_mark" title="Zamjeni stavku" id="{{ $equipment_level3->id }}" ><i class="fas fa-exchange-alt"></i></span>
                                                                @endif 
                                                            @endif  
                                                            @if (Sentinel::inRole('administrator') ) 
                                                                <a href="{{ route('equipment_lists.destroy', $equipment_level3->id) }}" class="action_confirm btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}" title="obriši stavku">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </a> 
                                                            @endif                                                                
                                                        </span>
                                                    </p>
                                                <!-- end stavka -->
                                            @endforeach
                                        </span> 
                                    @endif
                                <!-- end podnaslov -->
                            @endforeach
                        </span>
                    <!-- end naslov -->
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
                                    $delivered += $listUpdates_item->sum('quantity');
                                   /*  foreach ($listUpdates_item as $listUpdate) {
                                        $delivered +=  $listUpdate->quantity;
                                    } */
                                @endphp
                                <input name="id[{{ $equipment->id }}]" value="{{ $equipment->id }}" hidden/>
                            
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
                                        <input name="delivered[{{ $equipment->id }}]" type="number" step="0.01"  title="Please enter number only" value="{{ $delivered }}"  {!! ! Sentinel::inRole('priprema') && ! Sentinel::inRole('administrator') ? 'disabled' : '' !!}  />   
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
                                                                {{ date('d.m.Y H:i',strtotime($listUpdate->created_at)) . ' | ' . $listUpdate->quantity  . ' ' .  $equipment->unit . ' | ' }} {!! $listUpdate->user ? $listUpdate->user->first_name : '' !!}
                                                            @endif
                                                        </span>
                                                    @endforeach
                                                </span>
                                            @endif                                        
                                        @endif
                                    </span>
                                    <span class="td text_preparation delivered">
                                        <input name="quantity2[{{ $equipment->id }}]" type="number" step="0.01" title="Imam na skladištu" value="{{  $equipment->quantity2 }}" {!! ! Sentinel::inRole('priprema') && ! Sentinel::inRole('administrator') ? 'disabled' : '' !!} /> 
                                        <input name="comment[{{ $equipment->id }}]" type="text" maxlength="191" title="Komentar" value="{{  $equipment->comment }}" {!! ! Sentinel::inRole('priprema') && ! Sentinel::inRole('administrator') ? 'disabled' : '' !!} />  
                                    </span>
                                    <span class="td text_preparation replace">
                                        @if (Sentinel::inRole('nabava') || Sentinel::inRole('administrator'))
                                            @if ($equipment->replace_item == null && $equipment->delivered < $equipment->quantity || $delivered == 0  )
                                                <span class="action_confirm2 btn-file-input equipment_lists_mark" title="Zamjeni stavku" id="{{ $equipment->id }}" ><i class="fas fa-exchange-alt"></i></span>
                                            @endif 
                                        @endif
                                        @if (Sentinel::inRole('administrator') ) 
                                            <a href="{{ route('equipment_lists.destroy', $equipment->id) }}" class="action_confirm btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}" title="obriši stavku">
                                                <i class="fas fa-trash-alt"></i>
                                            </a> 
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
            @if ( Sentinel::inRole('priprema')  ||Sentinel::inRole('administrator')  )
                @csrf
                <input class="btn btn-lg btn-primary store_changes" type="submit" value="Spremi">
            @endif
        </form>
        <form class="create_item" accept-charset="UTF-8" role="form" method="post" action="{{ route('equipment_lists.store') }}">
            <p>Zamjena stavke</p>
            <input name="preparation_id" id="preparation_id"  value="{{ $preparation_id }}" hidden />
            <input name="product_number" id="product_number" placeholder="Upiši broj produkta..." value="{{ old('product_number') }}" required/>
            <input name="mark" id="mark" placeholder="Upiši oznaku..." value="{{ old('mark') }}" required />
            <input name="name" id="name" placeholder="Upiši naziv..." value="{{ old('name') }}" required/>
            <input name="unit" id="unit" placeholder="Upiši jmj..." value="{{ old('unit') }}" required/>
            <input name="quantity" id="quantity" placeholder="Upiši količinu..." value="{{ old('quantity') }}" required />
            @if ( $equipments->where('level1',1)->first())
                <input name="stavka_id_level1" id="stavka_id_level1" placeholder="Upiši stavku level1..." value="{{ old('stavka_id_level1') }}"  />
                <input name="stavka_id_level2" id="stavka_id_level2" placeholder="Upiši stavku level2..." value="{{ old('stavka_id_level2') }}" />
            @endif
            {{ csrf_field() }}    
            <input type="submit" class="btn btn-lg btn-primary store_changes" value="Upiši" />
        </form>
       
      {{--   @if ( $equipments->where('level1',1)->first())
            <form class="create_item_siemens" accept-charset="UTF-8" role="form" method="post" action="{{ route('equipment_lists.store') }}">
                <p>Unos nove stavke</p>
                <input name="preparation_id" id="preparation_id1"  value="{{ $preparation_id }}" hidden />
                <input name="product_number" id="product_number1" placeholder="Upiši broj produkta..." value="{{ old('product_number') }}" required/>
                <input name="mark" id="mark1" placeholder="Upiši oznaku..." value="{{ old('mark') }}" />
                <input name="name" id="name1" placeholder="Upiši naziv..." value="{{ old('name') }}" required/>
                <input name="unit" id="unit1" placeholder="Upiši jmj..." value="{{ old('unit') }}" required/>
                <input name="quantity" id="quantity1" placeholder="Upiši količinu..." value="{{ old('quantity') }}" required />
                <input name="stavka_id_level1" id="stavka_id_level1" placeholder="Upiši stavku level1..." value="{{ old('stavka_id_level1') }}"  />
                <input name="stavka_id_level2" id="stavka_id_level2" placeholder="Upiši stavku level2..." value="{{ old('stavka_id_level2') }}" />
                
                {{ csrf_field() }}    
                <input type="submit" class="btn btn-lg btn-primary store_changes" value="Upiši" />
            </form>
        @endif --}}
        <a href="#first_anchor"><i class="fas fa-arrow-up" ></i></a>
    </div>
</div>
<script>
    var equipment_lists_height = $('.equipment_lists').height();
    var id; // item id
    var el_replace;
    var id_row;
    var color;
    var status;
    var inputs = $(".text_preparation.delivered > input");
    
    init();

    function init () {
        line_color ();
        delivered_change_color();
        filter_color();
        $('.collapsible').click(function(event){    
            $(this).next('.content').toggle();
        });
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
        $('.action_confirm2').click(function(){
            if( confirm("Sigurno želiš zamjeniti stavku?") ) {

                $('.create_item').show();
                $('.create_item_siemens').remove();
                $('.equipment_lists').scrollTop(equipment_lists_height);
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
        /* $('.create_item_siemens').submit(function(e){
            e.preventDefault();
            var preparation_id = $('#preparation_id1').val();
            var product_number = $('#product_number1').val();
            var mark = $('#mark1').val();
            var name = $('#name1').val();
            var unit = $('#unit1').val();
            var quantity = $('#quantity1').val();
            var stavka_id_level1 = $('#stavka_id_level1').val();
            var stavka_id_level2 = $('#stavka_id_level2').val();
            var token = $('meta[name="csrf-token"]').attr('content');
         
            $.ajax({
                url: location.origin+'/addItem', 
                type: 'post',
                data: {
                        '_token':  token,
                        'preparation_id': preparation_id,
                        'product_number': product_number,
                        'mark': mark,
                        'name': name,
                        'unit': unit,
                        'quantity': quantity,
                        'replaced_item_id': id,
                        'stavka_id_level1': stavka_id_level1,
                        'stavka_id_level2': stavka_id_level2                
                    }
            })
            .done(function( msg ) {
                if(id != undefined) {
                    var url_update = location.origin + '/equipment_lists/' + id +'/edit/';
                    $.ajax({
                        type: 'POST',
                        url: location.origin+'/replaceItem',
                        data: {'id':id,
                                '_token':  $('meta[name="csrf-token"]').attr('content') },
                        success: function(data){
                            location.reload();
                        },
                    });
                } else {
                    location.reload();
                }
               
                alert( "Stavka je spremljena!" );
            })
            .fail(function(data) {
                alert( "Spremanje nije uspjelo" );
                console.log(data);
            })
        }); */
        $('.create_item').submit(function(e){
            e.preventDefault();
            var preparation_id = $('#preparation_id').val();
            var product_number = $('#product_number').val();
            var mark = $('#mark').val();
            var name = $('#name').val();
            var unit = $('#unit').val();
            var quantity = $('#quantity').val();
            var token = $('meta[name="csrf-token"]').attr('content');
            var url_update = location.origin + '/equipment_lists/' + id +'/edit/';   
            var stavka_id_level1 = null;
            var stavka_id_level2 = null;
            if( $('#stavka_id_level1')) {
                stavka_id_level1 = $('#stavka_id_level1').val();
            }
            if( $('#stavka_id_level1')) {
                stavka_id_level2 = $('#stavka_id_level2').val();
            }
            $.ajax({
                url:  location.origin +'/addItem', 
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
                console.log(msg);
                $('.tbody').load(location.href + ' .tbody>div');

            })
            .fail(function() {
                alert( "Spremanje nije uspjelo" );
            })
        });
        $('.arrow_down').click(function(){
            $( this ).siblings('.delivered_history').toggle();
        });
        $(inputs).keypress(function(e){
            if (e.keyCode == 13){
                inputs[inputs.index(this)+1].focus();
                e.preventDefault();
            }
        });
    }
    
    function line_color() {
        var delivered = 0;
        var quantity = 0;
        $.each( $('.row_preparation_text'), function( index, value ) {
            if( $( this ).hasClass('removed_item') || $( this ).hasClass('item_level1') || $( this ).hasClass('item_level2') ) {
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
    }
    
    function delivered_change_color() {
        $('.text_preparation.delivered>input:first-child').change(function(){
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
    }

    function filter_color() {
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

        $.each($('.row_preparation_text'), function( index, value ) {
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
    }
   
    $.getScript('/../js/filter.js');
    
            /*     $.ajax({
                    type: 'POST',
                    url:  location.origin +'/replaceItem',
                    data: {'id':id,
                            '_token':  $('meta[name="csrf-token"]').attr('content') },
                    success: function(data){
                        location.reload();
                    },
                    error: function(jqXHR,error, errorThrown) {  
                        console.log(jqXHR);
                        console.log(jqXHR.getMessage());
                }
                }); */
</script>
@stop