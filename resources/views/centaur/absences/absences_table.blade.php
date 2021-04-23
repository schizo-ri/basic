@extends('Centaur::layout')

@section('title', __('absence.absences'))
@php
	use App\Http\Controllers\BasicAbsenceController;
	use App\Http\Controllers\DashboardController;
@endphp
@section('content')
<span id="user_admin" hidden>{{ Sentinel::inRole('administrator') ? 'true' : '' }}</span>
<main class="col-lg-12 col-xl-12 index_main main_absence float_right">
	<section>
		<header class="header_absence">
			<a class="link_back" href="{{ url()->previous() }}" ><span class="curve_arrow_left"></span></a>
			<p>@lang('absence.all_requests')
				{{-- <a href="{{ route('absences.index') }}" class="view_all" title="{{ __('absence.absences')}}" >vidi zahtjeve za djelatnika</a> --}}
			</p>
		</header>
		<main class="all_absences table_absences">
			<header class="main_header">
				<label class="search_label col-xs-11 col-sm-11 col-md-6 col-lg-4">
					<input type="search" placeholder="Traži" onkeyup="mySearchTable()" id="mySearchTbl">
				</label>
				<label class="search_label col-xs-1 col-sm-1 col-md-6 col-lg-8">
					<span class="show_button"><i class="fas fa-download"></i></span>
				</label>
			</header>
			<section class="overflow_auto bg_white section_main">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padd_0 position_rel height100">
					<div class="table-responsive" >
						@if(count($employees)>0)
							<table id="index_table" class="display table table-hover ">
								<thead>
									<tr>
										<th>Prezime i ime</th>
										<th>Slobodni dan</th>
										<th>Odjel</th>
										<th>Staž Duplico <br>[g-m-d]</th>
										<th>Staž ukupno <br>[g-m-d]</th>
										@foreach ($years as $year)
											@if(  $year == date('Y') || $year == (date('Y')-1))		
												<th>Ukupno GO <br>{{ $year }}</th>
												<th >Iskorišteni dani <br>{{ $year }}</th>
											@endif
										@endforeach
										<th>Ukupno neiskorišteno <br>dana  GO</th>
										<th>Ukupno prekovremenih <br>sati </th>
										<th>Ukupno izlazaka <br>sati [dana]</th>
										<th>Ukupno slobodnih <br>dana</th>
										<th>Korišteno slobodnih <br>dana</th>
										<th >Neiskorišteni <br>slobodni <br>dani</th>
									</tr>
								</thead>
								<tbody class="overflow_auto">
									@foreach ($employees as $employee)
										@php
											$data_absence = array(
												'years_service'  => BasicAbsenceController::yearsServiceCompany( $employee ),  
												'all_servise'  	 => BasicAbsenceController::yearsServiceAll( $employee ), 
												'days_OG'  		 => BasicAbsenceController::daysThisYear( $employee ), 
												'zahtjevi' 		 => BasicAbsenceController::requestAllYear( $employee ), 
												'afterHours' 	 => BasicAbsenceController::afterHours( $employee ), 
												'afterHoursNoPaid' 	 => BasicAbsenceController::afterHoursNoPaid( $employee ), 												
												'izlasci_ukupno_h' => BasicAbsenceController::izlasci_ukupno( $employee ), 
												'slobodni_dani' 	=> BasicAbsenceController::slobodni_dani( $employee ), //izlasci u danima
												'days_off' 		 => BasicAbsenceController::days_off( $employee ), 
												'days_offUsed' => BasicAbsenceController::days_offUsed( $employee ), 
												'afterHours_withoutOuts' => BasicAbsenceController::afterHours_withoutOuts( $employee ), 
												'neiskoristenoGO' => BasicAbsenceController::neiskoristenoGO( $employee ), 
											);
											$sum = 0;
											$sum_correcting = 0;

											if( count( $employee->hasCorrectings ) > 0 ) {
												foreach ($employee->hasCorrectings as $correcting) {
													$sum += date('H',strtotime($correcting->time)) * 60;
													$sum += date('i',strtotime($correcting->time));
												}
												$sum_correcting = $sum / 60;
											}

											// Vraća broj dana godišnjeg ova godina    
										@endphp
										<tr class="tr_open_link tr" data-href="/absences/{{ $employee->id }} empl_{{ $employee->employee_id}}" >
											<td>{{ $employee->last_name . ' ' . $employee->first_name }}</td>
											<th>{!! $employee->days_off == 1 ? 'SLD' : 'ISP' !!}</th>
											<td>{!! $employee->work ? $employee->work->department->name : '' !!}</td>
											<td>{{ $data_absence['years_service']->y .'-'.$data_absence['years_service']->m .'-'.$data_absence['years_service']->d }}</td>
											<td>{{ $data_absence['all_servise'][0]  .'-'.$data_absence['all_servise'][1]  .'-'.$data_absence['all_servise'][2]}}</td>
											@foreach ($years as $year)	
												@if(  $year == date('Y') || $year == (date('Y')-1))											
													<td>{!! $year == date('Y') ? '('.$data_absence['days_OG'].')': '' !!} {{ BasicAbsenceController::razmjeranGO_Godina($employee, $year) }}</td>
													<td>{!! isset($data_absence['zahtjevi'][ $year ]) ? count($data_absence['zahtjevi'][ $year ] ): 0 !!}</td>
												@endif
											@endforeach
											<td>{{ $data_absence['neiskoristenoGO'] }}</td>
											<td>
												@if ($employee->days_off == 1)
													{!! $data_absence['afterHours'] == '00:00' ? '' : round($data_absence['afterHours'],2) . ' h' !!}
												@else
													{!! $data_absence['afterHoursNoPaid'] == '00:00' ? '' : round($data_absence['afterHoursNoPaid'],2) - $sum_correcting. ' h' !!}
												@endif
												
											</td>
											<td>{!! $data_absence['izlasci_ukupno_h']  == '0:0' ? '' : $data_absence['izlasci_ukupno_h'] . ' h ['.$data_absence['slobodni_dani']. ']' !!} </td>
											<td>{!! $employee->days_off == 1 ? $data_absence['afterHours_withoutOuts'] : '' !!}</td>
											<td>{!! $employee->days_off == 1 ? $data_absence['days_offUsed'] : '' !!}</td>
											<td>{!! $employee->days_off == 1 ? $data_absence['afterHours_withoutOuts']  - $data_absence['days_offUsed'] : '' !!}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						@else
							<div class="placeholder">
								<img class="" src="{{ URL::asset('icons/placeholder_absence.png') }}" alt="Placeholder image" />
								<p>@lang('basic.no_absence1')
									<label type="text" class="add_new" rel="modal:open" >
										<i style="font-size:11px" class="fa">&#xf067;</i>
									</label>
									@lang('basic.no_absence2')
								</p>
							</div>
						@endif
					</div>
				</div>
			</section>
		</main>
	</section>
</main>
</div>
<script>

</script>
@stop