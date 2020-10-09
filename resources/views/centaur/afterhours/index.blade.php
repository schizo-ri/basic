@extends('Centaur::admin')

@section('title', __('basic.afterhours'))

@section('content')
	<header class="page-header">
		<div class="index_table_filter">
			<select id="filter_month" class="select_filter change_month_afterhour" >
				{{-- <option value="all">@lang('basic.all_month')</option> --}}
			
					@foreach ($dates as $date)
						<option value="{{ $date }}">{{ $date }}</option>
					@endforeach
			
			</select>
			<select class="change_employee_work select_filter ">
				<option value="" selected>{{ __('basic.view_all')}} </option>
				
					@foreach ($employees as $employee)
						<option value="empl_{{ $employee->id }}">{{ $employee->user->first_name . ' ' . $employee->user->last_name }}</option>
					@endforeach
			</select>
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['afterhours.create']) || ($permission_dep && in_array('afterhours.view', $permission_dep)))
				<a class="btn-new" href="{{ route('afterhours.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if( count($afterhours))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.fl_name')</th>
							<th>@lang('basic.date')</th>
							<th>@lang('absence.time')</th>
							<th>@lang('absence.approve_h')</th>
							<th>@lang('basic.project')</th>
							<th>@lang('basic.comment')</th>
							<th>@lang('absence.approved')</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($afterhours as $afterhour)
							<tr class="empl_{{ $afterhour->employee_id }}">
								<td>{{ $afterhour->employee->user->first_name . ' ' . $afterhour->employee->user->last_name }}</td>
								<td>{!! $afterhour->date ? date('d.m.Y', strtotime($afterhour->date)) : '' !!}</td>
								<td>{!! $afterhour->start_time ? date('H:s', strtotime($afterhour->start_time))  : '' !!} - {!! $afterhour->end_time ? date('H:s', strtotime($afterhour->end_time)) : '' !!}</td>
								<td>{!! $afterhour->approve_h ? date('H:s', strtotime($afterhour->approve_h))  : '' !!}</td>
								<td>{{ $afterhour->project->id }}</td>
								<td>{{ $afterhour->comment }}</td>
								<td>{!! $afterhour->approve == 1 ? 'odobreno' : '' !!}{!! $afterhour->approve == 0 ? ' nije odobreno' : '' !!}</td>
								<td class="center">
									@if(Sentinel::getUser()->hasAccess(['afterhours.update']) || in_array('afterhours.update', $permission_dep))
										<a href="{{ route('afterhours.edit', $afterhour->id) }}" class="btn-edit" rel="modal:open">
												<i class="far fa-edit"></i>
										</a>
									@endif
									@if( Sentinel::getUser()->hasAccess(['afterhours.delete']) || in_array('afterhours.delete', $permission_dep))
										<a href="{{ route('afterhours.destroy', $afterhour->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
											<i class="far fa-trash-alt"></i>
										</a>
									@endif
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@else
				<p class="no_data">@lang('basic.no_data')</p>
			@endif
		</div>
	</main>
@stop