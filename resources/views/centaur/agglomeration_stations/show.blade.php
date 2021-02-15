@extends('Centaur::layout')

@section('title', 'Aglomeracija ' .$agglomeration->name )

@section('content')
<div class="page-header">
    <a class="link_back " href="{{ route('contracts.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Ugovori</a>
    <a class="link_back " href="{{ route('agglomerations.show', $agglomeration->contract_id) }}"> | Popis aglomeracija</a>
    <div class='btn-toolbar pull-right'>
        <label class="filter_empl">
            <input type="search" placeholder="Traži..." onkeyup="mySearchTable()" id="mySearchTbl">
            <i class="clearable__clear">&times;</i>
        </label>
        @if(Sentinel::getUser()->hasAccess(['agglomeration_stations.create']))
            <a href="{{ route('agglomeration_stations.create', ['agglomeration_id' =>  $agglomeration->id]) }}" rel="modal:open"><img class="" src="{{ URL::asset('icons/plus.png') }}" alt="arrow" /></a>
        @endif
    </div>
    <h1>Aglomeracija {{ $agglomeration->name }} - popis stanica</h1>
    <h4>Ukupno utrošeno {{ number_format( $sum_agglomerations, 2, ',','.' ) }} Kn</h4>
    @php
        $sum_stations =0;
    @endphp
</div>
<div class="contracts">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <form class="form_project" accept-charset="UTF-8" role="form" method="post" action="{{ route('updateStation') }}">
                <table class="table table-hover table_projects" id="index_table">
                    <thead>
                        <tr>
                            <th class="col-md-3">Aglomeracija</th>
                            <th class="col-md-3">Naziv stanice @if(Sentinel::getUser()->hasAccess(['agglomeration_stations.create']))<i class="fas fa-pencil-alt"></i>@endif</th>
                            <th class="col-md-2">Voditelj</th>
                            <th class="col-md-2">Projektant</th>
                            <th class="col-md-1">Iznos stanice</th>
                            <th class="col-md-1">Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agglomerationStations as $station)
                            @php
                                $sum_stations = $station->hasList->sum(function ($row) {
                                    return $row->quantity * $row->price;
                                });
                            @endphp
                            <tr class="" id="project_{{ $station->id }}">
                                <td class="col-md-3" ><span class="value" title="name">{{ $agglomeration->name }}</span></td>
                                <td class="{!! Sentinel::getUser()->hasAccess(['agglomeration_stations.create']) ? 'edit_name editable' : '' !!} col-md-3" ><span class="value" title="name">{{ $station->name }}</span></td>
                                <td class="col-md-2" ><span class="value" title="manager">{{ $agglomeration->managerUser->first_name . ' ' . $agglomeration->managerUser->last_name }}</span></td>
                                <td class="col-md-2"><span class="value" title="designer">{{ $agglomeration->designerUser->first_name . ' ' . $agglomeration->designerUser->last_name }}</span></td>
                                <td class="col-md-1"><span class="value" title="">{{ number_format($sum_stations, 2, ',','.') }} Kn</span></td>
                                <td class="col-md-1">
                                    <a href="{{ route('agglomeration_station_lists.show', $station->id) }}" >Lista</a>
                                    @if(Sentinel::getUser()->hasAccess(['agglomeration_stations.delete']))
                                        <a href="{{ route('agglomeration_stations.destroy', $station->id) }}" class="action_confirm btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}">
                                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                            Obriši
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
<script>	
    $.getScript('/../js/filter.js');
    $.getScript('/../js/project.js');
</script>
@stop