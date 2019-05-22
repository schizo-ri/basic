@extends('Centaur::layout')

@section('title', __('basic.modules'))

@section('content')
    <div class="page-header">
        @if(Sentinel::getUser()->hasAccess(['modules.create']))
			<div class='btn-toolbar pull-right'>
				<a class="btn btn-primary btn-lg" href="{{ route('modules.create') }}">
					<i class="fas fa-plus"></i>
					@lang('basic.add_module')
				</a>
			</div>
		@endif
        
        <h1>@lang('basic.modules')</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
				@if(count($modules))
					<table id="index_table" class="display table table-hover">
						<thead>
							<tr>
								<th>@lang('basic.name')</th>
								<th>@lang('basic.description')</th>
								<th>@lang('basic.options')</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($modules as $module)
								<tr>
									<td>{{ $module->name }}</td>
									<td>{{ $module->description }}</td>
									<td class="center">
										@if(Sentinel::getUser()->hasAccess(['modules.update']))
											<a href="{{ route('modules.edit', $module->id) }}" class="btn-edit">
												 <i class="far fa-edit"></i>
											</a>
										@endif
										@if(Sentinel::getUser()->hasAccess(['modules.delete']))
											<a href="{{ route('modules.destroy', $module->id) }}" class="btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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
