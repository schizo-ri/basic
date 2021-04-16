<div class="modal-header">
	<h3 class="panel-title">@lang('basic.edit_plan') - {{ $vacation_plan->employee->user->first_name . ' ' . $vacation_plan->employee->user->last_name }}</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('vacation_plans.update', $vacation_plan->id) }}">
		<div class="form-group   {{ ($errors->has('start_period')) ? 'has-error' : '' }}">
			<label>@lang('absence.start_date')</label>
			<select name="start_date" class="form-control" id="start_date">
				@foreach ($dates as $date)
					<option value="{{ $date }}">{{ date('d.m.Y',strtotime($date)) }}</option>
				@endforeach
			</select>
			{!! ($errors->has('start_period') ? $errors->first('start_period', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		{{ method_field('PUT') }}
		{{ csrf_field() }}
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
		<a href="" class="modal_close float_r" rel="modal:close">@lang('basic.cancel')</a>
	</form>
</div>