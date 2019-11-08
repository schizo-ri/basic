@extends('Centaur::layout')

@section('title', 'Projekti')

@section('content')
    <div class="page-header">
        <div class='btn-toolbar pull-right'>
            <a href="{{ route('projects.create') }}" rel="modal:open"><img class="" src="{{ URL::asset('icons/plus.png') }}" alt="arrow" /></a>
        </div>
        <h1>Projekti</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Broj</th>
                            <th>Naziv</th>
                            <th>Planirani početak</th>
                            <th>Procjenjeno trajanje [h]</th>
                            <th>Dnevni sati rada</th>
                            <th>Rad subotom</th>
                            <th>Opcije</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $project)
                            <tr>
                                <td>{{ $project->project_no }}</td>
                                <td>{{ $project->name }}</td>
                                <td>{{ $project->start_date  }}</td>
                                <td>{{ $project->duration  }}</td>
                                <td>{{ $project->day_hours  }}</td>
                                <td>{!! $project->saturday == 1 ? 'DA' : 'NE' !!}</td>
                                <td>
                                    <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-default" rel="modal:open">
                                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                        Edit
                                    </a>
                                    <a href="{{ route('projects.destroy', $project->id) }}" class="action_confirm btn btn-danger" data-method="delete" data-token="{{ csrf_token() }}">
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
@stop
