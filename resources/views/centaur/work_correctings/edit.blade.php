<div class="modal-header">
	<h3 class="panel-title">Ispravak popravka rada</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('work_correctings.update', $work_correcting->id) }}" enctype="multipart/form-data">
		<div class="form-group">
			<label>@lang('basic.employee')</label>
			<select class="form-control" name="employee_id" value="" id="select_level" required>
				<option value="" selected>
				@foreach($employees as $employee)
					<option value="{{ $employee->id }}" {!! $work_correcting->employee_id == $employee->id ? 'selected' : '' !!}>{{ $employee->user->first_name . ' ' . $employee->user->last_name }}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group">
			<label>@lang('basic.project')</label>
			<select class="form-control" name="project_id" value="" id="select_level" required>
				<option value="" selected>
				@foreach($projects as $project)
					<option value="{{ $project->id }}" {!! $work_correcting->project_id == $project->id ? 'selected' : '' !!}>{{ $project->erp_id . ' ' . $project->name }}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group datum ">
			<label>@lang('basic.date')</label>
			<input type="date" class="form-control" name="date" value="{{ $work_correcting->date }}" required>
		</div>
		<div class="form-group col-md-12 clear_l overflow_hidd padd_0 time_group" >
			<label>Vrijeme potrebno za popravak</label>
			<div class="form-group time ">
				<input type="time" class="form-control" name="time" value="{{ $work_correcting->time }}" required>
			</div>
		</div>
		<div class="form-group clear_l {{ ($errors->has('comment')) ? 'has-error' : '' }}">
			<label>@lang('basic.comment')</label>
			<textarea rows="4" name="comment" type="text" class="form-control" maxlength="16535" required>{{ $work_correcting->comment }}</textarea>
			{!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		@csrf
		@method('PUT')
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
