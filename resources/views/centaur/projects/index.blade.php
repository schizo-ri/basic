@extends('Centaur::layout')

@section('title', 'Projekti')

@section('content')
    <div class="page-header">
        <div class='btn-toolbar pull-right'>
            <label class="filter_empl">
                <input type="search" placeholder="Traži..." onkeyup="mySearchTable()" id="mySearchTbl">
                <i class="clearable__clear">&times;</i>
            </label>
            <a href="{{ route('projects.create') }}" rel="modal:open"><img class="" src="{{ URL::asset('icons/plus.png') }}" alt="arrow" /></a>
        </div>
        <h1>Projekti</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover" id="index_table">
                    <thead>
                        <tr>
                            <th>Broj</th>
                            <th>Naziv</th>
                            <th>Planirani početak</th>
                            <th>Datum isporuke</th>
                            <th>Procjenjeno trajanje [h]</th>
                            <th>Dnevni sati rada</th>
                            <th>Rad subotom</th>
                            <th>Kategorije djelatnika</th>
                            <th>Opcije</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $project)
                        @php
                            $categories = explode(',', $project->categories);
                        @endphp
                            <tr>
                                <td>{{ $project->project_no }}</td>
                                <td>{{ $project->name }}</td>
                                <td>{{ $project->start_date  }}</td>
                                <td>{!! $project->end_date != '0000-00-00' && $project->end_date != null ? $project->end_date : ''  !!}</td>
                                <td>{{ $project->duration  }}</td>
                                <td>{{ $project->day_hours  }}</td>
                                <td>{!! $project->saturday == 1 ? 'DA' : 'NE' !!}</td>
                                <td>
                                    @foreach ($categories as $category)
                                        @if ($category != "" && $category != 0)
                                            <span> {{ $categoryEmp->where('id', $category )->first()->mark }} </span>     
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    <a href="{{ route('projects.edit', $project->id) }}" class="btn" rel="modal:open">
                                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                        Edit
                                    </a>
                                    <a href="{{ route('projects.destroy', $project->id) }}" class="action_confirm btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}">
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<script>	
    $.getScript('/../js/filter.js');
</script>
@stop
