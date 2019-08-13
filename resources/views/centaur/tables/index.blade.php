@extends('Centaur::layout')

@section('title', __('basic.tables'))

@section('content')
<div class="row">
    <div class="page-header">
        <div class='btn-toolbar pull-right'>
			@if(Sentinel::inRole('superadmin'))
			    <a class="btn btn-primary btn-lg" href="{{ route('tables.create') }}">
					<i class="fas fa-plus"></i>
					@lang('basic.add_table')
				</a>
			@endif
        </div>
		<h1>@lang('basic.tables')</h1>
    </div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($tables))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.name')</th>
							<th>@lang('basic.description')</th>
							<th>@lang('basic.emailing')</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($tables as $table)
							<tr>
								<td>{{ $table->name }}</td>
								<td>{{ $table->description }}</td>
								<td>{!! $table->emailing == '0' ? 'neaktivno' : 'aktivno' !!}</td>
								<td class="center">
									@if(Sentinel::getUser()->hasAccess(['tables.update']))
										<a href="{{ route('tables.edit', $table->id) }}" class="btn-edit">
											 <i class="far fa-edit"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['tables.delete']))
										<a href="{{ route('tables.destroy', $table->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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
@stop