<div class="modal-header">
	<h3 class="panel-title">@lang('basic.edit_work_records')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('work_records.update',$work_record->id) }}" >
		<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
			<label>@lang('basic.employee')</label>
			<select class="form-control" name="employee_id" value="{{ $work_record->employee_id }}" autofocus required >
				@foreach ($employees as $employee)
					<option value="{{$employee->id}}" {!! $work_record->employee_id == $employee->id ? 'selected' : '' !!}>{{ $employee->first_name . ' ' . $employee->last_name }}</option>
				@endforeach	
			</select>
			{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group datum date1 float_l  {{ ($errors->has('start')) ? 'has-error' : '' }}" >
			<label>@lang('absence.start_date')</label>
			<input name="start" id="start" class="form-control" type="datetime-local" value="{{ date('Y-m-d\TH:i',strtotime($work_record->start)) }}" required>
			{!! ($errors->has('start') ? $errors->first('start', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group datum  date2 float_r  {{ ($errors->has('end')) ? 'has-error' : '' }}" >
			<label>@lang('absence.end_date')</label>
			<input name="end" id="end" class="form-control" type="datetime-local" value="{!! $work_record->end ? date('Y-m-d\TH:i',strtotime($work_record->end))  : date('Y-m-d\TH:i',strtotime($work_record->start)) !!}" >
			{!! ($errors->has('end') ? $errors->first('end', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<input type="hidden" name="checkout" id="entry" value="checkout">
		{{ csrf_field() }}
			{{ method_field('PUT') }}
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
$.getScript( '/../js/validate.js');
</script>