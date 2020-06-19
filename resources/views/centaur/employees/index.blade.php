@extends('Centaur::layout')

@section('title', 'Djelatnici')

@section('content')
    <div class="page-header">
        <a href="{{ route('category_employees.index') }}">Kategorije &#x2192;</a>
        <div class='btn-toolbar pull-right'>
            <label class="filter_empl">
                <input type="search" placeholder="Traži..." onkeyup="mySearchTable()" id="mySearchTbl">
                <i class="clearable__clear">&times;</i>
            </label>
            <a href="{{ route('employees.create') }}" rel="modal:open"><img class="" src="{{ URL::asset('icons/plus.png') }}" alt="arrow" /></a>
        </div>
        <h1>Djelatnici
        </h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover" id="index_table">
                    <thead>
                        <tr>
                            <th>Ime</th>
                            <th>Prezime</th>
                            <th>Kategorija</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <tr>
                                <td>{{ $employee->first_name }}</td>
                                <td>{{ $employee->last_name  }}</td>
                                <td>@if ($employee->category)
                                        {{ $employee->category['mark'] . ' | ' .   $employee->category['description']  }}</td>
                                    @endif
                                <td>
                                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn " rel="modal:open">
                                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                        Edit
                                    </a>
                                    <a href="{{ route('employees.destroy', $employee->id) }}" class="action_confirm btn btn-delete" data-method="delete" data-token="{{ csrf_token() }}">
                                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
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
