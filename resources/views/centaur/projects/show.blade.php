<div class="modal-header">
	<h3 class="panel-title">@lang('basic.project') {{ $project->erp_id . ' ' . $project->name }}</h3>
</div>
<div class="modal-body">
	<table class="table_project_task">
		<thead>
			<tr>
				<th>Radni zadatak</th>
				<th>Ugovoreno sati</th>
				<th>Iskorišteno sati</th>
				<th>Tvoji sati na zadatku</th>
				<th>Tvoj udio</th>
				<th>Tvoja razlika</th>
				@foreach ($project->hasDiary->groupBy('employee_id') as $key => $employee_diaries)
					@if (Sentinel::getUser()->employee->id != $key)
						<th class="hidden_task" >{{ $employee_diaries->first()->employee->user->first_name . ' ' . $employee_diaries->first()->employee->user->last_name }}</th>
					@endif
				@endforeach
				<th>Ukupno razlika</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($work_tasks as $work_task)
				@php
					$hours = 0;
					if(count($project->hasProjectWorkTask)>0  ) {
						$projectWorkTask = $projectWorkTasks->where('task_id', $work_task->id )->first();
						if  ($projectWorkTask) {
							$hours = $projectWorkTask->hours;
						} 
					}
					$total_hours = 0;
					$diary_tasks = $diary_items->where('task_id',$work_task->id);
					if ($diary_tasks && count ($diary_tasks ) > 0) {
						foreach ($diary_tasks as $diary_task) {
							$time = explode(':',$diary_task->time);
							$hour = $time[0];
							$minutes = $time[1] / 60;
							$hour = $hour + $minutes;
							$total_hours += $hour;
						}
					}	
					$total_employee_task = 0;
					foreach ($project->hasDiary->where('employee_id', Sentinel::getUser()->employee->id ) as $employee_diary ) {
						$employee_items_task = $employee_diary->hasWorkDiaryItem->where('task_id',$work_task->id);
						foreach ($employee_items_task as $employee_task) {
							$time = explode(':', $employee_task->time);
							$hour = $time[0];
							$minutes = $time[1] / 60;
							$hour = $hour + $minutes;
							$total_employee_task += $hour;
						}
					}	
					$difference_percent = number_format( round(($total_employee_task / $total_hours*100),2),2, '.', '');
					$difference = $hours - $total_hours;
				@endphp
				<tr>
					<td>{{ $work_task->name }}</td>
					<td>{{ number_format($hours,2, ',', '.') }}</td>
					<td>{{ number_format($total_hours,2, ',', '.') }}</td>
					<td>{{ number_format($total_employee_task,2, ',', '.') }}</td>					
					<td>{!! $difference_percent.'%' !!}</td>
					<td>{!! $difference_percent != 0 && $difference >0 ? number_format(($difference / 2) * ($difference_percent / 100),2, '.', '' ) . ' h' : 0 !!}</td>
					@foreach ($project->hasDiary->groupBy('employee_id') as $key => $employee_diaries)
						@if (Sentinel::getUser()->employee->id != $key)
							@php
								$total_employee_task = 0;
							@endphp
							@foreach ($employee_diaries->where('employee_id','<>', Sentinel::getUser()->employee->id) as $employee_diary)
								@php
									$employee_items = $employee_diary->hasWorkDiaryItem;							
								@endphp
								@php
									$employee_items_task = $employee_items->where('task_id',$work_task->id);
									foreach ($employee_items_task as $employee_task) {
										$time = explode(':',$employee_task->time);
										$hour = $time[0];
										$minutes = $time[1] / 60;
										$hour = $hour + $minutes;
										$total_employee_task += $hour;
									}
								@endphp
							@endforeach
						<td class="hidden_task" >{{ number_format($total_employee_task,2, ',', '.') }}</td>
						@endif
					@endforeach 
					
					<td class="{!! $difference <0 ? 'red' : '' !!}">{{ number_format($difference,2, ',', '.') }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>
<div class="modal-footer">
	<p><span class="show_all_tasks">Vidi sve djelatnike</span></p>
</div>
<script>
	$('.show_all_tasks').on('click',function(){
		$('.hidden_task').toggle();
	});
	$('body').on($.modal.AFTER_CLOSE, function(event, modal) {
		$.modal.defaults = {
			closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
			escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
			clickClose: true,       // Allows the user to close the modal by clicking the overlay
			closeText: 'Close',     // Text content for the close <a> tag.
			closeClass: '',         // Add additional class(es) to the close <a> tag.
			showClose: true,        // Shows a (X) icon/link in the top-right corner
			modalClass: "modal",    // CSS class added to the element being displayed in the modal.
			// HTML appended to the default spinner during AJAX requests.
			spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

			showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
			fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
			fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
		};
	});
</script>
