@extends('Centaur::layout')

@section('title', 'Kategorije')

@section('content')
    <div class="page-header">
        <a href="{{ route('employees.index') }}">&#x2190; Djelatnici</a>
        <div class='btn-toolbar pull-right'>
            <a href="{{ route('category_employees.create') }}" rel="modal:open"><img class="" src="{{ URL::asset('icons/plus.png') }}" alt="arrow" /></a>
        </div>
        <h1>Kategorije</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Oznaka</th>
                            <th>Opis</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->mark }}</td>
                                <td>{{ $category->description  }}</td>
                                <td>
                                    <a href="{{ route('category_employees.edit', $category->id) }}" class="btn btn-default" rel="modal:open">
                                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                        Edit
                                    </a>
                                    <a href="{{ route('category_employees.destroy', $category->id) }}" class="action_confirm btn btn-danger" data-method="delete" data-token="{{ csrf_token() }}" >
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
