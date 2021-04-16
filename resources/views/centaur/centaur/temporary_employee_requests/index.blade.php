@extends('Centaur::admin')

@section('title', __('basic.temporary_employee_requests'))

@section('content')
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['temporary_employee_requests.create']) || in_array('temporary_employee_requests.create', $permission_dep))
				<a href="{{ route('temporary_employee_requests.create') }}" class="btn-new " title="{{ __('basic.add_absence')}}" " rel="modal:open" >
					<i class="fas fa-plus"></i>
				</a>
			@endif
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($temporary_employee_requests) > 0)
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.fl_name')</th>
							<th class="sort_date">@lang('absence.request_date')</th>
							<th>@lang('absence.request_type')</th>
							<th class="sort_date">@lang('absence.start_date')</th>
							<th class="sort_date">@lang('absence.end_date')</th>
							<th>@lang('absence.time')</th>
							<th>@lang('basic.comment')</th>
							<th>@lang('absence.approved')</th>
							<!--<th>@lang('absence.aproved_by')</th>
							<th>@lang('absence.aprove_date')</th>-->
							<th class="not-export-column no-sort"></th>
						</tr>
					</thead>
					<tbody>
						@foreach ($temporary_employee_requests as $requests)
							<tr>
								<td>{{ $requests->employee->user['first_name'] . ' ' . $requests->employee->user['last_name'] }}</td>
								<td>{{ date('d.m.Y.',strtotime($requests->created_at)) }}</td>
								<td>{{ '[' . $requests->absence_type['mark'] . '] ' . $requests->absence_type['name'] }}</td>
								<td>{{ date('d.m.Y.',strtotime($requests->start_date))  }}</td>
								<td>{{  date('d.m.Y.',strtotime($requests->end_date))  }}</td>
								<td>{{ date('H:i',strtotime($requests->start_time)) . '-' .  date('H:i',strtotime($requests->end_time)) }}</td>
								<td>{{ $requests->comment }}</td>
								<td class="approve">
									@if($requests->approve == 1) 
										<span class="img_approve"><span>@lang('absence.approved')</span></span>
									@endif
									@if($requests->approve == "0") 
										<span class="img_denied"><span>@lang('absence.not_approved')</span></span>
									@endif
								</td>
								<td>
									@if(Sentinel::getUser()->hasAccess(['temporary_employee_requests.update']) || in_array('temporary_employee_requests.update', $permission_dep))
										<a href="{{ route('temporary_employee_requests.edit',$requests->id ) }}" class="edit_service btn-edit" title="{{ __('basic.edit')}}" rel="modal:open" >
											<i class="far fa-edit"></i>
										</a>
									@endif
									@if( Sentinel::getUser()->hasAccess(['temporary_employee_requests.delete']) || in_array('temporary_employee_requests.delete', $permission_dep))
										<a href="{{ route('temporary_employee_requests.destroy', $requests->id) }}" class="action_confirm btn-delete danger edit_service " data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
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