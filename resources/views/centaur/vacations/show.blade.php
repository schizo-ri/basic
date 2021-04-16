@extends('Centaur::layout')

@section('title', __('absence.vacation_plan'))
@php
	use App\Models\Department;
@endphp
@section('content')
<div class="index_page index_documents">
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
							@if( $checked_user->hasAccess(['vacations.create']) )
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
								@if($checked_user->hasAccess(['vacations.update']))
									<a href="{{ route('vacations.edit', $vacation->id) }}" class="btn-edit" rel="modal:open">
											<i class="far fa-edit"></i>
									</a>
								@endif
								@if(count($vacation->hasPlans->where('request_id', null)) > 0 && ($checked_user->hasAccess(['vacations.create']) )  )
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
									$department = Department::find( $department_id);
									$works_in_department = $department->hasWorks;
									$workers_in_department = collect();
									foreach ( $works_in_department as $work) {
										$workers_in_department = $workers_in_department ->merge($work->workers);
									}
									$count_termin = 0;
								@endphp
								@if ( $department )
									@if ( Sentinel::inRole('administrator') || $department_id == $checked_employee->work->department->id )
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
													@if( $workers_in_department && count($workers_in_department ) > 0)
														@foreach ($workers_in_department->where('checkout', null) as $employee)
															@php
																$begin = new DateTime( $vacation->start_period );
																$end   = new DateTime( $vacation->end_period) ;
																$employee_plan = $vacation->hasPlans->where('employee_id', $employee->id);
																$reg_date = new Datetime($employee->reg_date);
																$probation = $employee->probation;
																if( $probation  == null) {
																	$probation = 0 ;
																}
																$employee_probation = $reg_date->modify('+'. $probation .' months');
															@endphp
															<tr>											
																<td class="employee_name {!! count($employee_plan) >0 ? 'bg_lightgreen': '' !!}">
																	{{ $employee->user->last_name . ' ' . $employee->user->first_name }}
																	@if( isset($plan_department['employees'] ) && in_array( $employee->id, explode( ',', $plan_department['employees'])))
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
																		foreach ($workers_in_department as $employee_dep) {
																			$empl_id =  $employee_dep->id;
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
																				if ( in_array($pl->employee_id, $employees ) && in_array($employee->id, $employees ) &&  $pl->start_date == $i->format("Y-m-d") ) {
																					$poklapanje = true;
																				}
																			}
																			if( $vacation->no_week > 1) {
																				$new_date = new DateTime( $i->format("Y-m-d") );
																				
																				for ($x=1; $x < $vacation->no_week ; $x++) { 
																					$new_date->modify('+'. $vacation->interval .' day');
																					foreach ($vacation->hasPlans as $pl) {
																						if ( in_array($pl->employee_id, $employees ) && in_array($employee->id, $employees ) &&  $pl->start_date == $new_date->format("Y-m-d") ) {
																							$poklapanje = true;
																						}
																					}
																				}
																			}
																		}
																	@endphp
																	<td class="center">
																		@if ( $employee_plan_date )
																			@if ( $checked_employee->id == $employee->id || Sentinel::inRole('administrator') )
																				<span class="margin_r_20">{{ $employee->user->last_name . ' ' . $employee->user->first_name }}</span>
																				{{-- @if (Sentinel::inRole('administrator') && ! $employee_plan_date->request_id  )
																					<a href="{{ route('vacation_plans.edit', [ 'id' => $employee_plan_date->id, 'department_id' => $department->id ] )}}" class="btn-edit" rel="modal:open">
																						<i class="far fa-edit"></i>
																					</a>
																				@endif --}}
																				<a href="{{ route('vacation_plans.destroy', $employee_plan_date->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
																					<i class="far fa-trash-alt"></i>
																				</a>
																			@else
																				{{ $employee->user->last_name . ' ' . $employee->user->first_name }}
																			@endif
																		@else
																			@if ( count( $employee_plan) == 0 && $poklapanje == 0 && ($plan_department['no_people'] > $count_plan_department) )
																				<a class="add_plan" href="{{ action('VacationPlanController@vacationPlan', ['vacation_id' => $vacation->id, 'employee_id' => $employee->id, 'start_date' => $i->format("Y-m-d")]) }}" > 
																					@if ($checked_employee->id == $employee->id )
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
														@endforeach
													@endif
												@else
													@php
														$reg_date = new Datetime($checked_employee->reg_date);
														$probation = $checked_employee->probation;
														if($probation == null ) {
															$probation = 0;
														}
														$employee_probation = $reg_date->modify('+'. $probation .' months');
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
																	<div>
																		@php
																			$count = 0;
																		@endphp
																		@foreach ($workers_in_department->where('checkout', null) as $employee)
																			@php
																				$employee_plan = $vacation->hasPlans->where('employee_id', $employee->id);
																				$employee_plan_date = $employee_plan->where('start_date',$i->format("Y-m-d"))->first();
																			@endphp
																			@if ( $checked_employee->id == $employee->id )
																				@if( count( $employee_plan ) == 0)
																					@php
																						$count_plan_department = 0;
																						$count_plan_department_nextweek = 0;
																						foreach ($workers_in_department as $employee_dep) {
																							$empl_id = $employee_dep->id;
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
																							foreach ($vacation->hasPlans->where('start_date', $i->format("Y-m-d") ) as $pl) {
																								if ( in_array($pl->employee_id, $employees) && $pl->employee_id ==  $employee->id ) {
																									$poklapanje = true;
																								}
																							}
																							if( $vacation->no_week > 1) {
																								$new_date = new DateTime( $i->format("Y-m-d") );
																								
																								for ($x=1; $x < $vacation->no_week ; $x++) { 
																									$new_date->modify('+'. $vacation->interval .' day');
																									foreach ($vacation->hasPlans as $pl) {
																										if ( in_array($pl->employee_id, $employees ) && ( $pl->employee_id ==  $employee->id) &&  $pl->start_date == $new_date->format("Y-m-d") ) {
																											$poklapanje = true;
																										}
																									}
																								}
																							}
																						}
																					@endphp
																					@if ( $poklapanje == 0 && ( $plan_department['no_people'] > $count_plan_department )  && ($plan_department['no_people'] > $count_plan_department_nextweek ) )
																						<a class="add_plan btn_addVacation action_confirm" href="{{ action('VacationPlanController@vacationPlan',['vacation_id' => $vacation->id, 'employee_id' => $employee->id, 'start_date' => $i->format("Y-m-d")]) }}" style="order:1;"> Zapiši me </a>
																					@endif
																				@endif
																				@if ( $employee_plan_date )
																					<span class="vacation_list" >
																						@php
																							$count++;
																							
																						@endphp
																						{{ $count . '. '}}{{ $employee_plan_date->employee->user->last_name }}
																						@if ( $checked_employee->id == $employee->id )
																							<a href="{{ route('vacation_plans.destroy', $employee_plan_date->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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
								@endif
							
							@endforeach
						@endforeach
					</main>
					
				</div>
			</main>
		</section>
	</main>
</div>

<script>
	$('.add_plan').on('click',function(){
		if (! confirm("Sigurno želiš unijeti plan?")) {
			return false;
		} else {
			return true;
		}
	});
	$('.create_request').on('click',function(){
		if (! confirm("Sigurno želiš pokrenuti izradu zahtjeva?")) {
			return false;
		} else {
			return true;
		}
	});
	var slice = $('#no_week').text();
	$('tbody tr td').not('.employee_name').on('mouseover', function(){
		$( this ).css('background','#bbb');
		$( this ).nextAll().slice(0, slice - 1).css('background','#bbb');
		$( this ).nextAll().slice(0, slice - 1).find('a').css('visibility','hidden');
		
	});
	$('tbody tr td').not('.employee_name').on('mouseleave', function(){
		$( this ).css('background','inherit');
		$( this ).nextAll().slice(0, slice - 1).css('background','inherit');
		$( this ).nextAll().slice(0, slice - 1).find('a').css('visibility','visible');
	});
</script>
@stop