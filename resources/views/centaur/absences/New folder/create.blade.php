<div class="modal-header">
	<h3 class="panel-title">@lang('absence.add_absence')</h3>
</div>
<div class="modal-body">
	<form class="absence" role="form" method="post" name="myForm" accept-charset="UTF-8" action="{{ route('absences.store') }}" >
		@if (Sentinel::inRole('administrator'))
			<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
				<label>@lang('basic.employee')</label>
				<select class="form-control" id="select_employee" name="employee_id[]" value="{{ old('employee_id') }}" size="10" autofocus multiple required >
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
		<div class="form-group {{ ($errors->has('type')) ? 'has-error' : '' }}">
			<label>@lang('absence.abs_type')</label>
			<select class="form-control"  name="type" value="{{ old('type') }}" id="request_type" required >
				<option disabled selected value></option>
				@foreach($absenceTypes as $absenceType)
					@if(  $absenceType->mark != 'SLD' || ( $absenceType->mark == 'SLD' && Sentinel::inRole('administrator') || Sentinel::getUser()->employee->days_off == 1 ))
						<option value="{{ $absenceType->mark }}" {!! $type ==  $absenceType->mark ? 'selected' : '' !!}>{{ $absenceType->name}}</option>
					@endif
				@endforeach
			</select> 
			<p class="days_employee" style="display: none"></p>
			{!! ($errors->has('type') ? $errors->first('type', '<p class="text-danger">:message</p>') : '') !!}	
		</div>
		
		<div class="form-group datum date1 float_l  {{ ($errors->has('start_date')) ? 'has-error' : '' }}" >
			<label>@lang('absence.start_date')</label>
			<input name="start_date" id="start_date" class="form-control" type="date" value="{!!  old('start_date') ? old('start_date') : Carbon\Carbon::now()->format('Y-m-d') !!}" required>
			{!! ($errors->has('start_date') ? $errors->first('start_date', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		
		<div class="form-group datum  date2 clear_l float_r  {{ ($errors->has('end_date')) ? 'has-error' : '' }}" >
			<label>@lang('absence.end_date')</label>
			<input name="end_date" id="end_date" class="form-control" type="date" value="{!!  old('end_date') ? old('end_date') : Carbon\Carbon::now()->format('Y-m-d') !!}" required>
			{!! ($errors->has('end_date') ? $errors->first('end_date', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="col-md-12 clear_l overflow_hidd padd_0" >
            <div class="form-group time {{ ($errors->has('start_time')) ? 'has-error' : '' }}" >
                <label>@lang('absence.start_time')</label>
                <input name="start_time" class="form-control " type="time" value="{!!  old('start_time') ? old('start_time') : '08:00' !!}" required>
                {!! ($errors->has('start_time') ? $errors->first('start_time', '<p class="text-danger">:message</p>') : '') !!}
            </div>
            <div class="form-group time {{ ($errors->has('end_time')) ? 'has-error' : '' }}"  >
                <label>@lang('absence.end_time')</label>
                <input name="end_time" class="form-control" type="time" value="{!!  old('end_time') ? old('end_time') : '16:00' !!}" required>
                {!! ($errors->has('end_time') ? $errors->first('end_time', '<p class="text-danger">:message</p>') : '') !!}
            </div>
		</div>
		<p class="days_request clear_l" style="display: none">Nemoguće poslati zahtjev. Broj dana zahtjeva je veći od broja neiskorištenih dana za <span clas="days"></span> dana </p>
		<div class="form-group clear_l {{ ($errors->has('comment')) ? 'has-error' : '' }}">
			<label>@lang('basic.comment')</label>
			<textarea rows="4" name="comment" type="text" class="form-control" value="{{ old('comment') }}" maxlength="16535" required></textarea>
			{!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		@if (Sentinel::inRole('administrator'))
			<div class="form-group">
				<label for="email">@lang('absence.email_send')</label>
				<span><input type="radio" name="email" value="DA" checked /> @lang('basic.send_mail') </span>
				<span><input type="radio" name="email" value="NE" /> @lang('basic.dont_send_mail')</span>
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
	$.getScript('/../js/absence_create3.js');
</script>