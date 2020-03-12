<div class="modal-header" {!! $task->employee->color ? 'style="border-bottom: 1px solid' . $task->employee->color . '"' : 'style="border-bottom: 1px solid #aaa"'!!}>
	<h5 class="panel-title">{{ $task->employee->user['first_name'] . ' - ' . $task->title }}</h5>
</div>
<div class="modal-body modal_body_task">
	<p><b>@lang('basic.employee'): </b>{{ $task->employee->user['first_name'] . ' ' . $task->employee->user['last_name'] }}</p>
	<p><b>@lang('calendar.task'): </b>{{ $task->title }}</p>
	<p><b>@lang('basic.date'): </b>{{ $task->date . ' ' . $task->time1 . ' - ' . $task->time2  }}</p>
	<p><b>@lang('basic.car'): </b>{{ $task->car['model'] . ' ' . $task->car['registration'] }}</p>
	<p><b>@lang('basic.description'): </b>{{ $task->description }}</p>
</div>