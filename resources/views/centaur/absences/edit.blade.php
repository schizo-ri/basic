<div class="modal-header">
	<h3 class="panel-title">
		@if(! Sentinel::inRole('administrator') && $absence->absence->mark != 'BOL' )
			@lang('absence.request_edit_absence')
		@else
			@if( $absence->absence->mark == 'BOL' && $absence->end_date == null )
				@lang('absence.close_sick_leave')
			@else
				@lang('absence.edit_absence') 
			@endif
		@endif
	</h3>
</div>
<div class="modal-body">
	<form class="absence edit_absence" role="form" method="post" name="myForm" accept-charset="UTF-8" action="{{ route('absences.update', $absence->id ) }}">
		<input type="text" name="id" value="{{ $absence->id }}" hidden/>
		@if (Sentinel::inRole('administrator'))
			<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
				<label>@lang('basic.employee')</label>
				<select class="form-control" name="employee_id" value="{{ old('employee_id') }}" required id="select_employee" >
					<option name="employee_id" value="{{ $absence->employee->id }}"  >
						{{ $absence->employee->user['last_name']  . ' ' . $absence->employee->user['first_name'] }} 
					</option>
				</select>
				{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
		@else
			@if( $absence->absence->mark != 'BOL' )
				<input type="text" name="request_edit_absence" value="1" hidden/>
			@endif
			<p class="padd_10">@lang('absence.i'), {{ $absence->employee->user['first_name']  . ' ' . $absence->employee->user['last_name'] }} 
				<span class="">@lang('absence.please_approve') @lang('absence.request_edit')</span>
			</p>
			<input name="employee_id" type="hidden" value="{{  $absence->employee_id }}"  id="select_employee"/>
		@endif
		<div class="form-group {{ ($errors->has('erp_type')) ? 'has-error' : '' }}">
			<label>@lang('absence.abs_type')</label>
			@if( $leave_types && $absence->erp_type )
				<select class="form-control"  name="erp_type" value="{{ old('erp_type') }}" id="request_type" required >
					<option disabled selected value></option>
					@foreach($leave_types as $id => $absenceType)
						@if(  $id != 3 )
							<option value="{{ $id }}" {!! $id == $absence->erp_type ? 'selected' : '' !!} >{{ $absenceType }}</option>
						@endif
					@endforeach
				</select> 
			@else 
				<select class="form-control" name="type" value="{{ old('type') }}" id="request_type" required >
					<option disabled selected value></option>
					@foreach($absenceTypes as $absenceType)
						@if( $absenceType->mark != 'afterhour' && ( $absenceType->mark != 'SLD' ||  ($absenceType->mark == 'SLD' && Sentinel::inRole('administrator')) || Sentinel::getUser()->employee->days_off == 1 ) )
							<option value="{{ $absenceType->mark }}" {!! $absence->absence->mark ==  $absenceType->mark ? 'selected' : '' !!}>{{ $absenceType->name}}</option>
						@endif
					@endforeach
				</select> 
			@endif
			<p class="days_employee" style="display: none"></p>
			{!! ($errors->has('type') ? $errors->first('type', '<p class="text-danger">:message</p>') : '') !!}	
		</div>
		{{-- @if($tasks)
			<div class="form-group {{ ($errors->has('erp_task_id')) ? 'has-error' : '' }}">
				<label>@lang('basic.task')</label>
				<select id="select-state" name="erp_task_id" placeholder="Pick a state..."  value="{{ old('erp_task_id') }}" id="sel1" required>
					<option value="" disabled selected></option>
					@foreach ($tasks as $id => $task)
						<option class="project_list" name="erp_task_id" value="{{ $id }}" {!! $absence->erp_task_id ==  $id ? 'selected' : '' !!}>{{ $task  }}</option>
					@endforeach	
				</select>
			</div>
		@endif --}}
		<div class="form-group datum date1 float_l {{ ($errors->has('start_date')) ? 'has-error' : '' }}" >
			<label>@lang('absence.start_date')</label>
			<input name="start_date" id="start_date" class="form-control" type="date" value="{{ $absence->start_date }}" required  {!! $absence->absence->mark == 'BOL' ? 'readonly' : '' !!} >
			{!! ($errors->has('start_date') ? $errors->first('start_date', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group datum date2 float_r {{ ($errors->has('end_date')) ? 'has-error' : '' }}" >
			<label>@lang('absence.end_date')</label>
			<input name="end_date" id="end_date" class="form-control" type="date" value="{!! $absence->end_date != null ? $absence->end_date : Carbon\Carbon::now()->format('Y-m-d') !!}" required >
			{!! ($errors->has('end_date') ? $errors->first('end_date', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group col-md-12 clear_l overflow_hidd padd_0 time_group" >
			<div class="time col-md-6 {{ ($errors->has('start_time')) ? 'has-error' : '' }}" >
				<label>@lang('absence.start_time')</label>
				<input name="start_time" class="form-control" type="time" value="{{ $absence->start_time }}" required>
				{!! ($errors->has('start_time') ? $errors->first('start_time', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="time col-md-6 {{ ($errors->has('end_time')) ? 'has-error' : '' }}"  >
				<label>@lang('absence.end_time')</label>
				<input name="end_time" class="form-control" type="time" value="{{ $absence->end_time }}"required>
				{!! ($errors->has('end_time') ? $errors->first('end_time', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<p class="time_request clear_l" style="display: none">Nemoguće poslati zahtjev. Završno vrijeme je manje od početnog</p>
		</div>
		<p class="days_request clear_l" style="display: none">{{-- Nemoguće poslati zahtjev. Broj dana zahtjeva je veći od broja neiskorištenih dana za --}} <span clas="days"></span> dana </p>
		<div class="form-group {{ ($errors->has('comment')) ? 'has-error' : '' }}">
			<label>@lang('basic.comment')</label>
			<textarea rows="4" name="comment" type="text" class="form-control" value="{{ old('comment') }}" maxlength="16535" required>{{ $absence->comment }}</textarea>
			{!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		@if (Sentinel::inRole('administrator'))
			<div class="form-group">
				<label for="email">@lang('absence.email_send')</label>
				<input type="radio" name="email" value="DA"  /> @lang('basic.send_mail') 
				<input type="radio" name="email" value="NE" checked /> @lang('basic.dont_send_mail')
			</div>
		@else
			<input type="hidden" name="email" value="DA">
		@endif
		@if (Sentinel::inRole('administrator'))
			<div class="display_flex">
				<label for="decree">@lang('basic.decree')</label>
				<input class="margin_l_20" type="checkbox" name="decree" value="1" id="decree" {!! $absence->decree == 1 ? 'checked': '' !!}/>
			</div>
		@endif
		{{ csrf_field() }}
		{{ method_field('PUT') }}
		<input class="btn-submit" type="submit" value="{!! Sentinel::inRole('administrator') ? __('basic.edit') :  __('basic.send') !!}" id="stil1">
		<a href="#" rel="modal:close" class="btn-close">@lang('basic.cancel')</a>
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
$( document ).ready(function() {
	if($( "#request_type" ).val() == 'IZL') {
		$('.modal form .form-group.time_group').show();
		$( ".datum.date2" ).hide();
	}
	$( "#request_type" ).change(function() {
		if($(this).val() == 'IZL') {
			$('.form-group.time').show();
			$('.form-group.date2').hide();
			var start_date = $( "#start_date" ).val();
			var end_date = $( "#end_date" );
			end_date.val(start_date);
		} else {
			$('.form-group.time').hide();
			$('.form-group.date2').show();
		}
	});
	$( "#start_date" ).change(function() {
		var start_date = $( this ).val();
		var end_date = $( "#end_date" );
		end_date.val(start_date);
	});
	$.getScript('/../js/absence_create3.js');
});
</script>