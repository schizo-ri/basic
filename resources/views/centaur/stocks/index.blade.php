@extends('Centaur::layout')

@section('title', 'Skladište')

@section('content')
    <div class="page-header stock_header">
        <a class="link_back " href="{{ route('manufacturers.index') }}" rel="modal:open">{{-- <i class="fas fa-long-arrow-alt-left"> --}}</i>Proizvođači</a>
        <div class='btn-toolbar pull-right'>
            <label class="filter_empl">
                <input type="search" placeholder="Traži..." onkeyup="mySearchTable()" id="mySearchTbl">
                <i class="clearable__clear">&times;</i>
            </label>
            @if(Sentinel::getUser()->hasAccess(['stocks.create']))
                <a href="{{ route('stocks.create') }}" rel="modal:open"><img class="" src="{{ URL::asset('icons/plus.png') }}" alt="arrow" /></a>
                <button id="upload">Upload</button>
            @endif
        </div>
        <h1>Stanje robe na skladištu</h1>
        <h5><b>Ukupno isporučeno: {{ number_format( $sum_discharge,2,',','.') }} | Ukupno neispravno: {{ $sum_damaged }} | Ukupno nedostaje: {{ $sum_missing }} | Preostalo: {{  number_format( $sum,2,',','.') }}</b></h5>
    </div>
    <div class="stock_index">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <form class="form_project" accept-charset="UTF-8" role="form" method="post" action="{{ route('updateStock') }}">
                    <table class="table table-hover table_projects " id="index_table">
                        <thead>
                            <tr>
                                <th>Produkt</th>
                                <th>Proizvođač @if( Sentinel::getUser()->hasAccess(['stocks.update']) )<i class="fas fa-pencil-alt"></i>@endif</th>
                                <th>Naziv</th>
                                <th>Cijena</th>
                                <th>Jed.mj.</th>
                                <th>Količina</th>
                                <th>Isporučeno</th>
                                <th>Ukupna količina</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stock as $item)
                                <tr class="{!! Sentinel::getUser()->hasAccess(['stocks.update']) ? 'tr_open_link_new_page' : ''!!}" {!! Sentinel::getUser()->hasAccess(['stocks.update']) ? 'data-href="/stocks/'. $item->id .'"' : '' !!} id="stock_{{ $item->id }}" >
                                    <td><span>{{ $item->product_number }}</span></td>
                                    <td class="{!! Sentinel::getUser()->hasAccess(['stocks.update']) ? 'edit_manufacturer editable not_link' : '' !!} select">
                                        <span class="value"  title="manufacturer_id">{!! $item->manufacturer ? $item->manufacturer->name : '' !!}</span>
                                    </td>
                                    <td><span>{{ $item->name  }}</span></td>
                                    <td><span>{{ number_format( $item->price,2,',','.') }}</span></td>
                                    <td><span>{{ $item->unit  }}</span></td>
                                    <td><span>{{ number_format( $item->quantity,2,',','.')  }}</span></td>
                                    <td><span>{{ $item->hasDischarges->sum('quantity') }}</span></td>
                                    <td><span>{{ $item->quantity - $item->hasDischarges->sum('quantity') }}</span></td>
                                    <td class="not_link">
                                        @if(Sentinel::getUser()->hasAccess(['stocks.update']))
                                            @if ( ($item->quantity - $item->hasDischarges->sum('quantity')) > 0)
                                                <a href="{{ route('discharge_stocks.create', ['stock_id' => $item->id]) }}" class="btn " rel="modal:open">
                                                    <i class="fas fa-share-square"></i>
                                                    Razduži
                                                </a>
                                            @endif
                                            <a href="{{ route('stocks.edit', $item->id) }}" class="btn " rel="modal:open">
                                                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                                Edit
                                            </a>
                                        @endif
                                        @if(Sentinel::getUser()->hasAccess(['stocks.delete']))
                                            <a href="{{ route('stocks.destroy', $item->id) }}" class="action_confirm btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}">
                                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                                Delete
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @csrf
                </form>
            </div>
        </div>
    </div>
    <div class="importStockView" hidden>
        <form class="" accept-charset="UTF-8" role="form" method="post" action="{{ action('StockController@importStock') }}"  enctype="multipart/form-data">
            <span class="input_preparation for_file">
                <input type="file" style="display:none" name="file" id="file" required />
                <label for="file" class="label_file" title="Učitaj dokumenat"><i class="fas fa-upload" style="font-size: 20px;"></i></label>
                <span class="file_to_upload"></span>
            </span>
            {{ csrf_field() }}
            <input class="btn btn_spremi submit_createForm float_right" disabled type="submit" value="&#10004; Spremi">
        </form>
    </div>
    <span class="json_manufacturers" hidden>{{ json_encode($manufacturers)}}</span>
<script>	
    $.getScript('/../js/filter.js');
    $.getScript('/../js/project.js');

    $('#upload').on('click',function () {
        $('.importStockView').modal();
    })
    $('input[type=file]').on('change',function(){
        $('.submit_createForm').prop('disabled',false);
    });

</script>
@stop
