<div class="modal-header">
	<h3 class="panel-title">@lang('calendar.tasks')</h3>
</div>
<div class="modal-body">
	@if (count($tasks_group_date) > 0)
		@foreach ($tasks_group_date as $date => $tasks)
			<h5>{{ date('d.m.Y',strtotime($date)) }}</h5>
			@foreach ($tasks as $task)
				<ul class="task_list" {!! $task->employee->color ? 'style="background-color:'.$task->employee->color.'"':'style="background:none"' !!} >
					<li class="task_item">
						<span class="col-3">{{ $task->employee->user['first_name'] }}</span> 
						<span class="col-3">{!! $task->car_id ? ' - ' . $task->car->registration : '' !!}</span>
						<span class="col-3">{{  $task->title }}</span>
						<span class="col-3">{{ class_basename($task) }}</span>
					</li>
				</ul>
			@endforeach
		@endforeach
	@else
		<p class="no_data">@lang('basic.no_data')</p>
	@endif

</div>