<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_work_task')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('work_tasks.update', $workTask->id ) }}">
		<div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
			<label>@lang('basic.name')</label>
			<input name="name" type="text" class="form-control" value="{{ $workTask->name }}" maxlength="191" required >
			{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('description'))  ? 'has-error' : '' }}">
			<label>@lang('basic.description')</label>
			<textarea name="description" type="text" class="form-control" rows="3" required>{{ $workTask->description }}</textarea>
			{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		@csrf
		@method('PUT')
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
