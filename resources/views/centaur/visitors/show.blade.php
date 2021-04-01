@extends('layouts.admin')

@section('title', 'Posjetitelji')

@section('content')
<div class="">
    <div class="page-header">
        <h1>Posjetitelji</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
			@if(count($visitors) > 0)
                 <table id="table_id" class="display sort_5_desc" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Ime i prezime</th>
							<th>Tvrtka</th>
							<th>E-mail</th>
                            <th>Broj kartice</th>
                            <th>Datum posjete</th>
                            <th>Povrat kartice</th>
                            <th class="not-export-column">Opcije</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        @foreach ($visitors as $visitor)
                            <tr>
                                <td>{{ $visitor->first_name . ' ' . $visitor->last_name }}</td>
								<td>{{ $visitor->company }}</td>
                                <td>{{ $visitor->email  }}</td>
                                <td>{{ $visitor->card_id  }}</td>
                                <td>{{ date('Y-m-d', strtotime($visitor->created_at))  }}</td>
                                <td>
                                    @if ($visitor->returned != null)
                                        {{ $visitor->returned  }}
                                    @else
                                        <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.visitors.update', $visitor->id) }}">
                                            <input name="returned" type="date" required>
                                          
                                            <input name="only_return" value="1" type="hidden" />
                                            {{ method_field('PUT') }}
                                            {{ csrf_field() }}
                                            <input class="btn_return" type="submit" value="&#10004;"> 
                                        </form>
                                    @endif
                                   </td>
                               
                                 <td>
                                    <a href="{{ route('admin.visitors.edit', $visitor->id) }}">
                                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                    </a>
                                    <a href="{{ route('admin.visitors.destroy', $visitor->id) }}" class="action_confirm" data-method="delete" data-token="{{ csrf_token() }}">
                                        <i class="far fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
				@else
					{{'Nema podataka!'}}
				@endif
            </div>
        </div>
    </div>
</div>
@stop