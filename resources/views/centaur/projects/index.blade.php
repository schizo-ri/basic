@extends('Centaur::layout')

@section('title', 'Projekti')

@section('content')
    <div class="page-header">
        <div class='btn-toolbar pull-right'>
            <span class="show_inactive">Prikaži neaktivne</span>
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
                <form class="form_project" accept-charset="UTF-8" role="form" method="post" action="{{ route('updateProject') }}">
                <table class="table table-hover table_projects" id="index_table">
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
                                <tr class="{!! $project->active == 1 ? 'active' : 'inactive' !!} {!! ! $project->start_date ? 'red_background' : '' !!}" id="project_{{ $project->id }}">
                                    <td>{{ $project->project_no }}</td>
                                    <td class="edit_name editable" ><span class="value" title="name">{{ $project->name }}</span></td>
                                    <td class="edit_start_date editable" ><span class="value" title="start_date">{{ $project->start_date }}</span></td>
                                    <td class="edit_end_date editable"><span class="value" title="end_date">{!! $project->end_date != '0000-00-00' && $project->end_date != null ? $project->end_date : ''  !!}</span></td>
                                    <td class="edit_duration editable"><span class="value" title="duration">{{ $project->duration  }}</span></td>
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
                                        <a href="{{ action('ProjectController@close_project', $project->id) }}" class="btn" class="action_confirm">
                                            <i class="fas fa-check"></i>
                                            @if ($project->active == 1)
                                                Završi
                                            @else
                                                Vrati
                                            @endif                                       
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
