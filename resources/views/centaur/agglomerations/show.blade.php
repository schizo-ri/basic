@extends('Centaur::layout')

@section('title', 'Ugovor ' . $contract->number )

@section('content')
<div class="page-header">
    <a class="link_back " href="{{ route('contracts.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Ugovori</a>
    <div class='btn-toolbar pull-right'>
        <label class="filter_empl">
            <input type="search" placeholder="Traži..." onkeyup="mySearchTable()" id="mySearchTbl">
            <i class="clearable__clear">&times;</i>
        </label>
        @if(Sentinel::getUser()->hasAccess(['agglomerations.create']))
            <a href="{{ route('agglomerations.create', ['contract_id' => $contract->id]) }}" rel="modal:open"><img class="" src="{{ URL::asset('icons/plus.png') }}" alt="arrow" /></a>
        @endif
    </div>
    <h1><a href="{{ route('contracts.show', $contract->id) }}"> Ugovor {{ $contract->number }} - popis aglomeracija </a>
    </h1>
    <span class="contract_info_show">Stanje ugovora <i class="fas fa-chevron-down"></i></span>
    <script>
        $('.contract_info_show').on('click',function(){
            $('.contract_info').toggle();
            
        });
    </script>
    <div class="contract_info">
        <table>
            <thead>
                <tr>
                    <th>Podgrupa</th>
                    <th class="align_right">Ugovoreno</th>
                    <th class="align_right">Utrošeno</th>
                    <th class="align_right">Preostalo</th>
                </tr>
            </thead>
            <tbody>
                @php
                     $sum_agglomerations = 0;
                @endphp
                @foreach ($contract->hasList->groupBy('group') as $group)
                    @php
                        $sum_group = $group->sum(function ($row) {
                            return $row->quantity * $row->price;
                        });
                        $group_name = $group->first()->group;
                        $sum_group_aglo = 0;
                       
                        foreach ($contract->hasAgglomeration as $agglomeration) {
                            foreach ($agglomeration->hasStation as $station) {
                                $sum_group_aglo += $station->hasList->sum(function ($row) use ($group_name) {
                                    if($row->group == $group_name )
                                    return $row->quantity * $row->price;
                                });
                            }
                        }
                        $sum_agglomerations +=  $sum_group_aglo;
                    @endphp
                    <tr>
                        <td>{{ $group_name }}</td>
                        <td class="align_right">{{ number_format( $sum_group, 2, ',','.' )  }}</td>
                        <td class="align_right">{{ number_format( $sum_group_aglo, 2, ',','.' )  }}</td>
                        <td class="align_right">{{ number_format( $sum_group - $sum_group_aglo, 2, ',','.' )  }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>Iznos ugovora</td>
                    <td class="align_right">{{ number_format( $sum_contract, 2, ',','.' ) }} </td>
                    <td class="align_right">{{ number_format( $sum_agglomerations, 2, ',','.' ) }}</td>
                    <td class="align_right">{{ number_format( $sum_contract - $sum_agglomerations, 2, ',','.' ) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="contracts">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <form class="form_project" accept-charset="UTF-8" role="form" method="post" action="{{ route('updateAgglomeration') }}">
                <table class="table table-hover table_projects" id="index_table">
                    <thead>
                        <tr>
                            <th class="col-md-4">Naziv @if( Sentinel::getUser()->hasAccess(['agglomerations.create']) )<i class="fas fa-pencil-alt"></i>@endif</th>
                            <th class="col-md-2">Voditelj @if( Sentinel::getUser()->hasAccess(['agglomerations.create']) )<i class="fas fa-pencil-alt"></i>@endif</th>
                            <th class="col-md-2">Projektant @if( Sentinel::getUser()->hasAccess(['agglomerations.create']) )<i class="fas fa-pencil-alt"></i>@endif</th>
                            <th class="col-md-2 align_right">Utrošeno novaca</th>
                            <th class="col-md-2 align_center">Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agglomerations as $agglomeration)
                            @php
                                $stations = $agglomeration->hasStation;
                                $sum_stations = 0;
                                foreach ($stations as $station) {
                                    $sum_stations += $station->hasList->sum(function ($row) {
                                        return $row->quantity * $row->price;
                                    });
                                } 
                            @endphp                    
                            <tr class="" id="project_{{ $agglomeration->id }}">
                                <td class="{!! Sentinel::getUser()->hasAccess(['agglomerations.create']) ? 'edit_name editable' : '' !!} col-md-4" ><span class="value" title="name">{{ $agglomeration->name }}</span></td>
                                <td class="{!! Sentinel::getUser()->hasAccess(['agglomerations.create']) ? 'edit_manager editable' : '' !!} select col-md-2" ><span class="value" title="manager">{{ $agglomeration->managerUser->first_name . ' ' . $agglomeration->managerUser->last_name }}</span></td>
                                <td class="{!! Sentinel::getUser()->hasAccess(['agglomerations.create']) ? 'edit_designer editable' : '' !!} select col-md-2"><span class="value" title="designer">{{ $agglomeration->designerUser->first_name . ' ' . $agglomeration->designerUser->last_name }}</span></td>
                                <td class="col-md-2 align_right"><span class="value" title="">{{ number_format($sum_stations, 2, ',','.') }} Kn</span></td>
                                <td class="col-md-2 align_center">
                                    <a href="{{ route('agglomeration_stations.show', $agglomeration->id) }}" >Stanice</a>
                                    @if(Sentinel::getUser()->hasAccess(['agglomerations.delete']))
                                        <a href="{{ route('agglomerations.destroy', $agglomeration->id) }}" class="action_confirm btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}">
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
<span class="json_managers" hidden>{{ json_encode($voditelji)}}</span>
<span class="json_designers" hidden>{{ json_encode($projektanti)}}</span>
<script>	
    $.getScript('/../js/filter.js');
    $.getScript('/../js/project.js');
</script>
@stop