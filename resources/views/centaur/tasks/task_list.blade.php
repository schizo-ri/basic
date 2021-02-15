<div class="modal-header">
	<h3 class="panel-title">@lang('calendar.tasks')</h3>
	@if(Sentinel::getUser()->hasAccess(['tasks.create']) )
		<a class="btn-new" href="{{ route('tasks.create') }}" rel="modal:open">
			<i class="fas fa-plus"></i>
		</a>
	@endif
</div>
<div class="modal-body">
	@if (count($tasks_employee) > 0)
		<table class="task_list"  >
			<tbody>
				@foreach ($tasks_employee as $task_employee)
					@if($task_employee->task)
						<tr class="task_item" {!! $task_employee->employee->color ? 'style="background-color:'.$task_employee->employee->color.'"':'style="background:none"' !!}>
							<td class="col-2">{{ date('d.m.Y',strtotime($task_employee->created_at)) }}</td> 
							<td class="col-2">{{ $task_employee->employee->user['first_name'] . ' - ' .  $task_employee->employee->user['last_name']  }}</td> 
							<td class="col-5">{{  $task_employee->task->task . ' ' . $task_employee->task->description }}</td>
							<td class="col-3">{!! $task_employee->task->car ? ' - ' . $task_employee->task->car->registration : '' !!}</td>
							<!-- <span class="col-3">{{ class_basename($task_employee->task) }}</span> -->
						</tr>
					@endif
				@endforeach
			</tbody>
		</table>
	@else
		<p class="no_data">@lang('basic.no_data')</p>
	@endif
</div>