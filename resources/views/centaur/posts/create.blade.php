<div class="modal-body body_post">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('posts.store') }}" >
		@if(! isset($employee_publish))
			<div class="form-group {{ ($errors->has('to_department_id')) ? 'has-error' : '' }}">
				<label class="message_to_dep">@lang('basic.message_to_dep')</label>
				<select class="form-control department_id" name="to_department_id" >
					<option selected disabled></option>
					@foreach($departments as $department)
						<option value="{{ $department->id}}" >{{ $department->name }}</option>
					@endforeach
				</select>
				{!! ($errors->has('to_department_id') ? $errors->first('to_department_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>		
		@endif
		<div class="form-group {{ ($errors->has('to_employee_id')) ? 'has-error' : '' }}">
			<label class="message_to_empl">@lang('basic.message_to_empl') {!! isset($employee_publish) ? $employee_publish->user['first_name'] . ' ' .  $employee_publish->user['last_name'] : '' !!}</label>
			@if(isset($employee_publish))
				<input class="form-control employee_id" type="hidden" name="to_employee_id" value="{{ $employee_publish->id }}" required />
			@else 
				<select class="form-control employee_id" name="to_employee_id" required >
					<option selected disabled></option>
					@foreach($employees as $employee)
						<option value="{{ $employee->id}}" >{{ $employee->last_name . ' ' .  $employee->first_name }}</option>
					@endforeach
				</select>
			@endif
			{!! ($errors->has('to_employee_id') ? $errors->first('to_employee_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('content')) ? 'has-error' : '' }}">
			<label>@lang('basic.message')</label>
			<textarea name="content" type="text" class="form-control post_content" maxlength="65535" rows="7" required >{{ old('content') }}</textarea>
			{!! ($errors->has('content') ? $errors->first('content', '<p class="text-danger">:message</p>') : '') !!}
		</div>		
		{{ csrf_field() }}
		<input class="btn-submit" type="submit" value="{{ __('basic.send')}}">
		<a href="" class="modal_close float_r" rel="modal:close">@lang('basic.cancel')</a>
	</form>
</div>
<script>
	
	$('.form-control.department_id').hide();
	$('.message_to_dep').click(function(){
		$('.form-control.department_id').toggle();
		$('.form-control.employee_id').toggle();
		if($(".form-control.department_id").css("display") == "none"){
			$('.form-control.department_id').attr('required',false);
			$('.form-control.employee_id').attr('required',true);
		}else{
			$('.form-control.department_id').attr('required',true);
			$('.form-control.employee_id').attr('required',false);
		}
	});
	$('.message_to_empl').click(function(){
		$('.form-control.department_id').toggle();
		$('.form-control.employee_id').toggle();
		if($(".form-control.department_id").css("display") == "none"){
			$('.form-control.department_id').attr('required',false);
			$('.form-control.employee_id').attr('required',true);
		}else{
			$('.form-control.department_id').attr('required',true);
			$('.form-control.employee_id').attr('required',false);
		}
	});
	$.getScript( '/../js/validate.js');
</script>
	
	