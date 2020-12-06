<!DOCTYPE html>
<html lang="hr" style="font-size: 12px;font-family: Arial, sans-serif;">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>Evidencija</title>
		<script src="{{ URL::asset('/../node_modules/jquery/dist/jquery.min.js') }}"></script>
		<style>
			@page { margin:20px;size: landscape }
			@media print {
				.pageBreak {
					page-break-after: always;
				}
				.background_ccc {
					background-color: #ccc;
				}
			}
		</style>
	</head>
	<body style="font-family: DejaVu Sans;">
		@php
			use App\Http\Controllers\AbsenceController;
			use App\Http\Controllers\WorkRecordController;
			
		@endphp
		<div class="index_page " >
			@foreach($employees as $key => $employee)
				@php
					$sum_total = 0;
					foreach($sum as $k => $v)
					{
						$sum[$k] = 0;
					}
				
					$data = WorkRecordController::dataRecord($month, $employee); 
					$work_records =  $data['work_records'];
					$travelOrders =  $data['travelOrders'];
					$loccos =  $data['loccos'];
					$permission_dep =  $data['permission_dep'];
					$absences =  $data['absences'];
				@endphp
				<main class="col-lg-12 col-xl-12 index_main evidention_employee pageBreak">
					<section class="section_evidention">
						<div class="page-header">
							<div class="index_table_filter">
								<h5>Evidencija o radnom vremenu radnika za {{ date('Y-m', strtotime($month)) }}</h5>
								<h5>{{ $employee->user['last_name'] .' '. $employee->user['first_name'] }}</h5>
							</div>
						</div>
						<main class="">
							<div class="employee_view">
								<div class="table-responsive" style="width:100%">
									<table id="index_table" class="display table table-hover sort_0" style="font-size: 10px; position: relative;font-family: Arial, sans-serif;border-collapse: separate;border-spacing: 0;width:100%;">
										<thead style="display: table-header-group;vertical-align: middle; border-color:inherit;border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;font-family: Arial, sans-serif;">	
											<tr style="font-size: 0.625rem;display: table-row;border-color:inherit;">
												<th style="border-right:1px solid #ccc;padding: 2px 5px;border-collapse: separate;border-spacing: 0;"></th>
												<th style="border-right:1px solid #ccc;padding: 2px 5px;border-collapse: separate;border-spacing: 0;width:150px;text-align:left;"></th>
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
													<th style="border-right:1px solid #ccc;padding: 2px 5px;border-collapse: separate;border-spacing: 0;"><span>{{ $dan }}</span><br>{{ date('d', strtotime($day))}}</th>
												@endforeach
												<th class="ime" style="border-right:1px solid #ccc;padding: 2px 5px;border-collapse: separate;border-spacing: 0;">Ukupno vrijeme</th>
											</tr>
										</thead>
										<tbody style="display: table-row-group;vertical-align: middle;border-color:inherit;font-family: Arial, sans-serif;font-size:10px;">
											<tr style="display: table-row;font-family: Arial, sans-serif;font-size:10px;">
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >1</td>
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;width:150px;text-align:left;" >Početak rada</td>
												@foreach($list as $day2)
													@php
														$start = '';
														$work = $work_records->where('start','>', date('Y-m-d',strtotime($day2)) . ' 00:00:00')->where('start','<', date('Y-m-d',strtotime($day2)). ' 23:59:59')->first();
														if($work) {
															$start_time = strtotime($work->start);
															$start = '08:00';
														}
													@endphp
													<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >
														{{ $start }}
													</td>
												@endforeach
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
											</tr>
											<tr style="display: table-row;font-family: Arial, sans-serif;font-size:10px;">
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >2</td>
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;width:150px;text-align:left;" >Završetak rada</td>
												@foreach($list as $day2)
													@php
														$end = '';
														$work = $work_records->where('start','>', date('Y-m-d',strtotime($day2)) . ' 00:00:00')->where('start','<', date('Y-m-d',strtotime($day2)). ' 23:59:59')->first();
														if($work) {
															if($work->end) {
																$end_time = strtotime($work->end);
																if(date('N',strtotime($day2)) < 5 ) {
																		$end = '16:15';
																} else if(date('N',strtotime($day2) ) == 5) {
																		$end = '15:00';
																}
															}
														}
													@endphp
													<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >{{ $end }}</td>
												@endforeach
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
											</tr>
											<tr style="display: table-row;font-family: Arial, sans-serif;font-size:10px;background-color: #ccc;"  class="background_ccc">
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >3</td>
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;width:150px;text-align:left;" >UKUPNO RADNIH SATI DNEVNO</td>
												@php
													$minutes = 0;
													$hours = 0;
													$minutes_row_3 = 0;
												@endphp
												@foreach($list as $day2)
													@php
														$interval = '';
														$work = $work_records->where('start','>', date('Y-m-d',strtotime($day2)) . ' 00:00:00')->where('start','<', date('Y-m-d',strtotime($day2)). ' 23:59:59')->first();
														if($work && $work->end) {
															/* $start_time = strtotime($work->start); */
															$start = date('Y-m-d H:i', strtotime($day2 .' 08:00:00'));
														/* 	if($start_time >= strtotime($day2 .' 07:15:00') && $start_time <= strtotime($day2 .' 08:15:00') ) {
																$start = date('Y-m-d H:i', strtotime($day2 .' 08:00:00'));
															} else {
																$start = date('Y-m-d H:i', $start_time );
															} */
														/* 	$end_time = strtotime($work->end); */
															if(date('N',strtotime($day2)) < 5 ) {
																$end = date('Y-m-d H:i', strtotime($day2 .' 16:15:00'));
															} else if(date('N',strtotime($day2) ) == 5) {
																$end = date('Y-m-d H:i', strtotime($day2 .' 15:00:00'));
															}
															/* if(date('N',strtotime($day2)) < 5 ) {
																if($end_time >= strtotime($day2 .' 16:15:00') && $end_time <= strtotime($day2 .' 17:00:00') ) {
																	$end = date('Y-m-d H:i', strtotime($day2 .' 16:15:00'));
																} else {
																	$end = date('Y-m-d H:i', $end_time );
																}
															} else if(date('N',strtotime($day2) ) == 5) {
																if($end_time >= strtotime($day2 .' 14:45:00') && $end_time <= strtotime($day2 .' 16:00:00') ) {
																	$end = date('Y-m-d H:i', strtotime($day2 .' 15:00:00'));
																} else {
																	$end = date('Y-m-d H:i',$end_time );
																}
															} */
															$interval = AbsenceController::dateDifference($start, $end);
														} else {
															$interval = '';
														}

														if(date('N',strtotime($day2)) < 5 ) {
															if( strtotime($interval) > strtotime('7:00') && strtotime($interval) < strtotime('8:45')) {
																$interval = '8:15';
															}
														} else if(date('N',strtotime($day2) ) == 5) {
															if( strtotime($interval) > strtotime('6:00') && strtotime($interval) < strtotime('7:45')) {
																$interval = '7:00';
															}
														}
														$minutes = 0;
														$minutes += strstr($interval, ':', true) * 60; 
														$minutes += intval(str_replace(':','',strstr($interval, ':'))); 
														
														$sum[date('Y-m-d',strtotime($day2))] += $minutes;
													
													@endphp
													
													<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" class="sum_rr" >{{ $interval }}</td>
												@endforeach
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" class="total_rr total_sum" ></td>
											</tr>
											<tr style="display: table-row;font-family: Arial, sans-serif;font-size:10px;">
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >4</td>
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;width:150px;text-align:left;" >Vrijeme sati zastoja,prekida rada i sl.do kojega je došlo <br> krivnjom posl.ili uslijed dr. okolnosti za koje radnik nije odgovoran:</td>
												@foreach($list as $day2)
													<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
												@endforeach
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
											</tr>
											<tr style="display: table-row;font-family: Arial, sans-serif;font-size:10px;">
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >5</td>
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;width:150px;text-align:left;" >Ukupno dnevno radno vrijeme u satima te od toga:</td>
												@foreach($list as $day2)
													<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
												@endforeach
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
											</tr>
											<tr style="display: table-row;font-family: Arial, sans-serif;font-size:10px;">
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >7</td>
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;width:150px;text-align:left;" > - prekovremenog rada</td>
												@foreach($list as $day2)
													<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
												@endforeach
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;"  ></td>
											</tr>
											<tr style="display: table-row;font-family: Arial, sans-serif;font-size:10px;">
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >8</td>
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;width:150px;text-align:left;" > - sati rada u preraspodijeljenom radnom vremenu i razdoblje preraspodijeljenog radnog vremena</td>
												@foreach($list as $day2)
													<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
												@endforeach
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
											</tr>
											<tr style="display: table-row;font-family: Arial, sans-serif;font-size:10px;background-color: #ccc;" class="background_ccc">
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >8a</td>
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;width:150px;text-align:left;" >DRŽAVNI BLAGDAN</td> 
												@php
													$minutes_row_8a = 0;
												@endphp
												@foreach($list as $day2)
													@php
														$hol_hour = '';
														if (in_array( $day2, $holidaysThisYear) ) {
															if(date('N',strtotime($day2)) < 5 ) {
																$hol_hour = '8:15';
															} else if(date('N',strtotime($day2) ) == 5) {
																$hol_hour = '7:00';
															}
															$minutes = 0;
															$minutes += strstr($hol_hour, ':', true) * 60; 
															$minutes += intval(str_replace(':','',strstr($hol_hour, ':'))); 
															
															$sum[date('Y-m-d',strtotime($day2))] += $minutes;
														}
													@endphp
													<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" class="sum_bl">{{ $hol_hour }}</td>
												@endforeach
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" class="total_bl total_sum" ></td>
											</tr>
											<tr style="display: table-row;font-family: Arial, sans-serif;font-size:10px;">
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >9</td>
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;width:150px;text-align:left;" >SATI RADA NEDJELJOM ILI NERADNIM DANIMA UTVRĐENIM POSEBNIM PROPISOM</td>
												@foreach($list as $day2)
													@php
													/* 	$minutes += strstr($hol_hour, ':', true) * 60;  */
													/* 	$minutes += intval(str_replace(':','',strstr($hol_hour, ':')));  */
													/* 	$sum[date('Y-m-d',strtotime($day2))] += $minutes; */
													@endphp
													<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
												@endforeach
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
											</tr>
											<tr style="display: table-row;font-family: Arial, sans-serif;font-size:10px;">
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >10</td>
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;width:150px;text-align:left;" >Početak službenog puta / loko</td>
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
													<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >{!! $trav ? $trav->start_time : ''  !!}</td>
												@endforeach
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
											</tr>
											<tr style="display: table-row;font-family: Arial, sans-serif;font-size:10px;">
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >11</td>
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;width:150px;text-align:left;" >Kraj službenog puta / loko</td>
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
													<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >{!! $trav ? $trav->end_time : ''  !!}</td>
												@endforeach
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
											</tr>
											<tr style="display: table-row;font-family: Arial, sans-serif;font-size:10px;">
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >12</td>
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;width:150px;text-align:left;" >Sati provedeni na službenom putu</td>
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
														
														$minutes_locco = 0;
														$sum_locco_day = 0;
														if($loccos) {
															$loccos->each(function ($locco, $key) use ($day2,$minutes_locco,$sum_locco_day  ) {
																if(! $locco->travel) {
																	if(date('Y-m-d',strtotime($locco->date)) == $day2) {
																		$hasDate = true;
																		$minutes_locco += strstr($locco->interval, ':', true) * 60; 
																		$minutes_locco += intval(str_replace(':','',strstr($locco->interval, ':'))); 
																		$sum_locco_day += $minutes_locco;
																	} else {
																		$hasDate = false;
																	}
																	$locco->hasDate = $hasDate;
																	$locco->sum_day = $sum_locco_day;
																}
															});
														}
														$locco_day = $loccos->where('hasDate',true)->first();
													@endphp
													<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >{!! $trav ? $trav->interval : ''  !!} {!! $locco_day ?  $locco_day->interval : ''  !!}</td>
												@endforeach
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
											</tr>
											<tr style="display: table-row;font-family: Arial, sans-serif;font-size:10px;">
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >12a</td>
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;width:150px;text-align:left;" >Putni nalog</td>
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
														
														$minutes_locco = 0;
														$sum_locco_day = 0;
														if($loccos) {
															$loccos->each(function ($locco, $key) use ($day2,$minutes_locco,$sum_locco_day  ) {
																if(! $locco->travel) {
																	if(date('Y-m-d',strtotime($locco->date)) == $day2) {
																	$hasDate = true;
																	$minutes_locco += strstr($locco->interval, ':', true) * 60; 
																	$minutes_locco += intval(str_replace(':','',strstr($locco->interval, ':'))); 
																	$sum_locco_day += $minutes_locco;
																	
																} else {
																	$hasDate = false;
																}
																$locco->hasDate = $hasDate;
																$locco->sum_day = $sum_locco_day;
																}
															});
														}
														$locco_day = $loccos->where('hasDate',true)->first();
													@endphp
													<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" class="font_8" >{!! $trav ? 'PN - ' . $trav->car->car_index : '' !!}<br>{!! $locco_day ? 'L - ' . $locco_day->car->car_index : ''  !!}</td>
												@endforeach
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
											</tr>
											<tr style="display: table-row;font-family: Arial, sans-serif;font-size:10px;">
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >13</td>
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;width:150px;text-align:left;" >Sati pripravnosti te sati rada po pozivu</td>
												@foreach($list as $day2)
													<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
												@endforeach
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
											</tr>
											<tr style="display: table-row;font-family: Arial, sans-serif;font-size:10px;background-color: #ccc;"  class="background_ccc">
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >14</td> 
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;width:150px;text-align:left;" >GODIŠNJI ODMOR U SATIMA</td>
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
																	$time = '8:15';
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
														}
													@endphp
													<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" class="sum_go" >{!! $abs ? $abs->time : '' !!}</td>
												@endforeach
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" class="total_go total_sum" ></td>
											</tr>
											<tr style="display: table-row;font-family: Arial, sans-serif;font-size:10px;">
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >15</td>
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;width:150px;text-align:left;" >Sati privremene nesposobnosti za rad (bolovanje)</td>
												@foreach($list as $day2)
													@php
														$absences_bol = $absences->where('mark','BOL');
														$absences_bol->each(function ($absence, $key) use ($day2) {
															$days = $absence->days;
															$time = '';
															if(in_array($day2, $days )) {
																if(date('N',strtotime($day2)) < 5 ) {
																	$time = '8:15';
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
													<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >{!! $abs ? $abs->time : '' !!}</td>
												@endforeach
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
											</tr>
											<tr style="display: table-row;font-family: Arial, sans-serif;font-size:10px;background-color: #ccc;"  class="background_ccc">
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >16</td>
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;width:150px;text-align:left;" >UKUPNO BOLOVANJE SATI</td> 
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
																	$time = '8:15';
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
														}
													@endphp
													<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" class="sum_bol" >{!! $abs ? $abs->time : '' !!}</td>
												@endforeach
												@php
													if($minutes_row_16 > 0) {
														$hours = floor($minutes_row_16 / 60);
														$minutes_row_16 -= $hours * 60;
													}
												@endphp
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" class="total_bol total_sum" ></td>
											</tr>
											<tr style="display: table-row;font-family: Arial, sans-serif;font-size:10px;">
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >17</td>
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;width:150px;text-align:left;" >Vrijeme rodiljnog, roditeljskog dop. ili korištenja drugih prava sukladno posebnom propisu</td>
												@foreach($list as $day2)
													@php
														$absences_bol = $absences->where('mark','POR');
														$absences_bol->each(function ($absence, $key) use ($day2) {
															$days = $absence->days;
															$time = '';
															if(in_array($day2, $days )) {
																if(date('N',strtotime($day2)) < 5 ) {
																	$time = '8:15';
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
													<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >{!! $abs ? $abs->time : '' !!}</td>
												@endforeach
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
											</tr>
											<tr style="display: table-row;font-family: Arial, sans-serif;font-size:10px;">
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >18</td>
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;width:150px;text-align:left;" >Sati plaćenog dopusta</td>
												@foreach($list as $day2)
													@php
														$absences_bol = $absences->where('mark','PLD');
														$absences_bol->each(function ($absence, $key) use ($day2) {
															$days = $absence->days;
															$time = '';
															if(in_array($day2, $days )) {
																if(date('N',strtotime($day2)) < 5 ) {
																	$time = '8:15';
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
													<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >{!! $abs ? $abs->time : '' !!}</td>
												@endforeach
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
											</tr>
											<tr style="display: table-row;font-family: Arial, sans-serif;font-size:10px;">
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >19</td>
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;width:150px;text-align:left;" >Sati neplaćenog dopusta</td>
												@foreach($list as $day2)
													@php
														$absences_bol = $absences->where('mark','NPLD');
														$absences_bol->each(function ($absence, $key) use ($day2) {
															$days = $absence->days;
															$time = '';
															if(in_array($day2, $days )) {
																if(date('N',strtotime($day2)) < 5 ) {
																	$time = '8:15';
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
													<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >{!! $abs ? $abs->time : '' !!}</td>
												@endforeach
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
											</tr>
											<tr style="display: table-row;font-family: Arial, sans-serif;font-size:10px;">
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >20</td>
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;width:150px;text-align:left;" >Sati nenazočno u tijeku dnevnog rasporeda radnog vremena, odobrene ili neodobrene od poslodavca</td>
												@foreach($list as $day2)
													<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
												@endforeach
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
											</tr>
											<tr style="display: table-row;font-family: Arial, sans-serif;font-size:10px;">
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >21</td>
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;width:150px;text-align:left;" >Sati provedeni u štrajku</td>
												@foreach($list as $day2)
													<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
												@endforeach
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
											</tr>
											<tr style="display: table-row;font-family: Arial, sans-serif;font-size:10px;">
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >22</td>
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;width:150px;text-align:left;" >Sati isključenja s rada (lockout)</td>
												@foreach($list as $day2)
													<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
												@endforeach
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" ></td>
											</tr>
										</tbody>
										<tfoot  class="background_ccc" style="display:table-footer-group;vertical-align: middle;border-color:inherit;font-family: Arial, sans-serif;font-size: 10px;background-color: #ccc;"> 
											<tr style="display: table-row;vertical-align: inherit;border-color:inherit;font-family: Arial, sans-serif;font-size: 10px;font-weight: bold;">
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >23</td>
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;width:150px;text-align:left;">UKUPAN FOND SATI (3+8a+9+14+16)</td>
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
													<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" >{!! $hours ? sprintf('%02d:%02d', $hours, $minutes) : '' !!}</td>
												@endforeach
												<td style="font-size:8px;padding:2px 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align:center;" class="total" ></td>
											</tr>
										</tfoot>
									</table>
								</div>	
							</div>
						</main>
					</section>
				</main>
			@endforeach
			
		</div>
		<script>
		$(function() {
			$( ".section_evidention" ).each(function( index, element ) {
				sum_rr(element);
				sum_bol(element);
				sum_go(element);
				sum_bl(element);
				total_sum(element);
			});

			function sum_bol(element) {
				var total_bol = 0;
				var minutes_bol = 0;
				var minutes = 0;
				var hour = 0;
				$(element).find( ".sum_bol" ).each(function( index ) {
					var value = '';
					if ( $( this ).val() != '') {
						value = $( this ).val();
					} else if ( $( this ).text() != '') {
						value = $( this ).text();
					}
					minutes_bol += Number(value.substr(0, 1) * 60);
					minutes_bol += Number(value.substr(2, 3));

					hour = Math.floor(Number( minutes_bol )/60);
					minutes = Number(minutes_bol) - Number(hour)*60;
					total_bol = hour + ':' + ("0"+minutes).slice(-2);
					
				});
				$(element).find('.total_bol').text(total_bol);
			}
			function sum_go(element) {
				var total_go = 0;
				var minutes_go = 0;
				var minutes = 0;
				var hour = 0;
				$(element).find( ".sum_go" ).each(function( index ) {
					var value = '';
					if ( $( this ).val() != '') {
						value = $( this ).val();
					} else if ( $( this ).text() != '') {
						value = $( this ).text();
					}
					minutes_go += Number(value.substr(0, 1) * 60);
					minutes_go += Number(value.substr(2, 3));

					hour = Math.floor(Number( minutes_go )/60);
					minutes = Number(minutes_go) - Number(hour)*60;
					total_go = hour + ':' + ("0"+minutes).slice(-2);
					
				});
				$(element).find('.total_go').text(total_go);
			}
			function sum_bl(element) {
				var total_bl = 0;
				var minutes_bl = 0;
				var minutes = 0;
				var hour = 0;
				$(element).find( ".sum_bl" ).each(function( index ) {
					var value = '';
					
					if ( $( this ).val() != '') {
						value = $( this ).val();
					
					} else if ( $( this ).text() != '') {
						value = $( this ).text();
						
					}
					minutes_bl += Number(value.substr(0, 1) * 60);
					minutes_bl += Number(value.substr(2, 3));
					hour = Math.floor(Number( minutes_bl )/60);
					minutes = Number(minutes_bl) - Number(hour)*60;
					total_bl = hour + ':' + ("0"+minutes).slice(-2);
					
				});
				$(element).find('.total_bl').text(total_bl);
			}
			function sum_rr(element) {
				var total_rr = 0;
				var minutes_rr = 0;
				var minutes = 0;
				var hour = 0;
				$(element).find( ".sum_rr" ).each(function( index ) {
					var value = '';
					
					if ( $( this ).val() != '') {
						value = $( this ).val();
					
					} else if ( $( this ).text() != '') {
						value = $( this ).text();
						
					}
					if(value) {
						minutes_rr += Number(value.substr(0, value.search(':')) * 60);
						minutes_rr += Number(value.substr(value.search(':')+1, 3));
						hour = Math.floor(Number( minutes_rr )/60);
						minutes = Number(minutes_rr) - Number(hour)*60;
						total_rr = hour + ':' + ("0"+minutes).slice(-2);
					}
					
				});
				$(element).find('.total_rr').text(total_rr);
			}
			function total_sum(element) {
				var total_sum = 0;
				var minutes_total = 0;
				var minutes = 0;
				var hour = 0;
				$(element).find( ".total_sum" ).each(function( index ) {
					var value = '';
					
					if ( $( this ).val() != '') {
						value = $( this ).val();
					
					} else if ( $( this ).text() != '') {
						value = $( this ).text();
						
					}
					
					if(value != '0:00') {
						minutes_total += Number(value.substr(0, value.indexOf(":")) * 60);
						minutes_total += Number(value.substr(value.indexOf(":")+1, value.indexOf(":")+2));
					}
					hour = Math.floor(Number( minutes_total )/60);
					minutes = Number(minutes_total) - Number(hour)*60;
					total_sum = hour + ':' + ("0"+minutes).slice(-2);
					
				});
				$(element).find('.total').text(total_sum);
			}
		});
			$('#index_table').css('width','100%');
		</script>		
	</body>
</html>