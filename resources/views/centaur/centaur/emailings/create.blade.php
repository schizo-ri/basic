<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_emailing')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('emailings.store') }}" enctype="multipart/form-data">
		<fieldset>
			<div class="form-group {{ ($errors->has('model')) ? 'has-error' : '' }}" >
				<label>@lang('basic.model')</label>
				<select class="form-control" name="model" required value="{{ old('model') }}" required >
					<option value="" disabled selected ></option>				
					@foreach($models as $model_id => $model_description )
						<option value="{{ $model_id }}" >{{ $model_description }}</option>
					@endforeach
				</select>
				{!! ($errors->has('model') ? $errors->first('model', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('method')) ? 'has-error' : '' }}" >
				<label>@lang('basic.method')</label>
				<select class="form-control" name="method" required value="{{ old('method') }}" required >
					<option value="" disabled selected ></option>
					@foreach($methods as $method_name =>  $method_description )
						<option value="{{ $method_name }}" >{{ $method_description }}</option>
					@endforeach
				</select>
				{!! ($errors->has('method') ? $errors->first('method', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			@if (count($departments) >0)
				<div class="form-group {{ ($errors->has('sent_to_dep')) ? 'has-error' : '' }}">
					<label>@lang('basic.sent_to_dep')</label>
					<select class="form-control" name="sent_to_dep[]" multiple >
						<option value="" disabled selected ></option>
						@foreach($departments as $department)
							<option name="sent_to_dep" value="{{ $department->id }}" >{{ $department->name }}</option>
						@endforeach
					</select>
					{!! ($errors->has('sent_to_dep') ? $errors->first('sent_to_dep', '<p class="text-danger">:message</p>') : '') !!}
				</div>
			@endif			
			<div class="form-group {{ ($errors->has('sent_to_empl')) ? 'has-error' : '' }}">
				<label>@lang('basic.sent_to_empl')</label>
				<select class="form-control" name="sent_to_empl[]" multiple >
					<option value="" disabled selected ></option>
					@foreach($employees as $employee)
						<option name="sent_to_empl" value="{{ $employee->id }}" >{{ $employee->user['last_name'] . ' ' . $employee->user['first_name']}}</option>
					@endforeach
				</select>
				{!! ($errors->has('sent_to_empl') ? $errors->first('sent_to_empl', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			{{ csrf_field() }}
			<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
		</fieldset>
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
$.getScript( '/../js/validate.js');
</script>