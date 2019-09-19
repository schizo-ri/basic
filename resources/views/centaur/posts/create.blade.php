<div class="modal-body body_post">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('posts.store') }}" >
		<div class="form-group {{ ($errors->has('to_department_id')) ? 'has-error' : '' }}">
			<label class="message_to_dep">@lang('basic.message_to_dep')</label>
			<select class="form-control department_id" name="to_department_id">
					<option selected disabled></option>
					@foreach($departments as $department)
					<option value="{{ $department->id}}" >{{ $department->name }}</option>
				@endforeach
			</select>
		</div>		
		<div class="form-group {{ ($errors->has('to_employee_id')) ? 'has-error' : '' }}">
			<label class="message_to_empl">@lang('basic.message_to_empl')</label>
			<select class="form-control employee_id" name="to_employee_id" required>
					<option selected disabled</option>
					@foreach($employees as $employee)
					<option value="{{ $employee->id}}" >{{ $employee->first_name . ' ' .  $employee->last_name }}</option>
				@endforeach
			</select>
		</div>
		{!! ($errors->has('to_employee_id') ? $errors->first('to_employee_id', '<p class="text-danger">:message</p>') : '') !!}
		<div class="form-group {{ ($errors->has('content')) ? 'has-error' : '' }}">
			<label>@lang('basic.message')</label>
			<textarea name="content" type="text" class="form-control post_content" rows="7" required >{{ old('content') }}</textarea>
			
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
</script>