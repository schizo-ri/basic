<!-- Jquery validation -->
{{-- <script src="{{ URL::asset('/../node_modules/jquery-validation/dist/jquery.validate.js') }}"></script>
<script src="{{ URL::asset('/../node_modules/jquery-validation/dist/additional-methods.js') }}"></script> --}}
{{-- @if (App::isLocale('hr'))
	<script src="{{ URL::asset('/../node_modules/jquery-validation/dist/localization/messages_hr.js') }}"></script>
@endif --}}
<div class="modal-header">
	<h3 class="panel-title">@lang('calendar.edit_event')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('events.update', $event->id) }}">
		<div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
			<label>@lang('basic.title')</label>
			<input name="title" type="text" class="form-control" value="{{ $event->title }}" required>
			{!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group">
			<label >Status</label>
			<span><input type="radio" name="status" value="1" id="status1" {!! $event->status == 1 ? 'checked' : '' !!}  /><label for="status1"> @lang('basic.private')</label> </span>
			<span><input type="radio" name="status" value="0" id="status0"  {!! $event->status == 0 ? 'checked' : '' !!}  /> <label for="status0">Duplico </label></span>
		</div>
		<div class="form-group datum {{ ($errors->has('prezime')) ? 'has-error' : '' }}">
			<label>@lang('basic.date')</label>
			<input name="date" type="date" class="form-control" value="{{  $event->date }}" required>
			{!! ($errors->has('prezime') ? $errors->first('prezime', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<label class="time_label">@lang('basic.time')</label>
		<div class="form-group time {{ ($errors->has('time1')) ? 'has-error' : '' }}">
			<input name="time1" class="form-control" type="time" value="{{ $event->time1 }}" required />
			{!! ($errors->has('time1') ? $errors->first('time1', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group span">
			<span>@lang('calendar.to')</span>
		</div>
		<div class="form-group time {{ ($errors->has('time2')) ? 'has-error' : '' }}">
		<input name="time2" class="form-control" type="time" value="{{ $event->time2 }}" required />
			{!! ($errors->has('time2') ? $errors->first('time2', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group description clear_l {{ ($errors->has('description')) ? 'has-error' : '' }}">
			<label>@lang('basic.description')</label>
			<textarea name="description" class="form-control" type="text" required >{{ $event->description }}</textarea>
			{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		
		<input name="type" type="hidden" value="{{ $type }}" id="event_type" />
		{{ csrf_field() }}
		@method('PUT')
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
		<a href="" class="modal_close float_r" rel="modal:close">@lang('basic.cancel')</a>
	</form>
</div>
<script>
$.getScript( '/../js/validate.js');

	/* $( "form" ).validate({
		rules: {
			title: {
				required: true,
				maxlength: 255
			},
			date: {
				required: true,
			},
			time1: {
				required: true,
			},
			time2: {
				required: true,
			},
			description: {
				required: true,
				maxlength: 65535
			}
		}
	}); */
</script>