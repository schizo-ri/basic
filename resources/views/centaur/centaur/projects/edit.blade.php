<div class="modal-header">
	<h3 class="panel-title">@lang('basic.edit_projects')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('projects.update', $project->id ) }}" enctype="multipart/form-data">
		<fieldset>
			<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
				<label for="">@lang('basic.name')</label>
				<input class="form-control" name="name" type="text" maxlength="100" value="{{ $project->name }}" required />
				{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('erp_id')) ? 'has-error' : '' }}">
				<label for="">ERP ID</label>
				<input class="form-control" name="erp_id" type="text" maxlength="20" value="{{ $project->erp_id }}" required />
				{!! ($errors->has('erp_id') ? $errors->first('erp_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('customer_oib')) ? 'has-error' : '' }}">
				<label for="">OIB investitora</label>
				<input class="form-control" name="customer_oib" type="text" maxlength="20" value="{{ $project->customer_oib }}" required />
				{!! ($errors->has('customer_oib') ? $errors->first('customer_oib', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('object')) ? 'has-error' : '' }}" >
				<label for="">@lang('basic.object')</label>
				<input class="form-control" name="object" maxlength="50" type="text" value="{{ $project->object }}"  />
				{!! ($errors->has('object') ? $errors->first('object', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('employee_id')) ? 'has-error' : '' }}">
				<label>@lang('basic.manager')</label>
				<select class="form-control" name="employee_id" >
					<option value="" selected disabled></option>
					@foreach ($employees as $employee)
						@if ($employee->user)
							<option name="employee_id" value="{{ $employee->id }}" {!!  $project->employee_id != null && $project->employee_id == $employee->id ? 'selected' : '' !!} >{{ $employee->user->last_name. ' ' . $employee->user->first_name }}</option>
						@endif
					@endforeach	
				</select>
				{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('employee_id2')) ? 'has-error' : '' }}">
				<label>@lang('basic.site_manager')</label>
				<select class="form-control" name="employee_id2" value="{{ old('employee_id2') }}" >
					<option value="" selected disabled></option>
					@foreach ($employees as $employee)
						<option name="employee_id2" value="{{ $employee->id }}" {!!  $project->employee_id2 != null && $project->employee_id2 == $employee->id ? 'selected' : '' !!} >{{ $employee->user->last_name . ' ' . $employee->user->first_name }}</option>
					@endforeach	
				</select>
				{!! ($errors->has('employee_id2') ? $errors->first('employee_id2', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="">
				<label for="active_1">@lang('basic.active')</label>
				<input name="active" type="radio" id="active_1" value="1" {!! $project->active == 1 ? 'checked' : '' !!} />
				<label for="active_0">@lang('basic.inactive')</label>
				<input  name="active" type="radio" id="active_0" value="0" {!! $project->active == 0 ? 'checked' : '' !!} />
			</div>
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
		</fieldset>
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
//$.getScript( '/../js/validate.js');
</script>