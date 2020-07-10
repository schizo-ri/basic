@extends('Centaur::layout')

@section('title', 'Evidencija o radnom vremenu_' . $employee->user['first_name'] .'_'. $employee->user['last_name'] .'_'. $month)
@php
	use App\Models\WorkRecord;
	use App\Http\Controllers\AbsenceController;
	$sum_total = 0;
@endphp
@section('content')
<div class="index_page ">
	<main class="col-lg-12 col-xl-12 index_main evidention_employee">
		<section class="section_evidention">
			<div class="page-header">
				<div class="index_table_filter">
					<h5>Evidencija o radnom vremenu radnika</h5>
					<p>{{ $employee->user['last_name'] .' '. $employee->user['first_name'] }}</p>
				</div>
			</div>
			<main class="">
				<div class="employee_view">
					<div class="table-responsive">
						<table id="index_table" class="display table table-hover sort_0">
							<thead>	
								<tr>
									<th></th>
									<th colspan="">{{ date('m-Y',strtotime($month))}}</th>
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
										<th ><span>{{ $dan }}</span><br>{{ date('d', strtotime($day))}}</th>
									@endforeach
									<th class="ime">Ukupno vrijeme</th>
								</tr>
							</thead>
							<tbody>
								<tr> {{-- Početak rada --}}
									<td>1</td>
									<td>Početak rada</td>
									@foreach($list as $day2)
										@php
											$start = '';
											$work = $work_records->where('start','>', date('Y-m-d',strtotime($day2)) . ' 00:00:00')->where('start','<', date('Y-m-d',strtotime($day2)). ' 23:59:59')->first();
											if($work) {
												$start_time = strtotime($work->start);
												if($start_time >= strtotime($day2 .' 07:30:00') && $start_time <= strtotime($day2 .' 08:15:00') ) {
													$start = '08:00';
												} else {
													$start = date('H:i',$start_time );
												}
											}
										@endphp
										<td class="">
											{{ $start }}
										</td>
									@endforeach
									<td ></td>
								</tr>
								<tr> {{-- Završetak rada --}}
									<td>2</td>
									<td>Završetak rada</td>
									@foreach($list as $day2)
										@php
											$end = '';
											$work = $work_records->where('start','>', date('Y-m-d',strtotime($day2)) . ' 00:00:00')->where('start','<', date('Y-m-d',strtotime($day2)). ' 23:59:59')->first();
											if($work) {
												if($work->end) {
													$end_time = strtotime($work->end);
													if(date('N',strtotime($day2)) < 5 ) {
														if($end_time >= strtotime($day2 .' 15:45:00') && $end_time <= strtotime($day2 .' 17:00:00') ) {
															$end = '16:15';
														} else {
															$end = date('H:i',$end_time );
														}
													} else if(date('N',strtotime($day2) ) == 5) {
														if($end_time >= strtotime($day2 .' 14:45:00') && $end_time <= strtotime($day2 .' 16:00:00') ) {
															$end = '15:00';
														} else {
															$end = date('H:i',$end_time );
														}
													}
												}
												
											}
										@endphp
										<td class="">{{ $end }}</td>
									@endforeach
									<td ></td>
								</tr>
								<tr class="bg_ccc" > {{-- UKUPNO RADNIH SATI DNEVNO --}}
									<td>3</td>
									<td>UKUPNO RADNIH SATI DNEVNO</td>
									@php
										$minutes = 0;
										$hours = 0;
										$minutes_row_3 = 0;
									@endphp
									@foreach($list as $day2)
										@php
											$interval = '';
											$work = $work_records->where('start','>', date('Y-m-d',strtotime($day2)) . ' 00:00:00')->where('start','<', date('Y-m-d',strtotime($day2)). ' 23:59:59')->first();
											if($work) {
												if($work->end) {
													$interval = AbsenceController::dateDifference($work->start, $work->end);
												} else {
													$interval = '';
												}
											}
											if(date('N',strtotime($day2)) < 5 ) {
												if( strtotime($interval) > strtotime('7:30') && strtotime($interval) < strtotime('8:45')) {
													$interval = '08:25';
												}
											} else if(date('N',strtotime($day2) ) == 5) {
												if( strtotime($interval) > strtotime('6:30') && strtotime($interval) < strtotime('7:45')) {
													$interval = '07:00';
												}
											}
											$minutes = 0;
											$minutes += strstr($interval, ':', true) * 60; 
											$minutes += intval(str_replace(':','',strstr($interval, ':'))); 
											$sum[date('Y-m-d',strtotime($day2))] += $minutes;
											
											$minutes_row_3 += $minutes;
										@endphp
										<td class="">{{$interval}}</td>
									@endforeach
									@php
										if($minutes_row_3 > 0) {
											$hours = floor($minutes_row_3 / 60);
											$minutes_row_3 -= $hours * 60;
										}
									@endphp
									<td >{{ sprintf('%02d:%02d', $hours, $minutes_row_3) }}</td>
								</tr>
								<tr>{{-- Vrijeme sati zastoja,prekida rada --}}
									<td>4</td>
									<td>Vrijeme sati zastoja,prekida rada i sl.do kojega je došlo <br> krivnjom posl.ili uslijed dr. okolnosti za koje radnik nije odgovoran:</td>
									@foreach($list as $day2)
										<td class=""></td>
									@endforeach
									<td ></td>
								</tr>
								<tr> {{-- Ukupno dnevno radno vrijeme u satima te od toga --}}
									<td>5</td>
									<td>Ukupno dnevno radno vrijeme u satima te od toga:</td>
									@foreach($list as $day2)
										<td class=""></td>
									@endforeach
									<td ></td>
								</tr>
								<tr> {{-- prekovremenog rada --}}
									<td>7</td>
									<td> - prekovremenog rada</td>
									@foreach($list as $day2)
										<td class=""></td>
									@endforeach
									<td ></td>
								</tr>
								<tr> {{-- sati rada u preraspodijeljenom radnom vremenu i razdoblje preraspodijeljenog radnog vremena --}}
									<td>8</td>
									<td> - sati rada u preraspodijeljenom radnom vremenu i razdoblje preraspodijeljenog radnog vremena</td>
									@foreach($list as $day2)
										<td class=""></td>
									@endforeach
									<td ></td>
								</tr>
								<tr class="bg_ccc"> {{-- DRŽAVNI BLAGDAN --}}
									<td>8a</td>
									<td>DRŽAVNI BLAGDAN</td> 
									@php
										$minutes_row_8a = 0;
									@endphp
									@foreach($list as $day2)
										@php
											$hol_hour = '';
											if (in_array( $day2, $holidaysThisYear) ) {
												if(date('N',strtotime($day2)) < 5 ) {
													$hol_hour = '08:25';
												} else if(date('N',strtotime($day2) ) == 5) {
													$hol_hour = '07:00';
												}
												$minutes = 0;
												$minutes += strstr($hol_hour, ':', true) * 60; 
												$minutes += intval(str_replace(':','',strstr($hol_hour, ':'))); 
												
												$sum[date('Y-m-d',strtotime($day2))] += $minutes;
												$minutes_row_8a += $minutes;
											}
										@endphp
										<td class="">{{ $hol_hour }}</td>
									@endforeach
									@php
										if($minutes_row_8a > 0) {
											$hours = floor($minutes_row_8a / 60);
											$minutes_row_8a -= $hours * 60;
										}
									@endphp
									<td >{!! $minutes_row_8a > 0 ? sprintf('%02d:%02d', $hours, $minutes_row_8a) : '' !!}</td>
								</tr>
								<tr> {{-- SATI RADA NEDJELJOM ILI NERADNIM DANIMA --}}
									<td>9</td>
									<td>SATI RADA NEDJELJOM ILI NERADNIM DANIMA UTVRĐENIM POSEBNIM PROPISOM</td>
									@foreach($list as $day2)
										@php
										/* 	$minutes += strstr($hol_hour, ':', true) * 60;  */
										/* 	$minutes += intval(str_replace(':','',strstr($hol_hour, ':')));  */
										/* 	$sum[date('Y-m-d',strtotime($day2))] += $minutes; */
										@endphp
										<td class=""></td>
									@endforeach
									<td ></td>
								</tr>
								<tr> {{-- Početak službenog puta --}}
									<td>10</td>
									<td>Početak službenog puta / loko</td>
									@foreach($list as $day2)
										@php
											$travelOrders->each(function ($travel, $key) use ($day2) {
												$days = $travel->travelDays;
												$count_days = count($days);
												if(in_array($day2, $days )) {
													$hasDate = true;
													$day_no = array_search ($day2, $days)  +1;  // broj datuma u arrayu
													$start = date('H:i', strtotime($travel->start_date));
													$end = date('H:i', strtotime($travel->end_date) );
													if( $count_days > 1 ) {
														if($day_no == 1) {
															$end = '24:00';
														} else if($day_no != $count_days ) {
															$start = '00:00';
															$end = '24:00';
														} else if($day_no == $count_days) {
															$start = '00:00';
														}
													}
												} else {
													$start = null; 
													$end = null; 
													$hasDate = false;
												}
												$travel->hasDate = $hasDate;
												$travel->start_time = $start;
												$travel->end_time = $end;
												
											});
											$trav = $travelOrders->where('hasDate',true)->first();
										@endphp
										<td class="">{!! $trav ? $trav->start_time : ''  !!}</td>
									@endforeach
									<td ></td>
								</tr>
								<tr> {{-- Kraj službenog puta --}}
									<td>11</td>
									<td>Kraj službenog puta / loko</td>
									@foreach($list as $day2)
										@php
											$travelOrders->each(function ($travel, $key) use ($day2) {
												$days = $travel->travelDays;
												$count_days = count($days);
												
												if(in_array($day2, $days )) {
													$hasDate = true;
													$day_no = array_search ($day2, $days)  +1;  // broj datuma u arrayu
													$start = date('H:i', strtotime($travel->start_date));
													$end = date('H:i', strtotime($travel->end_date) );
													if( $count_days > 1 ) {
														if($day_no == 1) {
															$end = '24:00';
														} else if($day_no != $count_days ) {
															$start = '00:00';
															$end = '24:00';
														} else if($day_no == $count_days) {
															$start = '00:00';
														}
													}
													
												} else {
													$start = null; 
													$end = null; 
													$hasDate = false;
												}
												$travel->hasDate = $hasDate;
												$travel->start_time = $start;
												$travel->end_time = $end;
												
											});
											$trav = $travelOrders->where('hasDate',true)->first();
										@endphp
										<td class="">{!! $trav ? $trav->end_time : ''  !!}</td>
									@endforeach
									<td ></td>
								</tr>
								<tr> {{-- Sati provedeni na službenom putu --}}
									<td>12</td>
									<td>Sati provedeni na službenom putu</td>
									@foreach($list as $day2)
										@php
											$travelOrders->each(function ($travel, $key) use ($day2) {
												$days = $travel->travelDays;
												$count_days = count($days);
												
												if(in_array($day2, $days )) {
													$hasDate = true;
													$day_no = array_search ($day2, $days)  +1;  // broj datuma u arrayu
													$start = date('H:i', strtotime($travel->start_date));
													$end = date('H:i', strtotime($travel->end_date) );
													if( $count_days > 1 ) {
														if($day_no == 1) {
															$end = '24:00';
														} else if($day_no != $count_days ) {
															$start = '00:00';
															$end = '24:00';
														} else if($day_no == $count_days) {
															$start = '00:00';
														}
													}
													
													$datetime1 = new DateTime($start);
													$datetime2 = new DateTime( $end );
													$interval = $datetime1->diff($datetime2);
													$interval_set = $interval->format("%H:%I");
													if($start = '00:00' && $end = '24:00' && $interval_set == '00:00') {
														$interval_set = '24:00';
													}
													
												} else {
													$start = null; 
													$end = null; 
													$hasDate = false;
													$interval_set  = null;
												}
												$travel->hasDate = $hasDate;
												$travel->start_time = $start;
												$travel->end_time = $end;
												if($interval_set)
													$travel->interval = $interval_set;
												
											});
											$trav = $travelOrders->where('hasDate',true)->first();
										@endphp
										<td class="">{!! $trav ? $trav->interval : ''  !!}</td>
									@endforeach
									<td ></td>
								</tr>
								<tr> {{--  Putni nalog --}}
									<td>12a</td>
									<td>Putni nalog</td>
									@foreach($list as $day2)
										
										<td class=""></td>
									@endforeach
									<td ></td>
								</tr>
								<tr> {{-- Sati pripravnosti te sati rada po pozivu --}}
									<td>13</td>
									<td>Sati pripravnosti te sati rada po pozivu</td>
									@foreach($list as $day2)
										<td class=""></td>
									@endforeach
									<td ></td>
								</tr>
								<tr class="bg_ccc">{{-- GODIŠNJI ODMOR --}}
									<td>14</td> 
									<td>GODIŠNJI ODMOR U SATIMA</td>
									@php
										$minutes_row_14 = 0;
									@endphp
									@foreach($list as $day2)
										@php
											$absences_go = $absences->where('mark','GO');
											
											$absences_go->each(function ($absence, $key) use ($day2,$sum) {
												$days = $absence->days;
												$time = '';
												if(in_array($day2, $days )) {
													if(date('N',strtotime($day2)) < 5 ) {
														$time = '8:25';
													} else if(date('N',strtotime($day2) ) == 5) {
														$time = '7:00';
													}  
													$hasDate = true;
												} else {
													$time = '';
													$hasDate = false;
												}
												$absence->hasDate = $hasDate;
												$absence->time = $time;
											});
										
											$abs = $absences_go->where('hasDate',true)->first();
											if($abs) {
												$minutes = 0;
												$minutes += strstr($abs->time, ':', true) * 60; 
												$minutes += intval(str_replace(':','',strstr($abs->time, ':'))); 
												
												$sum[date('Y-m-d', strtotime($day2))] += $minutes;
												$minutes_row_14 += $minutes;
											}											
										@endphp
										<td class="">{!! $abs ? $abs->time : '' !!}</td>
									@endforeach
									@php
										if($minutes_row_14 > 0) {
											$hours = floor($minutes_row_14 / 60);
											$minutes_row_14 -= $hours * 60;
										}
									@endphp
									<td >{!! $minutes_row_14 > 0 ? sprintf('%02d:%02d', $hours, $minutes_row_14) : '' !!}</td>
								</tr>
								<tr > {{-- Privremena nesposobnost za rad (bolovanje) --}}
									<td>15</td>
									<td>Sati privremene nesposobnosti za rad (bolovanje)</td>
									@foreach($list as $day2)
										@php
											$absences_bol = $absences->where('mark','BOL');
											$absences_bol->each(function ($absence, $key) use ($day2) {
												$days = $absence->days;
												$time = '';
												if(in_array($day2, $days )) {
													if(date('N',strtotime($day2)) < 5 ) {
														$time = '8:25';
													} else if(date('N',strtotime($day2) ) == 5) {
														$time = '7:00';
													}  
													$hasDate = true;
												} else {
													$time = '';
													$hasDate = false;
												}
												$absence->hasDate = $hasDate;
												$absence->time = $time;								
											});
											$abs = $absences_bol->where('hasDate',true)->first();
										@endphp
										<td class="">{!! $abs ? $abs->time : '' !!}</td>
									@endforeach
									<td ></td>
								</tr>
								<tr class="bg_ccc">{{-- BOLOVANJE --}}
									<td>16</td>
									<td>UKUPNO BOLOVANJE SATI</td> 
									@php
										$minutes_row_16 = 0;
									@endphp
									@foreach($list as $day2)
										@php
											$absences_bol = $absences->where('mark','BOL');
											$absences_bol->each(function ($absence, $key) use ($day2,$sum) {
												$days = $absence->days;
												$time = '';
												if(in_array($day2, $days )) {
													if(date('N',strtotime($day2)) < 5 ) {
														$time = '8:25';
													} else if(date('N',strtotime($day2) ) == 5) {
														$time = '7:00';
													}  
													$hasDate = true;
												} else {
													$time = '';
													$hasDate = false;
												}
												$absence->hasDate = $hasDate;
												$absence->time = $time;
											
											});
											$abs = $absences_bol->where('hasDate',true)->first();
											if($abs) {
												$minutes = 0;
												$minutes += strstr($abs->time, ':', true) * 60; 
												$minutes += intval(str_replace(':','',strstr($abs->time, ':'))); 
												
												$sum[date('Y-m-d', strtotime($day2))] += $minutes;
												$minutes_row_16 += $minutes;
											}
										@endphp
										<td class="">{!! $abs ? $abs->time : '' !!}</td>
									@endforeach
									@php
										if($minutes_row_16 > 0) {
											$hours = floor($minutes_row_16 / 60);
											$minutes_row_16 -= $hours * 60;
										}
									@endphp
									<td >{!! $minutes_row_16 > 0 ? sprintf('%02d:%02d', $hours, $minutes_row_16) : '' !!}</td>
								</tr>
								<tr> {{-- PORODILJNI --}}
									<td>17</td>
									<td>Vrijeme rodiljnog, roditeljskog dop. ili korištenja drugih prava sukladno posebnom propisu</td>
									@foreach($list as $day2)
										@php
											$absences_bol = $absences->where('mark','POR');
											$absences_bol->each(function ($absence, $key) use ($day2) {
												$days = $absence->days;
												$time = '';
												if(in_array($day2, $days )) {
													if(date('N',strtotime($day2)) < 5 ) {
														$time = '8:25';
													} else if(date('N',strtotime($day2) ) == 5) {
														$time = '7:00';
													}  
													$hasDate = true;
												} else {
													$time = '';
													$hasDate = false;
												}
												$absence->hasDate = $hasDate;
												$absence->time = $time;								
											});
											$abs = $absences_bol->where('hasDate',true)->first();
										@endphp
										<td class="">{!! $abs ? $abs->time : '' !!}</td>
									@endforeach
									<td ></td>
								</tr>
								<tr>  {{-- Plaćeni dopust --}}
									<td>18</td>
									<td>Sati plaćenog dopusta</td>
									@foreach($list as $day2)
										@php
											$absences_bol = $absences->where('mark','PLD');
											$absences_bol->each(function ($absence, $key) use ($day2) {
												$days = $absence->days;
												$time = '';
												if(in_array($day2, $days )) {
													if(date('N',strtotime($day2)) < 5 ) {
														$time = '8:25';
													} else if(date('N',strtotime($day2) ) == 5) {
														$time = '7:00';
													}  
													$hasDate = true;
												} else {
													$time = '';
													$hasDate = false;
												}
												$absence->hasDate = $hasDate;
												$absence->time = $time;								
											});
											$abs = $absences_bol->where('hasDate',true)->first();
										@endphp
										<td class="">{!! $abs ? $abs->time : '' !!}</td>
									@endforeach
									<td ></td>
								</tr>
								<tr>{{-- Neplaćeni dopust --}}
									<td>19</td>
									<td>Sati neplaćenog dopusta</td>
									@foreach($list as $day2)
										@php
											$absences_bol = $absences->where('mark','NPLD');
											$absences_bol->each(function ($absence, $key) use ($day2) {
												$days = $absence->days;
												$time = '';
												if(in_array($day2, $days )) {
													if(date('N',strtotime($day2)) < 5 ) {
														$time = '8:25';
													} else if(date('N',strtotime($day2) ) == 5) {
														$time = '7:00';
													}  
													$hasDate = true;
												} else {
													$time = '';
													$hasDate = false;
												}
												$absence->hasDate = $hasDate;
												$absence->time = $time;								
											});
											$abs = $absences_bol->where('hasDate',true)->first();
										@endphp
										<td class="">{!! $abs ? $abs->time : '' !!}</td>
									@endforeach
									<td ></td>
								</tr>
								<tr>
									<td>20</td>
									<td>Sati nenazočno u tijeku dnevnog rasporeda radnog vremena, odobrene ili neodobrene od poslodavca</td>
									@foreach($list as $day2)
										<td class=""></td>
									@endforeach
									<td ></td>
								</tr>
								<tr>
									<td>21</td>
									<td>Sati provedeni u štrajku</td>
									@foreach($list as $day2)
										<td class=""></td>
									@endforeach
									<td ></td>
								</tr>
								<tr>
									<td>22</td>
									<td>Sati isključenja s rada (lockout)</td>
									@foreach($list as $day2)
										<td class=""></td>
									@endforeach
									<td ></td>
								</tr>
							</tbody>
							<tfoot> {{-- UKUPAN FOND SATI --}}
								<tr>
									<td>23</td>
									<td>UKUPAN FOND SATI (3+8a+9+14+16)</td>
									@foreach($list as $day2)
										@php
											if($sum[date('Y-m-d',strtotime($day2))] > 0) {
												$minutes = $sum[date('Y-m-d',strtotime($day2))];
												$sum_total += $minutes;

												$hours = floor($minutes / 60);
												$minutes -= $hours * 60;
												 
											} else {
												$hours="";
												$minutes="";
											}
										@endphp
										<td >{!! $hours ? sprintf('%02d:%02d', $hours, $minutes) : '' !!}</td>
									
									@endforeach
									@php
										if($sum_total > 0) {
											$hours_total = floor($sum_total  / 60);
											$sum_total -= $hours_total * 60;
										}
									@endphp
										<td >{!! $sum_total ? sprintf('%02d:%02d', $hours_total, $sum_total) : '' !!}</td>
								</tr>
							</tfoot>
						</table>
					</div>	
				</div>
			</main>
		</section>
	</main>
</div>

<script>
	$(function() {
		$( ".td_izostanak:contains('GO')" ).each(function( index ) {
			$( this ).addClass('abs_GO');
		});
		$( ".td_izostanak:contains('BOL')" ).each(function( index ) {
			$( this ).addClass('abs_BOL');
		});
	});
	$.getScript( '/../js/datatables_evidention.js');
	
</script>		
@stop