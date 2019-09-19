<div class="event_show">
        <div class="type_event" >
			<h3><span class="blue"></span>Add event</h3>
            <p>Creat your event, and add other users</p>
        </div>
        <div class="type_task" >
			<h3><span class="orange"></span>Add task</h3>
            <p>Save any task so you don’t miss any</p>
        </div>
        <div class="type_other" >
			<h3><span class="green"></span>Add other events</h3>
            <p>Add celebrating, birthdays for office parties</p>
        </div>
</div>
<script>
	$.getScript( '/../js/event_create_show.js');
</script>
<div class="event_hidden">
	<div class="modal-header">
		<h3 class="panel-title">@lang('calendar.add_event')</h3>
	</div>
	<div class="modal-body">
		<form accept-charset="UTF-8" role="form" method="post" action="{{ route('events.store') }}">
			<div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
				<label>@lang('basic.title')</label>
				<input name="title" type="text" class="form-control" required>
				{!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group datum {{ ($errors->has('prezime')) ? 'has-error' : '' }}">
				<label>@lang('basic.date')</label>
				<input name="date" type="date" class="form-control" value="{!! isset($date) ? $date : Carbon\Carbon::now()->format('Y-m-d') !!}" required>
				{!! ($errors->has('prezime') ? $errors->first('prezime', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<label class="time_label">@lang('basic.time')</label>
			<div class="form-group time {{ ($errors->has('time1')) ? 'has-error' : '' }}">
				<input name="time1" class="form-control" type="time" value="{!! isset($time1 ) ? $time1 : '08:00' !!}" required />
				{!! ($errors->has('time1') ? $errors->first('time1', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group span">
				<span>@lang('calendar.to')</span>
			</div>
			<div class="form-group time {{ ($errors->has('time2')) ? 'has-error' : '' }}">
			<input name="time2" class="form-control" type="time" value="{!! isset($time1 ) ? $time1 : '08:00' !!}" required />
				{!! ($errors->has('time2') ? $errors->first('time2', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group description clear_l {{ ($errors->has('description')) ? 'has-error' : '' }}">
				<label>@lang('basic.description')</label>
				<textarea name="description" class="form-control" type="text" required ></textarea>
				{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<input name="type" type="hidden" value="{{ $type }}" id="event_type" />
			{{ csrf_field() }}
			<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
			<a href="" class="modal_close float_r" rel="modal:close">@lang('basic.cancel')</a>
		</form>
	</div>
</div>