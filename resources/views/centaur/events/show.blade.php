<div class="modal-header" {!! $event->employee->color ? 'style="border-bottom: 1px solid' . $event->employee->color . '"' : 'style="border-bottom: 1px solid #aaa"'!!}>
	<h5 class="panel-title">{{ $event->employee->user['first_name'] . ' - ' . $event->title }}</h5>
</div>
<div class="modal-body modal_body_task">
	<p><b>@lang('basic.employee'): </b>{{ $event->employee->user['first_name'] . ' ' . $event->employee->user['last_name'] }}</p>
	<p><b>@lang('calendar.event'): </b>{{ $event->title }}</p>
	<p><b>@lang('basic.date'): </b>{{ $event->date . ' ' . $event->time1 . ' - ' . $event->time2  }}</p>
	<p><b>@lang('basic.description'): </b>{{ $event->description }}</p>
</div>
<div class="modal-footer">
	<a href="{{ route('events.edit', $event->id) }}" class="btn-edit" rel="modal:open" >
		<i class="far fa-edit"></i>
	</a>
	<a href="{{ route('events.destroy', $event->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
		<i class="far fa-trash-alt"></i>
	</a>
</div>