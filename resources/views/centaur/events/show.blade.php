<div class="modal-header" {!! $event->employee->color ? 'style="border-bottom: 1px solid' . $event->employee->color . '"' : 'style="border-bottom: 1px solid #aaa"'!!}>
	<h5 class="panel-title">{{ $event->employee->user['first_name'] . ' - ' . $event->title }}</h5>
</div>
<div class="modal-body modal_body_task">
	<p><b>@lang('basic.employee'): </b>{{ $event->employee->user['first_name'] . ' ' . $event->employee->user['last_name'] }}</p>
	<p><b>@lang('calendar.event'): </b>{{ $event->title }}</p>
	<p><b>@lang('basic.date'): </b>{{ $event->date . ' ' . $event->time1 . ' - ' . $event->time2  }}</p>
	<p><b>@lang('basic.description'): </b>{{ $event->description }}</p>
</div>