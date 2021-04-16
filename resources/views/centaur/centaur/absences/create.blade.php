<div class="modal-header">
	<h3 class="panel-title">@lang('absence.add_absence')</h3>
</div>
<div class="modal-body">
	<form class="absence" role="form" method="post" name="myForm" accept-charset="UTF-8" action="{{ route('absences.store') }}" >
		@if (Sentinel::inRole('administrator'))
			<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
				<label>@lang('basic.employee')</label>
				<select class="form-control js-example-basic-multiple js-states" id="select_employee" name="employee_id[]" value="{{ old('employee_id') }}" size="10" autofocus  multiple="multiple" required >
					<option value="" disabled></option>
					<option value="all" >@lang('basic.all_employees')</option>
					@foreach ($employees as $employee)
						<option name="employee_id" value="{{ $employee->id }}">{{ $employee->user['last_name']  . ' ' . $employee->user['first_name'] }}</option>
					@endforeach	
				</select>
				{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
		@else
			<p class="padd_10">Ja, {{ $user->first_name  . ' ' . $user->last_name }} 
				<span class="">@lang('absence.please_approve') </span>
			</p>
			<input name="employee_id" type="hidden" value="{{ $user->employee->id }}" id="select_employee"/>
		@endif
		<input type="hidden" value="{{ $preostali_dani }}" id="preostalo_dana">
		<div class="form-group {{ ($errors->has('erp_type')) ? 'has-error' : '' }}">
			<label>@lang('absence.abs_type')</label>
			@if( isset($leave_types) && $leave_types )
				<select class="form-control"  name="erp_type" value="{{ old('erp_type') }}" id="request_type" required >
					<option disabled selected value></option>
					@foreach($leave_types as $id => $absenceType)
						@if(  $id != 3 )
							<option value="{{ $id }}">{{ $absenceType }}</option>
						@endif
					@endforeach
				</select> 
			@else 
				<select class="form-control" name="type" value="{{ old('type') }}" id="request_type" required >
					<option disabled selected value></option>
					@foreach($absenceTypes as $absenceType)
						@if ( $absenceType->mark != 'SLD' || ($absenceType->mark == 'SLD' && $user->employee->days_off == 1  ) )
							<option value="{{ $absenceType->mark }}">{{ $absenceType->name}}</option>
					
						@endif
					@endforeach
				</select> 
			@endif
			<p class="days_employee" style="display: none"></p>
			{!! ($errors->has('type') ? $errors->first('type', '<p class="text-danger">:message</p>') : '') !!}	
		</div>
		<div class="form-group datum date1 float_l  {{ ($errors->has('start_date')) ? 'has-error' : '' }}" >
			<label>@lang('absence.start_date')</label>
			<input name="start_date" id="start_date" class="form-control" type="date" value="{!!  old('start_date') ? old('start_date') : Carbon\Carbon::now()->format('Y-m-d') !!}" required disabled hidden>
			{!! ($errors->has('start_date') ? $errors->first('start_date', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group datum date2 float_r  {{ ($errors->has('end_date')) ? 'has-error' : '' }}" >
			<label>@lang('absence.end_date')</label>
			<input name="end_date" id="end_date" class="form-control" type="date" value="{!!  old('end_date') ? old('end_date') : Carbon\Carbon::now()->format('Y-m-d') !!}" required disabled hidden>
			{!! ($errors->has('end_date') ? $errors->first('end_date', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		{{-- @if(isset($tasks) )
			<div class="form-group tasks {{ ($errors->has('erp_task_id')) ? 'has-error' : '' }}">
				<label>@lang('basic.task')</label>
				<select id="select-state" name="erp_task_id" placeholder="Pick a state..."  value="{{ old('erp_task_id') }}" id="sel1" required>
					<option value="" disabled selected></option>
					@foreach ($tasks as $id => $task)
						<option class="project_list" name="erp_task_id" value="{{ $id }}">{{ $task  }}</option>
					@endforeach	
				</select>
			</div>
		@endif --}}
		<div class="form-group col-md-12 clear_l overflow_hidd padd_0 time_group" >
            <div class="time float_l {{ ($errors->has('start_time')) ? 'has-error' : '' }}" >
                <label>@lang('absence.start_time')</label>
                <input name="start_time" class="form-control " type="time" value="{!!  old('start_time') ? old('start_time') : '07:00' !!}" required>
                {!! ($errors->has('start_time') ? $errors->first('start_time', '<p class="text-danger">:message</p>') : '') !!}
            </div>
            <div class="time float_l {{ ($errors->has('end_time')) ? 'has-error' : '' }}"  >
                <label>@lang('absence.end_time')</label>
                <input name="end_time" class="form-control" type="time" value="{!!  old('end_time') ? old('end_time') : '15:00' !!}" required>
                {!! ($errors->has('end_time') ? $errors->first('end_time', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<p class="time_request clear_l" style="display: none">Nemoguće poslati zahtjev. Završno vrijeme je manje od početnog</p>
		</div>
		<p class="days_request clear_l" style="display: none">{{-- Nemoguće poslati zahtjev. Broj dana zahtjeva je veći od broja neiskorištenih dana za --}} <span clas="days"></span> dana </p>
		
		<div class="form-group clear_l {{ ($errors->has('comment')) ? 'has-error' : '' }}">
			<label>@lang('basic.comment')</label>
			<textarea rows="4" name="comment" type="text" class="form-control" value="{{ old('comment') }}" maxlength="16535" required></textarea>
			{!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		@if (Sentinel::inRole('administrator'))
			<div class="form-group">
				<label for="email">@lang('absence.email_send')</label>
				<span><input type="radio" name="email" value="DA" id="email_da" checked /> <label for="email_da">@lang('basic.send_mail')</label> </span>
				<span><input type="radio" name="email" value="NE" id="email_ne"  /> <label for="email_ne">@lang('basic.dont_send_mail')</label></span>
			</div>
		@else
			<input type="hidden" name="email" value="DA">
		@endif
		@if (Sentinel::inRole('administrator'))
			<div class="display_flex">
				<label for="decree">@lang('basic.decree')</label>
				<input class="margin_l_20" type="checkbox" name="decree" value="1" id="decree" />
			</div>
		@endif
		{{ csrf_field() }}
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1" >
		<a href="#" rel="modal:close" class="btn-close">@lang('basic.cancel')</a>
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
	$('.btn-submit').on('click',function(){
		$( this ).hide();
	});
	
	$.getScript('/../js/absence_create.js');
</script>