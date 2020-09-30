@extends('Centaur::admin')

@section('title', __('basic.work_records'))

@section('content')
	@php
		use App\Models\WorkRecord;
		if(isset($_GET['date'])) {
			$request_date = $_GET['date'];
		} else {
			$request_date = date('Y-m-d');
		}
	@endphp
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
			<a class="change_view2" href="{{ route('work_records.index') }}" ></a>
			<select class="change_month select_filter ">
				@foreach ($months as $month)
					<option value="{{ $month }}">{{ date('Y m',strtotime($month))}}</option>
				@endforeach
			</select>
			<select class="change_employee_work select_filter ">
				<option value="" selected>{{ __('basic.view_all')}} </option>
				@foreach ($employees as $employee)
					<option value="empl_{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->last_name }}</option>
				@endforeach
			</select>
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12 main_work_records">
		<div class="second_view">
			<div class="table-responsive1">
				<table id="index_table1" class="display table table_work_record" style="width: 100%;">
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
							<th class="ime">Ukupno vrijeme</th>
						</tr>
					</thead>
					<tbody class="second">
						@foreach($employees as $employee)
							@php
								$minutes = 0;
								$hours = 0;
							@endphp
							<tr class="second empl_{{ $employee->id }}">
								<td>
									<a href="{{ route('work_records.show', ['id'=>$employee->id, 'date' => $request_date ]) }}" {{-- target="_blank"  --}}{{-- rel="modal:open" --}}>
										{{ $employee->user['last_name'] . ' ' . $employee->user['first_name'] }}
									</a>
								</td>
								@foreach($list as $day2)
									<?php 
										$dan2 = date('Y-m-d', strtotime($day2)); 
								
										$work = $employee->hasWorkingRecord->where('start','>', $dan2.' 00:00:00')->where('start','<', $dan2.' 23:59:59')->first();
										if($work) {
											if($work->end) {
												$interval = date_diff(date_create($work->start),date_create($work->end));
												$work->interval = date('H:i',strtotime( $interval->h .':'.$interval->i));
												$minutes += $interval->h * 60; 
												$minutes += $interval->i; 
											} 
										}
										if(count($absences ) >0) {
											$absence_employee = $absences->where('employee_id',  $employee->id);
										} else {
											$absence_employee = null;
										}
									?>
									<td class="td_izostanak {!! isset($work->interval) ? 'red_rad' : '' !!}">
										@if($absence_employee)
											@foreach ($absence_employee as $absence)
												@if($absence->absence['mark']!= 'IZL')
												
													@if(in_array($dan2, $absence->days))
														@php
															$minutes += 8*60;
														@endphp
														<span>{{ $absence->absence['mark']}}</span>
														{{ '08:00' }}
													@endif
												@endif
											@endforeach
										@endif
										@if ( isset($work->interval) && $work->interval )
											<span>RR</span>
											{{  $work->interval }}
										@endif
									</td>
								@endforeach
								@php
									if($minutes > 0) {
										$hours = floor($minutes / 60);
										$minutes -= $hours * 60;
									}
								@endphp
								<td>{{ sprintf('%02d:%02d', $hours, $minutes) }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>	
		</div>
	</main>
	<script>
	/* 	$.getScript( '/../js/work_records.js'); */
	</script>
@stop