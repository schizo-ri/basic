@extends('Centaur::layout')

@section('title', 'Ugovorna lista')

@section('content')
<div class="preparation">
    <div class="page-header">
        <a class="link_back " href="{{ route('contracts.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Ugovori</a>
        <a class="link_back " href="{{ route('agglomerations.show', $contract->id) }}"> | Popis aglomeracija</a>

        <div class='btn-toolbar pull-right'>
            <a href="{{ route('contracts.edit', $contract->id ) }}" class="btn" rel="modal:open">
                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                Ispravi
            </a>
            <a href="{{ route('contracts.destroy', $contract->id) }}" class="action_confirm btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}">
                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                Obriši ugovor
            </a>
            <label class="filter_empl">
                <input type="search" placeholder="Traži..." onkeyup="mySearchTable()" id="mySearchTbl">
                <i class="clearable__clear">&times;</i>
            </label>
        </div>
        <h1>Ugovor {{ $contract->number }} - lista OEM materijala </h1>
        @php
            $sum = $contract->hasList->sum(function ($row) {
                return $row->quantity * $row->price;
            });
        @endphp
        <h4>Iznos ugovora {{ number_format( $sum, 2, ',','.' ) }} Kn</h4>
    </div>
    <div class="">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover table_projects" id="index_table">
                    <thead>
                        <tr>
                            {{-- <th>Podgrupa</th> --}}
                            <th>Referenca</th>
                            <th>Opis</th>
                            <th class="align_right">Količina</th>
                            <th class="align_right">OEM cijena</th>
                            <th class="align_right">Ukupan iznos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contract->hasList->groupBy('group') as $group)
                            @php
                                $sum_group = $group->sum(function ($row) {
                                    return $row->quantity * $row->price;
                                });
                            @endphp
                            <tr class="background_green font_bold">
                                <td  colspan="3">{{ $group->first()->group }}</td>
                                <td class="align_right" colspan="2">{{ number_format( $sum_group, 2, ',','.' ) . ' Kn' }}</td>
                            </tr>
                            @foreach ( $group as $item)
                                <tr class="">
                                    {{-- <td>{{ $item->group }}</td> --}}
                                    <td>{{ $item->reference }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td class="align_right">{{$item->quantity }}</td>
                                    <td class="align_right">{{  number_format( $item->price,2,',','.' ) }}</td>
                                    <td class="align_right">{{ number_format( $item->quantity * $item->price,2,',','.') }}</td>
                                </tr>
                            @endforeach
                                
                        @endforeach
                    </tbody>
                </table>
               
            </div>
        </div>
    </div>
</div>
<script>	
    $.getScript('/../js/filter.js');
</script>
@stop
