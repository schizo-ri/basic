@extends('Centaur::layout')

@section('title', __('absence.absences'))

@section('content')
<div class="row">  
	<div class="page-header">
		<a href="{{ route('absences.index') }}"  class="load_page" >@lang('absence.absences')</a> / 
		<a href="{{ route('absence_types.index') }}"  class="load_page">@lang('absence.abs_types')</a>
		<div class='btn-toolbar pull-right'>
			@if(isset($empl))
				<a class="btn btn-primary btn-lg" href="{{ route('absences.create', ['emplyee' => $empl]) }}">
			@else
				<a class="btn btn-primary btn-lg" href="{{ route('absences.create') }}">
			@endif
				<i class="fas fa-plus"></i>
				@lang('absence.add_absence')
			</a>
        </div>
        <h1>@lang('absence.absences') </h1>
    </div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($absences))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.fl_name')</th>
							<th>@lang('absence.request_type')</th>
							<th>@lang('absence.start_date')</th>
							<th>@lang('absence.end_date')</th>
							<th>Period</th>
							<th>@lang('absence.time')</th>
							<th>@lang('basic.comment')</th>
							<th>@lang('absence.aprove')</th>
							<th>@lang('absence.aproved')</th>
							<th>@lang('absence.aprove_date')</th>
							
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($absences as $absence)
							<tr>
								<td>{{ $absence->employee->user['first_name'] . ' ' . $absence->employee->user['last_name'] }}</td>
								<td>{{ '[' . $absence->absence['mark'] . '] ' . $absence->absence['name'] }}</td>
								<td>{{ $absence->start_date }}</td>
								<td>{{ $absence->end_date }}</td>
								<td>xx dana</td>
								<td>{{ $absence->start_time . '-' .  $absence->end_time }}</td>
								<td>{{ $absence->comment }}</td>
								<td>{{ $absence->approve . ' ' . $absence->approve_reason }}</td>
								<td>{{ $absence->approved['first_name'] . ' ' . $absence->approved['last_name'] }}</td>
								<td>{{ $absence->approved_date }}</td>
								<td class="center">
									@if(Sentinel::getUser()->hasAccess(['absences.update']) || in_array('absences.update', $permission_dep) )
										<a href="{{ route('absences.edit', $absence->id) }}" class="btn-edit">
											 <i class="far fa-edit"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['absences.delete']) || in_array('absences.delete', $permission_dep))
										<a href="{{ route('absences.destroy', $absence->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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
<script type="text/javascript" src="{{ URL::asset('dataTables/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dataTables/JSZip-2.5.0/jszip.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dataTables/pdfmake-0.1.36/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dataTables/pdfmake-0.1.36/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dataTables/Buttons-1.5.6/js/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('js/datatables.js') }}"></script>
@stop