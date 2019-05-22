@extends('Centaur::layout')

@section('title', __('clients.requests'))

@section('content')
    <div class="page-header">
        <div class='btn-toolbar pull-right'>
			<!-- 
			@if(Sentinel::getUser()->hasAccess(['client_requests.create']))
				<a class="btn btn-primary btn-lg" href="{{ route('client_requests.create') }}">
					<i class="fas fa-plus"></i>
					@lang('basic.add_module')
				</a>
			@endif
			-->
        </div>
        <h1>@lang('clients.requests')</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
				@if(count($client_requests))
					<table id="index_table" class="display table table-hover">
						<thead>
							<tr>
								<th>@lang('clients.client')</th>
								<th>@lang('basic.modules')</th>
								<th>@lang('basic.options')</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($client_requests as $request)
								<tr>
									<td><a href="{{ route('clients.show', $request->client_id) }}">{{ $request->client['name'] }}</a></td>
									<?php $modules_req = explode(',', $request->modules); ?>
									<td>@foreach($modules_req as $module_req ){{ $modules->where('id', $module_req)->first()->name }}<br>@endforeach</td>
									<td class="center">
										@if(Sentinel::getUser()->hasAccess(['client_requests.update']))
											<a href="{{ route('client_requests.edit', $request->id) }}" class="btn-edit">
												<i class="far fa-edit"></i>
											</a>
										@endif
										@if(Sentinel::getUser()->hasAccess(['client_requests.delete']))
											<a href="{{ route('client_requests.destroy', $request->id) }}" class="btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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
