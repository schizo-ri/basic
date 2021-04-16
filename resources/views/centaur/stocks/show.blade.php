@extends('Centaur::layout')

@section('title', 'Stanje skladišta')

@section('content')
    <div class="page-header">
        <a class="link_back " href="{{ route('stocks.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Skladište</a>
        <div class='btn-toolbar pull-right'>
            <label class="filter_empl">
                <input type="search" placeholder="Traži..." onkeyup="mySearchTable()" id="mySearchTbl">
                <i class="clearable__clear">&times;</i>
            </label>
            @if(Sentinel::getUser()->hasAccess(['discharge_stocks.create']))
                @if( ($item->quantity - $item->hasDischarges->sum('quantity')) > 0 )
                    <a href="{{  route('discharge_stocks.create', ['stock_id' => $item->id]) }}" rel="modal:open"><img class="" src="{{ URL::asset('icons/plus.png') }}" alt="arrow" /></a>
                @endif
            @endif
        </div>
        <h1>Stanje na skladištu</h1>
        <h4>{{ $item->product_number . ' - '. $item->name }}</h4>
        <h5><b>Ukupno isporučeno: {{ $item->hasDischarges->sum('quantity') }} | Preostalo: {{ $item->quantity - $item->hasDischarges->sum('quantity') }}</b></h5>
    </div>
    <div class="">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover" id="index_table">
                    <thead>
                        <tr>
                            <th>Projekt</th>
                            <th>Produkt</th>
                            <th>Oznaka</th>
                            <th>Količina</th>
                            <th>Jedinica mjere</th>
                            <th>Komentar</th>
                            <th>Razdužio</th>
                            <th>Opcije</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dischargeStock as $discharges)
                            <tr>
                                <td>{!! $discharges->preparation ? $discharges->preparation->project_no . ' ' . $discharges->preparation->project_name : '' !!}</td>
                                <td>{{ $discharges->stock->product_number }}</td>
                                <td>{{ $discharges->stock->mark }}</td>
                                <td>{{ $discharges->quantity }}</td>
                                <td>{{ $discharges->stock->unit }}</td>
                                <td>{{ $discharges->comment }} {!! $discharges->missing == 1 ? '<span class="bg_yellow">Nedostaje</span>' : '' !!} {!! $discharges->damaged == 1 ? '<span class="bg_yellow">Neispravno</span>' : '' !!}</td>
                                <td>{!! $discharges->user_id ? $discharges->user->first_name . ' ' . $discharges->user->last_name : '' !!}</td>
                                <td class="not_link">
                                    @if(Sentinel::getUser()->hasAccess(['discharge_stocks.update']))
                                        <a href="{{ route('discharge_stocks.edit', $discharges->id) }}" class="btn " rel="modal:open">
                                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                            Edit
                                        </a>
                                    @endif
                                    @if(Sentinel::getUser()->hasAccess(['discharge_stocks.delete']))
                                        <a href="{{ route('discharge_stocks.destroy', $discharges->id) }}" class="action_confirm btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}">
                                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                            Delete
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
