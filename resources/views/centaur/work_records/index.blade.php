@extends('Centaur::admin')

@section('title', __('basic.work_records'))

@section('content')
	<header class="page-header work_record_header second_view_header">
		<div class="index_table_filter">
			<label>
				<input type="search" placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['work_records.create']) || in_array('work_records.create', $permission_dep))
				<a class="btn-new" href="{{ route('work_records.create') }}"  rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
		{{-- 	<a class="change_view2" href="{{ route('work_records.index') }}" ></a> --}}
			<select class="change_month select_filter">
				@foreach ($months as $month)
					<option value="{{ $month }}">{{ date('Y m',strtotime($month))}}</option>
				@endforeach
			</select>
		{{-- 	<select class="change_employee_work select_filter ">
				<option value="" selected>{{ __('basic.view_all')}} </option>
				@foreach ($employees as $employee)
					<option value="empl_{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->last_name }}</option>
				@endforeach
			</select> --}}
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12 main_work_records">
		<div class="second_view">
			<div class="table-responsive1">
				<table id="index_table" class="display table table_work_record" style="width: 100%;">
					<thead>	
						<tr>
							<th class="ime">Prezime i ime</th>
							@foreach($list as $day)
							<?php 
								$dan1 = date('D', strtotime($day));
							switch ($dan1) {
								case 'Mon':
									$dan = 'P';
									break;
								case 'Tue':
									$dan = 'U';
									break;
								case 'Wed':
									$dan = 'S';
									break;
								case 'Thu':
									$dan = 'Č';
									break;
								case 'Fri':
									$dan = 'P';
									break;
								case 'Sat':
									$dan = 'S';
									break;	
								case 'Sun':
									$dan = 'N';
									break;	
							}
							?>
								<th >{{ date('d', strtotime($day)) .' '. $dan }}</th>
							@endforeach
						
						</tr>
					</thead>
					<tbody class="second">
						@foreach($employees as $employee)
							@php
								$absence_employee = $absences->where('employee_id', $employee->id)
							@endphp
							<tr class="second empl_{{ $employee->id }}">
								<td>{{ $employee->last_name . ' ' . $employee->first_name }}</td>
								@foreach($list as $day2)
									<?php 
										$dan2 = date('Y-m-d', strtotime($day2)); 
										$hasAbsence = false;
										foreach ($absence_employee as $absence) {
											if (in_array($dan2, $absence->days) ) {
												$abs = $absence->absence['mark'];
												$hasAbsence = true;
											}
										}												
									?>
									<td class="td_izostanak">
										@if( $hasAbsence )
											<span>{{ $abs }}</span>
										@else 
											@if ( date('N', strtotime($dan2) ) < 6)
													@if( ( $employee->checkout == null && strtotime($dan2) >= strtotime($employee->reg_date) ) || ($employee->checkout != null && strtotime($dan2) < strtotime($employee->checkout) )  )
													<span>RR</span>
												@endif
											@endif
										@endif
									</td>
								@endforeach
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>	
		</div>
	</main>
@stop