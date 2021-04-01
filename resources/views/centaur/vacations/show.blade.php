@extends('Centaur::layout')

@section('title', __('absence.vacation_plan'))
@php
	use App\Models\Department;
@endphp
@section('content')
<div class="index_page index_documents vacation_index">
	<main class="col-md-12 index_main main_documents float_right">
		<section>
			<div class="page-header header_document">
				<a class="link_back" href="{{ url()->previous() }}" ><span class="curve_arrow_left"></span></a>
				@lang('absence.vacation_plan')
			</div>
			<main class="all_documents">
				<div class="table-responsive">
					<header class="page-header">
						<div class="index_table_filter">
							<label>
								<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
							</label>
							@if( $checked_user->hasAccess(['vacations.create']) || in_array('vacations.view', $permission_dep))
								<a class="add_new" href="{{ route('vacations.create') }}" rel="modal:open">
									<i class="fas fa-plus"></i>
								</a>
							@endif
						</div>
					</header>
					<main>
						@foreach ( $vacations as $vacation )
							<span id="no_week" class="" hidden >{{ $vacation->no_week }}</span>
							<h4>
								{{ $vacation->title }} 
								@if($checked_user->hasAccess(['vacations.update']) || in_array('vacations.update', $permission_dep))
									<a href="{{ route('vacations.edit', $vacation->id) }}" class="btn-edit" rel="modal:open">
											<i class="far fa-edit"></i>
									</a>
								@endif
								@if(count($vacation->hasPlans->where('request_id', null)) > 0 && ($checked_user->hasAccess(['vacations.create']) || in_array('vacations.view', $permission_dep))  )
									<a class="add_new create_request" href="{{ action('AbsenceController@requestsFromPlan',['vacation_id' => $vacation->id ]) }}">
										kreiraj zahtjeve
									</a>
								@endif
							</h4>
							<h6 class="margin_l_20">{{ $vacation->description }} </h6>
							@php
								$plan = json_decode($vacation->plan, true);
							@endphp	
							@foreach ( $plan as $department_id => $plan_department )
								@php
									$begin = new DateTime( $vacation->start_period );
									$end   = new DateTime( $vacation->end_period);
									$department = Department::with('hasEmployeeDepartment')->find($department_id);
									$employee_departments = $department->hasEmployeeDepartment;	
									$count_termin = 0;
								@endphp
								@if ( Sentinel::inRole('administrator') || in_array( $department_id, $checked_employee->hasEmployeeDepartmen->pluck('department_id')->toArray()) )
									<h5 class="center">{{ $department->name . '[' . $plan_department['no_people'] . ']'}}</h5>
									<table id="index_table{{ $department_id }}" class="display table table-hover vacation_plan">
										<thead>
											<tr>
												@if (Sentinel::inRole('administrator'))<th>@lang('basic.employee')</th>@endif
												@for ($i = $begin; $i < $end; $i->modify('+'. $vacation->interval .' day'))
													@php
														$date = $i->format("Y-m-d");
														$date = date_create($date );
														$date_mod = $date->modify('+'. ($vacation->interval)-1 .' day');
														$count_termin++;
													@endphp
													<th >{{ $i->format("d-m-Y") . ' - ' . $date_mod->format("d-m-Y") }}</th>
												@endfor
											</tr>
										</thead>
										<tbody>
											@if (Sentinel::inRole('administrator'))
												@foreach ($employee_departments as $employee_department)
													@if ( ! $employee_department->employee->checkout )
														@php
															$begin = new DateTime( $vacation->start_period );
															$end   = new DateTime( $vacation->end_period) ;
															$employee_plan = $vacation->hasPlans->where('employee_id', $employee_department->employee_id);
															$reg_date = new Datetime($employee_department->employee->reg_date);
															$employee_probation = $reg_date->modify('+'. $employee_department->employee->probation .' months');
														@endphp
														<tr id="empl_{{ $employee_department->employee_id }}">							
															<td class="employee_name {!! count($employee_plan) >0 ? 'bg_lightgreen': '' !!}">
																{{ $employee_department->employee->user->last_name . ' ' . $employee_department->employee->user->first_name }}
																@if( isset($plan_department['employees'] ) && in_array( $employee_department->employee_id, explode( ',', $plan_department['employees'])))
																	<i class="fas fa-users-slash"></i>
																@endif
																{{-- @if ( $employee_plan != null && $employee_plan->request_id )
																	<a href="{{ route('absences.edit', $employee_plan->request_id ) }}" rel="modal:open" title="{{ __('absence.edit_absence') }}">
																		<i class="fas fa-umbrella-beach"></i>
																	</a>
																@endif --}}
																@if (strtotime($employee_probation->format('Y-m-d')) >= strtotime(date('Y-m-d')))
																	<span class="red">PROBNI ROK!</span>
																@endif
															</td>
															@for ($i =  $begin; $i < $end; $i->modify('+'. $vacation->interval .' day'))
																@php
																	$employee_plan_date = null;
																	if( $employee_plan ) {
																		$employee_plan_date = $employee_plan->where('start_date', $i->format("Y-m-d"))->first();
																	}

																	$count_plan_department = 0;
																	foreach ($employee_departments as $employee_dep) {
																		$empl_id = $employee_dep->employee->id;
																		foreach ($vacation->hasPlans as $vac_plan) {
																			if( $vac_plan->employee_id == $empl_id && $vac_plan->start_date == $i->format("Y-m-d")) {
																				$count_plan_department++;
																			}
																		}
																	}
																	
																	// djelatnici sa kojima se ne smije poklapati
																	$poklapanje = 0;
																	if( isset($plan_department['employees'])) {
																		$employees = explode(',', $plan_department['employees']);
																		foreach ($vacation->hasPlans as $pl) {
																			if ( in_array($pl->employee_id, $employees ) && in_array($employee_department->employee_id, $employees ) && $pl->start_date == $i->format("Y-m-d") ) {
																				$poklapanje = true;
																			}
																		}
																		if( $vacation->no_week > 1) {
																			$new_date = new DateTime( $i->format("Y-m-d") );
																			
																			for ($x=1; $x < $vacation->no_week ; $x++) { 
																				$new_date->modify('+'. $vacation->interval .' day');
																				foreach ($vacation->hasPlans as $pl) {
																					if ( in_array($pl->employee_id, $employees ) && in_array($employee_department->employee_id, $employees ) &&  $pl->start_date == $new_date->format("Y-m-d") ) {
																						$poklapanje = true;
																					}
																				}
																			}
																		}
																	}
																@endphp
																<td class="center">
																	@if ( $employee_plan_date )
																		@if ( $checked_employee->id == $employee_department->employee_id || Sentinel::inRole('administrator') )
																			<span class="margin_r_20">{{ $employee_department->employee->user->last_name . ' ' . $employee_department->employee->user->first_name }}</span>
																			{{-- @if (Sentinel::inRole('administrator') && ! $employee_plan_date->request_id  )
																				<a href="{{ route('vacation_plans.edit', [ 'id' => $employee_plan_date->id, 'department_id' => $department->id ] )}}" class="btn-edit" rel="modal:open">
																					<i class="far fa-edit"></i>
																				</a>
																			@endif --}}
																			<a href="{{ route('vacation_plans.destroy', $employee_plan_date->id) }}"  id="{{ $employee_department->employee_id }}" class="action_confirm btn-delete danger" data-token="{{ csrf_token() }}">
																				<i class="far fa-trash-alt"></i>
																			</a>
																		@else
																			{{ $employee_department->employee->user->last_name . ' ' . $employee_department->employee->user->first_name }}
																		@endif
																	@else
																		@if ( count( $employee_plan) == 0 && $poklapanje == 0 && ($plan_department['no_people'] > $count_plan_department) )
																			<a class="add_plan" href="{{ action('VacationPlanController@vacationPlan', ['vacation_id' => $vacation->id, 'employee_id' => $employee_department->employee_id, 'start_date' => $i->format("Y-m-d")]) }}" id="{{ $employee_department->employee_id }}"> 
																				@if ($checked_employee->id == $employee_department->employee_id )
																					Zapiši me
																				@else
																					<i class="fas fa-plus"></i>
																				@endif
																			</a>
																		@endif
																	@endif
																</td>
															@endfor
														</tr>
													@endif
												@endforeach
											@else
												@php
													$reg_date = new Datetime($checked_employee->reg_date);
													$employee_probation = $reg_date->modify('+'. $checked_employee->probation .' months');
												@endphp
												@if (strtotime($employee_probation->format('Y-m-d')) >= strtotime(date($vacation->end_period)))
													<tr>
														<td class="employee_name center" colspan="{{ $count_termin }}">
															Probni rok ti još nije istekao, molim javi se u pravni odjel.
														</td>
													</tr>
												@else
													@php
														$begin = new DateTime( $vacation->start_period);
														$end   = new DateTime( $vacation->end_period);
													@endphp
													<tr class="basic_view">
														@for ($i =  $begin; $i < $end; $i->modify('+'. $vacation->interval .' day'))
															<td>
																<div style="display: inline-grid;">
																	@php
																		$count = 0;
																	@endphp
																	@foreach ( $employee_departments as $employee_department)
																		@if ( ! $employee_department->employee->checkout )
																			@php
																				$employee_plan = $vacation->hasPlans->where('employee_id', $employee_department->employee_id);
																				$employee_plan_date = $employee_plan->where('start_date',$i->format("Y-m-d"))->first();
																			@endphp
																			@if ( $checked_employee->id == $employee_department->employee_id )
																				@if( count( $employee_plan) == 0)
																					@php
																						$count_plan_department = 0;
																						$count_plan_department_nextweek = 0;
																						foreach ($employee_departments as $employee_dep) {
																							$empl_id =  $employee_dep->employee->id;
																							foreach ($vacation->hasPlans as $vac_plan) {
																								if( $vac_plan->employee_id == $empl_id && $vac_plan->start_date == $i->format("Y-m-d")) {
																									$count_plan_department++;
																								}
																							}
																							if( $vacation->no_week > 1) {
																								$new_date1 = new DateTime( $i->format("Y-m-d") );
																								for ($x=1; $x < $vacation->no_week ; $x++) { 
																									$new_date1->modify('+'. $vacation->interval .' day');
																									foreach ($vacation->hasPlans as $vac_plan) {
																										if( $vac_plan->employee_id == $empl_id && $vac_plan->start_date == $new_date1->format("Y-m-d")) {
																											$count_plan_department_nextweek++;
																										}
																									}
																								}
																							}
																						}
																						// djelatnici sa kojima se ne smije poklapati
																						$poklapanje = 0;
																						if( isset($plan_department['employees'])) {
																							
																							$employees = explode(',', $plan_department['employees']);
																							foreach ( $vacation->hasPlans->where('start_date', $i->format("Y-m-d") ) as $pl) {
																								if ( in_array( $checked_employee->id, $employees) && in_array($pl->employee_id, $employees) && in_array( $pl->employee_id, $employee_departments->pluck('employee_id')->toArray()) ) {
																									$poklapanje = true;
																								}
																							}
																							if( $vacation->no_week > 1) {
																								$new_date = new DateTime( $i->format("Y-m-d") );
																								
																								for ($x=1; $x < $vacation->no_week ; $x++) { 
																									$new_date->modify('+'. $vacation->interval .' day');
																									foreach ($vacation->hasPlans as $pl) {
																										if ( in_array($pl->employee_id, $employees ) && in_array($employee_department->employee_id, $employees ) &&  $pl->start_date == $new_date->format("Y-m-d") ) {
																											$poklapanje = true;
																										}
																									}
																								}
																							}
																						}
																					@endphp
																				
																					@if ( $poklapanje == 0 && ($plan_department['no_people'] > $count_plan_department )  && ($plan_department['no_people'] > $count_plan_department_nextweek ) )
																						<a class="add_plan btn_addVacation action_confirm" href="{{ action('VacationPlanController@vacationPlan',['vacation_id' => $vacation->id, 'employee_id' => $employee_department->employee_id, 'start_date' => $i->format("Y-m-d")]) }}" style="order:1;"> Zapiši me </a>
																					@endif
																				@endif
																				@if ( $employee_plan_date )
																					<span class="vacation_list" >
																						@php
																							$count++;
																							
																						@endphp
																						{{ $count . '. '}}{{ $employee_plan_date->employee->user->last_name }}
																						@if ( $checked_employee->id == $employee_department->employee_id )
																							<a href="{{ route('vacation_plans.destroy', $employee_plan_date->id) }}"  id="{{ $employee_department->employee_id }}" class="action_confirm btn-delete danger" data-token="{{ csrf_token() }}">
																								<i class="far fa-trash-alt"></i>
																							</a>
																						
																						@endif
																					</span>
																				@endif
																			@else
																				@if ( $employee_plan_date )
																					@if( $employee_plan_date->where('start_date',$i->format("Y-m-d")) )
																						@php
																							$count++;
																						@endphp
																						<span class="vacation_list">
																							{{ $count . '. '}}{{ $employee_plan_date->employee->user->last_name }}
																						</span>
																					@endif
																				@endif
																			@endif
																		@endif
																	@endforeach
																	@for ($j = $count+1; $j <= $plan_department['no_people'] ; $j++)
																		<span class="vacation_list">{{ $j . '. '}}</span>
																	@endfor
																</div>
															</td>
														@endfor
													</tr>
													<tr>
														<td class="employee_name center" colspan="{{ $count_termin }}">
															@if ($vacation->hasPlans->where('employee_id', $checked_employee->id)->first())
																Uspješno si zapisao/a termin godišnjeg odmora. Ukoliko želiš promijeniti plan prvo obriši prethodni zapis.
															@else
																Za upis termina godišnjeg odmora izaberi termin i klikni na gumb "Zapiši me".
															@endif
														</td>
													</tr>
												@endif
											@endif
										</tbody>
									</table>
								@endif
							@endforeach
						@endforeach
					</main>
				</div>
			</main>
		</section>
	</main>
</div>
@stop