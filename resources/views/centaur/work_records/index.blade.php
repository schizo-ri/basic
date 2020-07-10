@php
	use App\Models\WorkRecord;

	if(isset($_GET['date'])) {
		$request_date = $_GET['date'];
	} else {
		$request_date = date('Y-m-d');
	}

@endphp
<header class="page-header work_record_header">
	<div class="index_table_filter">
		<label>
			<input type="search" placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
		</label>
		@if(Sentinel::getUser()->hasAccess(['work_records.create']) || in_array('work_records.create', $permission_dep))
			<a class="btn-new" href="{{ route('work_records.create') }}"  rel="modal:open">
				<i class="fas fa-plus"></i>
			</a>
		@endif
		<span class="change_view"></span>
		<span class="change_view2"></span>
		<select class="change_month select_filter ">
			@foreach ($months as $month)
				<option value="{{ $month }}">{{ date('Y m',strtotime($month))}}</option>
			@endforeach
		</select>
		<select class="change_employee_work select_filter ">
			<option value="" selected>{{ __('basic.view_all')}} </option>
			@foreach ($employees as $employee)
				<option value="empl_{{ $employee->id }}">{{ $employee->user['first_name'] . ' ' . $employee->user['last_name'] }}</option>
			@endforeach
		</select>
	</div>
</header>
<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="table-responsive first_view">
		@if(count($work_records))
			<table id="index_table" class="display table table-hover ">
				<thead>
					<tr>
						<th>@lang('basic.employee')</th>
						<th>@lang('absence.start_time')</th>
						<th>@lang('absence.end_time')</th>
						<th>@lang('absence.time')</th>
						<th class="not-export-column">@lang('basic.options')</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($work_records as $record)
						<tr class="empl_{{ $record->employee_id }}">
							<td>{{ $record->employee->user['first_name'] . ' ' . $record->employee->user['last_name'] }}</td>
							<td>{{ $record->start }}</td>
							<td>{{ $record->end }}</td>
							<td>{{ $record->interval  }}</td>
							<td class="center">
								<!-- <button class="collapsible option_dots float_r"></button> -->
								@if(Sentinel::getUser()->hasAccess(['work_records.update']) || in_array('work_records.update', $permission_dep))
									<a href="{{ route('work_records.edit', $record->id) }}" class="btn-edit" rel="modal:open" >
											<i class="far fa-edit"></i>
									</a>
								@endif
								@if(  Sentinel::getUser()->hasAccess(['work_records.delete']) || in_array('work_records.delete', $permission_dep))
									<a href="{{ route('work_records.destroy', $record->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}" >
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
	<div class="second_view">
		<div class="table-responsive1">
			<table id="index_table1" class="display table_work_record" style="width: 100%;">
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
									$work = WorkRecord::where('employee_id', $employee->id)->whereDate('start', $dan2)->first();
									if($work) {
										if($work->end) {
											$interval = date_diff(date_create($work->start),date_create($work->end));
											$work->interval = date('H:i',strtotime( $interval->h .':'.$interval->i));
										} 
										
										$minutes += $interval->h * 60; 
										$minutes += $interval->i; 
									}
									if(count($absences ) >0) {
										$absence_employee = $absences->where('employee_id',  $employee->id);
									}
								?>
								<td class="td_izostanak {!! isset($work->interval) ? 'red_rad' : '' !!}">
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
	$('.change_view').click(function(){
	
		$( ".change_view" ).toggle();
		$( ".change_view2" ).toggle();
	
		$('.second_view').css('display','block');
		$('main>.table-responsive').toggle();		
	});
	$( ".change_view2" ).click(function() {
	
		$( ".change_view" ).toggle();
		$( ".change_view2" ).toggle();
		
		$('.second_view').css('display','none');
		$('main>.table-responsive').toggle();
	});

	$(function(){
		$.getScript( '/../js/filter_table.js');
		$.getScript( '/../restfulizer.js');
	});
	var month;
	var is_visible;
		var not_visible;
	$( ".change_month" ).change(function() {
		
		if($('.first_view').is(":visible")) {
			is_visible = ".first_view";
			not_visible = ".second_view";
		} else if ($('.second_view').is(":visible")){
			is_visible = ".second_view";
			not_visible = ".first_view";
		}
	
		if($(this).val() != undefined) {
			month = $(this).val().toLowerCase();
			console.log(month);
			var url = location.origin + '/work_records';
			$.ajax({
				type: "GET",
				date: { 'date': month },
				url: url + '?date='+month, 
				success: function(response) {
					$( '#admin_page' ).load(url+ '?date='+month, function( response, status, xhr ) {
						if ( status == "error" ) {
							var msg = "Sorry but there was an error: ";
							$( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
						}
						$.getScript( 'js/datatables.js');
						$.getScript( 'js/filter_table.js');
						$( is_visible ).show();
						$( not_visible ).hide();
						
						$( ".change_month option[value="+month+"]" ).attr("selected","selected" );
					});
					
				},
				error: function(jqXhr, json, errorThrown) {
					var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
										'message':  jqXhr.responseJSON.message,
										'file':  jqXhr.responseJSON.file,
										'line':  jqXhr.responseJSON.line };

					$.ajax({
						url: 'errorMessage',
						type: "get",
						data: data_to_send,
						success: function( response ) {
							$('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
						}, 
						error: function(jqXhr, json, errorThrown) {
							console.log(jqXhr.responseJSON); 
							
						}
					});
				}
			});
		
		}
		
	});
	$(function() {
		$( ".td_izostanak:contains('GO')" ).each(function( index ) {
			$( this ).addClass('abs_GO');
		});
		$( ".td_izostanak:contains('BOL')" ).each(function( index ) {
			$( this ).addClass('abs_BOL');
		});
	});
	$( ".change_employee_work" ).change(function() {
		var value = $(this).val().toLowerCase();
		console.log(value);
		
		$("tbody tr").filter(function() {
			//$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			$(this).toggle($(this).hasClass(value));
		});
		if(value == '') {
			$("tbody tr").show();
		
		}
	});
</script>		