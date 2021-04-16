<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_department')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('departments.store') }}" enctype="multipart/form-data">
		<div class="form-group {{ ($errors->has('company_id')) ? 'has-error' : '' }}">
			<select class="form-control" name="company_id" required>
				@foreach($companies as $company)
					<option value="{{ $company->id}}" >{{ $company->name }}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
			<input class="form-control" placeholder="{{ __('basic.name')}}" name="name" type="text" maxlength="50" value="{{ old('name') }}" required />
			{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
			<label>E-mail</label>
			<input name="email" type="email" class="form-control" value="{{ old('email') }}" maxlength="50" >
			{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group" id="razina" >
			<label>@lang('basic.level')</label>
			<select class="form-control" name="level1" id="level" required>
				<option value="0">0. @lang('basic.level')</option>
				<option value="1">1. @lang('basic.level')</option>
				<option value="2">2. @lang('basic.level')</option>
			</select>
		</div>
		@if($departments)
			<div class="form-group" id="level1" style="display:none">
				<label>1. @lang('basic.level')</label>
				<select class="form-control" name="level2" value="" id="select_level">
					<option value="" selected>
					@foreach($departments as  $department)
						<option class="level {{ $department->level1 }}" value="{{ $department->id }}" >{{$department->name }}</option>
					@endforeach
				</select>
			</div>
		@endif
		<div class="form-group">
			<label>@lang('basic.manager')</label>
			<select class="form-control" name="employee_id" value="" id="select_level">
				<option value="" selected>
				@foreach($employees as $employee)
					<option value="{{ $employee->id }}" >{{ $employee->user->first_name . ' ' . $employee->user->last_name }}</option>
				@endforeach
			</select>
		</div>
		{{ csrf_field() }}
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
$.getScript( '/../js/validate.js');
$(document).ready(function(){
    $('#level').change(function(){
		var level = $(this).val();
		if(level == 1 || level == 2){
			$('#level1').show();
			$('#select_level').prop('required',true);
			if(level == 1) {
				$('.level.1').hide();
			}else {
				$('.level.1').show();
			}
		}else{
			$('#level1').hide();
			$('#select_level').prop('required',false);
		}
	});
});
</script>