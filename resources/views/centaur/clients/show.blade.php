@extends('Centaur::layout')

@section('title', __('clients.requests'))

@section('content')
    <div class="page-header">
		<h1>{{ $client->name }}</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<table class="client_show">
				<tr>
					<td>Adresa: </td>
					<td>{{ $client->address }}</td>
				</tr>
				<tr>
					<td>Grad: </td>
					<td>{{ $client->city }}</td>
				</tr>
				<tr>
					<td>OIB: </td>
					<td>{{ $client->oib }}</td>
				</tr>
				<tr>
					<td>Ime i prezime:</td>
					<td> {{ $client->first_name . ' ' . $client->last_name }}</td>
				</tr>
				<tr>
					<td>e-mail: </td>
					<td>{{ $client->email }}</td>
				</tr>
				<tr>
					<td>Telefon: </td>
					<td>{{ $client->phone }}</td>
				</tr>
			<table>
        </div>
    </div>
<script src="{{ URL::asset('js/datatables.js') }}"></script>
@stop
