<div class="modal-header">
	<h3 class="panel-title">@lang('basic.edit_emailing')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('emailings.update', $emailing->id) }}" >
		<div class="form-group {{ ($errors->has('model')) ? 'has-error' : '' }}" >
			<label>@lang('basic.model')</label>
			<select class="form-control" name="model" required required >
				<option value="" disabled selected ></option>
				@foreach($models as $model)
					<option value="{{ $model->id }}" {!! $emailing->model == $model->id ? 'selected' : '' !!}>{{ $model->name }}</option>
				@endforeach
			</select>
			{!! ($errors->has('model') ? $errors->first('model', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('method')) ? 'has-error' : '' }}" >
			<label>@lang('basic.method')</label>
			<select class="form-control" name="method" required value="{{ old('method') }}" required >
				<option value="" disabled selected ></option>
				@foreach($methods as $method)
					<option {!! $emailing->method == $method ? 'selected' : '' !!} >{{ $method }}</option>
				@endforeach
			</select>
			{!! ($errors->has('method') ? $errors->first('method', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('sent_to_dep')) ? 'has-error' : '' }}">
			<label>@lang('basic.sent_to_dep')</label>
			<select class="form-control" name="sent_to_dep[]" multiple >
				<option value=""  {!! !$emailing->sent_to_dep ? 'selected' : '' !!} ></option>
				@foreach($departments as $department)
					<option name="sent_to_dep" value="{{ $department->id }}" {!! in_array($department->id, explode(',',$emailing->sent_to_dep )) ? 'selected' : '' !!} >{{ $department->name }}</option>
				@endforeach
			</select>
			{!! ($errors->has('sent_to_dep') ? $errors->first('sent_to_dep', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('sent_to_empl')) ? 'has-error' : '' }}">
			<label>@lang('basic.sent_to_empl')</label>
			<select class="form-control" name="sent_to_empl[]" multiple >
				<option value=""  {!! !$emailing->sent_to_empl ? 'selected' : '' !!}  ></option>
				@foreach($employees as $employee)
					<option name="sent_to_empl" value="{{ $employee->id}}"  {!! in_array($employee->id, explode(',',$emailing->sent_to_empl )) ? 'selected' : '' !!} >{{ $employee->user['first_name'] . ' ' . $employee->user['last_name']}}</option>
				@endforeach
			</select>
			{!! ($errors->has('sent_to_empl') ? $errors->first('sent_to_empl', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		{{ csrf_field() }}
		{{ method_field('PUT') }}
		<input class="btn btn-lg btn-primary btn-block" type="submit" value="{{ __('basic.edit')}}">
	</form>
</div>