@extends('Centaur::admin')

@section('title', __('basic.afterhours'))

@section('content')
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			<input class="btn-new btn-store" type="submit" value="Spremi isplaćene sati" id="stil1">
			@if(Sentinel::getUser()->hasAccess(['afterhours.create']) || ($permission_dep && in_array('afterhours.view', $permission_dep)))
				<a class="btn-new" href="{{ route('afterhours.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
			<div class="div_select2">
				<select id="filter_month" class="select_filter change_month_afterhour " >
					{{-- <option value="all">@lang('basic.all_month')</option> --}}
						@foreach ($dates as $date)
							<option value="{{ $date }}">{{ $date }}</option>
						@endforeach
				</select>
			</div>
			<div class="div_select2">
				<select id="filter_employees" class="change_employee_afterhour select_filter filter_employees ">
					<option value="all" selected>{{ __('basic.view_all')}} </option>
						@foreach ($employees as $employee)
							<option value="{{ $employee->id }}">{{ $employee->last_name . ' ' . $employee->first_name }}</option>
						@endforeach
				</select>
			</div>
		</div>
		{{-- 	<a class="btn-new" href="{{ route('afterhours_approve') }}" >
				<i class="far fa-calendar-check"></i>
			</a> --}}
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if( count($afterhours)>0 )
			<form accept-charset="UTF-8" id="afterHourPaid" role="form" method="post" action="{{ route('paidHours') }}">
				{{ csrf_field() }}
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
								<th>@lang('basic.paid')</th>
								<th class="not-export-column">@lang('basic.options')</th>
							</tr>
						</thead>
						<tbody>
							@php
								$i = 0;
							@endphp
							@foreach ($afterhours as $afterhour)
								<tr class="empl_{{ $afterhour->employee_id }}">
									<td>{!! $afterhour->employee->user ? $afterhour->employee->user->first_name . ' ' . $afterhour->employee->user->last_name : $afterhour->employee->email !!}</td>
									<td>{!! $afterhour->date ? date('d.m.Y', strtotime($afterhour->date)) : '' !!}</td>
									<td>{!! $afterhour->start_time ? date('H:i', strtotime($afterhour->start_time))  : '' !!} - {!! $afterhour->end_time ? date('H:i', strtotime($afterhour->end_time)) : '' !!}</td>
									<td>{!! $afterhour->approve_h ? date('H:i', strtotime($afterhour->approve_h))  : '' !!}</td>
									<td>{!! $afterhour->project ? $afterhour->project->erp_id : '' !!}</td>
									<td>{{ $afterhour->comment }}</td>
									<td>{!! $afterhour->approve == 1 ? 'odobreno' : '' !!}{!! $afterhour->approve == 0 ? ' nije odobreno' : '' !!}</td>
									<td>
										@if($afterhour->approve == 1)
											<span class="">
												<input class="checkbox_Paid" type="checkbox" name="paid[{{$i}}]" {!! $afterhour->paid == 1 ? 'checked' : '' !!} value="1" >
												<input type="hidden" name="id[{{$i}}]" value="{{ $afterhour->id }}"  >
											</span>
										@endif
									</td>
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
								@php
									$i++;
								@endphp
							@endforeach
						</tbody>
					</table>
				</form>
			@else
				<p class="no_data">@lang('basic.no_data')</p>
			@endif
		</div>
	</main>
<script>
	$(function(){
		if( $('tbody tr').length == 0)  {
			$('.btn-store').hide();
		} else {
			$('.btn-store').show();
		}
		$('.btn-store').on('click', function(){
			$('form#afterHourPaid').submit();
		});
	});
</script>
@stop