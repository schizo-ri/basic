@extends('Centaur::layout')

@section('title', __('clients.clients'))

@section('content')
    <div class="page-header">
        <div class='btn-toolbar pull-right'>
			@if(Sentinel::getUser()->hasAccess(['clients.create']))
			   <a class="btn btn-primary btn-lg" href="{{ route('clients.create') }}">
					<i class="fas fa-plus"></i>
					@lang('clients.add_client')
				</a>
			@endif
        </div>
        <h1>@lang('clients.clients')</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
				@if(count($clients))
					<table id="index_table" class="display table table-hover">
						<thead>
							<tr>
								<th>@lang('basic.name')</th>
								<th>@lang('clients.address')</th>
								<th>@lang('clients.city')</th>
								<th>@lang('clients.oib')</th>
								<th>@lang('clients.fl_name')</th>
								<th>e-mail</th>
								<th>@lang('clients.phone')</th>
								<th>@lang('basic.options')</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($clients as $client)
								<tr>
									<td>{{ $client->name }}</td>
									<td>{{ $client->address }}</td>
									<td>{{ $client->city }}</td>
									<td>{{ $client->oib }}</td>
									<td>{{ $client->fl_name }}</td>
									<td>{{ $client->email }}</td>
									<td>{{ $client->phone }}</td>
									<td class="center">
										@if(Sentinel::getUser()->hasAccess(['clients.update']))
											<a href="{{ route('clients.edit', $client->id) }}" class="btn-edit">
												 <i class="far fa-edit"></i>
											</a>
										@endif
										@if(Sentinel::getUser()->hasAccess(['clients.delete']))
										<a href="{{ route('clients.destroy', $client->id) }}" class="btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
											<i class="far fa-trash-alt"></i>
										</a>
										@endif
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				@else
					@lang('basic.no_data')
				@endif
            </div>
        </div>
    </div>
<script src="{{ URL::asset('js/datatables.js') }}"></script>
@stop
