@extends('Centaur::layout')

@section('title', __('absence.abs_type'))

@section('content')
<div class="row">
    <div class="page-header">
		<a href="{{ route('absences.index') }}"  class="load_page" >@lang('absence.absences')</a> /
		<a href="{{ route('absence_types.index') }}"  class="load_page">@lang('absence.abs_types')</a>
		<div class='btn-toolbar pull-right'>
			@if(Sentinel::getUser()->hasAccess(['absence_types.create']) || in_array('absence_types.view', $permission_dep))
				<a class="btn btn-primary btn-lg" href="{{ route('absence_types.create') }}">
					<i class="fas fa-plus"></i>
					@lang('absence.add_abs_type')
				</a>
			@endif
        </div>
        <h1>@lang('absence.abs_type')</h1>
    </div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($absenceTypes))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.name')</th>
							<th>@lang('absence.mark')</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($absenceTypes as $absenceType)
							<tr>
								<td>{{ $absenceType->name }}</td>
								<td>{{ $absenceType->mark }}</td>
								<td class="center">
									@if(Sentinel::getUser()->hasAccess(['absence_types.update']) || in_array('absence_types.update', $permission_dep))
										<a href="{{ route('absence_types.edit', $absenceType->id) }}" class="btn-edit">
											 <i class="far fa-edit"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['absence_types.delete']) || in_array('absence_types.delete', $permission_dep))
										<a href="{{ route('absence_types.destroy', $absenceType->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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