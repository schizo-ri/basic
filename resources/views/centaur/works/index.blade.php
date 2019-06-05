@extends('Centaur::layout')

@section('title', __('basic.works'))

@section('content')
<div class="row">
    <div class="page-header">
        <div class='btn-toolbar pull-right'>
			@if(Sentinel::getUser()->hasAccess(['works.create']) || in_array('works.create', $permission_dep))
			    <a class="btn btn-primary btn-lg" href="{{ route('works.create') }}">
					<i class="fas fa-plus"></i>
					@lang('basic.add_work')
				</a>
			@endif
        </div>
        <h1>@lang('basic.works')</h1>
    </div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($works))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.department')</th>
							<th>@lang('basic.name')</th>
							<th>@lang('basic.job_description')</th>
							<th>@lang('basic.director')</th>
							<th>@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($works as $work)
							<tr>
								<td>{{ $work->department['name'] }}</td>
								<td>{{ $work->name }}</td>
								<td>{{ $work->job_description }}</td>
								<td>{{ $work->employee['first_name'] . ' ' . $work->employee['last_name'] }}</td>
								<td class="center">
									@if(Sentinel::getUser()->hasAccess(['works.update']) || in_array('works.update', $permission_dep))
										<a href="{{ route('works.edit', $work->id) }}" class="btn-edit">
											 <i class="far fa-edit"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['works.delete']) || in_array('works.delete', $permission_dep) && !$employees->where('work_id',$work->id)->first())
										<a href="{{ route('works.destroy', $work->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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
<!-- Datatables -->
		<link rel="stylesheet" type="text/css" href="{{ URL::asset('dataTables/datatables.min.css') }}"/>
		
		<script type="text/javascript" src="{{ URL::asset('dataTables/datatables.min.js') }}"></script>
		<script type="text/javascript" src="{{ URL::asset('dataTables/JSZip-2.5.0/jszip.min.js') }}"></script>
		<script type="text/javascript" src="{{ URL::asset('dataTables/pdfmake-0.1.36/pdfmake.min.js') }}"></script>
		<script type="text/javascript" src="{{ URL::asset('dataTables/pdfmake-0.1.36/vfs_fonts.js') }}"></script>
		<script type="text/javascript" src="{{ URL::asset('dataTables/Buttons-1.5.6/js/buttons.print.min.js') }}"></script>
		<script src="{{ URL::asset('js/datatables.js') }}"></script>
@stop