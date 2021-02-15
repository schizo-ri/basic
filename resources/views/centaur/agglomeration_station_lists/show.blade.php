@extends('Centaur::layout')

@section('title', 'Lista stanice')

@section('content')
<div class="page-header">
    <a class="link_back " href="{{ route('contracts.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Ugovori</a>
    <a class="link_back " href="{{ route('agglomerations.show',  $agglomerationStation->agglomeration->contract_id) }}"> | Popis aglomeracija</a>
    <a class="link_back " href="{{ route('agglomeration_stations.show', $agglomerationStation->agglomeration_id) }}"> | Aglomeracija {{$agglomerationStation->agglomeration->name }}</a>
    <div class='btn-toolbar pull-right'>
        <label class="filter_empl">
            <input type="search" placeholder="Traži..." onkeyup="mySearchTable()" id="mySearchTbl">
            <i class="clearable__clear">&times;</i>
        </label>
        @if(Sentinel::getUser()->hasAccess(['agglomeration_stations.create']))
            <a href="{{ route('agglomeration_station_lists.create', ['station_id' =>  $agglomerationStation->id]) }}" rel="modal:open"><img class="" src="{{ URL::asset('icons/plus.png') }}" alt="arrow" /></a>
        @endif
    </div>
    <h1>Stanica {{ $agglomerationStation->name }} - lista</h1>
    <h4>Izos stanice {{ number_format( $sum_station, 2, ',','.' ) }} Kn /  {{ number_format( $sum_station *1.369863013, 2, ',','.' ) }} Kn</h4>
    @php
        $sum = 0;
    @endphp
</div>
<div class="contracts">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <form class="form_project" accept-charset="UTF-8" role="form" method="post" action="{{ route('updateList') }}">
                <table class="table table-hover table_projects" id="index_table">
                    <thead>
                        <tr>
                            <th>Referenca</th>
                            <th>Opis</th>
                            <th class="align_right">Količina  @if(Sentinel::getUser()->hasAccess(['agglomeration_station_lists.create']))<i class="fas fa-pencil-alt"></i>@endif</th>
                            <th class="align_right">OEM cijena</th>
                            <th class="align_right">Ukupan iznos</th>
                            <th class="align_right">Opcije</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agglomerationList->groupBy('group') as $group)
                            @php
                                $sum_group = $group->sum(function ($row) {
                                    return $row->quantity * $row->price;
                                });
                                $sum += $sum_group;
                            @endphp
                            <tr class="background_green font_bold group_title">
                                <td  colspan="3">{{ $group->first()->group }}</td>
                                <td class="align_right" colspan="3">{{ number_format( $sum_group, 2, ',','.' ) . ' Kn' }}</td>
                            </tr>
                            @foreach ( $group as $item)
                                <tr class="" id="project_{{ $item->id }}">
                                    {{-- <td>{{ $item->group }}</td> --}}
                                    <td>{{ $item->reference }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td  class="{!! Sentinel::getUser()->hasAccess(['agglomeration_stations.create']) ? 'edit_quantity editable' : '' !!} align_right"><span class="value" title="quantity">{{ $item->quantity }}</span></td>
                                    <td class="align_right">{{  number_format( $item->price,2,',','.' ) }}</td>
                                    <td class="align_right">{{ number_format( $item->quantity * $item->price,2,',','.') }}</td>
                                    <td class="align_right">
                                        @if(Sentinel::getUser()->hasAccess(['agglomeration_station_lists.delete']))
                                            <a href="{{ route('agglomeration_station_lists.destroy', $item->id) }}" class="action_confirm btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}">
                                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                                Obriši
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                     
                    </tbody>
                </table>
                @csrf
            </form>
        </div>
    </div>
</div>
<script>	
    $.getScript('/../js/filter.js');
    $.getScript('/../js/project.js');
</script>
@stop