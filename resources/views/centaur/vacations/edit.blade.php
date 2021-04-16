<div class="modal-header">
	<h3 class="panel-title">@lang('basic.edit_plan')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('vacations.update', $vacation->id) }}">
		<div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
			<label>@lang('basic.title')</label>
			<input name="title" type="text" id="title" class="form-control" maxlength="100" value="{{ $vacation->title }}" required>
			{!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
			<label>@lang('basic.description')</label>
			<textarea name="description"  class="form-control" rows="5" maxlength="65535" required >{{ $vacation->description }}</textarea>
			{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group datum float_l {{ ($errors->has('start_period')) ? 'has-error' : '' }}">
			<label>@lang('absence.start_date')</label>
			<input name="start_period" type="date" id="start_period" class="form-control" value="{{ $vacation->start_period }}" required>
			{!! ($errors->has('start_period') ? $errors->first('start_period', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group datum date1 float_r  {{ ($errors->has('start_date')) ? 'has-error' : '' }}" >
			<label>@lang('absence.end_date')</label>
			<input name="end_period" type="date" id="end_period" class="form-control" value="{{ $vacation->end_period }}" required>
			{!! ($errors->has('end_period') ? $errors->first('end_period', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group datum date2 float_l {{ ($errors->has('end_date')) ? 'has-error' : '' }}">
			<label>@lang('absence.closing')</label>
			<input name="end_date" type="date" id="end_date"  class="form-control" value="{{ $vacation->end_date }}" required>
			{!! ($errors->has('end_date') ? $errors->first('end_date', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('interval'))  ? 'has-error' : '' }} clear_l" id="period">
			<label class="label_period">Period korištenja (broj dana)</label>
			<input name="interval" type="number" class="form-control" min="1" max="21" value="{{  $vacation->interval }}" required>
		</div>
		<div class="form-group {{ ($errors->has('no_week'))  ? 'has-error' : '' }} clear_l" id="period">
			<label class="label_period">Broj uzastopnih tjedana korištenja</label>
			<input name="no_week" type="number" class="form-control" min="1" max="21" value="{{  $vacation->no_week }}" required>
		</div>
		<div class="form-group">
			<label>Status</label>
			<input type="radio" id="status_0" name="active" value="0" {!! $vacation->active == 0 ? 'checked' : '' !!} /><label for="status_0" class="decree">@lang('basic.inactive')</label>
			<input type="radio" id="status_1" name="active" value="1" {!! $vacation->active == 1 ? 'checked' : '' !!} /><label for="status_1" class="decree">@lang('basic.active')</label>
		</div>
		<div class="form-group">
			<table>
				<thead>
					<tr>
						<th class="col-8 align_l">@lang('basic.department')</th>
						<th class="col-2">@lang('basic.number_people')</th>
					</tr>				
				</thead>
				<tbody>
					@foreach ($departments as $department)
						@if (count($department->hasWorks) > 0)
							@php
								$plan = json_decode($vacation->plan, true);
								$dep_plan = null;
								if( count($plan) > 0 && isset($plan[$department->id])) {
									$dep_plan = $plan[$department->id];
								}
							@endphp
							<tr>
								<td >{{ $department->name }}</td>
								<td><input name="no_people[{{ $department->id}}]" type="number" min="1" max="10" value="{!! $dep_plan && isset($dep_plan['no_people']) ? $dep_plan['no_people'] : '' !!}"></td>
							</tr>
							<tr>
								<td colspan="2">
									<p class="padd_l_15">Nedozvoljeno istovremeno korištenje:</p>
									@php
										$i=0;
									@endphp
									@foreach ($department->hasWorks as $works)
										@foreach ($works->workers as $employee)
											@if ( ! $employee->checkout )
												<span class="col-6 float_l">
													<input type="checkbox" name="employee_id[{{ $department->id}}][]" id="employee_id[{{ $department->id}}][{{$i}}]" value="{{ $employee->id }}" 
													{!! $dep_plan && isset($dep_plan['employees'] ) && in_array( $employee->id, explode( ',', $dep_plan['employees'])) ? 'checked' : '' !!} />
													<label class="decree" for="employee_id[{{ $department->id}}][{{$i}}]">{{ $employee->user['last_name']  . ' ' . $employee->user['first_name'] }} </label>
												</span>
												@php
													$i++;
												@endphp
											@endif
										@endforeach	
									@endforeach	
								</td>
							</tr>
						@endif
					@endforeach
				</tbody>
			</table>
		</div>
		{{ method_field('PUT') }}
		{{ csrf_field() }}
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
		<a href="" class="modal_close float_r" rel="modal:close">@lang('basic.cancel')</a>
	</form>
</div>